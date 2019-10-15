<?php

    namespace Vresende\DataLayer;

    use PDO;
    use PDOException;

    /**
     * Class Connect
     * @package Vresende\DataLayer
     */
    class Connect
    {
        /** @var PDO */
        private static $instance;

        /** @var PDOException */
        private static $error;

        /**
         * @return PDO
         */

        public static function getInstance(): ?PDO
        {

            if (empty(self::$instance)) {
                try {
                    self::$instance = new PDO(
                        "sqlsrv:Server=" . DATA_LAYER_CONFIG["host"] . ";Database=" . DATA_LAYER_CONFIG["dbname"] . ";" . (!empty(DATA_LAYER_CONFIG["port"]) ? "port=" . DATA_LAYER_CONFIG["port"] : ""),
                        DATA_LAYER_CONFIG["username"],
                        DATA_LAYER_CONFIG["passwd"],
                        DATA_LAYER_CONFIG["options"]
                    );
                } catch (PDOException $exception) {
                    self::$error = $exception;
                }
            }

            return self::$instance;
        }


        /**
         * @return PDOException|null
         */
        public static function getError(): ?PDOException
        {
            return self::$error;
        }

        /**
         * Connect constructor.
         */
        final private function __construct()
        {
        }

        /**
         * Connect clone.
         */
        final private function __clone()
        {
        }
    }