<?php

    namespace Example\Models;

    use Vresende\DataLayer\DataLayer;

    /**
     * Class User
     * @package Example\Models
     */
    class User extends DataLayer
    {
        /**
         * User constructor.
         */
        public function __construct()
        {
            parent::__construct("dbname", ["Usuario", "Senha"], "cod", false);
        }
    }