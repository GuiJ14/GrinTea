<?php

namespace grintea;

use Ubiquity\cache\ClassUtils;
use Ubiquity\exceptions\AclException;
use Ubiquity\security\acl\cache\AclControllerParser;
use Ubiquity\security\acl\persistence\AclCacheProvider;

class AclManager extends \Ubiquity\security\acl\AclManager{

    public static function initCache(&$config) {
        if(!self::isStarted()){
            self::start();
            self::initFromProviders([
                new AclCacheProvider()
            ]);
        }
        self::filterProviders(AclCacheProvider::class);
        self::reloadFromSelectedProviders([]);
        self::registerAnnotations();
        $files = CacheManager::getControllersFiles($config, true);
        $parser = new AclControllerParser();
        $parser->init();
        foreach ($files as $file) {
            if (\is_file($file)) {
                $controller = ClassUtils::getClassFullNameFromFile($file);
                try {
                    $parser->parse($controller);
                } catch (\Exception $e) {
                    if ($e instanceof AclException) {
                        throw $e;
                    }
                }
            }
        }
        $parser->save();
        self::removefilterProviders();
        self::reloadFromSelectedProviders();
    }

}