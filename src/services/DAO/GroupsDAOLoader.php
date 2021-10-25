<?php

namespace grintea\services\DAO;

use Ubiquity\orm\DAO;

class GroupsDAOLoader{

    public function getByName(string $name){
        return DAO::getOne(Groups::class,'name = :name', false, ['name'=>$name]);
    }

}