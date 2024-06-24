<?php
declare(strict_types=1);

namespace Fyre\Http;

use InvalidArgumentException;

use function array_key_exists;
use function in_array;

/**
 * Message
 */
class Message
{
    protected const VALID_PROTOCOLS = [
        '1.0',
        '1.1',
        '2.0',
    ];

    protected string $body = '';
    protected array $headers = [];
    protected string $protocolVersion = '1.1';

    /**
     * New Message constructor.
     * @param array $options The message options.
     */
    public function __construct(array $options = [])
    {
        $options['body'] ??= '';
        $options['headers'] ??= [];
        $options['protocolVersion'] ??= '1.1';

        $this->body = $options['body'];

        foreach ($options['headers'] as $name => $value) {
            $this->headers[$name] = new Header($name, $value);
        }

        $this->protocolVersion = static::filterProtocolVersion($options['protocolVersion']);
    }

    /**
     * Append data to the message body.
     * @param string $data The data to append.
     * @return Message A new Message.
     */
    public function appendBody(string $data): static
    {
        $temp = clone $this;

        $temp->body .= $data;

        return $temp;
    }

    /**
     * Append a value to a message header.
     * @param string $name The header name.
     * @param string $value The header value.
     * @return Message A new Message.
     */
    public function appendHeader(string $name, string $value): static
    {
        $temp = clone $this;

        $header = $temp->headers[$name] ?? new Header($name);
        $temp->headers[$name] = $header->appendValue($value);

        return $temp;
    }

    /**
     * Get the message body.
     * @return string The message body.
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * Get a message header.
     * @param string $name The header name.
     * @return Header|null The Header, or null if it does not exist.
     */
    public function getHeader(string $name): Header|null
    {
        return $this->headers[$name] ?? null;
    }

    /**
     * Get the message headers.
     * @return array The message headers.
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * Get a message header value.
     * @param string $name The header name.
     * @return string|null The header value string.
     */
    public function getHeaderValue(string $name): string|null
    {
        if (!array_key_exists($name, $this->headers)) {
            return null;
        }

        return $this->getHeader($name)->getValueString();
    }

    /**
     * Get the protocol version.
     * @return string The protocol version.
     */
    public function getProtocolVersion(): string
    {
        return $this->protocolVersion;
    }

    /**
     * Determine if the message has a header.
     * @param string $name The header name.
     * @return bool TRUE if the message has the header, otherwise FALSE.
     */
    public function hasHeader(string $name): bool
    {
        return array_key_exists($name, $this->headers);
    }

    /**
     * Prepend a value to a message header.
     * @param string $name The header name.
     * @param string $value The header value.
     * @return Message A new Message.
     */
    public function prependHeader(string $name, string $value): static
    {
        $temp = clone $this;

        $header = $temp->headers[$name] ?? new Header($name);
        $temp->headers[$name] = $header->prependValue($value);

        return $temp;
    }

    /**
     * Remove a header.
     * @param string $name The header name.
     * @return Message A new Message.
     */
    public function removeHeader(string $name): static
    {
        $temp = clone $this;

        unset($temp->headers[$name]);

        return $temp;
    }

    /**
     * Set the message body.
     * @param string $data The message body.
     * @return Message A new Message.
     */
    public function setBody(string $data): static
    {
        $temp = clone $this;

        $temp->body = $data;

        return $temp;
    }

    /**
     * Set a message header.
     * @param string $name The header name.
     * @param string|array $value The header value.
     * @return Message A new Message.
     */
    public function setHeader(string $name, array|string $value): static
    {
        $temp = clone $this;

        $temp->headers[$name] = new Header($name, $value);

        return $temp;
    }

    /**
     * Set the protocol version.
     * @param string $version The protocol version.
     * @return Message A new Message.
     */
    public function setProtocolVersion(string $version): static
    {
        $temp = clone $this;

        $temp->protocolVersion = static::filterProtocolVersion($version);

        return $temp;
    }

    /**
     * Filter the protocol version.
     * @param string $version The protocol version.
     * @return string The filtered protcol version.
     * @throws InvalidArgumentException if the protocol version is not valid.
     */
    protected static function filterProtocolVersion(string $version): string
    {
        if (!in_array($version, static::VALID_PROTOCOLS)) {
            throw new InvalidArgumentException('Invalid protocol version: '.$version);
        }

        return $version;
    }
}
