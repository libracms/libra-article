<?php
return array(
    'router' => array(
        'routes' => array(
            'libra-article' => array(
                'type' => 'Segment',
                'priority' => -100,
                'options' => array(
                    'route' => '[/:alias]',
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
            'admin' => array(
                'child_routes' => array(
                    'libra-article' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/article',
                            'defaults' => array(
                                'module'     => 'libra-article',
                            ),
                        ),
                        'may_terminate' => true,
                        'child_routes' => array(
                            'default' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/article[/:controller[/:action[/:id]]]',
                                    'constraints' => array(
                                        'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                        'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                                        'id'         => '[a-zA-Z0-9_-]*',
                                    ),
                                    'defaults' => array(
                                        'controller' => 'index',
                                        'action'     => 'index',
                                    ),
                                ),
                            ),
                            'article' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/[:id]',
                                    'constraints' => array(
                                        'id'         => '[0-9]*',
                                    ),
                                    'defaults' => array(
                                        'controller' => 'admin-article-restful',
                                    ),
                                ),
                                'may_terminate' => true,
                                'child_routes' => array(
                                    'query' => array(
                                        'type' => 'Query',
                                    ),
                                ),
                            ),
                            'list' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/list[/:action][/:id]',
                                    'constraints' => array(
                                        'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                                        'id'         => '[0-9]*',
                                    ),
                                    'defaults' => array(
                                        'controller' => 'admin-article-list',
                                        'action'     => 'list',
                                    ),
                                ),
                            ),
                            'article' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/article/:action[/:id]',
                                    'constraints' => array(
                                        'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                                        'id'         => '[0-9]*',
                                    ),
                                    'defaults' => array(
                                        'controller' => 'admin-article',
                                        'action'     => 'edit',
                                    ),
                                ),
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'libra-article/index'           => 'LibraArticle\Controller\IndexController',
            'libra-article/admin-article-list'    => 'LibraArticle\Controller\AdminArticleListController',
            'libra-article/admin-article'         => 'LibraArticle\Controller\AdminArticleController',
            'libra-article/admin-article-restful' => 'LibraArticle\Controller\AdminArticleRestfulController',
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
