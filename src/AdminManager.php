<?php

namespace grintea;

use grintea\DOMGenerator\AdminManagerDOMLoader;
use Ubiquity\attributes\AttributesEngine;
use Ubiquity\cache\traits\ModelsCacheTrait;
use Ubiquity\cache\CacheManager;
use Ubiquity\controllers\Startup;
use Ubiquity\db\providers\pdo\PDOWrapper;
use Ubiquity\orm\creator\database\DbModelsCreator;
use Ubiquity\orm\DAO;
use Ubiquity\utils\base\UFileSystem;

class AdminManager {

    public static function _createDB(){
        $config = Startup::getConfig();
        $sqlFilename = 'query';
        $sqlFilePath = dirname(__FILE__,1). \DS. $sqlFilename . '.sql';
        if(\file_exists($sqlFilePath)){
            $query = trim(file_get_contents($sqlFilePath), "\xEF\xBB\xBF");
            try{
                $pdo = new PDOWrapper();
                $dsn = 'mysql:dbname='.$config['database']['dbName'].';host='.$config['database']['serverName'];
                $dbInstance = new \PDO($dsn, $config['database']['user'], $config['database']['password']);
                $pdo->setDbInstance($dbInstance);
                $pdo->execute($query);
            }
            catch (\Exception $exception){
                return $exception;
            }
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