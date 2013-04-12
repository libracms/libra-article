<?php

namespace LibraArticle\Controller;

use Zend\View\Model\ViewModel;

class ArticleController extends AbstractArticleController
{
    protected $entityName = 'LibraArticle\Entity\Article';

    /**
     * Display the article
     * @return ViewModel
     */
    public function viewAction()
    {
        $alias  = $this->params('alias');
        $locale = $this->params('locale', '');
        $article = $this->getRepository()->findByAliasAndLocale($alias, $locale);
        if (!$article) {
            return $this->notFoundAction();
        }
        $this->getEventManager()->trigger('view', $this, array('article' => $article));

        $this->getModel()->tidifyContent($article);

        $view = new ViewModel(array(
            'alias' => $alias,
            'article' => $article,
        ));

        return $view;
    }
}
