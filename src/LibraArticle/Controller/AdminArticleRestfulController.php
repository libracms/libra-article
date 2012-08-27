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
        $params = $this->getEvent()->getRouteMatch()->getParams();
        $articles = $this->getRepository()->findAll();

        $view = new ViewModel(array(
            'articles' => $articles,
        ));
        return $view;
    }

    public function get($id)
    {
        $id = $this->params('id');
        $article = $this->getRepository()->find($id);

        return new ViewModel(array(
            'article' => $article,
        ));
    }

    public function create($data)
    {
        $form = $this->getForm();
    }

    public function update($id, $data)
    {

    }

    public function delete($id)
    {

    }

    public function getForm()
    {
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
