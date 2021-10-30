<?php

namespace grintea;

use Ubiquity\cache\CacheFile;
use Ubiquity\cache\CacheManager as UbiquityCacheManager;
use Ubiquity\cache\ClassUtils;
use Ubiquity\cache\parser\ControllerParser;
use Ubiquity\contents\validation\ValidatorsManager;
use grintea\DiManager;
use Ubiquity\controllers\Startup;
use Ubiquity\exceptions\ParserException;
use Ubiquity\exceptions\UbiquityException;
use Ubiquity\orm\parser\Reflexion;
use Ubiquity\translation\TranslatorManager;
use Ubiquity\utils\base\UFileSystem;

class CacheManager extends UbiquityCacheManager{

    private static $modelsDatabaseKey = 'models' . \DIRECTORY_SEPARATOR . '_modelsDatabases';

    protected static function _getFiles(&$config, $type, $silent = true) {
        $files = parent::_getFiles($config, $type, $silent);
        $typeNS = $config['mvcNS'][$type];
        $typeDir = \dirname( __FILE__ ) . \DS . \str_replace("\\", \DS, $typeNS);
        return \array_merge($files, UFileSystem::glob_recursive($typeDir . \DS . '*.php'));
    }

    public static function getControllers($subClass = "\\Ubiquity\\controllers\\Controller", $backslash = false, $includeSubclass = false, $includeAbstract = false) {
        $result = [ ];
        if ($includeSubclass) {
            $result [] = $subClass;
        }
        $config = Startup::getConfig ();
        $files = self::getControllersFiles ( $config, true );
        try {
            $restCtrls = self::getRestCache ();
        } catch ( \Exception $e ) {
            $restCtrls = [ ];
        }
        foreach ( $files as $file ) {
            if (\is_file ( $file )) {
                $controllerClass = ClassUtils::getClassFullNameFromFile ( $file, $backslash );
                if (\class_exists ( $controllerClass ) && isset ( $restCtrls [$controllerClass] ) === false) {
                    $r = new \ReflectionClass ( $controllerClass );
                    if ($r->isSubclassOf ( $subClass ) && ($includeAbstract || ! $r->isAbstract ())) {
                        $result [] = $controllerClass;
                    }
                }
            }
        }
        return $result;
    }

    public static function getControllersFiles(&$config, $silent = false) {
        return self::_getFiles ( $config, 'controllers', $silent );
    }

    public static function getModelsFiles(&$config, $silent = false) {
        return self::_getFiles ( $config, 'models', $silent );
    }

    public static function initModelsCache(&$config, $forChecking = false, $silent = false) {
        $modelsDb = [ ];
        $files = self::getModelsFiles ( $config, $silent );
        foreach ( $files as $file ) {
            if (\is_file ( $file )) {
                $model = ClassUtils::getClassFullNameFromFile ( $file );
                if(!\class_exists($model)){
                    if(\file_exists($file)){
                        include $file;
                    }
                }
                if (! $forChecking) {
                    self::createOrmModelCache ( $model );
                    $db = 'default';
                    $ret = Reflexion::getAnnotationClass ( $model, 'database' );
                    if (\count ( $ret ) > 0) {
                        $db = $ret [0]->name;
                        if (! isset ( $config ['database'] [$db] )) {
                            throw new UbiquityException ( $db . ' connection is not defined in config array' );
                        }
                    }
                    $modelsDb [$model] = $db;
                    ValidatorsManager::initClassValidators ( $model );
                }
            }
        }
        self::$cache->store ( self::$modelsDatabaseKey, $modelsDb, 'models' );
    }

    private static function parseControllerFiles(&$config, $silent = false) {
        $routes = [ 'rest' => [ ],'default' => [ ] ];
        $files = self::getControllersFiles ( $config, $silent );
        $annotsEngine = self::getAnnotationsEngineInstance ();
        foreach ( $files as $file ) {
            if (is_file ( $file )) {
                $controller = ClassUtils::getClassFullNameFromFile ( $file );
                $parser = new ControllerParser ( $annotsEngine );
                $parser->setSilent($silent);
                try {
                    $parser->parse ( $controller);
                    $ret = $parser->asArray ();
                    $key = ($parser->isRest ()) ? 'rest' : 'default';
                    $routes [$key] = \array_merge ( $routes [$key], $ret );
                } catch ( \Exception $e ) {
                    if (!$silent && $e instanceof ParserException) {
                        throw $e;
                    }
                    // Nothing to do
                }
            }
        }
        self::sortByPriority ( $routes ['default'] );
        self::sortByPriority ( $routes ['rest'] );
        $routes ['rest-index'] = self::createIndex ( $routes ['rest'] );
        $routes ['default-index'] = self::createIndex ( $routes ['default'] );
        return $routes;
    }

    protected static function initRouterCache(&$config, $silent = true) {
        $routes = self::parseControllerFiles ( $config, $silent );
        self::$cache->store ( 'controllers/routes.default', $routes ['default'], 'controllers' );
        self::$cache->store ( 'controllers/routes.rest', $routes ['rest'], 'controllers' );
        self::$cache->store ( 'controllers/routes.default-index', $routes ['default-index'], 'controllers' );
        self::$cache->store ( 'controllers/routes.rest-index', $routes ['rest-index'], 'controllers' );
        DiManager::init ( $config );
    }

    public static function initCache(&$config, $type = 'all', $silent = true) {
        self::checkCache($config, $silent);
        self::start($config);
        if ($type === 'all' || $type === 'models') {
            self::initModelsCache($config, false, $silent);
        }
        if ($type === 'all' || $type === 'controllers') {
            if (\class_exists('\\Ubiquity\\security\\acl\\AclManager')) {
                self::getAnnotationsEngineInstance()->registerAcls();
            }
            self::initRouterCache($config, $silent);
        }
        if ($type === 'all' || $type === 'acls') {
            if (\class_exists('\\Ubiquity\\security\\acl\\AclManager')) {
                AclManager::initCache($config);
            }
        }

    }

    public static function initTranslationsCache(){
        CacheFile::delete(\ROOT . \DS . self::getCacheDirectory() . 'translations');
        TranslatorManager::start();
        $locales = TranslatorManager::getLocales();
        foreach ($locales as $locale) {
            TranslatorManager::getCatalogue($locale);
        }
    }

}