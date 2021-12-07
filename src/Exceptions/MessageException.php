<?php
declare(strict_types=1);

namespace Fyre\Message\Exceptions;

use
    RunTimeException;

/**
 * MessageException
 */
class MessageException extends RunTimeException
{

    public static function forInvalidProtocol(string $protocol)
    {
        return new static('Invalid Protocol: '.$protocol);
    }

}
