<?php

namespace LaminasTest\Mvc\Plugin\FlashMessenger\View\Helper;

use Laminas\I18n\Translator\Translator;
use Laminas\Mvc\Controller\PluginManager;
use Laminas\Mvc\Plugin\FlashMessenger\FlashMessenger as PluginFlashMessenger;
use Laminas\Mvc\Plugin\FlashMessenger\View\Helper\FlashMessenger;
use Laminas\Mvc\Plugin\FlashMessenger\View\Helper\FlashMessengerFactory;
use Laminas\ServiceManager\Config;
use Laminas\ServiceManager\Factory\InvokableFactory;
use Laminas\ServiceManager\ServiceManager;
use Laminas\View\HelperPluginManager;
use PHPUnit\Framework\TestCase;

class FlashMessengerTest extends TestCase
{
    private $mvcPluginClass;
    private $plugin;
    private $helper;

    public function setUp(): void
    {
        $_SESSION = [];
        $this->mvcPluginClass = PluginFlashMessenger::class;
        $this->helper = new FlashMessenger();
        $this->plugin = $this->helper->getPluginFlashMessenger();
    }

    public function seedMessages()
    {
        $helper = new FlashMessenger();
        $helper->addMessage('foo');
        $helper->addMessage('bar');
        $helper->addInfoMessage('bar-info');
        $helper->addSuccessMessage('bar-success');
        $helper->addWarningMessage('bar-warning');
        $helper->addErrorMessage('bar-error');
        unset($helper);
    }

    public function seedCurrentMessages()
    {
        $helper = new FlashMessenger();
        $helper->addMessage('foo');
        $helper->addMessage('bar');
        $helper->addInfoMessage('bar-info');
        $helper->addSuccessMessage('bar-success');
        $helper->addErrorMessage('bar-error');
    }

    public function createServiceManager(array $config = [])
    {
        $config = new Config([
            'services' => [
                'config' => $config,
            ],
            'factories' => [
                'ControllerPluginManager' => function ($services, $name, $options) {
                    return new PluginManager($services, [
                        'aliases' => [
                            'flashmessenger' => $this->mvcPluginClass,
                        ],
                        'factories' => [
                            $this->mvcPluginClass => InvokableFactory::class,
                        ],
                    ]);
                },
                'ViewHelperManager' => function ($services, $name, $options) {
                    return new HelperPluginManager($services, [
                        'factories' => [
                            'flashmessenger' => FlashMessengerFactory::class,
                        ],
                    ]);
                },
            ],
        ]);
        $sm = new ServiceManager();
        $config->configureServiceManager($sm);
        return $sm;
    }

    public function testCanAssertPluginClass()
    {
        $this->assertEquals($this->mvcPluginClass, get_class($this->plugin));
        $this->assertEquals($this->mvcPluginClass, get_class($this->helper->getPluginFlashMessenger()));
        $this->assertSame($this->plugin, $this->helper->getPluginFlashMessenger());
    }

    public function testCanRetrieveMessages()
    {
        $helper = $this->helper;

        $this->assertFalse($helper()->hasMessages());
        $this->assertFalse($helper()->hasInfoMessages());
        $this->assertFalse($helper()->hasSuccessMessages());
        $this->assertFalse($helper()->hasWarningMessages());
        $this->assertFalse($helper()->hasErrorMessages());

        $this->seedMessages();

        $this->assertNotEmpty($helper('default'));
        $this->assertNotEmpty($helper('info'));
        $this->assertNotEmpty($helper('success'));
        $this->assertNotEmpty($helper('warning'));
        $this->assertNotEmpty($helper('error'));

        $this->assertTrue($this->plugin->hasMessages());
        $this->assertTrue($this->plugin->hasInfoMessages());
        $this->assertTrue($this->plugin->hasSuccessMessages());
        $this->assertTrue($this->plugin->hasWarningMessages());
        $this->assertTrue($this->plugin->hasErrorMessages());
    }

    public function testCanRetrieveCurrentMessages()
    {
        $helper = $this->helper;

        $this->assertFalse($helper()->hasCurrentMessages());
        $this->assertFalse($helper()->hasCurrentInfoMessages());
        $this->assertFalse($helper()->hasCurrentSuccessMessages());
        $this->assertFalse($helper()->hasCurrentErrorMessages());

        $this->seedCurrentMessages();

        $this->assertNotEmpty($helper('default'));
        $this->assertNotEmpty($helper('info'));
        $this->assertNotEmpty($helper('success'));
        $this->assertNotEmpty($helper('error'));

        $this->assertFalse($this->plugin->hasCurrentMessages());
        $this->assertFalse($this->plugin->hasCurrentInfoMessages());
        $this->assertFalse($this->plugin->hasCurrentSuccessMessages());
        $this->assertFalse($this->plugin->hasCurrentErrorMessages());
    }

    public function testCanProxyAndRetrieveMessagesFromPluginController()
    {
        $this->assertFalse($this->helper->hasMessages());
        $this->assertFalse($this->helper->hasInfoMessages());
        $this->assertFalse($this->helper->hasSuccessMessages());
        $this->assertFalse($this->helper->hasWarningMessages());
        $this->assertFalse($this->helper->hasErrorMessages());

        $this->seedMessages();

        $this->assertTrue($this->helper->hasMessages());
        $this->assertTrue($this->helper->hasInfoMessages());
        $this->assertTrue($this->helper->hasSuccessMessages());
        $this->assertTrue($this->helper->hasWarningMessages());
        $this->assertTrue($this->helper->hasErrorMessages());
    }

    public function testCanProxyAndRetrieveCurrentMessagesFromPluginController()
    {
        $this->assertFalse($this->helper->hasCurrentMessages());
        $this->assertFalse($this->helper->hasCurrentInfoMessages());
        $this->assertFalse($this->helper->hasCurrentSuccessMessages());
        $this->assertFalse($this->helper->hasCurrentErrorMessages());

        $this->seedCurrentMessages();

        $this->assertTrue($this->helper->hasCurrentMessages());
        $this->assertTrue($this->helper->hasCurrentInfoMessages());
        $this->assertTrue($this->helper->hasCurrentSuccessMessages());
        $this->assertTrue($this->helper->hasCurrentErrorMessages());
    }

    public function testCanDisplayListOfMessages()
    {
        $displayInfoAssertion = '';
        $displayInfo = $this->helper->render('info');
        $this->assertEquals($displayInfoAssertion, $displayInfo);

        $this->seedMessages();

        $displayInfoAssertion = '<ul class="info"><li>bar-info</li></ul>';
        $displayInfo = $this->helper->render('info');
        $this->assertEquals($displayInfoAssertion, $displayInfo);
    }

    public function testCanDisplayListOfCurrentMessages()
    {
        $displayInfoAssertion = '';
        $displayInfo = $this->helper->renderCurrent('info');
        $this->assertEquals($displayInfoAssertion, $displayInfo);

        $this->seedCurrentMessages();

        $displayInfoAssertion = '<ul class="info"><li>bar-info</li></ul>';
        $displayInfo = $this->helper->renderCurrent('info');
        $this->assertEquals($displayInfoAssertion, $displayInfo);
    }

    public function testCanDisplayListOfMessagesByDefaultParameters()
    {
        $helper = $this->helper;
        $this->seedMessages();

        $displayInfoAssertion = '<ul class="default"><li>foo</li><li>bar</li></ul>';
        $displayInfo = $helper()->render();
        $this->assertEquals($displayInfoAssertion, $displayInfo);
    }

    public function testCanDisplayListOfMessagesByDefaultCurrentParameters()
    {
        $helper = $this->helper;
        $this->seedCurrentMessages();

        $displayInfoAssertion = '<ul class="default"><li>foo</li><li>bar</li></ul>';
        $displayInfo = $helper()->renderCurrent();
        $this->assertEquals($displayInfoAssertion, $displayInfo);
    }

    public function testCanDisplayListOfMessagesByInvoke()
    {
        $helper = $this->helper;
        $this->seedMessages();

        $displayInfoAssertion = '<ul class="info"><li>bar-info</li></ul>';
        $displayInfo = $helper()->render('info');
        $this->assertEquals($displayInfoAssertion, $displayInfo);
    }

    public function testCanDisplayListOfCurrentMessagesByInvoke()
    {
        $helper = $this->helper;
        $this->seedCurrentMessages();

        $displayInfoAssertion = '<ul class="info"><li>bar-info</li></ul>';
        $displayInfo = $helper()->renderCurrent('info');
        $this->assertEquals($displayInfoAssertion, $displayInfo);
    }

    public function testCanDisplayListOfMessagesCustomised()
    {
        $this->seedMessages();

        $displayInfoAssertion = '<div class="foo-baz foo-bar"><p>bar-info</p></div>';
        $displayInfo = $this->helper
            ->setMessageOpenFormat('<div%s><p>')
            ->setMessageSeparatorString('</p><p>')
            ->setMessageCloseString('</p></div>')
            ->render('info', ['foo-baz', 'foo-bar']);
        $this->assertEquals($displayInfoAssertion, $displayInfo);
    }

    public function testCanDisplayListOfCurrentMessagesCustomised()
    {
        $this->seedCurrentMessages();

        $displayInfoAssertion = '<div class="foo-baz foo-bar"><p>bar-info</p></div>';
        $displayInfo = $this->helper
            ->setMessageOpenFormat('<div%s><p>')
            ->setMessageSeparatorString('</p><p>')
            ->setMessageCloseString('</p></div>')
            ->renderCurrent('info', ['foo-baz', 'foo-bar']);
        $this->assertEquals($displayInfoAssertion, $displayInfo);
    }

    public function testCanDisplayListOfMessagesCustomisedSeparator()
    {
        $this->seedMessages();

        $displayInfoAssertion = '<div><p class="foo-baz foo-bar">foo</p><p class="foo-baz foo-bar">bar</p></div>';
        $displayInfo = $this->helper
            ->setMessageOpenFormat('<div><p%s>')
            ->setMessageSeparatorString('</p><p%s>')
            ->setMessageCloseString('</p></div>')
            ->render('default', ['foo-baz', 'foo-bar']);
        $this->assertEquals($displayInfoAssertion, $displayInfo);
    }

    public function testCanDisplayListOfCurrentMessagesCustomisedSeparator()
    {
        $this->seedCurrentMessages();

        $displayInfoAssertion = '<div><p class="foo-baz foo-bar">foo</p><p class="foo-baz foo-bar">bar</p></div>';
        $displayInfo = $this->helper
            ->setMessageOpenFormat('<div><p%s>')
            ->setMessageSeparatorString('</p><p%s>')
            ->setMessageCloseString('</p></div>')
            ->renderCurrent('default', ['foo-baz', 'foo-bar']);
        $this->assertEquals($displayInfoAssertion, $displayInfo);
    }

    public function testCanDisplayListOfMessagesCustomisedByConfig()
    {
        $this->seedMessages();

        $config = [
            'view_helper_config' => [
                'flashmessenger' => [
                    'message_open_format' => '<div%s><ul><li>',
                    'message_separator_string' => '</li><li>',
                    'message_close_string' => '</li></ul></div>',
                ],
            ],
        ];

        $services            = $this->createServiceManager($config);
        $helperPluginManager = $services->get('ViewHelperManager');
        $helper              = $helperPluginManager->get('flashmessenger');

        $displayInfoAssertion = '<div class="info"><ul><li>bar-info</li></ul></div>';
        $displayInfo = $helper->render('info');
        $this->assertEquals($displayInfoAssertion, $displayInfo);
    }

    public function testCanDisplayListOfCurrentMessagesCustomisedByConfig()
    {
        $this->seedCurrentMessages();
        $config = [
            'view_helper_config' => [
                'flashmessenger' => [
                    'message_open_format' => '<div%s><ul><li>',
                    'message_separator_string' => '</li><li>',
                    'message_close_string' => '</li></ul></div>',
                ],
            ],
        ];
        $services            = $this->createServiceManager($config);
        $helperPluginManager = $services->get('ViewHelperManager');
        $helper              = $helperPluginManager->get('flashmessenger');

        $displayInfoAssertion = '<div class="info"><ul><li>bar-info</li></ul></div>';
        $displayInfo = $helper->renderCurrent('info');
        $this->assertEquals($displayInfoAssertion, $displayInfo);
    }

    public function testCanDisplayListOfMessagesCustomisedByConfigSeparator()
    {
        $this->seedMessages();

        $config = [
            'view_helper_config' => [
                'flashmessenger' => [
                    'message_open_format' => '<div><ul><li%s>',
                    'message_separator_string' => '</li><li%s>',
                    'message_close_string' => '</li></ul></div>',
                ],
            ],
        ];
        $services            = $this->createServiceManager($config);
        $helperPluginManager = $services->get('ViewHelperManager');
        $helper              = $helperPluginManager->get('flashmessenger');

        $displayInfoAssertion = '<div><ul>'
            . '<li class="foo-baz foo-bar">foo</li>'
            . '<li class="foo-baz foo-bar">bar</li>'
            . '</ul></div>';
        $displayInfo = $helper->render('default', ['foo-baz', 'foo-bar']);
        $this->assertEquals($displayInfoAssertion, $displayInfo);
    }

    public function testCanDisplayListOfCurrentMessagesCustomisedByConfigSeparator()
    {
        $this->seedCurrentMessages();

        $config = [
            'view_helper_config' => [
                'flashmessenger' => [
                    'message_open_format' => '<div><ul><li%s>',
                    'message_separator_string' => '</li><li%s>',
                    'message_close_string' => '</li></ul></div>',
                ],
            ],
        ];
        $services            = $this->createServiceManager($config);
        $helperPluginManager = $services->get('ViewHelperManager');
        $helper              = $helperPluginManager->get('flashmessenger');

        $displayInfoAssertion = '<div><ul>'
            . '<li class="foo-baz foo-bar">foo</li>'
            . '<li class="foo-baz foo-bar">bar</li>'
            . '</ul></div>';
        $displayInfo = $helper->renderCurrent('default', ['foo-baz', 'foo-bar']);
        $this->assertEquals($displayInfoAssertion, $displayInfo);
    }

    public function testCanTranslateMessages()
    {
        $mockTranslator = $this->getMockBuilder(Translator::class)->getMock();
        $mockTranslator
            ->expects($this->exactly(1))
            ->method('translate')
            ->will($this->returnValue('translated message'));

        $this->helper->setTranslator($mockTranslator);
        $this->assertTrue($this->helper->hasTranslator());

        $this->seedMessages();

        $displayAssertion = '<ul class="info"><li>translated message</li></ul>';
        $display = $this->helper->render('info');
        $this->assertEquals($displayAssertion, $display);
    }

    public function testCanTranslateCurrentMessages()
    {
        $mockTranslator = $this->getMockBuilder(Translator::class)->getMock();
        $mockTranslator
            ->expects($this->exactly(1))
            ->method('translate')
            ->will($this->returnValue('translated message'));

        $this->helper->setTranslator($mockTranslator);
        $this->assertTrue($this->helper->hasTranslator());

        $this->seedCurrentMessages();

        $displayAssertion = '<ul class="info"><li>translated message</li></ul>';
        $display = $this->helper->renderCurrent('info');
        $this->assertEquals($displayAssertion, $display);
    }

    public function testAutoEscapeDefaultsToTrue()
    {
        $this->assertTrue($this->helper->getAutoEscape());
    }

    public function testCanSetAutoEscape()
    {
        $this->helper->setAutoEscape(false);
        $this->assertFalse($this->helper->getAutoEscape());

        $this->helper->setAutoEscape(true);
        $this->assertTrue($this->helper->getAutoEscape());
    }

    /**
     * @covers Laminas\Mvc\Plugin\FlashMessenger\View\Helper\FlashMessenger::render
     */
    public function testMessageIsEscapedByDefault()
    {
        $helper = new FlashMessenger;
        $helper->addMessage('Foo<br />bar');
        unset($helper);

        $displayAssertion = '<ul class="default"><li>Foo&lt;br /&gt;bar</li></ul>';
        $display = $this->helper->render('default');
        $this->assertSame($displayAssertion, $display);
    }

    /**
     * @covers Laminas\Mvc\Plugin\FlashMessenger\View\Helper\FlashMessenger::render
     */
    public function testMessageIsNotEscapedWhenAutoEscapeIsFalse()
    {
        $helper = new FlashMessenger;
        $helper->addMessage('Foo<br />bar');
        unset($helper);

        $displayAssertion = '<ul class="default"><li>Foo<br />bar</li></ul>';
        $display = $this->helper->setAutoEscape(false)
                                ->render('default');
        $this->assertSame($displayAssertion, $display);
    }

    /**
     * @covers Laminas\Mvc\Plugin\FlashMessenger\View\Helper\FlashMessenger::render
     */
    public function testCanSetAutoEscapeOnRender()
    {
        $helper = new FlashMessenger;
        $helper->addMessage('Foo<br />bar');
        unset($helper);

        $displayAssertion = '<ul class="default"><li>Foo<br />bar</li></ul>';
        $display = $this->helper->render('default', [], false);
        $this->assertSame($displayAssertion, $display);
    }

    /**
     * @covers Laminas\Mvc\Plugin\FlashMessenger\View\Helper\FlashMessenger::render
     */
    public function testRenderUsesCurrentAutoEscapeByDefault()
    {
        $helper = new FlashMessenger;
        $helper->addMessage('Foo<br />bar');
        unset($helper);

        $this->helper->setAutoEscape(false);
        $displayAssertion = '<ul class="default"><li>Foo<br />bar</li></ul>';
        $display = $this->helper->render('default');
        $this->assertSame($displayAssertion, $display);

        $helper = new FlashMessenger;
        $helper->addMessage('Foo<br />bar');
        unset($helper);

        $this->helper->setAutoEscape(true);
        $displayAssertion = '<ul class="default"><li>Foo&lt;br /&gt;bar</li></ul>';
        $display = $this->helper->render('default');
        $this->assertSame($displayAssertion, $display);
    }

    /**
     * @covers Laminas\Mvc\Plugin\FlashMessenger\View\Helper\FlashMessenger::renderCurrent
     */
    public function testCurrentMessageIsEscapedByDefault()
    {
        $this->helper->addMessage('Foo<br />bar');

        $displayAssertion = '<ul class="default"><li>Foo&lt;br /&gt;bar</li></ul>';
        $display = $this->helper->renderCurrent('default');
        $this->assertSame($displayAssertion, $display);
    }

    /**
     * @covers Laminas\Mvc\Plugin\FlashMessenger\View\Helper\FlashMessenger::renderCurrent
     */
    public function testCurrentMessageIsNotEscapedWhenAutoEscapeIsFalse()
    {
        $this->helper->addMessage('Foo<br />bar');

        $displayAssertion = '<ul class="default"><li>Foo<br />bar</li></ul>';
        $display = $this->helper->setAutoEscape(false)
                                ->renderCurrent('default');
        $this->assertSame($displayAssertion, $display);
    }

    /**
     * @covers Laminas\Mvc\Plugin\FlashMessenger\View\Helper\FlashMessenger::renderCurrent
     */
    public function testCanSetAutoEscapeOnRenderCurrent()
    {
        $this->helper->addMessage('Foo<br />bar');

        $displayAssertion = '<ul class="default"><li>Foo<br />bar</li></ul>';
        $display = $this->helper->renderCurrent('default', [], false);
        $this->assertSame($displayAssertion, $display);
    }

    /**
     * @covers Laminas\Mvc\Plugin\FlashMessenger\View\Helper\FlashMessenger::renderCurrent
     */
    public function testRenderCurrentUsesCurrentAutoEscapeByDefault()
    {
        $this->helper->addMessage('Foo<br />bar');

        $this->helper->setAutoEscape(false);
        $displayAssertion = '<ul class="default"><li>Foo<br />bar</li></ul>';
        $display = $this->helper->renderCurrent('default');
        $this->assertSame($displayAssertion, $display);

        $this->helper->setAutoEscape(true);
        $displayAssertion = '<ul class="default"><li>Foo&lt;br /&gt;bar</li></ul>';
        $display = $this->helper->renderCurrent('default');
        $this->assertSame($displayAssertion, $display);
    }
}
