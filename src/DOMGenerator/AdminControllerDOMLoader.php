<?php

namespace grintea\DOMGenerator;

use Ajax\JsUtils;
use Ubiquity\controllers\Router;

class AdminControllerDOMLoader{

    public function firstLaunch( $jquery ){
        $createDB = $jquery->semantic()->htmlButton('createDB','Créer la base de données');
        $createDB->getOnClick(Router::path('index.createDB'),'#response');
        $createModels = $jquery->semantic()->htmlButton('createModels','Créer les models');
		$createModels->getOnClick(Router::path('index.createModels'),'#response');
        $cacheModels = $jquery->semantic()->htmlButton('cacheModels','Générer le cache');
        $cacheModels->getOnClick(Router::path('index.createCache'),'#response');
    }
}