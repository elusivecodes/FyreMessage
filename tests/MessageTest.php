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

        $this->assertEquals(
            $this->message,
            $this->message->appendBody('1')
        );

        $this->assertEquals(
            'test1',
            $this->message->getBody()
        );
    }

    public function testAppendHeader(): void
    {
        $this->message->setHeader('test', 'value');

        $this->assertEquals(
            $this->message,
            $this->message->appendHeader('test', 'last')
        );

        $this->assertEquals(
            [
                'value',
                'last'
            ],
            $this->message->getHeader('test')->getValue()
        );
    }

    public function testAppendHeaderNew(): void
    {
        $this->assertEquals(
            $this->message,
            $this->message->appendHeader('test', 'last')
        );

        $this->assertEquals(
            [
                'last'
            ],
            $this->message->getHeader('test')->getValue()
        );
    }

    public function testAppendHeaderEmpty(): void
    {
        $this->message->setHeader('test', 'value');

        $this->assertEquals(
            $this->message,
            $this->message->appendHeader('test', '')
        );

        $this->assertEquals(
            [
                'value'
            ],
            $this->message->getHeader('test')->getValue()
        );
    }

    public function testGetProtocolVersionDefault(): void
    {
        $this->assertEquals(
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
        $this->assertEquals(
            null,
            $this->message->getHeader('invalid')
        );
    }

    public function testGetHeadesr(): void
    {
        $this->message->setHeader('test1', 'value');
        $this->message->setHeader('test2', 'value');

        $this->assertEquals(
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

        $this->assertEquals(
            'value',
            $this->message->getHeaderValue('test')
        );
    }

    public function testGetHeaderValueAssociative(): void
    {
        $this->message->setHeader('test', ['a' => 1, 'b' => 2]);

        $this->assertEquals(
            'a=1, b=2',
            $this->message->getHeaderValue('test')
        );
    }

    public function testHasHeaderTrue(): void
    {
        $this->message->setHeader('test', 'value');

        $this->assertEquals(
            true,
            $this->message->hasHeader('test')
        );
    }

    public function testHasHeaderTrueFalse(): void
    {
        $this->message->setHeader('test', 'value');

        $this->assertEquals(
            false,
            $this->message->hasHeader('invalid')
        );
    }

    public function testPrependHeader(): void
    {
        $this->message->setHeader('test', 'value');

        $this->assertEquals(
            $this->message,
            $this->message->prependHeader('test', 'first')
        );

        $this->assertEquals(
            [
                'first',
                'value'
            ],
            $this->message->getHeader('test')->getValue()
        );
    }

    public function testPrependHeaderNew(): void
    {
        $this->assertEquals(
            $this->message,
            $this->message->prependHeader('test', 'first')
        );

        $this->assertEquals(
            [
                'first'
            ],
            $this->message->getHeader('test')->getValue()
        );
    }

    public function testPrependHeaderEmpty(): void
    {
        $this->message->setHeader('test', 'value');

        $this->assertEquals(
            $this->message,
            $this->message->prependHeader('test', '')
        );

        $this->assertEquals(
            [
                'value'
            ],
            $this->message->getHeader('test')->getValue()
        );
    }

    public function testRemoveHeader(): void
    {
        $this->message->setHeader('test', 'value');

        $this->assertEquals(
            $this->message,
            $this->message->removeHeader('test')
        );

        $this->assertEquals(
            false,
            $this->message->hasHeader('test')
        );
    }

    public function testSetBody(): void
    {
        $this->assertEquals(
            $this->message,
            $this->message->setBody('test')
        );

        $this->assertEquals(
            'test',
            $this->message->getBody()
        );
    }

    public function testSetHeader(): void
    {
        $this->assertEquals(
            $this->message,
            $this->message->setHeader('test', 'value')
        );

        $this->assertEquals(
            [
                'value'
            ],
            $this->message->getHeader('test')->getValue()
        );
    }

    public function testSetHeaderArray(): void
    {
        $this->message->setHeader('test', ['first', 'last']);

        $this->assertEquals(
            [
                'first',
                'last'
            ],
            $this->message->getHeader('test')->getValue()
        );
    }

    public function testSetHeaderEmpty(): void
    {
        $this->assertEquals(
            $this->message,
            $this->message->setHeader('test', '')
        );

        $this->assertEquals(
            [],
            $this->message->getHeader('test')->getValue()
        );
    }

    public function testSetProtocolVersion(): void
    {
        $this->assertEquals(
            $this->message,
            $this->message->setProtocolVersion('2.0')
        );

        $this->assertEquals(
            '2.0',
            $this->message->getProtocolVersion()
        );
    }

    public function testSetProtocolVersionInvalid(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->message->setProtocolVersion('2.1');
    }

    public function setUp(): void
    {
        $this->message = new Message();
    }

}
