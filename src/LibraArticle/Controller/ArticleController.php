<?php

namespace LibraArticle\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use LibraArticle\Module;

class ArticleController extends AbstractActionController
{
    /**
     * Display the article
     * @return ViewModel
     */
    public function viewAction()
    {
        $alias  = $this->params('alias');
        if (Module::getOption('consider_locale')) {
            $locale = \Locale::getDefault();
            //$localeAlias = $this->params('locale', '');  //@todo: should be used instead of locale
        } else {
            $locale = '';
        }
        $service = $this->getServiceLocator()->get('LibraArticle\Service\Article');
        $article = $service->getArticle($alias, $locale);
        if (!$article) {
            return $this->notFoundAction();
        }
        $this->getEventManager()->trigger('view', $this, array('article' => $article));

        $service->tidifyContent($article, Module::getOption('tidy_config'));

        $view = new ViewModel(array(
            'alias' => $alias,
            'article' => $article,
        ));

        return $view;
    }
}
