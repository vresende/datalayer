<?php

    namespace Vresende\DataLayer;

    use Exception;
    use PDO;
    use PDOException;
    use stdClass;

    /**
     * Class DataLayer
     * @package Vresende\DataLayer
     */
    abstract class DataLayer
    {
        use CrudTrait;

        /** @var string $entity database table */
        private $entity;

        /** @var string $primary table primary key field */
        private $primary;
        /** @var bool $autoIncrement primaryKey autoIncrement */
        private $autoIncrement;

      /** @var string */
        private $distinct;

        /** @var string */
        private $groupBy;

        /** @var array $required table required fields */
        private $required;

        /** @var string $timestamps control created and updated at */
        private $timestamps;

        /** @var string */
        protected $statement;

        /** @var string */
        protected $params;

        /** @var int */
        protected $order;

        /** @var int */
        protected $limit;

        /** @var string */
        protected $offset;

        /** @var \PDOException|null */
        protected $fail;

        /** @var object|null */
        protected $data;

        /**
         * DataLayer constructor.
         * @param string $entity
         * @param array $required
         * @param string $primary
         * @param bool $timestamps
         * @param bool $autoIncrement
         */
        public function __construct(
            string $entity,
            array $required,
            string $primary = 'id',
            bool $timestamps = true,
            bool $autoIncrement = true
        ) {
            $this->entity = $entity;
            $this->primary = $primary;
            $this->required = $required;
            $this->timestamps = $timestamps;
            $this->autoIncrement = $autoIncrement;
        }

        /**
         * @param $name
         * @param $value
         */
        public function __set($name, $value)
        {
            if (empty($this->data)) {
                $this->data = new stdClass();
            }

            $this->data->$name = $value;
        }

        /**
         * @param $name
         * @return bool
         */
        public function __isset($name)
        {
            return isset($this->data->$name);
        }

        /**
         * @param $name
         * @return string|null
         */
        public function __get($name)
        {
            return ($this->data->$name ?? null);
        }

        /**
         * @return object|null
         */
        public function data(): ?object
        {
            return $this->data;
        }

        /**
         * @param string $groupBy
         * @return DataLayer|null
         */
        public function groupBy(string $groupBy): ?DataLayer
        {
            $this->groupBy = " GROUP BY {$groupBy}";
            return $this;
        }

        /**
         * @return DataLayer|null
         */
        public function distinct(): ?DataLayer
        {
            $this->distinct = " DISTINCT";
            return $this;
        }

        /**
         * @return object|null
         */
        public function fail(): ?object
        {
            if (!empty($this->fail)) {
                $fail = new stdClass();
                $fail->message = $this->fail->getMessage();
                $fail->code = $this->fail->getCode();
                $fail->file = $this->fail->getFile();
                $fail->line = $this->fail->getLine();
                return $fail;
            }
            return null;
        }

        /**
         * @param string|null $terms
         * @param string|null $params
         * @param string $columns
         * @return DataLayer
         */
        public function find(?string $terms = null, ?string $params = null, string $columns = "*"): DataLayer
        {
            if ($terms) {
                $this->statement = "SELECT {$this->distinct} {$this->limit} {$columns} FROM {$this->entity} WHERE {$terms}";
                parse_str($params, $this->params);
                return $this;
            }

            $this->statement = "SELECT {$this->distinct} {$this->limit} {$columns} FROM {$this->entity}";
            return $this;
        }

        /**
         * @param int $id
         * @param string $columns
         * @return DataLayer|null
         */
        public function findById(int $id, string $columns = "*"): ?DataLayer
        {

            $find = $this->find($this->primary . " = :id", "id={$id}", $columns);
            return $find->fetch();
        }

        /**
         * @param string $columnOrder
         * @return DataLayer|null
         */
        public function order(string $columnOrder): ?DataLayer
        {
            $this->order = " ORDER BY {$columnOrder}";
            return $this;
        }

        /**
         * @param int $limit
         * @return DataLayer|null
         */
        public function limit(int $limit): ?DataLayer
        {
            $this->limit = "TOP {$limit}";
            return $this;
        }

        /**
         * @param int $offset
         * @return DataLayer|null
         */
        public function offset(int $offset): ?DataLayer
        {
            $this->offset = " OFFSET {$offset}";
            return $this;
        }

        /**
         * @param bool $all
         * @return array|mixed|null
         */
        public function fetch(bool $all = false)
        {

            try {
                $stmt = Connect::getInstance()->prepare($this->statement . $this->groupBy . $this->order . $this->offset);
                $stmt->execute($this->params);

                if (!$stmt->rowCount()) {
                    return null;
                }

                if ($all) {
                    return $stmt->fetchAll(PDO::FETCH_CLASS, static::class);
                }

                return $stmt->fetchObject(static::class);
            } catch (PDOException $exception) {

                $this->fail = $exception;
                return null;
            }
        }

        /**
         * @return int
         */
        public function count(): int
        {
            $stmt = Connect::getInstance()->prepare($this->statement);
            $stmt->execute($this->params);

            return count($stmt->fetchAll());
        }

        /**
         * @return bool
         */
        public function save(): bool
        {
            $primary = $this->primary;
            $id = null;

            try {
                if (!$this->required()) {
                    throw new Exception("Preencha os campos necessários");
                }

                /** Update */
                if (!empty($this->data->$primary)) {
                    $id = $this->data->$primary;
                    $res = $this->update($this->safe(), $this->primary . " = :id", "id={$id}");
                }

                /** Create */

                if (empty($this->data->$primary)) {
                    $id = $this->create($this->safe());
                }
                if (!$this->autoIncrement) {
                    if ($res < 1) {
                        $id = $this->create($this->safe());
                    }
                }

                if (!$id) {
                    return false;
                }

                return true;
            } catch (Exception $exception) {
                $this->fail = $exception;
                return false;
            }
        }

        /**
         * @return bool
         */
        public function destroy(): bool
        {
            $primary = $this->primary;
            $id = $this->data->$primary;

            if (empty($id)) {
                return false;
            }

            $destroy = $this->delete($this->primary . " = :id", "id={$id}");
            return $destroy;
        }

        /**
         * @return bool
         */
        protected function required(): bool
        {
            $data = (array)$this->data();
            foreach ($this->required as $field) {
                if (empty($data[$field])) {
                    return false;
                }
            }
            return true;
        }

        /**
         * @return array|null
         */
        protected function safe(): ?array
        {
            $safe = (array)$this->data;
            if ($this->autoIncrement) {
                unset($safe[$this->primary]);
            }

            return $safe;
        }
    }