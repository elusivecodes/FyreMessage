<?php
declare(strict_types=1);

namespace Fyre\Http;

use
    Fyre\Http\Header,
    InvalidArgumentException;

use function
    array_key_exists,
    in_array;

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

    protected string $protocolVersion = '1.1';

    protected array $headers = [];

    protected string $body = '';

    /**
     * Append data to the message body.
     * @param string $data The data to append.
     * @return Message The Message.
     */
    public function appendBody(string $data): static
    {
        $this->body .= $data;

        return $this;
    }

    /**
     * Append a value to a message header.
     * @param string $name The header name.
     * @param string $value The header value.
     * @return Message The Message.
     */
    public function appendHeader(string $name, string $value): static
    {
        $this->headers[$name] ??= new Header($name);
        $this->headers[$name]->appendValue($value);

        return $this;
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
     * @return string The header value string.
     */
    public function getHeaderValue(string $name): string
    {
        if (!$this->hasHeader($name)) {
            return '';
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
     * @return Message The Message.
     */
    public function prependHeader(string $name, string $value): static
    {
        $this->headers[$name] ??= new Header($name);
        $this->headers[$name]->prependValue($value);

        return $this;
    }

    /**
     * Remove a header.
     * @param string $name The header name.
     * @return Message The Message.
     */
    public function removeHeader(string $name): static
    {
        unset($this->headers[$name]);

        return $this;
    }

    /**
     * Set the message body.
     * @param string $data The message body.
     * @return Message The Message.
     */
    public function setBody(string $data): static
    {
        $this->body = $data;

        return $this;
    }

    /**
     * Set a message header.
     * @param string $name The header name.
     * @param string|array $value The header value.
     * @return Message The Message.
     */
    public function setHeader(string $name, string|array $value): static
    {
        $this->headers[$name] ??= new Header($name);
        $this->headers[$name]->setValue($value);

        return $this;
    }

    /**
     * Set the protocol version.
     * @param string $version The protocol version.
     * @return Message The Message.
     * @throws InvalidArgumentException if the protocol version is not valid.
     */
    public function setProtocolVersion(string $version): static
    {
        if (!in_array($version, static::VALID_PROTOCOLS)) {
            throw new InvalidArgumentException('Invalid Protocol: '.$version);
        }

        $this->protocolVersion = $version;

        return $this;
    }

}
