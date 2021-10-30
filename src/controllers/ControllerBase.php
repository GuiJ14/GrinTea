<?php

namespace grintea\controllers;

use Ajax\php\ubiquity\JsUtils;
use grintea\services\Manager;
use Ubiquity\attributes\items\di\Autowired;
use Ubiquity\controllers\Controller;
use Ubiquity\controllers\Startup;
use Ubiquity\utils\http\URequest;
use Ubiquity\utils\http\USession;

/**
 * controllers$ControllerBase
 */
abstract class ControllerBase extends Controller{

    #[Autowired]
    public JsUtils $jquery;

    #[Autowired]
    public Manager $loader;

    protected $headerView = "@grintea/parts/header.html";
    protected $footerView = "@grintea/parts/footer.html";

    public function initialize()
    {
        Startup::$templateEngine->addPath('vendor/grinto/grintea/src/views', 'grintea');
        if (!URequest::isAjax()) {
            $this->loadView($this->headerView);
        }
    }

    public function finalize()
    {
        if (!URequest::isAjax()) {
            $this->loadView($this->footerView);
        }
    }

    public function _getRole(){
        //USession::get('activeUser')['groups']
        return '@guest';
    }
}

