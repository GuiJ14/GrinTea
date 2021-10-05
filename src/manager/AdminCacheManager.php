<?php

namespace grinto\manager;

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

class AdminCacheManager extends CacheManager{
	use ModelsCacheTrait;

	public static function _getFiles(&$config, $type, $silent = false) {
		$typeNS = $config['mvcNS'][$type];
		$typeDir = dirname(__FILE__,2) . \DS . \str_replace("\\", \DS, $typeNS);
		if (! $silent)
			echo \ucfirst($type) . ' directory is ' . \realpath(\ROOT . $typeNS) . "\n";
		return UFileSystem::glob_recursive($typeDir . \DS . '*.php');
	}

	public static function _generateSQL(){
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
				//\unlink($sqlPath);
			}
			catch (\Exception $exception){
				return $exception;
			}
		}
	}

	public static function _generateModel($singleTable = null) {
		$config = Startup::getConfig();
		\ob_start();
		(new DbModelsCreator())->create($config, false, $singleTable, 'default');
		\ob_get_clean();
	}

	public static function _generateCache($type){
		$config = Startup::getConfig();
		switch ($type) {
			case "Models":
				self::initModelsCache($config, false, true);
				break;
			case "Controllers":
				self::initCache($config, "controllers");
				break;
		}
	}
}