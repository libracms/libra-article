<?php

namespace LibraArticle\Controller;

use Zend\View\Model\ViewModel;
use LibraArticle\Mapper\ArticleDoctrineMapper;

class IndexController extends AbstractArticleController
{
    protected $class = 'Article';
    protected $entityName = 'LibraArticle\Entity\Article';

    /**
     * Display the article
     * @return \Zend\View\Model\ViewModel
     */
    public function indexAction()
    {
        $params = $this->getEvent()->getRouteMatch()->getParams();
        //$article = $this->model->getArticle($params);
        $criteria = array(
            'alias'  => $params['alias'],
            'locale' => array('', 'en_GB', 'ru_RU'),
        );
        $article = $this->getRepository()->findOneBy($criteria);
        $view = new ViewModel(array(
            'alias' => $params['alias'],
            'article' => $article,
        ));
        
        if (!$article) {
            return $this->notFoundAction();
            //$view->setTemplate('libra-article/index/article-not-found');
        }
        return $view;
    }

    public function setClassName($className = 'Article')
    {
        $this->class = $className;
    }

}
