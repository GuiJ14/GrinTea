<?php

namespace grintea\services\UI\traits;

trait JSLoaderTrait{

    public function loadJS( $jquery , string $viewName, array $modules = []):void {
        $this->injectJs( $jquery, 'functions');
        foreach($modules as $module){
            $this->injectJs( $jquery, $module);
        }
        $this->injectJs( $jquery, "view/$viewName");
    }

    private function injectJs( $jquery, $fileName ){
        $jquery->execJSFromFile("@grintea/assets/js/$fileName");
    }

}