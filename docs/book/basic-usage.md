# Basic Usage

A typical use case is to set a flash message in a controller action and render it in a view script after a redirect.
The following example shows the use of a success message.

Before starting, make sure laminas-mvc-plugin-flashmessenger is [installed and configured](installation.md).

## Create a Flash Message

Store a message in the messenger of a controller action, e.g. `module/Album/Controller/AlbumController.php`:

```php
namespace Album\Controller;

use Laminas\Mvc\Controller\AbstractActionController;

class AlbumController extends AbstractActionController
{
    public function editAction()
    {
        // Do some work…
    
        // Add success message
        $this->flashMessenger()->addSuccessMessage(
            'Album created successfully.'
        );
        
        // Redirect
        return $this->redirect()->toRoute('album');
    }
}
```

## Render a Flash Message

Render all added success messages in a view script, e.g. `module/Album/view/album/album/index.phtml`:

<!-- markdownlint-disable MD038 MD009 MD046 -->
=== "Name Usage"
    ```php
    <?= $this->flashMessenger()->render('success') ?>
    ```

=== "Constant Usage"
    ```php
    <?= $this->flashMessenger()->render(
      Laminas\Mvc\Plugin\FlashMessenger\FlashMessenger::NAMESPACE_SUCCESS
    ) ?>
    ```
<!-- markdownlint-restore -->

Output:

```html
<ul class="success"><li>Album created successfully.</li></ul>
```

## Usage of Namespaces

The flash messenger supports namespaces.
This allows to render messages of different types in different places.

### Create Flash Messages for Different Namespaces

Store a `success` message in the messenger of a controller action:

```php
$this->flashMessenger()->addSuccessMessage('…');
```

Store an `info` message in the messenger of a controller action:

```php
$this->flashMessenger()->addInfoMessage('…');
```

Store a `warning` message in the messenger of a controller action:

```php
$this->flashMessenger()->addWarningMessage('…');
```

Store an `error` message in the messenger of a controller action:

```php
$this->flashMessenger()->addErrorMessage('…');
```

### Render Flash Messages for Different Namespaces

Render all added `success` messages in a view script:

<!-- markdownlint-disable MD038 MD009 MD046 -->
=== "Name Usage"
    ```php
    <?= $this->flashMessenger()->render('success') ?>
    ```

=== "Constant Usage"
    ```php
    <?= $this->flashMessenger()->render(
      Laminas\Mvc\Plugin\FlashMessenger\FlashMessenger::NAMESPACE_SUCCESS
    ) ?>
    ```
<!-- markdownlint-restore -->

Render all added `info` messages in a view script:

<!-- markdownlint-disable MD038 MD009 MD046 -->
=== "Name Usage"
    ```php
    <?= $this->flashMessenger()->render('info') ?>
    ```

=== "Constant Usage"
    ```php
    <?= $this->flashMessenger()->render(
      Laminas\Mvc\Plugin\FlashMessenger\FlashMessenger::NAMESPACE_INFO
    ) ?>
    ```
<!-- markdownlint-restore -->

Render all added `warning` messages in a view script:

<!-- markdownlint-disable MD038 MD009 MD046 -->
=== "Name Usage"
    ```php
    <?= $this->flashMessenger()->render('warning') ?>
    ```

=== "Constant Usage"
    ```php
    <?= $this->flashMessenger()->render(
      Laminas\Mvc\Plugin\FlashMessenger\FlashMessenger::NAMESPACE_WARNING
    ) ?>
    ```
<!-- markdownlint-restore -->

Render all added `error` messages in a view script:

<!-- markdownlint-disable MD038 MD009 MD046 -->
=== "Name Usage"
    ```php
    <?= $this->flashMessenger()->render('error') ?>
    ```

=== "Constant Usage"
    ```php
    <?= $this->flashMessenger()->render(
      Laminas\Mvc\Plugin\FlashMessenger\FlashMessenger::NAMESPACE_ERROR
    ) ?>
    ```
<!-- markdownlint-restore -->

### Use Default Namespace

The flash messenger supports a default namespace which does not represent an explicit status.

Store a `default` message in the messenger of a controller action:

```php
$this->flashMessenger()->addMessage('…');
```

Render all added `default` messages in a view script:

```php
<?= $this->flashMessenger()->render() ?>
```

## Learn More

- [The controller plugin](controller-plugin.md)
- [The view helper](view-helper.md)