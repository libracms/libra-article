<?php

namespace LibraArticle\Controller;

use Zend\View\Model\ViewModel;

class AdminIndexController extends AbstractArticleController
{
    protected $class = 'Article';

    public function indexAction()
    {
        $params = $this->getEvent()->getRouteMatch()->getParams();
        $articles = $this->getRepository()->findAll();

        return new ViewModel(array(
            'articles' => $articles,
        ));
    }
    
    public function setClassName($className = 'Article')
    {
        $this->class = $className;
    }

}
