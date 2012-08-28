<?php

namespace LibraArticle\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\ViewModel;
use Zend\Stdlib\RequestInterface as Request;
use Zend\Stdlib\ResponseInterface as Response;

class AdminArticleRestfulController extends AbstractRestfulController
{
    protected $entityName = 'LibraArticle\Entity\Article';
    protected $entityManager;

    public function getList()
    {
        $articles = $this->getRepository()->findAll();
        $view = new ViewModel(array(
            'articles' => $articles,
        ));
        return $view;
    }

    public function get($id)
    {
        /**
         * @var \LibraArticle\Entity\Article Description
         */
        $article = $this->getRepository()->find($id);

        $form = $this->getForm();
        $filter = new \LibraArticle\Form\ArticleFilter;
        $form->setInputFilter($filter);
        $data['id'] = $article->getId();
        $data['headline'] = $article->getHeadline();
        $data['alias'] = $article->getAlias();
        $data['metaKeys'] = $article->getParam('metaKeys');
        $data['metaDescription'] = $article->getParam('metaDescription');
        $data['content'] = $article->getContent();
        $form->setData($data);

        return new ViewModel(array(
            'form' => $form,
            'article' => $article,
        ));
    }

    public function create($data)
    {
        $form = $this->getForm();
        $filter = new \LibraArticle\Form\ArticleFilter;
        $form->setInputFilter($filter);
        $form->setData($data);
        if (!empty($data) && $form->isValid()) {
            $model = new \LibraArticle\Model\ArticleModel($this->getEntityManager());
            $id = $model->createArticleFromForm($form->getData());
            if ($id > 0) {
                $this->getResponse()->setStatusCode(201);
                return $this->redirect()->toRoute('admin/libra-article/article/' . $id);
            }
        }
        return new ViewModel(array(
            'form' => $form,
        ));
    }

    public function update($id, $data)
    {
        $form = $this->getForm();
        $filter = new \LibraArticle\Form\ArticleFilter;
        $form->setInputFilter($filter);
        $form->setData($data);
        if (!empty($data) && $form->isValid()) {
            $model = new \LibraArticle\Model\ArticleModel($this->getEntityManager());
            $result = $model->updateArticle($id, $form->getData());
            if ($result) {
                //$this->getResponse()->setStatusCode(201);
                return $this->redirect()->toRoute('admin/libra-article/article/' . $id);
            }
        }
        return new ViewModel(array(
            'form' => $form,
        ));
    }

    public function delete($id)
    {

    }

    /**
     *
     * @return \LibraArticle\Form\ArticleForm
     */
    public function getForm()
    {
        return new \LibraArticle\Form\ArticleForm;
        return $this->serviceLocator()->get('LibraArticle\Form\ArticleForm');
    }

    /**
     *
     * @return \Doctrine\ORM\EntityManager;
     */
    public function getEntityManager()
    {
        if ($this->entityManager === null) {
            $this->entityManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        }
        return $this->entityManager;
    }

    /**
     *
     * @return \LibraArticle\Repository\ArticleRepository
     */
    public function getRepository()
    {
        return $this->getEntityManager()->getRepository($this->entityName);
    }

    public function dispatch(Request $request, Response $response = null)
    {
        $user = $this->zfcuserauthentication()->getIdentity();
        if (!$user) {
            $this->layout()->setTemplate('layout/admin-default/login-layout');
            return $this->redirect()->toRoute('zfcuser/login');
            return $this->redirect()->toRoute('admin/libra-app/login');
        }

        return parent::dispatch($request, $response);
    }
}
