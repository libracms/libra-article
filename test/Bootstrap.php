<?php

namespace LibraArticleTest;

use Zend\Mvc\Application;
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
    protected static $appPath = null;

    public static function init()
    {
        $appPath = static::getAppPath();
        static::initAutoloader();

        // use ModuleManager to load this module and it's dependencies
        $config = array(
            'module_listener_options' => array(
                'module_paths' => array(
                    $appPath . '/module',
                    $appPath . '/vendor',
                ),
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

        chdir($appPath);
        $config = include $appPath . '/config/application.config.php';
        //$app = Application::init($config);
        //static::$serviceManager = $app->getServiceManager();
        $smConfig = isset($configuration['service_manager']) ? $configuration['service_manager'] : array();
        $serviceManager = new ServiceManager(new ServiceManagerConfig($smConfig));
        $serviceManager->setService('ApplicationConfig', $config);
        $serviceManager->get('ModuleManager')->loadModules();
        //$serviceManager->get('Application')->bootstrap();
        static::$serviceManager = $serviceManager;
    }

    public static function getServiceManager()
    {
        return static::$serviceManager;
    }

    public static function getAppPath()
    {
        if (static::$appPath === null) {
            static::$appPath = dirname(static::findParentFilePath('init_autoloader.php'));
        }
        return static::$appPath;

    }

    protected static function initAutoloader()
    {
        //$autoloaderPath = static::findParentFilePath('init_autoloader.php');
        $autoloaderPath = static::getAppPath() . '/init_autoloader.php';
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
