<?php

declare(strict_types=1);

namespace Laminas\Mvc\Plugin\FlashMessenger\View\Helper;

use Laminas\Mvc\Plugin\FlashMessenger\FlashMessenger as PluginFlashMessenger;
use Laminas\View\Exception\InvalidArgumentException;
use Laminas\View\Helper\AbstractHelper;
use Laminas\View\Helper\EscapeHtml;
use Laminas\View\Helper\TranslatorAwareTrait;

use function array_walk_recursive;
use function assert;
use function call_user_func_array;
use function gettype;
use function implode;
use function is_object;
use function method_exists;
use function sprintf;

/**
 * Helper to proxy the plugin flash messenger
 * Duck-types against Laminas\I18n\Translator\TranslatorAwareInterface.
 *
 * @method PluginFlashMessenger addMessage(string $string)
 * @method PluginFlashMessenger addInfoMessage(string $string)
 * @method PluginFlashMessenger addSuccessMessage(string $string)
 * @method PluginFlashMessenger addErrorMessage(string $string)
 * @method PluginFlashMessenger addWarningMessage(string $string)
 * @method bool hasMessages(?string $namespace = null)
 * @method bool hasInfoMessages()
 * @method bool hasSuccessMessages()
 * @method bool hasErrorMessages()
 * @method bool hasWarningMessages()
 * @method bool hasCurrentMessages(?string $namespace = null)
 * @method bool hasCurrentInfoMessages()
 * @method bool hasCurrentSuccessMessages()
 * @method bool hasCurrentErrorMessages()
 * @method bool hasCurrentWarningMessages()
 */
class FlashMessenger extends AbstractHelper
{
    use TranslatorAwareTrait;

    /** @var array<string, FlashMessengerNamespace> */
    protected array $namespaces = [];

    protected FlashMessengerNamespace $defaultNamespace;

    /**
     * Flag whether to escape messages
     *
     * @var bool
     */
    protected $autoEscape = true;

    /**
     * Html escape helper
     *
     * @var EscapeHtml|null
     */
    protected $escapeHtmlHelper;

    /**
     * Flash messenger plugin
     *
     * @var PluginFlashMessenger|null
     */
    protected $pluginFlashMessenger;

    public function __construct()
    {
        $this->setDefaultNamespace(new FlashMessengerNamespace(PluginFlashMessenger::NAMESPACE_DEFAULT));
    }

    /**
     * Returns the flash messenger plugin controller
     *
     * @param  string|null $namespace
     * @return FlashMessenger|PluginFlashMessenger|array<array-key, string>
     */
    public function __invoke($namespace = null)
    {
        if (null === $namespace) {
            return $this;
        }
        $flashMessenger = $this->getPluginFlashMessenger();

        return $flashMessenger->getMessagesFromNamespace($namespace);
    }

    /**
     * Proxy the flash messenger plugin controller
     *
     * @param  string $method
     * @param  array  $argv
     * @return mixed
     */
    public function __call($method, $argv)
    {
        $flashMessenger = $this->getPluginFlashMessenger();
        return call_user_func_array([$flashMessenger, $method], $argv);
    }

    /**
     * Render Messages
     *
     * @param  string    $namespace
     * @param  array<array-key, string> $classes
     * @param  null|bool $autoEscape
     * @return string
     */
    public function render($namespace = 'default', array $classes = [], $autoEscape = null)
    {
        $flashMessenger = $this->getPluginFlashMessenger();
        $messages       = $flashMessenger->getMessagesFromNamespace($namespace);
        return $this->renderMessages($namespace, $messages, $classes, $autoEscape);
    }

    /**
     * Render Current Messages
     *
     * @param  string    $namespace
     * @param  array     $classes
     * @param  bool|null $autoEscape
     * @return string
     */
    public function renderCurrent($namespace = 'default', array $classes = [], $autoEscape = null)
    {
        $flashMessenger = $this->getPluginFlashMessenger();
        $messages       = $flashMessenger->getCurrentMessagesFromNamespace($namespace);
        return $this->renderMessages($namespace, $messages, $classes, $autoEscape);
    }

    /**
     * Render Messages
     *
     * @param string    $namespace
     * @param array<array-key, string> $messages
     * @param array<array-key, string> $classes
     * @param bool|null $autoEscape
     * @return string
     */
    protected function renderMessages(
        $namespace = 'default',
        array $messages = [],
        array $classes = [],
        $autoEscape = null
    ) {
        if (empty($messages)) {
            return '';
        }

        $objNamespace = $this->getDefaultNamespace();
        if (isset($this->namespaces[$namespace])) {
            $objNamespace = $this->namespaces[$namespace];
        }

        // Prepare classes for opening tag
        if (empty($classes)) {
            $classes = [$objNamespace->getClasses()];
        }

        $autoEscape ??= $this->autoEscape;

        // Flatten message array
        $escapeHtml           = $this->getEscapeHtmlHelper();
        $messagesToPrint      = [];
        $translator           = $this->getTranslator();
        $translatorTextDomain = $this->getTranslatorTextDomain();
        array_walk_recursive(
            $messages,
            function ($item) use (&$messagesToPrint, $escapeHtml, $autoEscape, $translator, $translatorTextDomain) {
                if ($translator !== null) {
                    $item = $translator->translate(
                        $item,
                        $translatorTextDomain
                    );
                }

                if ($autoEscape) {
                    $messagesToPrint[] = $escapeHtml($item);
                    return;
                }

                $messagesToPrint[] = $item;
            }
        );

        if (empty($messagesToPrint)) {
            return '';
        }

        // Generate markup
        $markup  = sprintf($objNamespace->getMessageOpenFormat(), ' class="' . implode(' ', $classes) . '"');
        $markup .= implode(
            sprintf($objNamespace->getMessageSeparatorString(), ' class="' . implode(' ', $classes) . '"'),
            $messagesToPrint
        );
        $markup .= $objNamespace->getMessageCloseString();
        return $markup;
    }

    /**
     * Set whether or not auto escaping should be used
     *
     * @param  bool $autoEscape
     * @return self
     */
    public function setAutoEscape($autoEscape = true)
    {
        $this->autoEscape = (bool) $autoEscape;
        return $this;
    }

    /**
     * Return whether auto escaping is enabled or disabled
     *
     * @return bool
     */
    public function getAutoEscape()
    {
        return $this->autoEscape;
    }

    /**
     * Set the flash messenger plugin
     *
     * @param  PluginFlashMessenger $pluginFlashMessenger
     * @return FlashMessenger
     * @throws InvalidArgumentException For an invalid $pluginFlashMessenger.
     */
    public function setPluginFlashMessenger($pluginFlashMessenger)
    {
        if (
            ! $pluginFlashMessenger instanceof PluginFlashMessenger
        ) {
            throw new InvalidArgumentException(sprintf(
                '%s expects a %s instance; received %s',
                __METHOD__,
                PluginFlashMessenger::class,
                is_object($pluginFlashMessenger) ? get_class($pluginFlashMessenger) : gettype($pluginFlashMessenger)
            ));
        }

        $this->pluginFlashMessenger = $pluginFlashMessenger;
        return $this;
    }

    /**
     * Get the flash messenger plugin
     *
     * @return PluginFlashMessenger
     */
    public function getPluginFlashMessenger()
    {
        if (null === $this->pluginFlashMessenger) {
            $this->setPluginFlashMessenger(new PluginFlashMessenger());
        }
        assert($this->pluginFlashMessenger instanceof PluginFlashMessenger);

        return $this->pluginFlashMessenger;
    }

    /**
     * Retrieve the escapeHtml helper
     *
     * @return EscapeHtml
     */
    protected function getEscapeHtmlHelper()
    {
        if ($this->escapeHtmlHelper) {
            return $this->escapeHtmlHelper;
        }

        $view = $this->getView();

        if ($view && method_exists($view, 'plugin')) {
            $plugin = $view->plugin('escapehtml');
            assert($plugin instanceof EscapeHtml);
            $this->escapeHtmlHelper = $plugin;
        }

        if (! $this->escapeHtmlHelper instanceof EscapeHtml) {
            $this->escapeHtmlHelper = new EscapeHtml();
        }

        return $this->escapeHtmlHelper;
    }

    /**
     * @return array<string, FlashMessengerNamespace>
     */
    public function getNamespaces(): array
    {
        return $this->namespaces;
    }

    /**
     * @return FlashMessenger
     */
    public function addNamespace(FlashMessengerNamespace $flashMessengerNamespace): self
    {
        $this->namespaces[$flashMessengerNamespace->getName()] = $flashMessengerNamespace;

        return $this;
    }

    public function getDefaultNamespace(): FlashMessengerNamespace
    {
        return $this->defaultNamespace;
    }

    public function setDefaultNamespace(FlashMessengerNamespace $defaultNamespace): void
    {
        $this->defaultNamespace = $defaultNamespace;
    }
}
