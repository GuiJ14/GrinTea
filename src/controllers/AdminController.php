<?php

namespace grintea\controllers;

use Ajax\php\ubiquity\JsUtils;
use grintea\controllers\ControllerBase;
use grintea\AdminManager;
use grintea\controllers\traits\UserTrait;
use grintea\services\Manager;
use Ubiquity\attributes\items\di\Autowired;
use Ubiquity\attributes\items\router\Route;
use Ubiquity\controllers\Router;
use Ubiquity\controllers\Startup;
use Ubiquity\security\acl\controllers\AclControllerTrait;
use Ubiquity\attributes\items\acl\Allow;


#[Route('admin',automated:true,inherited:true)]
class AdminController extends ControllerBase {
	use UserTrait;

    private function adminAccountCreation(){
        $jsCallback = 'ajaxCallback(data, setMessage.bind(null, document.getElementById("response"), data), redirection(data.redirect,2000));';
        $this->loader->getUILoader('User')->userCreationForm($this->jquery , $jsCallback);
        $this->loader->getUILoader('Admin')->loadJS( $this->jquery, 'install', ['toggleInputVisibility', 'passwordGenerator', 'formValidation']);
        $this->jquery->renderView('@grintea/admin/install');
    }

    public function installation(){
        AdminManager::_initConfig();
        $indexRoute = Router::path('admin.index');
        if(!AdminManager::isAdminAccountCreated()){
            $this->adminAccountCreation();
        }
        else{
            header("location:$indexRoute");
        }
    }

    public function index(){
        $this->loader->getUILoader('Admin')->loadJS( $this->jquery, 'index', ['menuIndicator']);
        $this->jquery->renderView('@grintea/admin/index');
    }

}