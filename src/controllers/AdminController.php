<?php

namespace grintea\controllers;

use grintea\controllers\traits\UserTrait;
use controllers\ControllerBase;
use models\Setting;
use Ubiquity\controllers\Startup;
use grintea\AdminManager;
use Ubiquity\orm\DAO;
use Ubiquity\translation\TranslatorManager;

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

    private function installation(){
        AdminManager::_initConfig();
        if(!AdminManager::isAdminAccountCreated()) {
            $jsCallback = 'ajaxCallback(data, setMessage.bind(null, document.getElementById("response"), data));';
            $this->loader->getUILoader('User')->userCreationForm($this->jquery , $jsCallback);
        }
        $this->loader->getUILoader('Admin')->loadJS( $this->jquery, 'install', ['toggleInputVisibility', 'passwordGenerator', 'formValidation']);
        $this->jquery->renderView('@grintea/admin/install');
    }

    public function index(){
        if(!AdminManager::isInstalled()){
            $this->installation();
        }
        else{
            $this->loader->getUILoader('Admin')->loadJS( $this->jquery, 'index', ['menuIndicator']);
            $this->jquery->renderView('@grintea/admin/index');
        }
    }

}