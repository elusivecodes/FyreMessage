<?php
declare(strict_types=1);

namespace Tests;

use Fyre\Http\Header;
use Fyre\Http\Message;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class MessageTest extends TestCase
{

    protected Message $message;

    public function testAppendBody(): void
    {
        $message1 = new Message();
        $message2 = $message1->setBody('test');
        $message3 = $message2->appendBody('1');

        $this->assertSame(
            'test',
            $message2->getBody()
        );

        $this->assertSame(
            'test1',
            $message3->getBody()
        );
    }

    public function testAppendHeader(): void
    {
        $message1 = new Message();
        $message2 = $message1->setHeader('test', 'value');
        $message3 = $message2->appendHeader('test', 'last');

        $this->assertSame(
            [
                'value'
            ],
            $message2->getHeader('test')->getValue()
        );

        $this->assertSame(
            [
                'value',
                'last'
            ],
            $message3->getHeader('test')->getValue()
        );
    }

    public function testAppendHeaderNew(): void
    {
        $message1 = new Message();
        $message2 = $message1->appendHeader('test', 'last');

        $this->assertFalse(
            $message1->hasHeader('test')
        );

        $this->assertSame(
            [
                'last'
            ],
            $message2->getHeader('test')->getValue()
        );
    }

    public function testAppendHeaderEmpty(): void
    {
        $message1 = new Message();
        $message2 = $message1->setHeader('test', 'value');
        $message3 = $message2->appendHeader('test', '');

        $this->assertSame(
            [
                'value'
            ],
            $message2->getHeader('test')->getValue()
        );

        $this->assertSame(
            [
                'value'
            ],
            $message3->getHeader('test')->getValue()
        );
    }

    public function testGetProtocolVersionDefault(): void
    {
        $message = new Message();

        $this->assertSame(
            '1.1',
            $message->getProtocolVersion()
        );
    }

    public function testGetHeader(): void
    {
        $message1 = new Message();
        $message2 = $message1->setHeader('test', 'value');

        $this->assertInstanceOf(
            Header::class,
            $message2->getHeader('test')
        );
    }

    public function testGetHeaderInvalid(): void
    {
        $message = new Message();

        $this->assertNull(
            $message->getHeader('invalid')
        );
    }

    public function testGetHeaders(): void
    {
        $message1 = new Message();
        $message2 = $message1->setHeader('test1', 'value');
        $message3 = $message2->setHeader('test2', 'value');

        $this->assertSame(
            [
                'test1' => $message2->getHeader('test1')
            ],
            $message2->getHeaders()
        );

        $this->assertSame(
            [
                'test1' => $message3->getHeader('test1'),
                'test2' => $message3->getHeader('test2')
            ],
            $message3->getHeaders()
        );
    }

    public function testGetHeaderValue(): void
    {
        $message1 = new Message();
        $message2 = $message1->setHeader('test', 'value');

        $this->assertSame(
            'value',
            $message2->getHeaderValue('test')
        );
    }

    public function testGetHeaderValueAssociative(): void
    {
        $message1 = new Message();
        $message2 = $message1->setHeader('test', ['a' => 1, 'b' => 2]);

        $this->assertSame(
            'a=1, b=2',
            $message2->getHeaderValue('test')
        );
    }

    public function testHasHeaderTrue(): void
    {
        $message1 = new Message();
        $message2 = $message1->setHeader('test', 'value');

        $this->assertTrue(
            $message2->hasHeader('test')
        );
    }

    public function testHasHeaderTrueFalse(): void
    {
        $message1 = new Message();
        $message2 = $message1->setHeader('test', 'value');

        $this->assertFalse(
            $message2->hasHeader('invalid')
        );
    }

    public function testPrependHeader(): void
    {
        $message1 = new Message();
        $message2 = $message1->setHeader('test', 'value');
        $message3 = $message2->prependHeader('test', 'first');

        $this->assertSame(
            [
                'value'
            ],
            $message2->getHeader('test')->getValue()
        );

        $this->assertSame(
            [
                'first',
                'value'
            ],
            $message3->getHeader('test')->getValue()
        );
    }

    public function testPrependHeaderNew(): void
    {
        $message1 = new Message();
        $message2 = $message1->prependHeader('test', 'first');

        $this->assertSame(
            [
                'first'
            ],
            $message2->getHeader('test')->getValue()
        );
    }

    public function testPrependHeaderEmpty(): void
    {
        $message1 = new Message();
        $message2 = $message1->setHeader('test', 'value');
        $message3 = $message2->prependHeader('test', '');

        $this->assertSame(
            [
                'value'
            ],
            $message2->getHeader('test')->getValue()
        );

        $this->assertSame(
            [
                'value'
            ],
            $message3->getHeader('test')->getValue()
        );
    }

    public function testRemoveHeader(): void
    {
        $message1 = new Message();
        $message2 = $message1->setHeader('test', 'value');
        $message3 = $message2->removeHeader('test');

        $this->assertTrue(
            $message2->hasHeader('test')
        );

        $this->assertFalse(
            $message3->hasHeader('test')
        );
    }

    public function testSetBody(): void
    {
        $message1 = new Message();
        $message2 = $message1->setBody('test');

        $this->assertSame(
            'test',
            $message2->getBody()
        );
    }

    public function testSetHeader(): void
    {
        $message1 = new Message();
        $message2 = $message1->setHeader('test', 'value');

        $this->assertSame(
            [
                'value'
            ],
            $message2->getHeader('test')->getValue()
        );
    }

    public function testSetHeaderArray(): void
    {
        $message1 = new Message();
        $message2 = $message1->setHeader('test', ['first', 'last']);

        $this->assertSame(
            [
                'first',
                'last'
            ],
            $message2->getHeader('test')->getValue()
        );
    }

    public function testSetHeaderEmpty(): void
    {
        $message1 = new Message();
        $message2 = $message1->setHeader('test', '');

        $this->assertSame(
            [],
            $message2->getHeader('test')->getValue()
        );
    }

    public function testSetProtocolVersion(): void
    {
        $message1 = new Message();
        $message2 = $message1->setProtocolVersion('2.0');

        $this->assertSame(
            '2.0',
            $message2->getProtocolVersion()
        );
    }

    public function testSetProtocolVersionInvalid(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $message = new Message();
        $message->setProtocolVersion('2.1');
    }

}
