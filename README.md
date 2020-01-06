# laminas-mvc-plugin-flashmessenger

[![Build Status](https://travis-ci.com/laminas/laminas-mvc-plugin-flashmessenger.svg?branch=master)](https://travis-ci.com/laminas/laminas-mvc-plugin-flashmessenger)
[![Coverage Status](https://coveralls.io/repos/github/laminas/laminas-mvc-plugin-flashmessenger/badge.svg?branch=master)](https://coveralls.io/github/laminas/laminas-mvc-plugin-flashmessenger?branch=master)

Flash messages [derive from Rails](http://api.rubyonrails.org/classes/ActionDispatch/Flash.html),
and are used to expose messages from one action to the next, after which they
are cleared; a typical use case is with
[Post/Redirect/Get](https://docs.laminas.dev/laminas-mvc-plugin-prg/), where
they are created in the `POST` handler, and then displayed by the `GET` handler
to indicate success or failure to the end-user.

This component provides a flash messenger controller plugin for
[laminas-mvc](https://docs.laminas.dev/laminas-mvc/) versions 3.0 and up.

## Installation

Run the following to install this library:

```bash
$ composer require laminas/laminas-mvc-plugin-flashmessenger
```

If you are using the [laminas-component-installer](https://docs.laminas.dev/laminas-component-installer/),
you're done!

If not, you will need to add the component as a module to your
application. Add the entry `'Laminas\Mvc\Plugin\FlashMessenger'` to
your list of modules in your application configuration (typically
one of `config/application.config.php` or `config/modules.config.php`).

## Documentation

Browse the documentation online at https://docs.laminas.dev/laminas-mvc-plugin-flashmessenger/

## Support

* [Issues](https://github.com/laminas/laminas-mvc-plugin-flashmessenger/issues/)
* [Chat](https://laminas.dev/chat/)
* [Forum](https://discourse.laminas.dev/)
