<?php

use LibraArticleTest\Bootstrap;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

/**
 * Description of ArticleControllerTest
 * @author duke
 */
class ArticleControllerTest extends AbstractHttpControllerTestCase
{
    protected $traceError = true;

    public function setUp()
    {
        $this->setApplicationConfig(
            include Bootstrap::$appRootPath . '/config/application.config.php'
        );
        parent::setUp();
    }

    public function testIndexActionCanBeAccessed()
    {
        /*$albumTableMock = $this->getMockBuilder('Album\Model\AlbumTable')
                                ->disableOriginalConstructor()
                                ->getMock();

        $albumTableMock->expects($this->once())
                        ->method('fetchAll')
                        ->will($this->returnValue(array()));

        $serviceManager = $this->getApplicationServiceLocator();
        $serviceManager->setAllowOverride(true);
        $serviceManager->setService('Album\Model\AlbumTable', $albumTableMock);*/

        //$this->dispatch('libra-article');
        //$this->dispatch('/');
        //$this->dispatch('libra-article', 'GET', array('alias' => 'home'));
        //$this->dispatch('http://libra-cms/robotx.txt');
        //$this->dispatch('/en/article/home');
        //$this->dispatch('/en/home');
        $this->dispatch('/home');
        $this->assertModulesLoaded(array(
                'DoctrineModule',
                'DoctrineORMModule',
                'Libra',
                'LibraModuleManager',
                'LibraApp',
                'LibraArticle',
                'LibraLocale',
        ));
        $this->assertModuleName('LibraArticle');
        return;
        $this->assertResponseStatusCode(200);

        $this->assertModuleName('LibraArticle');
        //$this->assertControllerName('LibraArticleA\Controller\Article');
        $this->assertControllerName('libra-article/article');
        $this->assertControllerClass('ArticleController');
        $this->assertMatchedRouteName('libra-article');
    }
}