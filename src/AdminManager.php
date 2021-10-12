<?php

namespace grintea;

use grintea\DOMGenerator\AdminManagerDOMLoader;
use models\Settings;
use Ubiquity\attributes\AttributesEngine;
use Ubiquity\cache\traits\ModelsCacheTrait;
use Ubiquity\cache\CacheManager;
use Ubiquity\controllers\Startup;
use Ubiquity\db\providers\pdo\PDOWrapper;
use Ubiquity\orm\creator\database\DbModelsCreator;
use Ubiquity\orm\DAO;
use Ubiquity\utils\base\UFileSystem;

class AdminManager {

	public static function _initConfig(){
        if(\in_array('settings',DAO::getDatabase()->getTablesName())){
            $state = DAO::getOne(Settings::class,'type = :type',false,['type'=>'installState']);
        }
        if( isset($state) && (int) $state->getValue() == 2){
            return;
        }

        if( !isset($state) ){
            self::_createDB();
        }

        if( !isset($state) || (int) $state->getValue() == 1){
            self::_createModels();
            self::_createCache();
            if(!isset($state))
                $state = DAO::getOne(Settings::class,'type = :type',false,['type'=>'installState']);
            $state->setValue('2');
            DAO::save($state);
        }
	}

	private static function getFile(string $filePath){
		return trim(file_get_contents($filePath), "\xEF\xBB\xBF");
	}

    public static function _createDB(){
        $config = Startup::getConfig();
        $sqlFilename = 'query';
        $sqlFilePath = dirname(__FILE__,1). \DS. $sqlFilename . '.sql';
        $query = self::getFile($sqlFilePath);
		try{
            $dbInstance = DAO::getDatabase();
			$dbInstance->execute($query);
		}
		catch (\Exception $exception){
			throw $exception;
		}
    }

    public static function _createModels(){
    	$config = Startup::getConfig();
		CacheManager::start($config);
		(new DbModelsCreator())->create($config, false);
	}

    public static function _createCache(){
        $config = Startup::getConfig();
        CacheManager::initModelsCache($config, false, true);
    }
}