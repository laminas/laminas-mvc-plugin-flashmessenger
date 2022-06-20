<?php

declare(strict_types=1);

namespace Laminas\Mvc\Plugin\FlashMessenger\View\Helper;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;

use function is_array;
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
        $helper                  = new FlashMessenger();
        $controllerPluginManager = $container->get('ControllerPluginManager');
        $flashMessenger          = $controllerPluginManager->get('flashmessenger');

        $helper->setPluginFlashMessenger($flashMessenger);

        $config = $container->get('config');
        if (
            isset($config['view_helper_config']['flashmessenger']) &&
            is_array($config['view_helper_config']['flashmessenger'])
        ) {
            $configHelper        = $config['view_helper_config']['flashmessenger'];
            $isArrayOneDimensial = true;
            foreach ($configHelper as $property) {
                if (is_array($property)) {
                    $isArrayOneDimensial = false;
                    break;
                }
            }

            if ($isArrayOneDimensial === true) {
                if (isset($configHelper['message_open_format'])) {
                    $helper->getDefaultNamespace()
                        ->setMessageOpenFormat($configHelper['message_open_format']);
                }
                if (isset($configHelper['message_separator_string'])) {
                    $helper->getDefaultNamespace()
                        ->setMessageSeparatorString($configHelper['message_separator_string']);
                }
                if (isset($configHelper['message_close_string'])) {
                    $helper->getDefaultNamespace()
                        ->setMessageCloseString($configHelper['message_close_string']);
                }
            } else {
                foreach ($configHelper as $configNamespace => $arrProperties) {
                    $namespace = new FlashMessengerNamespace($configNamespace);
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
                    if (isset($arrProperties['classes'])) {
                        $namespace
                            ->setClasses((string) $arrProperties['classes']);
                    }
                    $helper->addNamespace($namespace);
                }
            }
        }

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
