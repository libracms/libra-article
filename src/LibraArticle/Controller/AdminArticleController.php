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
        $id = (int) $this->params('id', 0);
        $uid = $this->params()->fromQuery('uid', null);
        $form = $this->getForm();
        $filter = new \LibraArticle\Form\ArticleFilter;
        $form->setInputFilter($filter);

        if ($this->getRequest()->isPost()) {
            $form->setData($this->params()->fromPost());
            if ($form->isValid()) {
                try {
                    if ($id === 0) {
                        $id = $this->getModel()->createArticleFromForm($form->getData(), $uid);
                    } else {
                        $savedArticle = $this->getModel()->updateArticle($id, $form->getData());
                    }
                    $this->getResponse()->setStatusCode(201);
                    $this->flashMessenger('libra-article-form')->addMessage('All OK');
                    return $this->redirect()->toRoute('admin/libra-article/article', array('id' => $id));
                } catch (\Doctrine\DBAL\DBALException $exc) {
                    $this->flashMessenger('libra-article-form')->addMessage('DB error. May be duplicate entry. ' . $exc->getMessage());
                    $article = $this->getRepository()->find($id);
                }
            } else {
                $article = $this->getRepository()->find($id);
            }
        } elseif ($this->getRequest()->isGet()) {
            /**
             * @var \LibraArticle\Entity\Article Description
             */
            $article = $this->getRepository()->find($id);
            if ($article) {
                $data['id'] = $article->getId();
                $data['heading'] = $article->getHeading();
                $data['alias'] = $article->getAlias();
                $data['headTitle'] = $article->getParam('headTitle');
                $data['metaKeywords'] = $article->getParam('metaKeywords');
                $data['metaDescription'] = $article->getParam('metaDescription');
                $data['content'] = $article->getContent();
                $data['locale'] = $article->getLocale();
                $form->setData($data);
            }
        }

        return new ViewModel(array(
            'form' => $form,
            'article' => $article,
            'id'      => $id,
            'uid'      => $uid,
            'formErrorMessages' => $this->flashMessenger('libra-article-form'),
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
