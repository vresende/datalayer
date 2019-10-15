<?php


    namespace Example\Models;


    use Vresende\DataLayer\DataLayer;

    class Category extends DataLayer
    {
        public function __construct()
        {
            parent::__construct("dev..tbl_colombo_categories", ["groupId", "lineId","lineName","familyId"], "groupId", false);
        }

    }