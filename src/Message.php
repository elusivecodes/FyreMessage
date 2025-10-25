<?php
declare(strict_types=1);

namespace Fyre\Http;

use Fyre\Http\Exceptions\MessageException;
use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\StreamInterface;
use Stringable;

use function array_combine;
use function array_key_exists;
use function array_map;
use function gettype;
use function implode;
use function in_array;
use function is_numeric;
use function is_string;
use function preg_match;
use function strtolower;
use function trim;

/**
 * Message
 */
class Message implements MessageInterface
{
    protected const VALID_PROTOCOLS = [
        '1.0',
        '1.1',
        '2.0',
    ];

    protected StreamInterface $body;

    protected array $headerNames = [];

    protected array $headers = [];

    protected string $protocolVersion = '1.1';

    /**
     * New Message constructor.
     *
     * @param array $options The message options.
     */
    public function __construct(array $options = [])
    {
        $options['body'] ??= '';
        $options['headers'] ??= [];
        $options['protocolVersion'] ??= '1.1';

        if (is_string($options['body'])) {
            $options['body'] = Stream::createFromString($options['body']);
        }

        if ($options['body'] instanceof StreamInterface) {
            $this->body = $options['body'];
        } else if (is_string($options['body']) || $options['body'] instanceof Stringable) {
            $this->body = Stream::createFromString((string) $options['body']);
        } else {
            throw MessageException::forInvalidBodyType(gettype($options['body']));
        }

        foreach ($options['headers'] as $name => $value) {
            $filteredName = static::filterHeaderName($name);

            $this->headerNames[$filteredName] = $name;
            $this->headers[$filteredName] = static::filterHeaderValue($value);
        }

        $this->protocolVersion = static::filterProtocolVersion($options['protocolVersion']);
    }

    /**
     * Get the message body.
     *
     * @return StreamInterface The message body.
     */
    public function getBody(): StreamInterface
    {
        return $this->body;
    }

    /**
     * Get the values of message header.
     *
     * @param string $name The header name.
     * @return array The header values.
     */
    public function getHeader(string $name): array
    {
        $name = strtolower($name);

        return $this->headers[$name] ?? [];
    }

    /**
     * Get the value string of a message header.
     *
     * @param string $name The header name.
     * @return string The header value string.
     */
    public function getHeaderLine(string $name): string
    {
        $name = strtolower($name);

        $value = $this->headers[$name] ?? [];

        return implode(', ', $value);
    }

    /**
     * Get the message headers.
     *
     * @return array The message headers.
     */
    public function getHeaders(): array
    {
        $headerNames = array_map(
            fn(string $name): string => $this->headerNames[$name],
            array_keys($this->headers)
        );

        return array_combine($headerNames, $this->headers);
    }

    /**
     * Get the protocol version.
     *
     * @return string The protocol version.
     */
    public function getProtocolVersion(): string
    {
        return $this->protocolVersion;
    }

    /**
     * Determine whether the message has a header.
     *
     * @param string $name The header name.
     * @return bool TRUE if the message has the header, otherwise FALSE.
     */
    public function hasHeader(string $name): bool
    {
        $name = strtolower($name);

        return array_key_exists($name, $this->headers);
    }

    /**
     * Clone the Message with new value(s) added to a header.
     *
     * @param string $name The header name.
     * @param mixed $value The header value.
     * @return Message A new Message.
     */
    public function withAddedHeader(string $name, mixed $value): static
    {
        $name = strtolower($name);

        if (!array_key_exists($name, $this->headers)) {
            return $this->withHeader($name, $value);
        }

        $temp = clone $this;

        $temp->headers[$name] = [
            ...$temp->headers[$name],
            ...static::filterHeaderValue($value),
        ];

        return $temp;
    }

    /**
     * Clone the Message with a new body.
     *
     * @param StreamInterface $body The message body.
     * @return Message A new Message.
     */
    public function withBody(StreamInterface $body): static
    {
        $temp = clone $this;

        $temp->body = $body;

        return $temp;
    }

    /**
     * Clone the Message with a new header.
     *
     * @param string $name The header name.
     * @param mixed $value The header value.
     * @return Message A new Message.
     */
    public function withHeader(string $name, mixed $value): static
    {
        $temp = clone $this;

        $filteredName = static::filterHeaderName($name);

        $temp->headerNames[$filteredName] = $name;
        $temp->headers[$filteredName] = static::filterHeaderValue($value);

        return $temp;
    }

    /**
     * Clone the Message without a header.
     *
     * @param string $name The header name.
     * @return Message A new Message.
     */
    public function withoutHeader(string $name): static
    {
        $temp = clone $this;

        $name = strtolower($name);

        unset($temp->headerNames[$name]);
        unset($temp->headers[$name]);

        return $temp;
    }

    /**
     * Clone the Message with a new protocol version.
     *
     * @param string $version The protocol version.
     * @return Message A new Message.
     */
    public function withProtocolVersion(string $version): static
    {
        $temp = clone $this;

        $temp->protocolVersion = static::filterProtocolVersion($version);

        return $temp;
    }

    /**
     * Filter a header name.
     *
     * @param string $name The header name.
     * @return string The filtered header name.
     *
     * @throws MessageException if the header name is not valid.
     */
    protected static function filterHeaderName(string $name): string
    {
        if (!preg_match('/^[!#$%&\'*+\-.^_`|~0-9A-Za-z]+$/', $name)) {
            throw MessageException::forInvalidHeaderName($name);
        }

        return strtolower($name);
    }

    /**
     * Filter a header value.
     *
     * @param mixed $value The header value.
     * @return array The filtered header value(s).
     *
     * @throws MessageException if the header value is not valid.
     */
    protected static function filterHeaderValue(mixed $value): array
    {
        $values = (array) $value;

        if ($values === []) {
            throw MessageException::forEmptyHeaderValue();
        }

        $values = array_map(
            static function(mixed $value): string {
                if (!is_string($value) && !is_numeric($value)) {
                    throw MessageException::forInvalidHeaderValueType(gettype($value));
                }

                $value = (string) $value;

                if (preg_match('/[^\x09\x20-\x7E]/', $value)) {
                    throw MessageException::forInvalidHeaderValue($value);
                }

                return trim($value, " \t");
            },
            $values
        );

        return $values;
    }

    /**
     * Filter the protocol version.
     *
     * @param string $version The protocol version.
     * @return string The filtered protcol version.
     *
     * @throws MessageException if the protocol version is not valid.
     */
    protected static function filterProtocolVersion(string $version): string
    {
        if (!in_array($version, static::VALID_PROTOCOLS)) {
            throw MessageException::forInvalidProtocolVersion($version);
        }

        return $version;
    }
}
