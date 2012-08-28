<?php

namespace LibraArticle\Controller;

use Zend\View\Model\ViewModel;
use Zend\Stdlib\RequestInterface as Request;
use Zend\Stdlib\ResponseInterface as Response;

class AdminArticleController extends AbstractArticleController
{
    protected $class = 'Article';

    public function listAction()
    {
        $params = $this->getEvent()->getRouteMatch()->getParams();
        $articles = $this->getRepository()->findAll();

        return new ViewModel(array(
            'articles' => $articles,
        ));
    }


    public function editAction()
    {
        $id = $this->params('id', 0);
        /**
         * @var \LibraArticle\Entity\Article Description
         */
        $article = $this->getRepository()->find($id);

        $form = $this->getForm();
        $filter = new \LibraArticle\Form\ArticleFilter;
        $form->setInputFilter($filter);

        if ($this->getRequest()->isPost()) {
            $form->setData($this->params()->fromPost());
            if ($form->isValid()) {
                if ($id === 0) {
                    $newId = $this->getModel()->createArticleFromForm($form->getData());
                } else {
                    $savedArticle = $this->getModel()->updateArticle($id, $form->getData());
                }
                $this->getResponse()->setStatusCode(201);
                //$this->flushMessanger('All OK);
                return $this->redirect()->toRoute('admin/libra-article/article/', array('id' => $id));
            }
        } elseif ($this->getRequest()->isGet()) {
            $data['id'] = $article->getId();
            $data['headline'] = $article->getHeadline();
            $data['alias'] = $article->getAlias();
            $data['metaKeys'] = $article->getParam('metaKeys');
            $data['metaDescription'] = $article->getParam('metaDescription');
            $data['content'] = $article->getContent();
            $form->setData($data);
        }

        return new ViewModel(array(
            'form' => $form,
            'article' => $article,
        ));
    }


    public function setClassName($className = 'Article')
    {
        $this->class = $className;
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
