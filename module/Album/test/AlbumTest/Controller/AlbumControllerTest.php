<?php

namespace AlbumTest\Controller;

use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

class AlbumControllerTest extends AbstractHttpControllerTestCase
{
    protected $traceError = true;

    public function setUp()
    {
        $this->setApplicationConfig(
            include '/srv/httpd/www.awesome.dev/zf2-tutorial/config/application.config.php'
        );
        parent::setUp();
    }

    public function _testIndexActionCanBeAccessed()
    {
        $this->dispatch('/album');
        $this->assertResponseStatusCode(200);

        $this->assertModuleName('Album');
        $this->assertControllerName('Album\Controller\Album');
        $this->assertControllerClass('AlbumController');
        $this->assertMatchedRouteName('album');
    }

    public function testIndexActionCanBeAccessed()
    {
        $albumTableMock = $this->getMockBuilder('Album\Model\AlbumTable')
                                ->disableOriginalConstructor()
                                ->getMock();

        $albumTableMock->expects($this->once())
                        ->method('fetchAll')
                        ->will($this->returnValue(array()));

        $serviceManager = $this->getApplicationServiceLocator();
        $serviceManager->setAllowOverride(true);
        $serviceManager->setService('Album\Model\AlbumTable', $albumTableMock);

        $this->dispatch('/album');
        $this->assertResponseStatusCode(200);

        $this->assertModuleName('Album');
        $this->assertControllerName('Album\Controller\Album');
        $this->assertControllerClass('AlbumController');
        $this->assertMatchedRouteName('album');
    }

    public function testAddActionRedirectsAfterValidPost()
    {
        $albumTableMock = $this->getMockBuilder('Album\Model\AlbumTable')
                                ->disableOriginalConstructor()
                                ->getMock();

        $albumTableMock->expects($this->once())
                        ->method('saveAlbum')
                        ->will($this->returnValue(null));

        $serviceManager = $this->getApplicationServiceLocator();
        $serviceManager->setAllowOverride(true);
        $serviceManager->setService('Album\Model\AlbumTable', $albumTableMock);

        $postData = array(
            'title'  => 'Led Zeppelin III',
            'artist' => 'Led Zeppelin',
            'id'     => '',
        );
        $this->dispatch('/album/add', 'POST', $postData);
        $this->assertResponseStatusCode(302);

        $this->assertRedirectTo('/album');
    }

    public function testEditActionRedirectsAfterValidPost()
    {
        $albumTableMock = $this->getMockBuilder('Album\Model\AlbumTable')
                                ->disableOriginalConstructor()
                                ->getMock();

        $albumTableMock->expects($this->once())
                        ->method('saveAlbum')
                        ->will($this->returnValue(null));

        $albumTableMock->expects($this->once())
            ->method('getAlbum')
            ->will($this->returnValue(new \Album\Model\Album()));

        $serviceManager = $this->getApplicationServiceLocator();
        $serviceManager->setAllowOverride(true);
        $serviceManager->setService('Album\Model\AlbumTable', $albumTableMock);

        $postData = array(
            'title'  => 'Led Zeppelin III',
            'artist' => 'Led Zeppelin',
            'id'     => '',
        );
        $this->dispatch('/album/edit/1', 'POST', $postData);
        $this->assertResponseStatusCode(302);

        $this->assertRedirectTo('/album');
    }

    public function testDeleteActionRedirectsAfterValidPost()
    {
        $albumTableMock = $this->getMockBuilder('Album\Model\AlbumTable')
                                ->disableOriginalConstructor()
                                ->getMock();

        $serviceManager = $this->getApplicationServiceLocator();
        $serviceManager->setAllowOverride(true);
        $serviceManager->setService('Album\Model\AlbumTable', $albumTableMock);

        $postData = array(
            'title'  => 'Led Zeppelin III',
            'artist' => 'Led Zeppelin',
            'id'     => '',
        );
        $this->dispatch('/album/delete/1', 'POST', $postData);
        $this->assertResponseStatusCode(302);

        $this->assertRedirectTo('/album');
    }
}
