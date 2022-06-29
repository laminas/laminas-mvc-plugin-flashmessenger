# Introduction

Giving feedback to a user is an important part of a good application.
Flash messages are used to notify the user about a successful form submit, failure on saving in the database, wrong authentification credentials or something similar.
At the end of a request, a message is created and shown to the user at the next request.
The flash messages are self-expiring and session-based.

To create and expose flash messages in a laminas-mvc-based application this packages provides:

- [a controller plugin to create and retrieve messages](controller-plugin.md)
- [a view helper to render the messages](view-helper.md)

A message is set in a controller and then rendered in a view script.

## Namespaces

The controller plugin and the view helper supports different types of messages:

- default
- success
- warning
- error
- info

These namespaces allow to handle different output formats.
