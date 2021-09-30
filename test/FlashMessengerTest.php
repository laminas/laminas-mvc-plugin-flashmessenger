<?php

namespace LaminasTest\Mvc\Plugin\FlashMessenger;

use Laminas\Mvc\Plugin\FlashMessenger\FlashMessenger;
use Laminas\Session\ManagerInterface;
use Laminas\Session\SessionManager;
use PHPUnit\Framework\TestCase;

class FlashMessengerTest extends TestCase
{
    protected function setUp(): void
    {
        $this->helper  = new FlashMessenger();
    }

    public function seedMessages()
    {
        $helper = new FlashMessenger();
        $helper->addMessage('foo');
        $helper->addMessage('bar');
        $helper->addInfoMessage('bar-info');
        $helper->addSuccessMessage('bar-success');
        $helper->addErrorMessage('bar-error');
        unset($helper);
    }

    public function testComposesSessionManagerByDefault()
    {
        $helper  = new FlashMessenger();
        $session = $helper->getSessionManager();
        $this->assertInstanceOf(SessionManager::class, $session);
    }

    public function testSessionManagerIsMutable()
    {
        $session = $this->getMockBuilder(ManagerInterface::class)->getMock();
        $currentSessionManager = $this->helper->getSessionManager();

        $this->helper->setSessionManager($session);
        $this->assertSame($session, $this->helper->getSessionManager());
        $this->assertNotSame($currentSessionManager, $this->helper->getSessionManager());
    }

    public function testUsesContainerNamedAfterClass()
    {
        $container = $this->helper->getContainer();
        $this->assertEquals('FlashMessenger', $container->getName());
    }

    public function testUsesNamespaceNamedDefaultWithNoConfiguration()
    {
        $this->assertEquals('default', $this->helper->getNamespace());
    }

    public function testNamespaceIsMutable()
    {
        $this->helper->setNamespace('foo');
        $this->assertEquals('foo', $this->helper->getNamespace());
    }

    public function testMessengerIsEmptyByDefault()
    {
        $this->assertFalse($this->helper->hasMessages());
        $this->assertFalse($this->helper->hasMessages(FlashMessenger::NAMESPACE_INFO));
    }

    public function testCanAddMessages()
    {
        $this->helper->addMessage('foo');
        $this->assertTrue($this->helper->hasCurrentMessages());

        $this->helper->addMessage('bar-info', FlashMessenger::NAMESPACE_INFO);
        $this->assertTrue($this->helper->hasCurrentMessages(FlashMessenger::NAMESPACE_INFO));
    }

    public function testAddMessagesDoesNotChangeNamespace()
    {
        $this->helper->setNamespace('foo');
        $this->helper->addMessage('bar-info', FlashMessenger::NAMESPACE_INFO);
        $this->assertEquals('foo', $this->helper->getNamespace());
    }

    public function testAddingMessagesDoesNotChangeCount()
    {
        $this->assertCount(0, $this->helper);
        $this->helper->addMessage('foo');
        $this->assertCount(0, $this->helper);
    }

    public function testCanClearMessages()
    {
        $this->seedMessages();
        $this->assertTrue($this->helper->hasMessages());
        $this->assertTrue($this->helper->hasInfoMessages());
        $this->assertTrue($this->helper->hasMessages(FlashMessenger::NAMESPACE_INFO));
        $this->assertTrue($this->helper->hasSuccessMessages());
        $this->assertTrue($this->helper->hasMessages(FlashMessenger::NAMESPACE_SUCCESS));
        $this->assertTrue($this->helper->hasErrorMessages());
        $this->assertTrue($this->helper->hasMessages(FlashMessenger::NAMESPACE_ERROR));

        $this->helper->clearMessages();
        $this->assertFalse($this->helper->hasMessages());
        $this->assertTrue($this->helper->hasInfoMessages());
        $this->assertTrue($this->helper->hasMessages(FlashMessenger::NAMESPACE_INFO));
        $this->assertTrue($this->helper->hasSuccessMessages());
        $this->assertTrue($this->helper->hasMessages(FlashMessenger::NAMESPACE_SUCCESS));
        $this->assertTrue($this->helper->hasErrorMessages());
        $this->assertTrue($this->helper->hasMessages(FlashMessenger::NAMESPACE_ERROR));

        $this->helper->clearMessagesFromNamespace(FlashMessenger::NAMESPACE_INFO);
        $this->assertFalse($this->helper->hasInfoMessages());
        $this->assertFalse($this->helper->hasMessages(FlashMessenger::NAMESPACE_INFO));

        $this->helper->clearMessages(FlashMessenger::NAMESPACE_SUCCESS);
        $this->assertFalse($this->helper->hasSuccessMessages());
        $this->assertFalse($this->helper->hasMessages(FlashMessenger::NAMESPACE_SUCCESS));

        $this->helper->clearMessagesFromContainer();
        $this->assertFalse($this->helper->hasMessages());
        $this->assertFalse($this->helper->hasInfoMessages());
        $this->assertFalse($this->helper->hasMessages(FlashMessenger::NAMESPACE_INFO));
        $this->assertFalse($this->helper->hasSuccessMessages());
        $this->assertFalse($this->helper->hasMessages(FlashMessenger::NAMESPACE_SUCCESS));
        $this->assertFalse($this->helper->hasErrorMessages());
        $this->assertFalse($this->helper->hasMessages(FlashMessenger::NAMESPACE_ERROR));
    }

    public function testCanRetrieveMessages()
    {
        $this->seedMessages();
        $this->assertTrue($this->helper->hasMessages());
        $messages = $this->helper->getMessages();
        $this->assertCount(2, $messages);
        $this->assertContains('foo', $messages);
        $this->assertContains('bar', $messages);

        $messages = $this->helper->getInfoMessages();
        $this->assertCount(1, $messages);
        $this->assertContains('bar-info', $messages);

        $messages = $this->helper->getMessagesFromNamespace(FlashMessenger::NAMESPACE_INFO);
        $this->assertCount(1, $messages);
        $this->assertContains('bar-info', $messages);

        $messages = $this->helper->getMessages(FlashMessenger::NAMESPACE_INFO);
        $this->assertCount(1, $messages);
        $this->assertContains('bar-info', $messages);

        $messages = $this->helper->getSuccessMessages();
        $this->assertCount(1, $messages);
        $this->assertContains('bar-success', $messages);

        $messages = $this->helper->getMessagesFromNamespace(FlashMessenger::NAMESPACE_SUCCESS);
        $this->assertCount(1, $messages);
        $this->assertContains('bar-success', $messages);

        $messages = $this->helper->getMessages(FlashMessenger::NAMESPACE_SUCCESS);
        $this->assertCount(1, $messages);
        $this->assertContains('bar-success', $messages);

        $messages = $this->helper->getErrorMessages();
        $this->assertCount(1, $messages);
        $this->assertContains('bar-error', $messages);

        $messages = $this->helper->getMessagesFromNamespace(FlashMessenger::NAMESPACE_ERROR);
        $this->assertCount(1, $messages);
        $this->assertContains('bar-error', $messages);

        $messages = $this->helper->getMessages(FlashMessenger::NAMESPACE_ERROR);
        $this->assertCount(1, $messages);
        $this->assertContains('bar-error', $messages);
    }

    public function testCanRetrieveCurrentMessages()
    {
        $this->seedMessages();
        $messages = $this->helper->getCurrentMessages();
        $this->assertCount(2, $messages);
        $this->assertContains('foo', $messages);
        $this->assertContains('bar', $messages);

        $messages = $this->helper->getCurrentInfoMessages();
        $this->assertCount(1, $messages);
        $this->assertContains('bar-info', $messages);

        $messages = $this->helper->getCurrentMessagesFromNamespace(FlashMessenger::NAMESPACE_INFO);
        $this->assertCount(1, $messages);
        $this->assertContains('bar-info', $messages);

        $messages = $this->helper->getCurrentMessages(FlashMessenger::NAMESPACE_INFO);
        $this->assertCount(1, $messages);
        $this->assertContains('bar-info', $messages);

        $messages = $this->helper->getCurrentSuccessMessages();
        $this->assertCount(1, $messages);
        $this->assertContains('bar-success', $messages);

        $messages = $this->helper->getCurrentMessagesFromNamespace(FlashMessenger::NAMESPACE_SUCCESS);
        $this->assertCount(1, $messages);
        $this->assertContains('bar-success', $messages);

        $messages = $this->helper->getCurrentMessages(FlashMessenger::NAMESPACE_SUCCESS);
        $this->assertCount(1, $messages);
        $this->assertContains('bar-success', $messages);

        $messages = $this->helper->getCurrentErrorMessages();
        $this->assertCount(1, $messages);
        $this->assertContains('bar-error', $messages);

        $messages = $this->helper->getCurrentMessagesFromNamespace(FlashMessenger::NAMESPACE_ERROR);
        $this->assertCount(1, $messages);
        $this->assertContains('bar-error', $messages);

        $messages = $this->helper->getCurrentMessages(FlashMessenger::NAMESPACE_ERROR);
        $this->assertCount(1, $messages);
        $this->assertContains('bar-error', $messages);
    }

    public function testCanClearCurrentMessages()
    {
        $this->helper->addMessage('foo');
        $this->assertTrue($this->helper->hasCurrentMessages());
        $this->helper->clearCurrentMessages();
        $this->assertFalse($this->helper->hasCurrentMessages());

        $this->seedMessages();
        $this->assertTrue($this->helper->hasCurrentMessages());
        $this->assertTrue($this->helper->hasCurrentInfoMessages());
        $this->assertTrue($this->helper->hasCurrentMessages(FlashMessenger::NAMESPACE_INFO));
        $this->assertTrue($this->helper->hasCurrentSuccessMessages());
        $this->assertTrue($this->helper->hasCurrentMessages(FlashMessenger::NAMESPACE_SUCCESS));
        $this->assertTrue($this->helper->hasCurrentErrorMessages());
        $this->assertTrue($this->helper->hasCurrentMessages(FlashMessenger::NAMESPACE_ERROR));

        $this->helper->clearCurrentMessages();
        $this->assertFalse($this->helper->hasCurrentMessages());
        $this->assertTrue($this->helper->hasCurrentInfoMessages());
        $this->assertTrue($this->helper->hasCurrentMessages(FlashMessenger::NAMESPACE_INFO));
        $this->assertTrue($this->helper->hasCurrentSuccessMessages());
        $this->assertTrue($this->helper->hasCurrentMessages(FlashMessenger::NAMESPACE_SUCCESS));
        $this->assertTrue($this->helper->hasCurrentErrorMessages());
        $this->assertTrue($this->helper->hasCurrentMessages(FlashMessenger::NAMESPACE_ERROR));

        $this->helper->clearCurrentMessagesFromNamespace(FlashMessenger::NAMESPACE_INFO);
        $this->assertFalse($this->helper->hasCurrentInfoMessages());
        $this->assertFalse($this->helper->hasCurrentMessages(FlashMessenger::NAMESPACE_INFO));

        $this->helper->clearCurrentMessages(FlashMessenger::NAMESPACE_SUCCESS);
        $this->assertFalse($this->helper->hasCurrentSuccessMessages());
        $this->assertFalse($this->helper->hasCurrentMessages(FlashMessenger::NAMESPACE_SUCCESS));

        $this->helper->clearCurrentMessagesFromContainer();
        $this->assertFalse($this->helper->hasCurrentMessages());
        $this->assertFalse($this->helper->hasCurrentInfoMessages());
        $this->assertFalse($this->helper->hasCurrentMessages(FlashMessenger::NAMESPACE_INFO));
        $this->assertFalse($this->helper->hasCurrentSuccessMessages());
        $this->assertFalse($this->helper->hasCurrentMessages(FlashMessenger::NAMESPACE_SUCCESS));
        $this->assertFalse($this->helper->hasCurrentErrorMessages());
        $this->assertFalse($this->helper->hasCurrentMessages(FlashMessenger::NAMESPACE_ERROR));
    }

    public function testIterationOccursOverMessages()
    {
        $this->seedMessages();
        $test = [];
        foreach ($this->helper as $message) {
            $test[] = $message;
        }
        $this->assertEquals(['foo', 'bar'], $test);
    }

    public function testCountIsOfMessages()
    {
        $this->seedMessages();
        $this->assertCount(2, $this->helper);
    }

    public function testAddMessageWithLoops()
    {
        $helper  = new FlashMessenger();
        $helper->addMessage('foo');
        $helper->addMessage('bar', null, 2);
        $helper->addMessage('baz', null, 5);
        $this->assertCount(3, $helper->getCurrentMessages());
        $helper->clearCurrentMessages();
        $this->assertCount(0, $helper->getCurrentMessages());
    }
}
