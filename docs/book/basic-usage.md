# Basic Usage

In the following example, a flash message is set in a controller action and the rendering is done after a redirect.

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

Render all messages in a view script, e.g. `module/Album/view/album/album/index.phtml`:

```php
<?= $this->flashMessenger()->render() ?>
```

Output:

```html
<ul class="success"><li>Album created successfully.</li></ul>
```