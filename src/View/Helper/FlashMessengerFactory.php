<?php

/**
 * @see       https://github.com/laminas/laminas-mvc-plugin-flashmessenger for the canonical source repository
 * @copyright https://github.com/laminas/laminas-mvc-plugin-flashmessenger/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-mvc-plugin-flashmessenger/blob/master/LICENSE.md New BSD License
 */

namespace Laminas\Mvc\Plugin\FlashMessenger\View\Helper;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;

class FlashMessengerFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ContainerInterface $container
     * @param string $name
     * @param null|array $options
     * @return FlashMessenger
     */
    public function __invoke(ContainerInterface $container, $name, array $options = null)
    {
        // test if we are using Laminas\ServiceManager v2 or v3
        if (! method_exists($container, 'configure')) {
            $container = $container->getServiceLocator();
        }
        $helper                  = new FlashMessenger();
        $controllerPluginManager = $container->get('ControllerPluginManager');
        $flashMessenger          = $controllerPluginManager->get('flashmessenger');

        $helper->setPluginFlashMessenger($flashMessenger);

        $config = $container->get('config');
        if (isset($config['view_helper_config']['flashmessenger'])) {
            $configHelper = $config['view_helper_config']['flashmessenger'];
            if (isset($configHelper['message_open_format'])) {
                $helper->setMessageOpenFormat($configHelper['message_open_format']);
            }
            if (isset($configHelper['message_separator_string'])) {
                $helper->setMessageSeparatorString($configHelper['message_separator_string']);
            }
            if (isset($configHelper['message_close_string'])) {
                $helper->setMessageCloseString($configHelper['message_close_string']);
            }
        }

        return $helper;
    }

    /**
     * Create service (v2)
     *
     * @param ServiceLocatorInterface $container
     * @param string $normalizedName
     * @param string $requestedName
     * @return FlashMessenger
     */
    public function createService(ServiceLocatorInterface $container, $normalizedName = null, $requestedName = null)
    {
        return $this($container, $requestedName);
    }
}
