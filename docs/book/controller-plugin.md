# FlashMessenger Controller Plugin

The `FlashMessenger` controller plugin is designed to create and retrieve
self-expiring, session-based messages.

## Available Methods

The plugin exposes a number of methods:

- `setSessionManager(Laminas\Session\ManagerInterface $manager) : FlashMessenger`:
  Allows you to specify an alternate session manager, if desired.

- `getSessionManager() : Laminas\Session\ManagerInterface`: Allows you to retrieve
  the session manager registered.

- `getContainer() : Laminas\Session\Container`: Returns the
  `Laminas\Session\Container` instance in which the flash messages are stored.

- `setNamespace(string $namespace = 'default') : FlashMessenger`:
  Allows you to specify a specific namespace in the container in which to store
  or from which to retrieve flash messages.

- `getNamespace() : string`: retrieves the name of the flash message namespace.

- `addMessage(string $message) : FlashMessenger`: Allows you to add a message to
  the current namespace of the session container.

- `hasMessages() : bool`: Lets you determine if there are any flash messages
  from the current namespace in the session container.

- `getMessages() : array`: Retrieves the flash messages from the current
  namespace of the session container

- `clearMessages() : bool`: Clears all flash messages in current namespace of
  the session container. Returns `true` if messages were cleared, `false` if
  none existed.

- `hasCurrentMessages() : bool`: Indicates whether any messages were added
  during the current request.

- `getCurrentMessages() : array`: Retrieves any messages added during the
  current request.

- `clearCurrentMessages() : bool`: Removes any messages added during the current
  request. Returns `true` if current messages were cleared, `false` if none
  existed.

- `clearMessagesFromContainer() : bool`: Clear all messages from the container.
  Returns `true` if any messages were cleared, `false` if none existed.

This plugin also provides four meaningful namespaces, namely: `INFO`, `ERROR`,
`WARNING`, and `SUCCESS`. The following functions are related to these
namespaces:

- `addInfoMessage(string $message): FlashMessenger`: Add a message to "info"
  namespace.

- `hasCurrentInfoMessages() : bool`: Check to see if messages have been added to
  "info" namespace within this request.

- `addWarningMessage(string $message) : FlashMessenger`: Add a message to
  "warning" namespace.

- `hasCurrentWarningMessages() : bool`: Check to see if messages have been added
  to "warning" namespace within this request.

- `addErrorMessage(string $message) : FlashMessenger`: Add a message to "error"
  namespace.

- `hasCurrentErrorMessages() : bool`: Check to see if messages have been added
  to "error" namespace within this request.

- `addSuccessMessage(string $message) : FlashMessenger`: Add a message to
  "success" namespace.

- `hasCurrentSuccessMessages() :bool`: Check to see if messages have been added
  to "success" namespace within this request.

Additionally, the `FlashMessenger` implements both `IteratorAggregate` and
`Countable`, allowing you to iterate over and count the flash messages in the
current namespace within the session container.

## Example

```php
public function processAction()
{
    // ... do some work ...
    $this->flashMessenger()->addMessage('You are now logged in.');
    return $this->redirect()->toRoute('user-success');
}

public function successAction()
{
    $return = ['success' => true];
    $flashMessenger = $this->flashMessenger();
    if ($flashMessenger->hasMessages()) {
        $return['messages'] = $flashMessenger->getMessages();
    }
    return $return;
}
```
