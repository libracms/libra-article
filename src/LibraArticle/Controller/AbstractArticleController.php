<?php

/**
 * eJoom.com
 * 
 * This source file is subject to the new BSD license.
 */

namespace LibraArticle\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use LibraArticle\Form\ArticleForm;
use Doctrine\ORM\EntityManager;
use LibraArticle\Model\ArticleModel;
use LibraArticle\Repository\ArticleRepository;

/**
 * Description of AbstractArticleController
 *
 * @author duke
 */
abstract class AbstractArticleController extends AbstractActionController
{
    protected $entityName = 'LibraArticle\Entity\Article';
    protected $form;
    protected $em;
    protected $model;

    public function setForm($form)
    {
        $this->form = $form;
        return $this->form;
    }
    
    public function getForm()
    {
        if (null === $this->form) {
            $this->form = new ArticleForm;
        }
        return $this->form;
    }

    /**
     *
     * @param EntityManager $em
     */
    public function setEntityManager(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     *
     * @return EntityManager;
     */
    public function getEntityManager()
    {
        if ($this->em === null) {
            $this->em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        }
        return $this->em;
    }

    /**
     * @return ArticleRepository
     */
    public function getRepository()
    {
        return $this->getEntityManager()->getRepository($this->entityName);
    }

    public function setModel($model)
    {
        $this->model = $model;
        return $this;
    }

    /**
     *
     * @param type $model
     * @return ArticleModel
     */
    public function getModel()
    {
        if ($this->model === null) {
            $this->model = new ArticleModel($this->getEntityManager());
        }
        return $this->model;
    }
}
