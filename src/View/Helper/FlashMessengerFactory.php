<?php

declare(strict_types=1);

namespace Laminas\Mvc\Plugin\FlashMessenger\View\Helper;

use Interop\Container\ContainerInterface;
use Laminas\Mvc\Plugin\FlashMessenger\FlashMessenger as PluginFlashMessenger;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;

use function method_exists;

class FlashMessengerFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param string $name
     * @param null|array $options
     * @return FlashMessenger
     */
    public function __invoke(ContainerInterface $container, $name, ?array $options = null)
    {
        // test if we are using Laminas\ServiceManager v2 or v3
        if (! method_exists($container, 'configure')) {
            $container = $container->getServiceLocator();
        }

        $controllerPluginManager = $container->get('ControllerPluginManager');
        $flashMessenger          = $controllerPluginManager->get('flashmessenger');

        $config     = $container->get('config');
        if (
            isset($config['view_helper_config']['flashmessenger']) &&
            is_array($config['view_helper_config']['flashmessenger'])
        ) {
            $isArrayOneDimensial = true;
            foreach ($config['view_helper_config']['flashmessenger'] as $property) {
                if (is_array($property)) {
                    $isArrayOneDimensial = false;
                    break;
                }
            }


            if ($isArrayOneDimensial === true) {
                return $this->createHelperWithOldConfig($flashMessenger, $config);
            } else {
                return $this->createHelperWithActualConfig($flashMessenger, $config);
            }
        }

        return new FlashMessenger();
    }

    private function createHelperWithOldConfig($flashMessenger, $config)
    {
        $helper = new FlashMessenger();
        $helper->setPluginFlashMessenger($flashMessenger);

        $configHelper = $config['view_helper_config']['flashmessenger'];
        if (isset($configHelper['message_open_format'])) {
            $helper
                ->setMessageOpenFormat($configHelper['message_open_format']);
        }
        if (isset($configHelper['message_separator_string'])) {
            $helper
                ->setMessageSeparatorString($configHelper['message_separator_string']);
        }
        if (isset($configHelper['message_close_string'])) {
            $helper
                ->setMessageCloseString($configHelper['message_close_string']);
        }

        return $helper;
    }

    private function createHelperWithActualConfig($flashMessenger, $config)
    {
        $namespaces   = [];
        $configHelper = $config['view_helper_config']['flashmessenger'];
        foreach ($configHelper as $configNamespace => $arrProperties) {
            $namespace = new FlashMessengerNamespace($configNamespace, isset($arrProperties['classes']) ? (string) $arrProperties['classes'] : '');
            if (isset($arrProperties['message_open_format'])) {
                $namespace
                    ->setMessageOpenFormat((string) $arrProperties['message_open_format']);
            }
            if (isset($arrProperties['message_separator_string'])) {
                $namespace
                    ->setMessageSeparatorString((string) $arrProperties['message_separator_string']);
            }
            if (isset($arrProperties['message_close_string'])) {
                $namespace
                    ->setMessageCloseString((string) $arrProperties['message_close_string']);
            }
            $namespaces[$namespace->getName()] = $namespace;
        }

        $helper = new FlashMessenger($namespaces);
        $helper->setPluginFlashMessenger($flashMessenger);
        return $helper;
    }

    /**
     * Create service (v2)
     *
     * @param string $normalizedName
     * @param string $requestedName
     * @return FlashMessenger
     */
    public function createService(ServiceLocatorInterface $container, $normalizedName = null, $requestedName = null)
    {
        return $this($container, $requestedName);
    }
}
