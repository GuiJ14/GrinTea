<?php

namespace grintea\services\UI\traits;

trait JSLoaderTrait{

    private function jsFunctions( $jquery ){
        $jquery->execJSFromFile('@grintea/assets/js/functions');
    }

    private function passwordGenerator ( $jquery ){
        $jquery->execJSFromFile('@grintea/assets/js/passwordGenerator');
    }

    private function toggleInputVisibility( $jquery ){
        $jquery->execJSFromFile('@grintea/assets/js/toggleInputVisibility');
    }

}