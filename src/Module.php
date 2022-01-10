<?php

declare(strict_types=1);

namespace Laminas\Mvc\Plugin\FlashMessenger;

use Laminas\Mvc\Plugin\FlashMessenger\View;
use Laminas\ServiceManager\Factory\InvokableFactory;

/**
 * @psalm-suppress UndefinedClass
 */
class Module
{
    /**
     * Provide application configuration.
     *
     * Adds aliases and factories for the FlashMessenger plugin.
     *
     * @return array
     */
    public function getConfig()
    {
        return [
            'controller_plugins' => [
                'aliases'   => [
                    'flashmessenger'                               => FlashMessenger::class,
                    'flashMessenger'                               => FlashMessenger::class,
                    'FlashMessenger'                               => FlashMessenger::class,
                    'Laminas\Mvc\Controller\Plugin\FlashMessenger' => FlashMessenger::class,

                    // Legacy Zend Framework aliases
                    // @codingStandardsIgnoreStart
                    'Zend\Mvc\Controller\Plugin\FlashMessenger'           => 'Laminas\Mvc\Controller\Plugin\FlashMessenger',
                    \Zend\Mvc\Plugin\FlashMessenger\FlashMessenger::class => FlashMessenger::class,
                    // @codingStandardsIgnoreEnd
                ],
                'factories' => [
                    FlashMessenger::class => InvokableFactory::class,
                ],
            ],
            'view_helpers'       => [
                'aliases'   => [
                    'flashmessenger' => View\Helper\FlashMessenger::class,
                    'flashMessenger' => View\Helper\FlashMessenger::class,
                    'FlashMessenger' => View\Helper\FlashMessenger::class,

                    // Legacy Zend Framework aliases
                    // @codingStandardsIgnoreStart
                    \Zend\Mvc\Plugin\FlashMessenger\View\Helper\FlashMessenger::class => View\Helper\FlashMessenger::class,
                    'zendviewhelperflashmessenger' => 'laminasviewhelperflashmessenger',
                    // @codingStandardsIgnoreEnd
                ],
                'factories' => [
                    View\Helper\FlashMessenger::class => View\Helper\FlashMessengerFactory::class,
                    'laminasviewhelperflashmessenger' => View\Helper\FlashMessengerFactory::class,
                ],
            ],
        ];
    }
}
