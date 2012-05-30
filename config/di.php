<?php
return array(
    'alias' => array(
    ),
    'LibraArticle\Controller\IndexController' => array(
        'parameters' => array(
            'mapper' => 'LibraArticle\Mapper\ArticleDoctrineMapper',
        ),
    ),
    'LibraArticle\Mapper\ArticleDoctrineMapper' => array(
        'parameters' => array(
            'em' => 'Doctrine\ORM\EntityManager',
        ),
    ),
    'orm_driver_chain' => array(
        'parameters' => array(
            'drivers' => array(
                /*
                'libra_article_xml_driver' => array(
                    'class' => 'Doctrine\ORM\Mapping\Driver\XmlDriver',
                    'namespace' => 'LibraArticle\Entity',
                    'paths' => array(__DIR__ . '/xml'),
                    //'file_extension' => '.dcm.xml', // by default '.dcm.xml'
                ),
                */
                'libra_article_annotation_driver' => array(
                    'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                    'namespace' => 'LibraArticle\Entity',
                    'paths' => array(__DIR__ . '/../src/LibraArticle/Entity'),
                ),
            ),
        )
    ),
);
