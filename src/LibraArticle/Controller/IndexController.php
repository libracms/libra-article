<?php

namespace LibraArticle\Controller;

use Zend\Mvc\Controller\ActionController,
    Zend\View\Model\ViewModel;
use LibraArticle\Mapper\ArticleDoctrineMapper;

class IndexController extends ActionController
{
    /**
     *
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;


    public function indexAction()
    {
        $alias = $this->getEvent()->getRouteMatch()->getParam('alias');
        $coll = new \Doctrine\Common\Collections\ArrayCollection();

        // Create a simple "default" Doctrine ORM configuration for XML Mapping
        $isDevMode = true;
        $config = \Doctrine\ORM\Tools\Setup::createXMLMetadataConfiguration(array(__DIR__."/xml"), $isDevMode);
        // or if you prefer yaml or annotations
        //$config = Setup::createAnnotationMetadataConfiguration(array(__DIR__."/entities"), $isDevMode);
        //$config = Setup::createYAMLMetadataConfiguration(array(__DIR__."/config/yaml"), $isDevMode);

        //require_once 'Entities/User.php';
        //require_once 'Entities/Address.php';
        $q = $this->mapper->em->createQuery('select  u from LibraArticle\Entity\User u where u.name = ?1');
        $q->setParameter(1, 'Garfield');
        $resultSet = $q->getResult();
        return new ViewModel(array(
            'alias' => $alias,
            'resultSet' => $resultSet,
        ));
    }

    public function setMapper(ArticleDoctrineMapper $mapper)
    {
        $this->mapper = $mapper;
    }

}
