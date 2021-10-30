<?php

namespace grintea;

use models\Setting;
use Ubiquity\controllers\Startup;
use Ubiquity\orm\creator\database\DbModelsCreator;
use Ubiquity\orm\DAO;

class AdminManager {

	public static function _initConfig(){
        $config = Startup::getConfig();
        CacheManager::start($config);
        if(!self::isDBInstalled()) {
            self::_createDB();
        }
        if(!self::areModelsGenerated()){
            self::_createModels();
        }
        self::_createTranslation();
        CacheManager::initTranslationsCache();
        CacheManager::initCache( $config, 'models');
        CacheManager::initCache( $config, 'controllers');
        CacheManager::initCache( $config, 'acls');

    }

    public static function areModelsGenerated():bool {
        if(class_exists('models\User') && class_exists('models\Setting') && class_exists('models\Groups')){
            return true;
        }
        return false;
    }

    public static function isDBInstalled():bool {
        $dbInstance = DAO::getDatabase();
        $dbName = Startup::getConfig()['database']['dbName'];
        $tableList = $dbInstance->getTablesName();
        return (0 == \count(\array_diff(['user','groups','setting'], $tableList)));
    }

    public static function isAdminAccountCreated() {
        return \filter_var(DAO::getOne(Setting::class,'type=:type',false,['type'=>'isAdminAccountCreated']), FILTER_VALIDATE_BOOLEAN);
    }

	private static function getFile(string $filePath){
		return \trim(\file_get_contents($filePath), "\xEF\xBB\xBF");
	}

    public static function _createDB(){
        $config = Startup::getConfig();
        $sqlFilename = 'query';
        $sqlFilePath = \dirname(__FILE__,1). \DS. $sqlFilename . '.sql';
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
        $modelsCreator = new DbModelsCreator();
        $modelsCreator->setSilent(true);
		$modelsCreator->create($config, false);
	}

    public static function _createTranslation(){
        $translationDirectory = \dirname( __FILE__) . \DS . 'translations';
        $destination = \ROOT . 'translations';
        Utils::copy($translationDirectory, $destination);
    }

}