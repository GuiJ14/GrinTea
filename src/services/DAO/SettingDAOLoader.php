<?php

namespace grintea\services\DAO;

use models\Setting;
use Ubiquity\orm\DAO;

class SettingDAOLoader{

    public function getByType(string $type){
       return DAO::getOne(Setting::class,'type=:type',false,['type'=>$type]);
    }
}