<?php

namespace grintea;

use models\Setting;
use models\User;
use Ubiquity\cache\CacheFile;
use Ubiquity\cache\CacheManager;
use Ubiquity\controllers\Startup;
use Ubiquity\orm\creator\database\DbModelsCreator;
use Ubiquity\orm\DAO;
use Ubiquity\translation\TranslatorManager;

class AdminManager {

	public static function _initConfig(){
        CacheManager::start($config);
        if(!self::isDBInstalled()) {
            self::_createDB();
        }
        if(!self::areModelsGenerated()) {
            self::_createModels();
        }
        self::_createTranslation();
        self::_initTranslationsCache();
        self::_initModelsCache();
    }

    public static function isInstalled():bool {
        return self::isDBInstalled() && self::areModelsGenerated() && self::isAdminAccountCreated();
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
        $translationDirectoryFiles = array_diff(\scandir($translationDirectory), ['.', '..']);
        $destination = \ROOT . 'translations';

        //create translation folder in app
        if(!\is_dir($destination)){
            \mkdir($destination);
        }

        //create locales folders in app
        foreach($translationDirectoryFiles as $path){
            if(!\is_dir($destination . \DS . $path)){
                \mkdir($destination . \DS . $path);
                $filesToCopy = array_diff(\scandir($translationDirectory . \DS . $path), ['.', '..']);
                foreach ($filesToCopy as $file) {
                    \copy($translationDirectory . \DS . $path . \DS . $file, $destination . \DS . $path . \DS . $file);
                }
            }
        }
    }

    public static function _initTranslationsCache(){
        CacheFile::delete(\ROOT . \DS . CacheManager::getCacheDirectory() . 'translations');
        TranslatorManager::start();
        $locales = TranslatorManager::getLocales();
        foreach ($locales as $locale) {
            TranslatorManager::getCatalogue($locale);
        }
    }

    public static function _initModelsCache(){
        $config = Startup::getConfig();
        CacheManager::initModelsCache($config, false, true);
    }
}