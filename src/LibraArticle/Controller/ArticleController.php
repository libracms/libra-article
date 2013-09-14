<?php

namespace LibraArticle\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use LibraArticle\Module;
use LibraModuleManager\Module as ModuleManager;

class ArticleController extends AbstractActionController
{
    /**
     * Display the article
     * @return ViewModel
     */
    public function viewAction()
    {
        $alias  = $this->params('alias');
        if (ModuleManager::isModulePresent('LibraLocale')) {
            $locale = $this->params('locale', '');
        } else {
            $locale = '';
        }
        $service = $this->getServiceLocator()->get('LibraArticle\Service\Article');
        $article = $service->getArticle($alias, $locale);
        if (!$article) {
            // Compatibility mode to detect by locale.
            // Will be removed in 0.6.0
            if (ModuleManager::isModulePresent('LibraLocale')) {
                $locale = \Locale::getDefault();
                $article = $service->getArticle($alias, $locale);
            }

            if (!$article) {
                return $this->notFoundAction();
            }
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
