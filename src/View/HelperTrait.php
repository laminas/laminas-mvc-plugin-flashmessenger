<?php
/**
 * @see       https://github.com/zendframework/zend-mvc-plugin-flashmessenger for the canonical source repository
 * @copyright Copyright (c) 2018 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   https://github.com/zendframework/zend-mvc-plugin-flashmessenger/blob/master/LICENSE.md New BSD License
 */

namespace Zend\Mvc\Plugin\FlashMessenger\View;

use Zend\Mvc\Plugin\FlashMessenger\FlashMessenger as PluginFlashMessenger;
use Zend\Mvc\Plugin\FlashMessenger\View\Helper\FlashMessenger;

/**
 * Helper trait for auto-completion of code in modern IDEs.
 *
 * The trait provides convenience methods for view helpers,
 * defined by the zend-mvc-plugin-flashmessenger component.
 * It is designed to be used for type-hinting $this variable
 * inside zend-view templates via doc blocks.
 *
 * The base class is PhpRenderer, followed by the helper trait from
 * the zend-mvc-plugin-flashmessenger component. However, multiple helper traits
 * from different Zend Framework components can be chained afterwards.
 *
 * @example @var \Zend\View\Renderer\PhpRenderer|\Zend\Mvc\Plugin\FlashMessenger\View\HelperTrait $this
 *
 * @method FlashMessenger|PluginFlashMessenger flashMessenger(string|null $namespace = null)
 */
trait HelperTrait
{
}
