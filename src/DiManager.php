<?php

namespace grintea;

use grintea\CacheManager;
use Ubiquity\controllers\di\DiControllerParser;

class DiManager extends \Ubiquity\controllers\di\DiManager{

    public static function init(&$config) {
        $controllers = CacheManager::getControllers();
        foreach ( $controllers as $controller ) {
            CacheManager::$cache->remove ( self::getControllerCacheKey ( $controller ) );
            $parser = new DiControllerParser ();
            $parser->parse ( $controller, $config );
            $injections = $parser->getInjections ();
            if (\count ( $injections ) > 0) {
                self::store ( $controller, $injections );
            }
        }
    }

}