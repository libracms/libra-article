<?php

namespace LibraArticle\Model;

use LibraArticle\Repository\ArticleRepository;
use LibraArticle\Entity\Article;
use Doctrine\ORM\EntityManager;

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

    public function __construct(EntityManager $em, $entityName = false)
    {
        $entityName = 'LibraArticle\Entity\Article';
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

    /**
     *
     * @param array $params
     * @return Article
     */
    public function getArticle($params)
    {
        $alias = $params['alias'];
        $repository = $this->repository;
        //$article = $repository->findOneBy(array('alias' => $alias));
        $article = $repository->findOneByAlias($alias);
        return $article;
    }

    public function createArticleFromForm($data)
    {
        $article = new Article();
        $article->setId($data['id']);
        $article->setCreated(null);
        $article->setHeading($data['heading']);
        $article->setAlias($data['alias']);
        $article->setParams(array(
            'headTitle' => $data['headTitle'],
            'metaKeywords' => $data['metaKeywords'],
            'metaDescription' => $data['metaDescription']
        ));
        $article->setContent($data['content']);
        $article->setLocale('');
        $article->setUid(uniqid());
        $article->setCreatedBy(0);
        $article->setModified(null);
        $article->setModifiedBy(0);
        $article->setOrdering(0);
        $article->setState(\LibraArticle\Entity\Article::STATE_PUBLISHED);
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
        $this->em->persist($article);
        $this->em->flush($article);
        return $article;
    }

}
