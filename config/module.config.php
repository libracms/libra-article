<?php
return array(
    'router' => array(
        'routes' => array(
            'libra-article' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/article[/:controller[/:action[/:id]]]',
                    'constraints' => array(
                        'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'         => '[a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'module'  => 'libra-article',
                        'controller' => 'index',
                        'action'     => 'index',
                    ),
                )
            ),
        ),
    ),
    'controller' => array(
        'classes' => array(
            'libra-article-index' => 'LibraArticle\Controller\IndexController',
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
);
