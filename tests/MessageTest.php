<?php
declare(strict_types=1);

namespace Tests;

use
    Fyre\Http\Header,
    Fyre\Http\Message,
    InvalidArgumentException,
    PHPUnit\Framework\TestCase;

final class MessageTest extends TestCase
{

    protected Message $message;

    public function testAppendBody(): void
    {
        $this->message->setBody('test');

        $this->assertSame(
            $this->message,
            $this->message->appendBody('1')
        );

        $this->assertSame(
            'test1',
            $this->message->getBody()
        );
    }

    public function testAppendHeader(): void
    {
        $this->message->setHeader('test', 'value');

        $this->assertSame(
            $this->message,
            $this->message->appendHeader('test', 'last')
        );

        $this->assertSame(
            [
                'value',
                'last'
            ],
            $this->message->getHeader('test')->getValue()
        );
    }

    public function testAppendHeaderNew(): void
    {
        $this->message->appendHeader('test', 'last');

        $this->assertSame(
            [
                'last'
            ],
            $this->message->getHeader('test')->getValue()
        );
    }

    public function testAppendHeaderEmpty(): void
    {
        $this->message->setHeader('test', 'value');
        $this->message->appendHeader('test', '');

        $this->assertSame(
            [
                'value'
            ],
            $this->message->getHeader('test')->getValue()
        );
    }

    public function testGetProtocolVersionDefault(): void
    {
        $this->assertSame(
            '1.1',
            $this->message->getProtocolVersion()
        );
    }

    public function testGetHeader(): void
    {
        $this->message->setHeader('test', 'value');

        $this->assertInstanceOf(
            Header::class,
            $this->message->getHeader('test')
        );
    }

    public function testGetHeaderInvalid(): void
    {
        $this->assertNull(
            $this->message->getHeader('invalid')
        );
    }

    public function testGetHeadesr(): void
    {
        $this->message->setHeader('test1', 'value');
        $this->message->setHeader('test2', 'value');

        $this->assertSame(
            [
                'test1' => $this->message->getHeader('test1'),
                'test2' => $this->message->getHeader('test2')
            ],
            $this->message->getHeaders()
        );
    }

    public function testGetHeaderValue(): void
    {
        $this->message->setHeader('test', 'value');

        $this->assertSame(
            'value',
            $this->message->getHeaderValue('test')
        );
    }

    public function testGetHeaderValueAssociative(): void
    {
        $this->message->setHeader('test', ['a' => 1, 'b' => 2]);

        $this->assertSame(
            'a=1, b=2',
            $this->message->getHeaderValue('test')
        );
    }

    public function testHasHeaderTrue(): void
    {
        $this->message->setHeader('test', 'value');

        $this->assertTrue(
            $this->message->hasHeader('test')
        );
    }

    public function testHasHeaderTrueFalse(): void
    {
        $this->message->setHeader('test', 'value');

        $this->assertFalse(
            $this->message->hasHeader('invalid')
        );
    }

    public function testPrependHeader(): void
    {
        $this->message->setHeader('test', 'value');

        $this->assertSame(
            $this->message,
            $this->message->prependHeader('test', 'first')
        );

        $this->assertSame(
            [
                'first',
                'value'
            ],
            $this->message->getHeader('test')->getValue()
        );
    }

    public function testPrependHeaderNew(): void
    {
        $this->message->prependHeader('test', 'first');

        $this->assertSame(
            [
                'first'
            ],
            $this->message->getHeader('test')->getValue()
        );
    }

    public function testPrependHeaderEmpty(): void
    {
        $this->message->setHeader('test', 'value');
        $this->message->prependHeader('test', '');

        $this->assertSame(
            [
                'value'
            ],
            $this->message->getHeader('test')->getValue()
        );
    }

    public function testRemoveHeader(): void
    {
        $this->message->setHeader('test', 'value');

        $this->assertSame(
            $this->message,
            $this->message->removeHeader('test')
        );

        $this->assertFalse(
            $this->message->hasHeader('test')
        );
    }

    public function testSetBody(): void
    {
        $this->assertSame(
            $this->message,
            $this->message->setBody('test')
        );

        $this->assertSame(
            'test',
            $this->message->getBody()
        );
    }

    public function testSetHeader(): void
    {
        $this->assertSame(
            $this->message,
            $this->message->setHeader('test', 'value')
        );

        $this->assertSame(
            [
                'value'
            ],
            $this->message->getHeader('test')->getValue()
        );
    }

    public function testSetHeaderArray(): void
    {
        $this->message->setHeader('test', ['first', 'last']);

        $this->assertSame(
            [
                'first',
                'last'
            ],
            $this->message->getHeader('test')->getValue()
        );
    }

    public function testSetHeaderEmpty(): void
    {
        $this->message->setHeader('test', '');

        $this->assertSame(
            [],
            $this->message->getHeader('test')->getValue()
        );
    }

    public function testSetProtocolVersion(): void
    {
        $this->assertSame(
            $this->message,
            $this->message->setProtocolVersion('2.0')
        );

        $this->assertSame(
            '2.0',
            $this->message->getProtocolVersion()
        );
    }

    public function testSetProtocolVersionInvalid(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->message->setProtocolVersion('2.1');
    }

    protected function setUp(): void
    {
        $this->message = new Message();
    }

}
