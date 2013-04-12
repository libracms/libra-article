<?php

namespace LibraArticle\Repository;

use Doctrine\ORM\EntityRepository;
use LibraArticle\Entity\Article;

/**
 * Description of ArticleRepository
 *
 * @author duke
 */
class ArticleRepository extends EntityRepository
{
    /**
     * @param array $params
     * @return Article
     */
    public function findByAliasAndLocale($alias, $locale, $oprions = array())
    {
        $criteria = array(
            'alias'  => $alias,
            'locale' => $locale,
            'state'  => isset($oprions['state']) ? $oprions['state'] : Article::STATE_PUBLISHED,
        );
        $article = $this->findOneBy($criteria);

        if (!$article) {
            //look for all '' locales
            $criteria['locale'] = '';
            $article = $this->findOneBy($criteria);
        }
        return $article;
    }

    /**
     * @param array|null of Articles $options
     */
    public function findAllAsGroups()
    {
        $qb = $this->createQueryBuilder('a');
        $qb->select('a.uid')
           ->addGroupBy('a.uid')
           ->orderBy('a.ordering', 'ASC');
        $uids = $qb->getQuery()->getArrayResult();
        $groups = array();
        foreach ($uids as $uid) {
            $groups[$uid['uid']] = $this->findBy(array('uid' => $uid['uid']), array('locale' => 'ASC'));
        }
        return $groups;
    }
}
