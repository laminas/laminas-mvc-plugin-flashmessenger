# laminas-mvc-plugin-flashmessenger

[![Build Status](https://travis-ci.org/laminas/laminas-mvc-plugin-flashmessenger.svg?branch=master)](https://travis-ci.org/laminas/laminas-mvc-plugin-flashmessenger)
[![Coverage Status](https://coveralls.io/repos/github/laminas/laminas-mvc-plugin-flashmessenger/badge.svg?branch=master)](https://coveralls.io/github/laminas/laminas-mvc-plugin-flashmessenger?branch=master)

Flash messages [derive from Rails](http://api.rubyonrails.org/classes/ActionDispatch/Flash.html),
and are used to expose messages from one action to the next, after which they
are cleared; a typical use case is with
[Post/Redirect/Get](https://docs.laminas.dev/laminas-mvc-plugin-prg), where
they are created in the `POST` handler, and then displayed by the `GET` handler
to indicate success or failure to the end-user.

This component provides a flash messenger controller plugin for
[laminas-mvc](https://docs.laminas.dev/laminas-mvc/) versions 3.0 and up.

- File issues at https://github.com/laminas/laminas-mvc-plugin-flashmessenger/issues
- Documentation is at https://docs.laminas.dev/laminas-mvc-plugin-flashmessenger/
