<?php

namespace grinto\manager;

use Ubiquity\cache\CacheManager;
use Ubiquity\cache\ClassUtils;
use Ubiquity\contents\validation\ValidatorsManager;
use Ubiquity\controllers\admin\popo\MaintenanceMode;
use Ubiquity\controllers\Startup;
use Ubiquity\db\reverse\DbGenerator;
use Ubiquity\exceptions\UbiquityException;
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
		$models = self::getModels($config);
		$meta = include ('C:\Users\Guillaume\Downloads\grintoAdmin\app\cache\grinto\models\User.cache.php');
		$table = new TableReversor($models);
		$table->init($meta);
		$generator = new DbGenerator();
		$table->generateSQL($generator);
		var_dump($generator->getSqlScript());
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