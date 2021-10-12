<?php

namespace grintea\services\UI\traits;

trait JSLoaderTrait{

    private function passwordGenerator ( $jquery ){
        $jquery->execJSFromFile('@grintea/assets/js/passwordGenerator');
    }

    private function startStepper( $jquery ){
        $jquery->execJSFromFile('@grintea/assets/js/stepper');
    }

    private function userCreationFormValidation( $jquery ){
        $jquery->execJSFromFile('@grintea/assets/js/formValidation');
    }

    private function toggleInputVisibility( $jquery ){
        $jquery->execJSFromFile('@grintea/assets/js/toggleInputVisibility');
    }

}