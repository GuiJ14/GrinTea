<?php

namespace grintea\services\UI;

use grintea\services\UI\traits\JSLoaderTrait;

class AdminUILoader{
    use JSLoaderTrait;

    public function installation( $jquery ){
        $this->jsFunctions( $jquery );
        $this->toggleInputVisibility( $jquery );
        $this->passwordGenerator( $jquery );
        $this->injectInstallJs( $jquery );
    }

    private function injectInstallJs( $jquery ){
        $jquery->execJSFromFile('@grintea/assets/js/admin/install');
    }
}