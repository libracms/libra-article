<?php

namespace LibraArticle\Controller;

use ArrayObject;
use Doctrine\DBAL\DBALException;
use Libra\Mvc\Controller\AbstractAdminActionController;
use LibraArticle\Form\ArticleFilter;
use LibraArticle\Form\ArticleForm;
use Zend\EventManager\Event;
use Zend\Stdlib\ResponseInterface as Response;
use Zend\View\Model\ViewModel;

class AdminArticleController extends AbstractAdminActionController
{
    public function editAction()
    {
        $id = (int) $this->params('id', 0);
        $uid = $this->params()->fromQuery('uid', null);
        $form = new ArticleForm();
        $filter = new ArticleFilter;
        $form->setInputFilter($filter);
        $service = $this->getServiceLocator()->get('LibraArticle\Service\Article');
        $eventManager = $this->getEventManager();

        $eventManager->trigger('form.init', $this, array(
            'form' => $form,
        ));

        $redirectUrl = $this->url()->fromRoute('admin/libra-article/article', array('action'=> 'edit', 'id' => $id));
        $prg = $this->prg($redirectUrl, true);
        if ($prg instanceof Response) {
            return $prg;
        } elseif ($prg !== false) {
            $form->setData($prg);
            if ($form->isValid()) {
                try {
                    $data = $form->getData();
                    $data = new ArrayObject($data);  // To keep modification by reference
                    $eventManager->trigger('save.pre', $this, array('data' => $data));

                    $eventPost = new Event(null, $this, array(
                        'data' => $data,
                        'rawData' => $prg,
                    ));
                    if ($id === 0) {
                        $article = $service->createFromForm($data, $uid);
                        $id = $article->getId();  //redutant
                        $eventPost->setParam('article', $article);
                        $responses = $eventManager->trigger($eventPost->setName('create.post'));
                    } else {
                        $article = $service->getArticle($id);
                        $article = $service->update($article, $data);
                        $eventPost->setParam('article', $article);
                        $responses = $eventManager->trigger($eventPost->setName('update.post'));
                    }
                    $responsesOnSave = $eventManager->trigger($eventPost->setName('save.post'));

                    // Save article if there was detected changes
                    if ($responsesOnSave->contains(true) || $responses->contains(true)) {
                        $service->getEntityManager()->flush($article);
                    }

                    $this->getResponse()->setStatusCode(201);
                    $this->flashMessenger()->setNamespace('libra-article-form-ok')->addMessage('Article is saved');
                    return $this->redirect()->toRoute('admin/libra-article/article', array('id' => $id));
                } catch (DBALException $exc) {
                    $this->flashMessenger()->setNamespace('libra-article-form-err')->addMessage('DB error. May be duplicate entry. ' . $exc->getMessage());
                    $article = $service->getArticle($id);
                }
            } else {
                $article = $service->getArticle($id);
                $this->flashMessenger()->setNamespace('libra-article-form-err')->addMessage('Article wasn\'t saved');
            }

        } elseif ($prg === false) {  //as usual first GET query
            $article = $service->getArticle($id);
            if ($article) {
                $data['id'] = $article->getId();
                $data['heading'] = $article->getHeading();
                $data['alias'] = $article->getAlias();
                $data['headTitle'] = $article->getParam('headTitle');
                $data['metaKeywords'] = $article->getParam('metaKeywords');
                $data['metaDescription'] = $article->getParam('metaDescription');
                $data['content'] = $article->getContent();
                $data['locale'] = $article->getLocale();

                $data = new ArrayObject($data);  // To keep modification by reference
                $eventManager->trigger('get', $this, array(
                    'article' => $article,
                    'data' => $data,
                ));
                $form->setData($data);
            }
        }

        return new ViewModel(array(
            'form'    => $form,
            'article' => $article,
            'id'      => $id,
            'uid'     => $uid,
            'googlePreview' => $service->getGooglePreviewHtml($article, $this->url()),
            'formOkMessages' => $this->flashMessenger()->setNamespace('libra-article-form-ok')->getMessages(),
            'formErrorMessages' => $this->flashMessenger()->setNamespace('libra-article-form-err')->getCurrentMessages(),
        ));
    }
}
