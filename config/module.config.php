<?php
return array(
    'router' => array(
        'routes' => array(
            'libra-article' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/article[/:alias]',
                    'constraints' => array(
                        'alias'      => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        //'__NAMESPACE__' => 'libra-article',
                        'module'     => 'libra-article',
                        'controller' => 'index',
                        'action'     => 'index',
                        'alias'      => 'homepage',
                    ),
                )
            ),
            'libra-article-admin' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/article/:controller[/:action[/:id]]',
                    'constraints' => array(
                        'controller' => 'admin-[a-zA-Z][a-zA-Z0-9_-]*',
                        'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'         => '[a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'module'     => 'libra-article',
                        'controller' => 'admin-index',
                        'action'     => 'index',
                    ),
                )
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'libra-article-index'       => 'LibraArticle\Controller\IndexController',
            'libra-article-admin-index' => 'LibraArticle\Controller\AdminIndexController',
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    'doctrine' => array(
        'driver' => array(
            'libra_article_annotation_driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(__DIR__ . '/../src/LibraArticle/Entity'),
            ),
            'orm_default' => array(
                'drivers' => array(
                    'LibraArticle\Entity' => 'libra_article_annotation_driver',
                ),
            ),
        ),
    ),

);
