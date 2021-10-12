<?php

namespace grintea\controllers;

use Ajax\JsUtils;
use Ajax\php\symfony\Jquery_;
use Ajax\php\symfony\JquerySemantic;
use models\Settings;
use Ubiquity\assets\AssetsManager;
use Ubiquity\attributes\items\di\Autowired;
use Ubiquity\attributes\items\router\Get;
use Ubiquity\attributes\items\router\Post;
use Ubiquity\cache\CacheManager;
use Ubiquity\cache\ClassUtils;
use Ubiquity\contents\validation\ValidatorsManager;
use Ubiquity\controllers\admin\popo\MaintenanceMode;
use Ubiquity\controllers\admin\traits\ConfigTrait;
use Ubiquity\controllers\ControllerBase;
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
use Ubiquity\controllers\semantic\InsertJqueryTrait;
use grinto\DOMGenerator\AdminManagerDOMLoader;
use Ubiquity\utils\http\URequest;
use grintea\AdminManager;
use grintea\controllers\traits\UserTrait;

/**
 * Controller AdminController
 */
class AdminController extends ControllerBase {
	use UserTrait;

    protected $headerView = "@grintea/parts/header.html";
    protected $footerView = "@grintea/parts/footer.html";

	public function initialize() {
        Startup::$templateEngine->addPath('vendor/grinto/grintea/src/views','grintea');
        $config = Startup::getConfig();
        parent::initialize();
    }

    public function index(){
        AdminManager::_initConfig();
        $this->loader->getUILoader('Admin')->firstLaunch($this->jquery);
        $this->jquery->renderView('@grintea/admin/index');
    }

}