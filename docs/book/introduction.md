# Introduction

Giving feedback to a user is an important part of a good application.
Flash messages provide notifications to the user, such as for successful form submissions, failure to save data in the database, and incorrect authentication credentials.
A message is created at the end of one request, and shown to the user in the next request.
Flash messages are self-expiring and session-based.

To create and expose flash messages in a laminas-mvc-based application, this packages provides:

- [a controller plugin to create and retrieve messages](controller-plugin.md)
- [a view helper to render the messages](view-helper.md)

A flash message is set in a controller and then rendered in a view script.

## Namespaces

The controller plugin and the view helper support different types of messages:

- `default`
- `info`
- `success`
- `warning`
- `error`

These namespaces provide support for handling different output formats.
