<?php

namespace LibraArticle\Service;

use Libra\Mvc\Service\AbstractEntityManagerProvider;
use LibraArticle\Entity\Article as ArticleEntity;
use Zend\Stdlib\Hydrator\ClassMethods;

/**
 * Article service for creating form and etc.
 * @author duke
 */
class Article extends AbstractEntityManagerProvider
{
    public function getEntityName()
    {
        return 'LibraArticle\Entity\Article';
    }

    /**
     * @return \LibraArticle\Repository\ArticleRepository
     */
    public function getRepository()
    {
        return parent::getRepository();
    }

    public function getArticle($idOrAlias, $locale = '')
    {
        if (is_int($idOrAlias)) {
            return $this->getRepository()->find($idOrAlias);
        }

        $article = $this->getRepository()->findByAliasAndLocale($idOrAlias, $locale);
        return $article;
    }

    public function createFromForm($data, $uid = null)
    {
        $hydrator = new ClassMethods(true);
        $article = new Article();
        $hydrator->hydrate($data, $article);

        $article->setParams(array(
            'headTitle' => $data['headTitle'],
            'metaKeywords' => $data['metaKeywords'],
            'metaDescription' => $data['metaDescription']
        ));
        $article->setUid(uniqid());
        $article->setCreated(null);
        $article->setUid(uniqid());
        $article->setCreatedBy(0);
        $article->setModified(null);
        $article->setModifiedBy(0);
        $article->setOrdering(0);
        $article->setState(ArticleEntity::STATE_PUBLISHED);
        $article->setUid($uid ?: uniqid());
        $article->setRev(0);
        $this->getEntityManager()->persist($article);
        $this->getEntityManager()->flush($article);
        return $article;
    }

    /**
     * @param ArticleEntity $article
     * @param array $data
     * @return type
     */
    public function update(ArticleEntity $article, $data)
    {
        $article->setHeading($data['heading']);
        $article->setAlias($data['alias']);
        $article->setModified(null);
        $article->setContent($data['content']);
        $article->setParams(array(
            'headTitle' => $data['headTitle'],
            'metaKeywords' => $data['metaKeywords'],
            'metaDescription' => $data['metaDescription']
        ));
        $article->setRevision($article->getRevision() + 1);
        if (isset($data['locale'])) $article->setLocale($data['locale']);
        $em = $this->getEntityManager();
        $em->persist($article);
        $em->flush($article);
        return $article;
    }

    public function getSitemap(\Zend\View\Helper\Url $urlHelper)
    {
        $criteria = array(
            'state'  => Article::STATE_PUBLISHED,
        );
        $orderBy = array('ordering' => 'ASC');
        $articles = $this->getRepository()->findBy($criteria, $orderBy);
        $urlset = array();
        foreach ($articles as $article) {
            $urlset[] = array(
//            'loc'        => $urlHelper('libra-article', array(
//                              'alias' => $article->getAlias(),
//                              'locale' => $article->getLocale())), //don't work properly
            'loc'        => $urlHelper('libra-article', array('alias' => $article->getAlias())),
            'lastmod'    => $article->getModified()->format(\DateTime::ATOM),
            'changefreq' => null,
            'priority'   => null,
            );
        }
        return $urlset;
    }

    protected function highlightKeywords($keywords, $subject)
    {
        if (strpos($keywords, ',') !== false) {
            $keywords = substr($keywords, 0, strpos($keywords, ','));
        }
        $keywords = explode(' ', $keywords);

        foreach ($keywords as $keyword) {
            $subject = preg_replace("/($keyword)/i", "<strong>$1</strong>", $subject);
        }

        return $subject;
    }

    /**
     *
     * @param Article $article
     * @return Article | false
     */
    public function getGooglePreviewHtml($article, $urlHelper)
    {
        if (!$article) return false;
        //$siteHeadTitle = 'Site Head Title';
        $siteHeadTitle = '';
        $articleHeadTitle = $article->getParam('headTitle') ?: $article->getHeading();
        $headTitle = $siteHeadTitle ? $articleHeadTitle . ' - ' . $siteHeadTitle : $articleHeadTitle;
        $breadcrumb = $this->getServiceLocator()->get('viewRenderer')->plugin('serverUrl')->getHost();
        $navContainer = $this->getServiceLocator()->get('navigation');
        $page = $navContainer->findOneBy('label', $article->getHeading());
        if ($page) {
            $page->setActive ();
            $brc = clone $this->getServiceLocator()->get('viewRenderer')->navigation()->breadcrumbs();
            $breadcrumb .= ' > ' . $brc->render($navContainer);  //@todo: do partial render to remove anchors.
        } else {
            $path = $urlHelper->fromRoute('libra-article', array(
                'locale' => $article->getLocale(),
                'alias' => $article->getAlias(),
            ));
            $breadcrumb .= $path;
        }

        $googlePreview = sprintf(
            '
                <div style="margin: 2px 0 0px 0;"><a target="_blank" href="%2$s">%1$s</a></div>
                <div style="color: green;">%3$s</div>
                <div>%4$s</div>
            ',
            $this->highlightKeywords($article->getParam('metaKeywords'), substr($headTitle, 0, 70)),
            $urlHelper->fromRoute('libra-article', array('alias' => $article->getAlias())),
            $this->highlightKeywords($article->getParam('metaKeywords'), $breadcrumb),
            $this->highlightKeywords($article->getParam('metaKeywords'), substr($article->getParam('metaDescription'), 0, 170))
        );

        return $googlePreview;
    }

    /**
     * tidies the article content
     */
    public function tidifyContent(
        ArticleEntity $article,
        $options = array(
            'indent'         => true,
            'indent-spaces'  => 4,
            'wrap'           => 120,
            'show-body-only' => true,
        ),
        $encoding = 'utf8'
    )
    {
        if (!class_exists('tidy')) {
            return false;
        }
        $content = $article->getContent();
        $tidy = new \tidy();
        $cleanContent = $tidy->repairString($content, $options, $encoding);

        $article->setContent($cleanContent);
        return true;
    }

    public function getGroups()
    {
        return $this->getRepository()->findAllAsGroups();
    }

    public function publish(ArticleEntity $article)
    {
        $article->setState('published');
        $this->getEntityManager()->flush();
    }

    public function unpublish(ArticleEntity $article)
    {
        $article->setState('unpublished');
        $this->getEntityManager()->flush();
    }

    public function remove(ArticleEntity $article)
    {
        $this->getEntityManager()->remove($article);
        $this->getEntityManager()->flush();
    }
}
