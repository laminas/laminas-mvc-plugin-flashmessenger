# Use Alerts from Bootstrap

To use the syntax of the [alerts from the Bootstrap CSS framework](https://getbootstrap.com/docs/5.2/components/alerts/) for the output of the view helper, the configuration of the message format must be adjusted.

To do that, add the following lines to the local or global configuration file, e.g. `config/autoload/global.config.php`:

```php
return [
    'view_helper_config' => [
        'flashmessenger' => [
            'default' => [
                'message_open_format'      => '<div%s role="alert">',
                'message_close_string'     => '</div>',
                'message_separator_string' => '</div><div%s role="alert">',
                'classes'                  => 'alert alert-primary',
            ],
            'success' => [
                'message_open_format'      => '<div%s role="alert">',
                'message_close_string'     => '</div>',
                'message_separator_string' => '</div><div%s role="alert">',
                'classes'                  => 'alert alert-success',
            ],
            'warning' => [
                 'message_open_format'      => '<div%s role="alert">',
                 'message_close_string'     => '</div>',
                 'message_separator_string' => '</div><div%s role="alert">',
                 'classes'                  => 'alert alert-success',
            ],
            'error'   => [
                 'message_open_format'      => '<div%s role="alert">',
                 'message_close_string'     => '</div>',
                 'message_separator_string' => '</div><div%s role="alert">',
                 'classes'                  => 'alert alert-danger',
            ],
            'info'    => [
                  'message_open_format'      => '<div%s role="alert">',
                 'message_close_string'     => '</div>',
                 'message_separator_string' => '</div><div%s role="alert">',
                 'classes'                  => 'alert alert-info',
            ],
        ],
    ],
];
```

This will set the format [for all uses of the view helper](application-wide-layout.md).
