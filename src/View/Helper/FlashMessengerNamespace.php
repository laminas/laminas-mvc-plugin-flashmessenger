<?php

declare(strict_types=1);

namespace Laminas\Mvc\Plugin\FlashMessenger\View\Helper;

class FlashMessengerNamespace
{
    /**
     * Namespace name
     */
    protected string $name;

    /**
     * Default attributes for the open format tag
     */
    protected string $classes = '';

    /**
     * Templates for the open/close/separators for message tags
     */
    protected string $messageCloseString     = '</li></ul>';
    protected string $messageOpenFormat      = '<ul%s><li>';
    protected string $messageSeparatorString = '</li><li>';

    public function __construct(string $name)
    {
        $this->name = $name;
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

    public function setClasses(string $classes): self
    {
        $this->classes = $classes;

        return $this;
    }
}
