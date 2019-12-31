<?php

/**
 * @see       https://github.com/laminas/laminas-mvc-plugin-flashmessenger for the canonical source repository
 * @copyright https://github.com/laminas/laminas-mvc-plugin-flashmessenger/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-mvc-plugin-flashmessenger/blob/master/LICENSE.md New BSD License
 */

namespace Laminas\Mvc\Plugin\FlashMessenger\View;

use Laminas\Mvc\Plugin\FlashMessenger\FlashMessenger as PluginFlashMessenger;
use Laminas\Mvc\Plugin\FlashMessenger\View\Helper\FlashMessenger;

/**
 * Helper trait for auto-completion of code in modern IDEs.
 *
 * The trait provides convenience methods for view helpers,
 * defined by the laminas-mvc-plugin-flashmessenger component.
 * It is designed to be used for type-hinting $this variable
 * inside laminas-view templates via doc blocks.
 *
 * The base class is PhpRenderer, followed by the helper trait from
 * the laminas-mvc-plugin-flashmessenger component. However, multiple helper traits
 * from different Laminas components can be chained afterwards.
 *
 * @example @var \Laminas\View\Renderer\PhpRenderer|\Laminas\Mvc\Plugin\FlashMessenger\View\HelperTrait $this
 *
 * @method FlashMessenger|PluginFlashMessenger flashMessenger(string|null $namespace = null)
 */
trait HelperTrait
{
}
