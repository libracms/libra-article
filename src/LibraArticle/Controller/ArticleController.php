<?php

namespace LibraArticle\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class ArticleController extends AbstractActionController
{
    /**
     * Display the article
     * @return ViewModel
     */
    public function viewAction()
    {
        $alias  = $this->params('alias');
        $locale = $this->params('locale', '');
        $service = $this->getServiceLocator()->get('LibraArticle\Service\Article');
        $article = $service->getArticle($alias, $locale);
        if (!$article) {
            return $this->notFoundAction();
        }
        $this->getEventManager()->trigger('view', $this, array('article' => $article));

        $service->tidifyContent($article);

        $view = new ViewModel(array(
            'alias' => $alias,
            'article' => $article,
        ));

        return $view;
    }
}
