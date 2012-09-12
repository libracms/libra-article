<?php

namespace LibraArticle\Controller;

use Zend\View\Model\ViewModel;
use Zend\Stdlib\RequestInterface as Request;
use Zend\Stdlib\ResponseInterface as Response;
use Doctrine\ORM\ORMInvalidArgumentException;

class AdminArticlesController extends AbstractArticleController
{
    protected $class = 'Article';

    public function viewAction()
    {
        $qb = $this->getRepository()->createQueryBuilder('a');
        $qb->select('a.uid')
           ->where('a.state = :state')
           ->setParameter('state' , 'published')
           ->addGroupBy('a.uid')
           ->orderBy('a.ordering', 'ASC');
        $uids = $qb->getQuery()->getArrayResult();
        $groups = array();
        foreach ($uids as $uid) {
            $groups[$uid['uid']] = $this->getRepository()->findByUid($uid['uid'], array('locale' => 'ASC'));
        }

        return new ViewModel(array(
            'groups'   => $groups,
            'messages' => $this->flashMessenger()->getMessages(),
        ));
    }

    public function update($action = 'unpiblish', $plural = null)
    {
        $ids = $this->params()->fromPost('ids', array());

        if (empty($ids)) {
            $this->flashMessenger()->addMessage('No article was selected');
            return $this->redirect()->toRoute();
        }

        foreach ($ids as $id) {
            try {
                $article = $this->getRepository()->find($id);
                switch ($action) {
                    case 'unpublish':
                        $article->setState('unpublished');
                        $message = '%d articles was unpublished successfully';
                        break;

                    case 'publish':
                        $article->setState('published');
                        $message = '%d articles was published successfully';
                        break;

                    case 'remove':
                        $message = '%d articles was removed successfully';
                        $this->getEntityManager()->remove($article);
                        break;

                    default:
                        throw new \Exception('can\'t do this action');
                        break;
                }
            } catch (ORMInvalidArgumentException $exc) {
                $this->flashMessenger()->addMessage('Query error. Wash selected nonexistent article');
                return $this->redirect()->toRoute();
            }
        }
        $this->getEntityManager()->flush();
        $this->flashMessenger()->addMessage(sprintf($message, count($ids)));
        return $this->redirect()->toRoute();
    }

    public function removeAction()
    {
        return $this->update('remove');
    }
    
    public function publishAction()
    {
        return $this->update('publish');
    }

    public function unpublishAction()
    {
        return $this->update('unpublish');
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
