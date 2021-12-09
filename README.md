# FyreMessage

**FyreMessage** is a free, HTTP message library for *PHP*.


## Table Of Contents
- [Installation](#installation)
- [Message Creation](#message-creation)
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


## Message Creation

```php
$message = new Message();
```


## Methods

**Append Body**

Append data to the message body.

- `$data` is a string representing the data to append.

```php
$message->appendBody($data);
```

**Append Header**

Append a value to a message header.

- `$name` is a string representing the header name.
- `$value` is a string representing the header value.

```php
$message->appendHeader($name, $value);
```

**Get Body**

Get the message body.

```php
$body = $message->getBody();
```

**Get Header**

Get a message header.

- `$name` is a string representing the header name.

```php
$header = $message->getHeader($name);
```

**Get Headers**

Get the message headers.

```php
$headers = $message->getHeaders();
```

**Get Header Value**

Get a message header value.

- `$name` is a string representing the header name.

```php
$value = $message->getHeaderValue($name);
```

**Get Protocol Version**

Get the protocol version.

```php
$version = $message->getProtocolVersion();
```

**Has Header**

Determine if the message has a header.

- `$name` is a string representing the header name.

```php
$hasHeader = $message->hasHeader($name);
```

**Prepend Header**

Prepend a value to a message header.

- `$name` is a string representing the header name.
- `$value` is a string representing the header value.

```php
$message->prependHeader($name, $value);
```

**Remove Header**

Remove a header.

- `$name` is a string representing the header name.

```php
$message->removeHeader($name);
```

**Set Body**

Set the message body.

- `$data` is a string representing the message body.

```php
$message->setBody($data);
```

**Set Header**

Set a message header.

- `$name` is a string representing the header name.
- `$value` is a string representing the header value.

```php
$message->setHeader($name, $value);
```

**Set Protocol Version**

Set the protocol version.

- `$version` is a string representing the protocol version.

```php
$message->setProtocolVersion($version);
```