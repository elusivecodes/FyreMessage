# FyreMessage

**FyreMessage** is a free, open-souce immutable HTTP message library for *PHP*.


## Table Of Contents
- [Installation](#installation)
- [Basic Usage](#basic-usage)
- [Methods](#methods)



## Installation

**Using Composer**

```
composer require fyre/message
```

In PHP:

```php
use Fyre\Http\Message;
```


## Basic Usage

- `$options` is an array containing the message options.
    - `body` is a *StreamInterface* or string representing the message body, and will default to "".
    - `headers` is an array containing headers to set, and will default to *[]*.
    - `protocolVersion` is a string representing the protocol version, and will default to "*1.1*".

```php
$message = new Message($options);
```


## Methods

**Get Body**

Get the message body.

```php
$stream = $message->getBody();
```

**Get Header**

Get a message header.

- `$name` is a string representing the header name.

```php
$header = $message->getHeader($name);
```

**Get Header Line**

Get the value string of a message header.

- `$name` is a string representing the header name.

```php
$value = $message->getHeaderValue($name);
```

**Get Headers**

Get the message headers.

```php
$headers = $message->getHeaders();
```

**Get Protocol Version**

Get the protocol version.

```php
$version = $message->getProtocolVersion();
```

**Has Header**

Determine whether the message has a header.

- `$name` is a string representing the header name.

```php
$hasHeader = $message->hasHeader($name);
```

**With Added Header**

Clone the *Message* with new value(s) added to a header.

- `$name` is a string representing the header name.
- `$value` is a string or array representing the header value.

```php
$newMessage = $message->withAddedHeader($name, $value);
```

**With Body**

Clone the *Message* with a new body.

- `$stream` is a *StreamInterface* representing the message body.

```php
$newMessage = $message->withBody($stream);
```

**With Header**

Clone the *Message* with a new header.

- `$name` is a string representing the header name.
- `$value` is a string or array representing the header value.

```php
$newMessage = $message->withHeader($name, $value);
```

**Without Header**

Clone the *Message* without a header.

- `$name` is a string representing the header name.

```php
$newMessage = $message->withoutHeader($name);
```

**With Protocol Version**

Clone the *Message* with a new protocol version.

- `$version` is a string representing the protocol version.

```php
$newMessage = $message->withProtocolVersion($version);
```