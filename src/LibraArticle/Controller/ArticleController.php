<?php

namespace LibraArticle\Controller;

use Zend\View\Model\ViewModel;
use LibraArticle\Mapper\ArticleDoctrineMapper;

class ArticleController extends AbstractArticleController
{
    protected $class = 'Article';
    protected $entityName = 'LibraArticle\Entity\Article';

    /**
     * Display the article
     * @return \Zend\View\Model\ViewModel
     */
    public function viewAction()
    {
        $routeMatch = $this->getEvent()->getRouteMatch();
        $alias = $routeMatch->getParam('alias', $routeMatch->getParam('param'));   //'param' if called by default router
        $locale = $routeMatch->getParam('locale');
        //$article = $this->model->getArticle($params);
        $criteria = array(
            'alias'  => $alias,
            'locale' => $locale,
        );
        $article = $this->getRepository()->findOneBy($criteria);

        if (!$article) {
            //look for all '' locales
            $criteria['locale'] = '';
            $article = $this->getRepository()->findOneBy($criteria);
            if (!$article) {
                return $this->notFoundAction();
            }
        }

        $view = new ViewModel(array(
            'alias' => $alias,
            'article' => $article,
        ));

        return $view;
    }

    public function setClassName($className = 'Article')
    {
        $this->class = $className;
    }

}
