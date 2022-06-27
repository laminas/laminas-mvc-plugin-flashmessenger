<?php

declare(strict_types=1);

namespace Laminas\Mvc\Plugin\FlashMessenger\View\Helper;

/**
 * Flashmessenger namespace
 *
 * @internal
 */
class FlashMessengerNamespace
{
    /**
     * Namespace name
     */
    private string $name;

    /**
     * String of css classes to be attached
     */
    private string $classes = '';

    /**
     * Templates for the open/close/separators for message tags
     */
    private string $messageCloseString     = '</li></ul>';
    private string $messageOpenFormat      = '<ul%s><li>';
    private string $messageSeparatorString = '</li><li>';

    public function __construct(string $name, string $classes = '')
    {
        $this->name    = $name;
        $this->classes = $classes;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getMessageCloseString(): string
    {
        return $this->messageCloseString;
    }

    public function setMessageCloseString(string $messageCloseString): void
    {
        $this->messageCloseString = $messageCloseString;
    }

    public function getMessageOpenFormat(): string
    {
        return $this->messageOpenFormat;
    }

    public function setMessageOpenFormat(string $messageOpenFormat): self
    {
        $this->messageOpenFormat = $messageOpenFormat;

        return $this;
    }

    public function getMessageSeparatorString(): string
    {
        return $this->messageSeparatorString;
    }

    public function setMessageSeparatorString(string $messageSeparatorString): self
    {
        $this->messageSeparatorString = $messageSeparatorString;

        return $this;
    }

    public function getClasses(): string
    {
        return $this->classes;
    }
}
