<?php

declare(strict_types=1);

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
use Psr\Container\ContainerInterface;

use function assert;
use function get_class;

class FlashMessengerTest extends TestCase
{
    /** @var class-string */
    private string $mvcPluginClass;
    private PluginFlashMessenger $plugin;
    private FlashMessenger $helper;

    public function setUp(): void
    {
        $_SESSION             = [];
        $this->mvcPluginClass = PluginFlashMessenger::class;
        $this->helper         = new FlashMessenger();
        $this->plugin         = $this->helper->getPluginFlashMessenger();
    }

    public function seedMessages(): void
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

    public function seedCurrentMessages(): void
    {
        $helper = new FlashMessenger();
        $helper->addMessage('foo');
        $helper->addMessage('bar');
        $helper->addInfoMessage('bar-info');
        $helper->addSuccessMessage('bar-success');
        $helper->addErrorMessage('bar-error');
    }

    private function createServiceManager(array $config = []): ServiceManager
    {
        $config = new Config([
            'services'  => [
                'config' => $config,
            ],
            'factories' => [
                'ControllerPluginManager' => fn(ContainerInterface $services) => new PluginManager($services, [
                    'aliases'   => [
                        'flashmessenger' => $this->mvcPluginClass,
                    ],
                    'factories' => [
                        $this->mvcPluginClass => InvokableFactory::class,
                    ],
                ]),
                'ViewHelperManager'       => fn(ContainerInterface $services) => new HelperPluginManager($services, [
                    'factories' => [
                        FlashMessenger::class => FlashMessengerFactory::class,
                    ],
                    'aliases'   => [
                        'flashmessenger' => FlashMessenger::class,
                    ],
                ]),
            ],
        ]);
        $sm     = new ServiceManager();
        $config->configureServiceManager($sm);
        return $sm;
    }

    private function retrieveViewHelperFrom(ServiceManager $container): FlashMessenger
    {
        $plugins = $container->get('ViewHelperManager');
        self::assertInstanceOf(HelperPluginManager::class, $plugins);
        $helper = $plugins->get('flashmessenger');
        self::assertInstanceOf(FlashMessenger::class, $helper);

        return $helper;
    }

    public function testCanAssertPluginClass(): void
    {
        $this->assertEquals($this->mvcPluginClass, get_class($this->plugin));
        $this->assertEquals($this->mvcPluginClass, get_class($this->helper->getPluginFlashMessenger()));
        $this->assertSame($this->plugin, $this->helper->getPluginFlashMessenger());
    }

    public function testCanRetrieveMessages(): void
    {
        $invoked = ($this->helper)();
        assert($invoked instanceof FlashMessenger);

        $this->assertFalse($invoked->hasMessages());
        $this->assertFalse($invoked->hasInfoMessages());
        $this->assertFalse($invoked->hasSuccessMessages());
        $this->assertFalse($invoked->hasWarningMessages());
        $this->assertFalse($invoked->hasErrorMessages());

        $this->seedMessages();

        $this->assertNotEmpty(($this->helper)('default'));
        $this->assertNotEmpty(($this->helper)('info'));
        $this->assertNotEmpty(($this->helper)('success'));
        $this->assertNotEmpty(($this->helper)('warning'));
        $this->assertNotEmpty(($this->helper)('error'));

        $this->assertTrue($this->plugin->hasMessages());
        $this->assertTrue($this->plugin->hasInfoMessages());
        $this->assertTrue($this->plugin->hasSuccessMessages());
        $this->assertTrue($this->plugin->hasWarningMessages());
        $this->assertTrue($this->plugin->hasErrorMessages());
    }

    public function testCanRetrieveCurrentMessages(): void
    {
        $invoked = ($this->helper)();
        assert($invoked instanceof FlashMessenger);

        $this->assertFalse($invoked->hasCurrentMessages());
        $this->assertFalse($invoked->hasCurrentInfoMessages());
        $this->assertFalse($invoked->hasCurrentSuccessMessages());
        $this->assertFalse($invoked->hasCurrentErrorMessages());

        $this->seedCurrentMessages();

        $this->assertNotEmpty(($this->helper)('default'));
        $this->assertNotEmpty(($this->helper)('info'));
        $this->assertNotEmpty(($this->helper)('success'));
        $this->assertNotEmpty(($this->helper)('error'));

        $this->assertFalse($this->plugin->hasCurrentMessages());
        $this->assertFalse($this->plugin->hasCurrentInfoMessages());
        $this->assertFalse($this->plugin->hasCurrentSuccessMessages());
        $this->assertFalse($this->plugin->hasCurrentErrorMessages());
    }

    public function testCanProxyAndRetrieveMessagesFromPluginController(): void
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

    public function testCanProxyAndRetrieveCurrentMessagesFromPluginController(): void
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

    public function testCanDisplayListOfMessages(): void
    {
        $displayInfoAssertion = '';
        $displayInfo          = $this->helper->render('info');
        $this->assertEquals($displayInfoAssertion, $displayInfo);

        $this->seedMessages();

        $displayInfoAssertion = '<ul class=""><li>bar-info</li></ul>';
        $displayInfo          = $this->helper->render('info');
        $this->assertEquals($displayInfoAssertion, $displayInfo);
    }

    public function testCanDisplayListOfCurrentMessages(): void
    {
        $displayInfoAssertion = '';
        $displayInfo          = $this->helper->renderCurrent('info');
        $this->assertEquals($displayInfoAssertion, $displayInfo);

        $this->seedCurrentMessages();

        $displayInfoAssertion = '<ul class=""><li>bar-info</li></ul>';
        $displayInfo          = $this->helper->renderCurrent('info');
        $this->assertEquals($displayInfoAssertion, $displayInfo);
    }

    public function testCanDisplayListOfMessagesByDefaultParameters(): void
    {
        $this->seedMessages();

        $displayInfoAssertion = '<ul class=""><li>foo</li><li>bar</li></ul>';
        $displayInfo          = $this->helper->render();
        $this->assertEquals($displayInfoAssertion, $displayInfo);
    }

    public function testCanDisplayListOfMessagesByDefaultCurrentParameters(): void
    {
        $this->seedCurrentMessages();

        $this->helper->getDefaultNamespace()->setClasses('default');
        $displayInfoAssertion = '<ul class="default"><li>foo</li><li>bar</li></ul>';
        $displayInfo          = $this->helper->renderCurrent();
        $this->assertEquals($displayInfoAssertion, $displayInfo);
    }

    public function testCanDisplayListOfMessagesByInvoke(): void
    {
        $invoked = ($this->helper)();
        self::assertSame($this->helper, $invoked);
        $this->seedMessages();

        $displayInfoAssertion = '<ul class=""><li>bar-info</li></ul>';
        $displayInfo          = $invoked->render('info');
        $this->assertEquals($displayInfoAssertion, $displayInfo);
    }

    public function testCanDisplayListOfCurrentMessagesByInvoke(): void
    {
        $helper = $this->helper;
        $this->seedCurrentMessages();

        $displayInfoAssertion = '<ul class=""><li>bar-info</li></ul>';
        $invoked              = $helper();
        assert($invoked instanceof FlashMessenger);
        $displayInfo = $invoked->renderCurrent('info');
        $this->assertEquals($displayInfoAssertion, $displayInfo);
    }

    public function testCanDisplayListOfMessagesCustomised(): void
    {
        $this->seedMessages();

        $this->helper->getDefaultNamespace()
            ->setMessageOpenFormat('<div%s><p>')
            ->setMessageSeparatorString('</p><p>')
            ->setMessageCloseString('</p></div>');
        $displayInfoAssertion = '<div class="foo-baz foo-bar"><p>bar-info</p></div>';
        $displayInfo          = $this->helper->render('info', ['foo-baz', 'foo-bar']);
        $this->assertEquals($displayInfoAssertion, $displayInfo);
    }

    public function testCanDisplayListOfCurrentMessagesCustomised(): void
    {
        $this->seedCurrentMessages();

        $this->helper->getDefaultNamespace()
            ->setMessageOpenFormat('<div%s><p>')
            ->setMessageSeparatorString('</p><p>')
            ->setMessageCloseString('</p></div>');
        $displayInfoAssertion = '<div class="foo-baz foo-bar"><p>bar-info</p></div>';
        $displayInfo          = $this->helper->renderCurrent('info', ['foo-baz', 'foo-bar']);
        $this->assertEquals($displayInfoAssertion, $displayInfo);
    }

    public function testCanDisplayListOfMessagesCustomisedSeparator(): void
    {
        $this->seedMessages();

        $this->helper->getDefaultNamespace()
            ->setMessageOpenFormat('<div><p%s>')
            ->setMessageSeparatorString('</p><p%s>')
            ->setMessageCloseString('</p></div>');
        $displayInfoAssertion = '<div><p class="foo-baz foo-bar">foo</p><p class="foo-baz foo-bar">bar</p></div>';
        $displayInfo          = $this->helper->render('default', ['foo-baz', 'foo-bar']);
        $this->assertEquals($displayInfoAssertion, $displayInfo);
    }

    public function testCanDisplayListOfCurrentMessagesCustomisedSeparator(): void
    {
        $this->seedCurrentMessages();

        $this->helper->getDefaultNamespace()
            ->setMessageOpenFormat('<div><p%s>')
            ->setMessageSeparatorString('</p><p%s>')
            ->setMessageCloseString('</p></div>');
        $displayInfoAssertion = '<div><p class="foo-baz foo-bar">foo</p><p class="foo-baz foo-bar">bar</p></div>';
        $displayInfo          = $this->helper->renderCurrent('default', ['foo-baz', 'foo-bar']);
        $this->assertEquals($displayInfoAssertion, $displayInfo);
    }

    public function testCanDisplayListOfMessagesCustomisedByOneDimensialConfig(): void
    {
        $this->seedMessages();

        $config = [
            'view_helper_config' => [
                'flashmessenger' => [
                    'message_open_format'      => '<div%s><ul><li>',
                    'message_separator_string' => '</li><li>',
                    'message_close_string'     => '</li></ul></div>',
                ],
            ],
        ];

        $services = $this->createServiceManager($config);
        $helper   = $this->retrieveViewHelperFrom($services);

        $displayInfoAssertion = '<div class=""><ul><li>bar-info</li></ul></div>';
        $displayInfo          = $helper->render('info');
        $this->assertEquals($displayInfoAssertion, $displayInfo);
    }

    public function testCanDisplayListOfMessagesCustomisedByMultiDimensialConfig(): void
    {
        $this->seedMessages();

        $config = [
            'view_helper_config' => [
                'flashmessenger' => [
                    'info' => [
                        'message_open_format'      => '<div%s><ul><li>',
                        'message_separator_string' => '</li><li>',
                        'message_close_string'     => '</li></ul></div>',
                        'classes'                  => 'info',
                    ],
                ],
            ],
        ];

        $services = $this->createServiceManager($config);
        $helper   = $this->retrieveViewHelperFrom($services);

        $displayInfoAssertion = '<div class="info"><ul><li>bar-info</li></ul></div>';
        $displayInfo          = $helper->render('info');
        $this->assertEquals($displayInfoAssertion, $displayInfo);
    }

    public function testCanDisplayListOfMessagesCustomisedByConfigSeparator(): void
    {
        $this->seedMessages();

        $config   = [
            'view_helper_config' => [
                'flashmessenger' => [
                    'message_open_format'      => '<div><ul><li%s>',
                    'message_separator_string' => '</li><li%s>',
                    'message_close_string'     => '</li></ul></div>',
                ],
            ],
        ];
        $services = $this->createServiceManager($config);
        $helper   = $this->retrieveViewHelperFrom($services);

        $displayInfoAssertion = '<div><ul>'
            . '<li class="foo-baz foo-bar">foo</li>'
            . '<li class="foo-baz foo-bar">bar</li>'
            . '</ul></div>';
        $displayInfo          = $helper->render('default', ['foo-baz', 'foo-bar']);
        $this->assertEquals($displayInfoAssertion, $displayInfo);
    }

    public function testCanDisplayListOfCurrentMessagesCustomisedByConfigSeparator(): void
    {
        $this->seedCurrentMessages();

        $config   = [
            'view_helper_config' => [
                'flashmessenger' => [
                    'message_open_format'      => '<div><ul><li%s>',
                    'message_separator_string' => '</li><li%s>',
                    'message_close_string'     => '</li></ul></div>',
                ],
            ],
        ];
        $services = $this->createServiceManager($config);
        $helper   = $this->retrieveViewHelperFrom($services);

        $displayInfoAssertion = '<div><ul>'
            . '<li class="foo-baz foo-bar">foo</li>'
            . '<li class="foo-baz foo-bar">bar</li>'
            . '</ul></div>';
        $displayInfo          = $helper->renderCurrent('default', ['foo-baz', 'foo-bar']);
        $this->assertEquals($displayInfoAssertion, $displayInfo);
    }

    public function testCanTranslateMessages(): void
    {
        $mockTranslator = $this->getMockBuilder(Translator::class)->getMock();
        $mockTranslator
            ->expects($this->exactly(1))
            ->method('translate')
            ->will($this->returnValue('translated message'));

        $this->helper->getDefaultNamespace()->setClasses('info');
        $this->helper->setTranslator($mockTranslator);
        $this->assertTrue($this->helper->hasTranslator());

        $this->seedMessages();

        $displayAssertion = '<ul class="info"><li>translated message</li></ul>';
        $display          = $this->helper->render('info');
        $this->assertEquals($displayAssertion, $display);
    }

    public function testCanTranslateCurrentMessages(): void
    {
        $mockTranslator = $this->getMockBuilder(Translator::class)->getMock();
        $mockTranslator
            ->expects($this->exactly(1))
            ->method('translate')
            ->will($this->returnValue('translated message'));

        $this->helper->getDefaultNamespace()->setClasses('info');
        $this->helper->setTranslator($mockTranslator);
        $this->assertTrue($this->helper->hasTranslator());

        $this->seedCurrentMessages();

        $displayAssertion = '<ul class="info"><li>translated message</li></ul>';
        $display          = $this->helper->renderCurrent('info');
        $this->assertEquals($displayAssertion, $display);
    }

    public function testAutoEscapeDefaultsToTrue(): void
    {
        $this->assertTrue($this->helper->getAutoEscape());
    }

    public function testCanSetAutoEscape(): void
    {
        $this->helper->setAutoEscape(false);
        $this->assertFalse($this->helper->getAutoEscape());

        $this->helper->setAutoEscape(true);
        $this->assertTrue($this->helper->getAutoEscape());
    }

    /**
     * @covers \Laminas\Mvc\Plugin\FlashMessenger\View\Helper\FlashMessenger::render
     */
    public function testMessageIsEscapedByDefault(): void
    {
        $helper = new FlashMessenger();
        $helper->addMessage('Foo<br />bar');
        unset($helper);

        $displayAssertion = '<ul class=""><li>Foo&lt;br /&gt;bar</li></ul>';
        $display          = $this->helper->render('default');
        $this->assertSame($displayAssertion, $display);
    }

    /**
     * @covers \Laminas\Mvc\Plugin\FlashMessenger\View\Helper\FlashMessenger::render
     */
    public function testMessageIsNotEscapedWhenAutoEscapeIsFalse(): void
    {
        $helper = new FlashMessenger();
        $helper->addMessage('Foo<br />bar');
        unset($helper);

        $displayAssertion = '<ul class=""><li>Foo<br />bar</li></ul>';
        $display          = $this->helper->setAutoEscape(false)
                                ->render('default');
        $this->assertSame($displayAssertion, $display);
    }

    /**
     * @covers \Laminas\Mvc\Plugin\FlashMessenger\View\Helper\FlashMessenger::render
     */
    public function testCanSetAutoEscapeOnRender(): void
    {
        $helper = new FlashMessenger();
        $helper->addMessage('Foo<br />bar');
        unset($helper);

        $displayAssertion = '<ul class=""><li>Foo<br />bar</li></ul>';
        $display          = $this->helper->render('default', [], false);
        $this->assertSame($displayAssertion, $display);
    }

    /**
     * @covers \Laminas\Mvc\Plugin\FlashMessenger\View\Helper\FlashMessenger::render
     */
    public function testRenderUsesCurrentAutoEscapeByDefault(): void
    {
        $helper = new FlashMessenger();
        $helper->addMessage('Foo<br />bar');
        unset($helper);

        $this->helper->setAutoEscape(false);
        $displayAssertion = '<ul class=""><li>Foo<br />bar</li></ul>';
        $display          = $this->helper->render('default');
        $this->assertSame($displayAssertion, $display);

        $helper = new FlashMessenger();
        $helper->addMessage('Foo<br />bar');
        unset($helper);

        $this->helper->setAutoEscape(true);
        $displayAssertion = '<ul class=""><li>Foo&lt;br /&gt;bar</li></ul>';
        $display          = $this->helper->render('default');
        $this->assertSame($displayAssertion, $display);
    }

    /**
     * @covers \Laminas\Mvc\Plugin\FlashMessenger\View\Helper\FlashMessenger::renderCurrent
     */
    public function testCurrentMessageIsEscapedByDefault(): void
    {
        $this->helper->addMessage('Foo<br />bar');

        $displayAssertion = '<ul class=""><li>Foo&lt;br /&gt;bar</li></ul>';
        $display          = $this->helper->renderCurrent('default');
        $this->assertSame($displayAssertion, $display);
    }

    /**
     * @covers \Laminas\Mvc\Plugin\FlashMessenger\View\Helper\FlashMessenger::renderCurrent
     */
    public function testCurrentMessageIsNotEscapedWhenAutoEscapeIsFalse(): void
    {
        $this->helper->addMessage('Foo<br />bar');

        $displayAssertion = '<ul class=""><li>Foo<br />bar</li></ul>';
        $display          = $this->helper->setAutoEscape(false)
                                ->renderCurrent('default');
        $this->assertSame($displayAssertion, $display);
    }

    /**
     * @covers \Laminas\Mvc\Plugin\FlashMessenger\View\Helper\FlashMessenger::renderCurrent
     */
    public function testCanSetAutoEscapeOnRenderCurrent(): void
    {
        $this->helper->addMessage('Foo<br />bar');

        $displayAssertion = '<ul class=""><li>Foo<br />bar</li></ul>';
        $display          = $this->helper->renderCurrent('default', [], false);
        $this->assertSame($displayAssertion, $display);
    }

    /**
     * @covers \Laminas\Mvc\Plugin\FlashMessenger\View\Helper\FlashMessenger::renderCurrent
     */
    public function testRenderCurrentUsesCurrentAutoEscapeByDefault(): void
    {
        $this->helper->addMessage('Foo<br />bar');

        $this->helper->setAutoEscape(false);
        $displayAssertion = '<ul class=""><li>Foo<br />bar</li></ul>';
        $display          = $this->helper->renderCurrent('default');
        $this->assertSame($displayAssertion, $display);

        $this->helper->setAutoEscape(true);
        $displayAssertion = '<ul class=""><li>Foo&lt;br /&gt;bar</li></ul>';
        $display          = $this->helper->renderCurrent('default');
        $this->assertSame($displayAssertion, $display);
    }
}
