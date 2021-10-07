<?php

namespace grintea\manager;

use Ubiquity\cache\CacheManager;
use Ubiquity\cache\ClassUtils;
use Ubiquity\contents\validation\ValidatorsManager;
use Ubiquity\controllers\admin\popo\MaintenanceMode;
use Ubiquity\controllers\Startup;
use Ubiquity\db\providers\pdo\PDOWrapper;
use Ubiquity\db\reverse\DbGenerator;
use Ubiquity\exceptions\UbiquityException;
use Ubiquity\orm\creator\database\DbModelsCreator;
use Ubiquity\orm\DAO;
use Ubiquity\orm\parser\ModelParser;
use Ubiquity\orm\parser\Reflexion;
use Ubiquity\orm\reverse\DatabaseReversor;
use Ubiquity\orm\reverse\TableReversor;
use Ubiquity\utils\base\UFileSystem;
use Ubiquity\cache\traits\ModelsCacheTrait;

class AdminCacheManager{

	public static function _createSQL(){
		$config = Startup::getConfig();
		$sqlPath = \dirname(__FILE__,2). \DS . 'sql.sql';
		if(\file_exists($sqlPath)){
			$query = trim(file_get_contents($sqlPath), "\xEF\xBB\xBF");
			$pdo = new PDOWrapper();
			$dsn = 'mysql:dbname='.$config['database']['dbName'].';host='.$config['database']['serverName'];
			$dbInstance = new \PDO($dsn, $config['database']['user'], $config['database']['password']);
			$pdo->setDbInstance($dbInstance);
			try{
				$pdo->execute($query);
			}
			catch (\Exception $exception){
				return $exception;
			}
		}
	}

	public static function _createCache(){
		$config = Startup::getConfig();
		var_dump(CacheManager::initModelsCache($config,false,true));
	}
}