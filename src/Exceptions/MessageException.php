<?php
declare(strict_types=1);

namespace Fyre\Http\Exceptions;

use RuntimeException;

/**
 * MessageException
 */
class MessageException extends RuntimeException
{
    public static function forEmptyHeaderValue(): static
    {
        return new static('Header value cannot be empty');
    }

    public static function forInvalidBodyType(string $type): static
    {
        return new static('Invalid body type: '.$type);
    }

    public static function forInvalidHeaderName(string $name): static
    {
        return new static('Invalid header name:'.$name);
    }

    public static function forInvalidHeaderValue(string $value): static
    {
        return new static('Invalid header value:'.$value);
    }

    public static function forInvalidHeaderValueType(string $type): static
    {
        return new static('Invalid header value type: '.$type);
    }

    public static function forInvalidProtocolVersion(string $version): static
    {
        return new static('Invalid protocol version: '.$version);
    }
}
