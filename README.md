# zend-mvc-plugin-flashmessenger

[![Build Status](https://secure.travis-ci.org/zendframework/zend-mvc-plugin-flashmessenger.svg?branch=master)](https://secure.travis-ci.org/zendframework/zend-mvc-plugin-flashmessenger)
[![Coverage Status](https://coveralls.io/repos/github/zendframework/zend-mvc-plugin-flashmessenger/badge.svg?branch=master)](https://coveralls.io/github/zendframework/zend-mvc-plugin-flashmessenger?branch=master)

Flash messages [derive from Rails](http://api.rubyonrails.org/classes/ActionDispatch/Flash.html),
and are used to expose messages from one action to the next, after which they
are cleared; a typical use case is with
[Post/Redirect/Get](https://docs.zendframework.com/zend-mvc-plugin-prg/), where
they are created in the `POST` handler, and then displayed by the `GET` handler
to indicate success or failure to the end-user.

This component provides a flash messenger controller plugin for
[zend-mvc](https://docs.zendframework.com/zend-mvc/) versions 3.0 and up.

## Installation

Run the following to install this library:

```bash
$ composer require zendframework/zend-mvc-plugin-flashmessenger
```

If you are using the [zend-component-installer](https://docs.zendframework.com/zend-component-installer/),
you're done!

If not, you will need to add the component as a module to your
application. Add the entry `'Zend\Mvc\Plugin\FlashMessenger'` to
your list of modules in your application configuration (typically
one of `config/application.config.php` or `config/modules.config.php`).

## Documentation

Browse the documentation online at https://docs.zendframework.com/zend-mvc-plugin-flashmessenger/

## Support

* [Issues](https://github.com/zendframework/zend-mvc-plugin-flashmessenger/issues/)
* [Chat](https://zendframework-slack.herokuapp.com/)
* [Forum](https://discourse.zendframework.com/)
