<?php

namespace LibraArticle\Model;

use Doctrine\ORM\EntityManager;
use LibraArticle\Repository\ArticleRepository;
use LibraArticle\Entity\Article;
Use \tidy;

/**
 * Description of ArticleModel
 * dummy model for IndexController, It is not using now
 *
 * @author duke
 */
class ArticleModel
{

    protected $entityName = 'LibraArticle\Entity\Article';
    /**
     * @var EntityManager
     */
    protected $em;
    /**
     * @var ArticleRepository
     */
    protected $repository;

    public function __construct(EntityManager $em, $entityName = 'LibraArticle\Entity\Article')
    {
        $this->repository = $em->getRepository($entityName);
        $this->em = $em;
    }

    public function setRepository(ArticleRepository $repository)
    {
        $this->repository = $repository;
        return $this;
    }

    public function getRepository()
    {
        return $this->repository;
    }

    public function createArticleFromForm($data, $uid = null)
    {
        $hydrator = new \Zend\Stdlib\Hydrator\ClassMethods(true);
        $article = new Article();
        $hydrator->hydrate($data, $article);

        $article->setParams(array(
            'headTitle' => $data['headTitle'],
            'metaKeywords' => $data['metaKeywords'],
            'metaDescription' => $data['metaDescription']
        ));
        $article->setUid(uniqid());

//        $article->setParams(array(
//            'headTitle' => $data['headTitle'],
//            'metaKeywords' => $data['metaKeywords'],
//            'metaDescription' => $data['metaDescription']
//        ));
//        $article->setId($data['id']);
        $article->setCreated(null);
//        $article->setHeading($data['heading']);
//        $article->setAlias($data['alias']);
//        $article->setParams(array(
//            'headTitle' => $data['headTitle'],
//            'metaKeywords' => $data['metaKeywords'],
//            'metaDescription' => $data['metaDescription']
//        ));
//        $article->setContent($data['content']);
//        $article->setLocale('');
        $article->setUid(uniqid());
        $article->setCreatedBy(0);
        $article->setModified(null);
        $article->setModifiedBy(0);
        $article->setOrdering(0);
        $article->setState(\LibraArticle\Entity\Article::STATE_PUBLISHED);
        $article->setUid($uid ?: uniqid());
        $article->setRev(0);
//        if (isset($data['locale'])) $article->setLocale($data['locale']);
        $this->em->persist($article);
        $this->em->flush($article);
        return $article->getId();
    }


    public function updateArticle($id, $data)
    {
        $article = $this->getRepository()->find($id);
        $article->setHeading($data['heading']);
        $article->setAlias($data['alias']);
        $article->setModified(null);
        $article->setContent($data['content']);
        $article->setParams(array(
            'headTitle' => $data['headTitle'],
            'metaKeywords' => $data['metaKeywords'],
            'metaDescription' => $data['metaDescription']
        ));
        $article->setRev($article->getRev() + 1);
        if (isset($data['locale'])) $article->setLocale($data['locale']);
        $this->em->persist($article);
        $this->em->flush($article);
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

    /**
     * tidies the article content
     */
    public function tidifyContent(Article $article, $options = array(
            'indent'         => true,
            'indent-spaces'  => 4,
            'wrap'           => 120,
            'show-body-only' => true,
        ), $encoding = 'utf8')
    {
        if (!class_exists('tidy')) {
            return false;
        }
        $content = $article->getContent();
        $tidy = new tidy();
        $cleanContent = $tidy->repairString($content, $options, $encoding);

        $article->setContent($cleanContent);
        return true;
    }
}
