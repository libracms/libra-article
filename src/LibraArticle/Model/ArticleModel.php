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

}
