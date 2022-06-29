# FlashMessenger View Helper

The `FlashMessenger` view helper is used to render the messages of the [FlashMessenger controller plugin](controller-plugin.md).

## Basic Usage

When only using the default `namespace` for the `FlashMessenger`, you can do the
following:

```php
// Usable in any of your .phtml files
echo $this->flashMessenger()->render();
```

The first argument of the `render()` function is the `namespace`. If no
`namespace` is defined, the default
`Laminas\Mvc\Controller\Plugin\FlashMessenger::NAMESPACE_DEFAULT` will be used,
which translates to `default`.

```php
// Usable in any of your .phtml files
echo $this->flashMessenger()->render('error');

// Alternatively use one of the pre-defined namespaces
// (aka: use Laminas\Mvc\Controller\Plugin\FlashMessenger;)
echo $this->flashMessenger()->render(FlashMessenger::NAMESPACE_SUCCESS);
```

## CSS Layout

The `FlashMessenger` default rendering adds a CSS class to the generated HTML,
that matches the defined `namespace` that should be rendered. While it may work
well for the default cases, every so often you may want to add specific CSS
classes to the HTML output. This can be done while making use of the second
parameter of the `render()` function.

```php
// Usable in any of your .phtml files
echo $this->flashMessenger()->render('error', ['alert', 'alert-danger']);
```

The output of this example, using the default HTML rendering settings, would
look like this:

```html
<ul class="alert alert-danger">
    <li>Some FlashMessenger Content</li>
    <li>You, the developer, are AWESOME!</li>
</ul>
```

## HTML Layout

Aside from modifying the rendered CSS classes of the `FlashMessenger`, you are
furthermore able to modify the generated HTML as a whole to create even more
distinct visuals for your flash messages. The default output format is defined
within the source code of the `FlashMessenger` view helper itself.

```php
protected $messageCloseString     = '</li></ul>';
protected $messageOpenFormat      = '<ul%s><li>';
protected $messageSeparatorString = '</li><li>';
```

These defaults exactly match what we're trying to do. The placeholder `%s` will
be filled with the CSS classes output.

To change this, all we need to do is call the respective setter methods of these
variables and give them new strings; for example:

```php
// In any of your .phtml files:
echo $this->flashMessenger()
    ->setMessageOpenFormat('<div%s><p>')
    ->setMessageSeparatorString('</p><p>')
    ->setMessageCloseString('</p></div>')
    ->render('success');
```

The above code sample then would then generate the following output:

```html
<div class="success">
    <p>Some FlashMessenger Content</p>
    <p>You, who's reading the docs, are AWESOME!</p>
</div>
```

## Sample Modification for Twitter Bootstrap 3

Taking all the above knowledge into account, we can create a nice, highly usable
and user-friendly rendering strategy using the
[Bootstrap front-end framework](http://getbootstrap.com/) version 3 layouts:

```php
// In any of your .phtml files:
$flash = $this->flashMessenger();
$flash->setMessageOpenFormat('<div%s>
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
        &times;
    </button>
    <ul><li>')
    ->setMessageSeparatorString('</li><li>')
    ->setMessageCloseString('</li></ul></div>');

echo $flash->render('error',   ['alert', 'alert-dismissible', 'alert-danger']);
echo $flash->render('info',    ['alert', 'alert-dismissible', 'alert-info']);
echo $flash->render('default', ['alert', 'alert-dismissible', 'alert-warning']);
echo $flash->render('success', ['alert', 'alert-dismissible', 'alert-success']);
```

The output of the above example would create dismissable `FlashMessages` with
the following HTML markup. The example only covers one type of `FlashMessenger`
output; if you would have several `FlashMessages` available in each of the
rendered `namespaces`, then you would receive the same output multiple times
only having different CSS classes applied.

```html
<div class="alert alert-dismissable alert-success">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    <ul>
        <li>Some FlashMessenger Content</li>
        <li>You, who's reading the docs, are AWESOME!</li>
    </ul>
</div>
```

## Alternative Configuration of the ViewHelper Layout

`Laminas\View\Helper\Service\FlashMessengerFactory` checks the application
configuration, making it possible to set up the `FlashMessenger` strings through
your `module.config.php`, too. The next example will set up the output to be
identical with the above Twitter Bootstrap 3 Example

```php
'view_helper_config' => [
    'flashmessenger' => [
        'message_open_format'      => '<div%s><button type="button" class="close"
data-dismiss="alert" aria-hidden="true">&times;</button><ul><li>',
        'message_close_string'     => '</li></ul></div>',
        'message_separator_string' => '</li><li>',
    ],
],
```

<!-- markdownlint-disable MD001 -->
> TIP: **IDE Auto-Completion in Templates**
> The `Laminas\Mvc\Plugin\FlashMessenger\View\HelperTrait` trait can be used to provide auto-completion for modern IDEs. It defines the aliases of the view helpers in a DocBlock as `@method` tags.
>
> ### Usage
>
> In order to allow auto-completion in templates, `$this` variable should be type-hinted via a DocBlock at the top of a template.
> It is recommended that always the `Laminas\View\Renderer\PhpRenderer` is added as the first type, so that the IDE can auto-suggest the default view helpers from `laminas-view`.
> The `HelperTrait` from `laminas-mvc-plugin-flashmessenger` can be chained with a pipe symbol (a.k.a. vertical bar) `|`:
>
> ```php
> /**
>  * @var Laminas\View\Renderer\PhpRenderer|Laminas\Mvc\Plugin\FlashMessenger\View\HelperTrait $this
>  */
> ```
>
> The `HelperTrait` traits can be chained as many as needed, depending on which view helpers from the different Laminas component are used and where the auto-completion is to be made.
<!-- markdownlint-restore -->
