# Set Application-Wide Layout

The format for the `FlashMessenger` view helper can be configured for an entire application as well as all uses of the helper.

`Laminas\View\Helper\Service\FlashMessengerFactory` checks the application
configuration and creates the view helper with the given format options.

## Set the Format for All Namespaces

Add the following lines to the local or global configuration file, e.g. `config/autoload/global.config.php`:

```php
return [
    'view_helper_config' => [
        'flashmessenger' => [
            'message_open_format'      => '<p%s>',
            'message_close_string'     => '</p>',
            'message_separator_string' => '<br>',
        ],
    ],
];
```

## Set Formats Individually for Namespaces

INFO: **New Feature**
Available since version 1.8.0

Add the following lines to the local or global configuration file, e.g. `config/autoload/global.config.php`:

```php
return [
    'view_helper_config' => [
        'flashmessenger' => [
            'default' => [
                'message_open_format'      => '<p%s>',
                'message_close_string'     => '</p>',
                'message_separator_string' => '<br>',
                'classes'                  => 'custom-default example-class',
            ],
            'success' => [
                'message_open_format'      => '<p%s>',
                'message_close_string'     => '</p>',
                'message_separator_string' => '<br>',
                'classes'                  => 'custom-success example-class',
            ],
            'warning' => [
                 // …
            ],
            'error'   => [
                 // …
            ],
            'info'    => [
                 // …
            ],
        ],
    ],
];
```

If the default unordered list should be retained, then set only the classes:

```php
return [
    'view_helper_config' => [
        'flashmessenger' => [
            'default' => [
                'classes' => 'custom-default',
            ],
            'success' => [
                'classes' => 'custom-success',
            ],
            'warning' => [
                'classes' => 'custom-warning',
            ],
            'error'   => [
                'classes' => 'custom-error',
            ],
            'info'    => [
                'classes' => 'custom-info',
            ],
        ],
    ],
];
```
