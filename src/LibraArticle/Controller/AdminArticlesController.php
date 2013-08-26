<?php

namespace LibraArticle\Controller;

use Doctrine\ORM\ORMInvalidArgumentException;
use Libra\Mvc\Controller\AbstractAdminActionController;
use Zend\View\Model\ViewModel;

class AdminArticlesController extends AbstractAdminActionController
{
    public function viewAction()
    {
        $service = $this->getServiceLocator()->get('LibraArticle\Service\Article');
        $groups = $service->getGroups();
        return new ViewModel(array(
            'groups'   => $groups,
            'messages' => $this->flashMessenger()->getMessages(),
        ));
    }

    protected function update($action = 'unpiblish')
    {
        $ids = $this->params()->fromPost('ids', array());
        $service = $this->getServiceLocator()->get('LibraArticle\Service\Article');

        if (empty($ids)) {
            $this->flashMessenger()->addMessage('No article was selected');
            return $this->redirect()->toRoute();
        }

        foreach ($ids as $id) {
            try {
                $article = $service->getArticle($id);
                switch ($action) {
                    case 'unpublish':
                        $service->unpublish($article);
                        $message = '%d articles was unpublished successfully';
                        break;

                    case 'publish':
                        $service->publish($article);
                        $message = '%d articles was published successfully';
                        break;

                    case 'remove':
                        $service->remove($article);
                        $message = '%d articles was removed successfully';
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
}
