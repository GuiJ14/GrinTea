<?php

namespace grintea\services\UI;

use Ajax\JsUtils;
use models\User;
use Ubiquity\controllers\Router;
use grintea\services\UI\traits\JSLoaderTrait;

class AdminUILoader{
    use JSLoaderTrait;

    public function firstLaunch( $jquery ){
        $this->startStepper( $jquery );
        $this->toggleInputVisibility( $jquery );
        $this->userCreationFormValidation( $jquery );
        $this->passwordGenerator( $jquery );
        $this->injectInstallJs( $jquery );
    }

    private function injectInstallJs( $jquery ){
        $jquery->execJSFromFile('@grintea/assets/js/admin/install');
    }
}