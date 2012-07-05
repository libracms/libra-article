<?php

namespace LibraArticle;

use Zend\ModuleManager\ModuleManager,
    Zend\ModuleManager\Feature\AutoloaderProviderInterface,
    Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\ModuleEvent;
use Zend\Mvc\MvcEvent;

class Module implements AutoloaderProviderInterface, ConfigProviderInterface
{
    protected static $options;

    public function init(ModuleManager $moduleManager)
    {
        $moduleManager->getEventManager()->attach('loadModules.post', array($this, 'setOptions'));
    }

    public function getConfig()
    {
        $config = include __DIR__ . '/config/module.config.php';
        $configDi['di']['instance'] = include __DIR__ . '/config/di.php';
        $config = array_merge_recursive($config, $configDi);
        $config = array_merge_recursive($config, include __DIR__ . '/config/libra-article.php');
        return $config;
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function setOptions(ModuleEvent $e)
    {
        $config = $e->getConfigListener()->getMergedConfig();
        static::$options = $config['libra_article'];
    }

    public static function getOption($option)
    {
        if (!isset(static::$options[$option])) {
            return null;
        }
        return static::$options[$option];
    }

    /**
     * executes on boostrap
     * @param \Zend\Mvc\MvcEvent $e
     * @return null
     */
    public function onBootstrap(MvcEvent $e)
    {
    }

}
