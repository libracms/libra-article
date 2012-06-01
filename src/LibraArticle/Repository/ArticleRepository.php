<?php

namespace LibraArticle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadata;

/**
 * Description of ArticleRepository
 *
 * @author duke
 */
class ArticleRepository extends EntityRepository
{
    public function non__construct(EntityManager $em, $class = 'LibraArticle\Entity\Article')
    {
        $class = 'LibraArticle\Entity\Article'; //@todo need fix
        if (is_string($class)) {
            $class = $em->getClassMetadata($class);
        }
        if (!$class instanceof ClassMetadata) {
            throw new \InvalidArgumentException("Is not instance of ClassMetadata");
        }
        return parent::__construct($em, $class);
    }

}
