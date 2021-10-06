<?php

namespace grinto;

use grinto\DOMGenerator\AdminManagerDOMLoader;
use Ubiquity\cache\traits\ModelsCacheTrait;
use Ubiquity\cache\CacheManager;
use Ubiquity\controllers\Startup;
use Ubiquity\db\providers\pdo\PDOWrapper;
use Ubiquity\utils\base\UFileSystem;

class AdminManager extends CacheManager {
    use ModelsCacheTrait;

    public static function _getFiles(&$config, $type, $silent = false) {
        $typeNS = $config['mvcNS'][$type];
        $typeDir = \dirname(__FILE__) .\DS . \str_replace("\\", \DS, $typeNS);
        return UFileSystem::glob_recursive($typeDir . '/*.php');
    }

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
                //\unlink($sqlPath);
            }
            catch (\Exception $exception){
                return $exception;
            }
        }
        else{
            echo 'ok';
        }
    }

    public static function _createCache($type = 'Models'){
        $config = Startup::getConfig();
        self::initModelsCache($config, false, true);
        /*
        switch ($type) {
            case "Models":
                self::initModelsCache($config, false, true);
                break;
            case "Controllers":
                self::initCache($config, "controllers");
                break;
        }*/
    }
}