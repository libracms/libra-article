<?php

namespace LibraArticle\Controller;

use Zend\Mvc\Controller\ActionController,
    Zend\View\Model\ViewModel;
use LibraArticle\Mapper\ArticleDoctrineMapper;

class IndexController extends ActionController
{
    protected $model;
    protected $em;

    public function setMapper(ArticleDoctrineMapper $mapper)
    {
        $this->mapper = $mapper;
    }

    public function setEntityManager(\Doctrine\ORM\EntityManager $em)
    {
        $this->em = $em;
    }

    public function setModel($model)
    {
        $this->model = $model;
        return $this;
    }

    /**
     * Display the article
     * @return \Zend\View\Model\ViewModel
     */
    public function indexAction()
    {
        $params = $this->getEvent()->getRouteMatch()->getParams();
        $article = $this->model->getArticle($params);
        return new ViewModel(array(
            'alias' => $params['alias'],
            'article' => $article,
        ));
    }

}
