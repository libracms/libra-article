<?php

namespace LibraArticleTest;

use Zend\Mvc\Service\ServiceManagerConfig;
use Zend\ServiceManager\ServiceManager;

error_reporting(E_ALL | E_STRICT);
chdir(__DIR__);

/**
 * Test bootstrap, for setting up autoloading
 */
class Bootstrap
{
    protected static $serviceManager;
    public static $appRootPath = null;

    public static function init()
    {
        $zf2ModulePaths = array(dirname(dirname(__DIR__)));
        if (($path = static::findParentPath('vendor'))) {
            $zf2ModulePaths[] = $path;
        }
        if (($path = static::findParentPath('module')) !== $zf2ModulePaths[0]) {
            $zf2ModulePaths[] = $path;
        }

        static::$appRootPath = dirname(static::findParentFilePath('init_autoloader.php'));

        static::initAutoloader();

        // use ModuleManager to load this module and it's dependencies
        $config = array(
            'module_listener_options' => array(
                'module_paths' => $zf2ModulePaths,
            ),
            'modules' => array(
                'DoctrineModule',
                'DoctrineORMModule',
                'Libra',
                'LibraModuleManager',
                'LibraApp',
                'LibraArticle',
                'LibraLocale',
                //'LibraArticleTest',
            )
        );

        chdir(static::$appRootPath);
        //$config = static::$appRootPath . '/config/application.config.php';
        $serviceManager = new ServiceManager(new ServiceManagerConfig());
        $serviceManager->setService('ApplicationConfig', $config);
        $serviceManager->get('ModuleManager')->loadModules();
        static::$serviceManager = $serviceManager;
    }

    public static function getServiceManager()
    {
        return static::$serviceManager;
    }

    protected static function initAutoloader()
    {
        $autoloaderPath = static::findParentFilePath('init_autoloader.php');
        $cwd = getcwd();
        chdir(dirname($autoloaderPath));
        require $autoloaderPath;
        chdir($cwd);
    }

    protected static function findParentFilePath($file)
    {
        $dir = __DIR__;
        $previousDir = '.';
        while (!file_exists($dir . '/' . $file)) {
            $dir = dirname($dir);
            if ($previousDir === $dir) return false;
            $previousDir = $dir;
        }
        return $dir . '/' . $file;
    }

    protected static function findParentPath($path)
    {
        $dir = __DIR__;
        $previousDir = '.';
        while (!is_dir($dir . '/' . $path)) {
            $dir = dirname($dir);
            if ($previousDir === $dir) return false;
            $previousDir = $dir;
        }
        return $dir . '/' . $path;
    }
}

Bootstrap::init();
