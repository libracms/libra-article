<?php

namespace LibraArticle\Controller;

use Doctrine\DBAL\DBALException;
use LibraArticle\Entity\Article;
use LibraArticle\Form\ArticleFilter;
use Zend\Stdlib\RequestInterface as Request;
use Zend\Stdlib\ResponseInterface as Response;
use Zend\View\Model\ViewModel;

class AdminArticleController extends AbstractArticleController
{

    protected function highlightKeywords($keywords, $subject)
    {
        if (strpos($keywords, ',') !== false) {
            $keywords = substr($keywords, 0, strpos($keywords, ','));
        }
        $keywords = explode(' ', $keywords);

        foreach ($keywords as $keyword) {
            $subject = preg_replace("/($keyword)/i", "<strong>$1</strong>", $subject);
        }

        return $subject;
    }

    /**
     * 
     * @param type $article
     * @return Article | false
     */
    protected function getGooglePreviewHtml( $article)
    {
        if (!$article) return false;
        //$siteHeadTitle = 'Site Head Title';
        $siteHeadTitle = '';
        $articleHeadTitle = $article->getParam('headTitle') ?: $article->getHeading();
        $headTitle = $siteHeadTitle ? $articleHeadTitle . ' - ' . $siteHeadTitle : $articleHeadTitle;
        $breadcrumb = $this->getServiceLocator()->get('viewRenderer')->plugin('serverUrl')->getHost();
        $navContainer = $this->getServiceLocator()->get('navigation');
        $page = $navContainer->findOneBy('label', $article->getHeading());
        if ($page) {
            $page->setActive ();
            $brc = clone $this->getServiceLocator()->get('viewRenderer')->navigation()->breadcrumbs();
            $breadcrumb .= ' > ' . $brc->render($navContainer);  //@todo: do partial render to remove anchors.
        } else {
            $path = $this->url()->fromRoute('libra-article', array(
                'locale' => $article->getLocale(),
                'alias' => $article->getAlias(),
            ));
            $breadcrumb .= $path;
        }

        $googlePreview = sprintf(
            '
                <div style="margin: 2px 0 0px 0;"><a target="_blank" href="%2$s">%1$s</a></div>
                <div style="color: green;">%3$s</div>
                <div>%4$s</div>
            ',
            $this->highlightKeywords($article->getParam('metaKeywords'), substr($headTitle, 0, 70)),
            $this->url()->fromRoute('libra-article', array('alias' => $article->getAlias())),
            $this->highlightKeywords($article->getParam('metaKeywords'), $breadcrumb),
            $this->highlightKeywords($article->getParam('metaKeywords'), substr($article->getParam('metaDescription'), 0, 170))
        );

        return $googlePreview;
    }

    public function editAction()
    {
        $id = (int) $this->params('id', 0);
        $uid = $this->params()->fromQuery('uid', null);
        $form = $this->getForm();
        $filter = new ArticleFilter;
        $form->setInputFilter($filter);

        $redirectUrl = $this->url()->fromRoute('admin/libra-article/article', array('action'=> 'edit', 'id' => $id));
        $prg = $this->prg($redirectUrl, true);
        if ($prg instanceof Response) {
            return $prg;
        } elseif ($prg !== false) {
        //if ($this->getRequest()->isPost()) {
            $form->setData($prg);
            if ($form->isValid()) {
                try {
                    $data = $form->getData();
                    $this->getEventManager()->trigger('save.pre', $this, array('data' => &$data));
                    if ($id === 0) {
                        $id = $this->getModel()->createArticleFromForm($data, $uid);
                        $savedArticle = $this->getRepository()->find($id);
                        $this->getEventManager()->trigger('create.post', $this, array('article' => $savedArticle));
                    } else {
                        $savedArticle = $this->getModel()->updateArticle($id, $data);
                        $this->getEventManager()->trigger('update.post', $this, array('article' => $savedArticle));
                    }
                    $this->getEventManager()->trigger('save.post', $this, array('article' => $savedArticle));
                    $this->getResponse()->setStatusCode(201);
                    $this->flashMessenger()->setNamespace('libra-article-form-ok')->addMessage('Article is saved');
                    return $this->redirect()->toRoute('admin/libra-article/article', array('id' => $id));
                } catch (DBALException $exc) {
                    $this->flashMessenger()->setNamespace('libra-article-form-err')->addMessage('DB error. May be duplicate entry. ' . $exc->getMessage());
                    $article = $this->getRepository()->find($id);
                }
            } else {
                $article = $this->getRepository()->find($id);
                $this->flashMessenger()->setNamespace('libra-article-form-err')->addMessage('Article wasn\'t saved');
            }

        } elseif ($prg === false) {  //as usual first GET query
        //} elseif ($this->getRequest()->isGet()) {
            /**
             * @var Article Description
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

                $this->getEventManager()->trigger('get', $this, array('data' => &$data));
                $form->setData($data);
            }
        }

        return new ViewModel(array(
            'form'    => $form,
            'article' => $article,
            'id'      => $id,
            'uid'     => $uid,
            'googlePreview' => $this->getGooglePreviewHtml($article),
            'formOkMessages' => $this->flashMessenger()->setNamespace('libra-article-form-ok')->getMessages(),
            'formErrorMessages' => $this->flashMessenger()->setNamespace('libra-article-form-err')->getCurrentMessages(),
        ));
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
