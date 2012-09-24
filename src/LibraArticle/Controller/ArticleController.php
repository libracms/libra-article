<?php

namespace LibraArticle\Controller;

use Zend\View\Model\ViewModel;
use LibraArticle\Mapper\ArticleDoctrineMapper;
use tidy;
use LibraArticle\Entity\Article;

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
        $alias  = $this->params('alias');
        $locale = $this->params('locale', '');
        //$article = $this->model->getArticle($params);
        $criteria = array(
            'alias'  => $alias,
            'locale' => $locale,
            'state'  => Article::STATE_PUBLISHED,
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

        /**
         * tidies the article content
         */
        if (class_exists('tidy')) {
            $content = $article->getContent();
            $tidy = new tidy();
            $cleanContent = $tidy->repairString($content, array(
                'indent'         => true,
                'indent-spaces'  => 4,
                'wrap'           => 120,
                'show-body-only' => true,
            ), 'utf8');

            $article->setContent($cleanContent);
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
