# Basic Usage

In the following example, a flash message is set in an edit action and the rendering is done after a redirect.

## Create a Flash Message

Set a message to the messenger in a controller action, e.g. `module/Album/Controller/AlbumController.php`:

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