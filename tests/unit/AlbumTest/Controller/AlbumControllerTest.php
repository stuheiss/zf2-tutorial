<?php

namespace AlbumTest\Controller;

use Mockery as m;
use \Codeception\Util\Debug;

use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

class AlbumControllerTest extends AbstractHttpControllerTestCase
{
    protected $traceError = true;

    public function setUp()
    {
        $this->setApplicationConfig(
            include 'config/application.config.php'
        );
        parent::setUp();
    }

    public function testIndexActionCanBeAccessed()
    {
/*
        $albumTableMock = $this->getMockBuilder('Album\Model\AlbumTable')
                                ->disableOriginalConstructor()
                                ->getMock();

        $albumTableMock->expects($this->once())
                        ->method('fetchAll')
                        ->will($this->returnValue(array()));
*/
        $albumTableMock = m::mock('Album\Model\AlbumTable');
        $albumTableMock->shouldDeferMissing() // don't call real object's constructor if this is a partial mock
                        ->shouldReceive('fetchAll')
                        ->once()
                        ->andReturn(array())
                        ->getMock();

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
/*
        $albumTableMock = $this->getMockBuilder('Album\Model\AlbumTable')
                                ->disableOriginalConstructor()
                                ->getMock();

        $albumTableMock->expects($this->once())
                        ->method('saveAlbum')
                        ->will($this->returnValue(null));
*/
        $albumTableMock = m::mock('Album\Model\AlbumTable');
        $albumTableMock->shouldDeferMissing() // don't call real object's constructor if this is a partial mock
                        ->shouldReceive('saveAlbum')
                        ->once()
                        ->andReturn(null)
                        ->getMock();

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
/*
        $albumTableMock = $this->getMockBuilder('Album\Model\AlbumTable')
                                ->disableOriginalConstructor()
                                ->getMock();

        $albumTableMock->expects($this->once())
                        ->method('saveAlbum')
                        ->will($this->returnValue(null));

        $albumTableMock->expects($this->once())
            ->method('getAlbum')
            ->will($this->returnValue(new \Album\Model\Album()));
*/
        $albumTableMock = m::mock('Album\Model\AlbumTable');
        $albumTableMock->shouldDeferMissing() // don't call real object's constructor if this is a partial mock
                        ->shouldReceive('saveAlbum')
                        ->once()
                        ->andReturn(null)
                        ->shouldReceive('getAlbum')
                        ->once()
                        ->andReturn(new \Album\Model\Album())
                        ->getMock();

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
/*
        $albumTableMock = $this->getMockBuilder('Album\Model\AlbumTable')
                                ->disableOriginalConstructor()
                                ->getMock();
*/
        $albumTableMock = m::mock('Album\Model\AlbumTable');
        $albumTableMock->shouldDeferMissing() // don't call real object's constructor if this is a partial mock
                        ->shouldReceive('deleteAlbum')
                        ->once()
                        ->andReturn(array())
                        ->getMock();

        $serviceManager = $this->getApplicationServiceLocator();
        $serviceManager->setAllowOverride(true);
        $serviceManager->setService('Album\Model\AlbumTable', $albumTableMock);

        $postData = array(
            'title'  => 'Led Zeppelin III',
            'artist' => 'Led Zeppelin',
            'id'     => '',
            'del'    => 'Yes',
        );
        $this->dispatch('/album/delete/1', 'POST', $postData);
        $this->assertResponseStatusCode(302);

        $this->assertRedirectTo('/album');
    }
}
