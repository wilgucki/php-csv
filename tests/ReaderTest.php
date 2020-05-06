<?php
namespace Wilgucki\PhpCsv\Tests;

use PHPUnit\Framework\TestCase;
use Wilgucki\PhpCsv\Exceptions\FileException;
use Wilgucki\PhpCsv\Reader;

class ReaderTest extends TestCase
{
    protected string $filepath;
    protected Reader $reader;

    public function setUp(): void
    {
        $dir = __DIR__; // xdebug issue workaround
        $this->filepath = $dir.'/assets/test1.csv';
    }

    public function testOpen(): void
    {
        $reader = new Reader();
        $csv = $reader->open($this->filepath);
        $this->assertInstanceOf(Reader::class, $csv);
    }

    public function testOpenNonExistingFile(): void
    {
        $this->expectException(FileException::class);
        $filepath = md5(uniqid('', true).microtime()).'.csv';
        $reader = new Reader();
        $reader->open($filepath);
    }

    public function testGetHeader(): void
    {
        $reader = new Reader();
        $reader->open($this->filepath);
        $header = $reader->getHeader();

        static::assertCount(3, $header);
        static::assertContains('Field 1', $header);
        static::assertContains('Field 2', $header);
        static::assertContains('Field 3', $header);
    }

    public function testReadLine(): void
    {
        $reader = new Reader();
        $reader->open($this->filepath);
        $line = $reader->readLine();

        static::assertEquals('Field 1', $line[0]);
        static::assertEquals('Field 2', $line[1]);
        static::assertEquals('Field 3', $line[2]);
        static::assertCount(3, $line);
    }

    public function testReadSecondLine(): void
    {
        $reader = new Reader();
        $reader->open($this->filepath);
        $reader->readLine();
        $line = $reader->readLine();

        static::assertEquals('aaa', $line[0]);
        static::assertEquals('bbb', $line[1]);
        static::assertEquals('ccc', $line[2]);
        static::assertCount(3, $line);
    }

    public function testReadLineWithHeader(): void
    {
        $reader = new Reader();
        $reader->open($this->filepath);
        $reader->getHeader();
        $line = $reader->readLine();

        static::assertArrayHasKey('Field 1', $line);
        static::assertArrayHasKey('Field 2', $line);
        static::assertArrayHasKey('Field 3', $line);
        static::assertEquals('aaa', $line['Field 1']);
        static::assertEquals('bbb', $line['Field 2']);
        static::assertEquals('ccc', $line['Field 3']);
        static::assertCount(3, $line);
    }

    public function testReadAll(): void
    {
        $reader = new Reader();
        $reader->open($this->filepath);
        $lines = $reader->readAll();

        static::assertCount(3, $lines);
        static::assertIsArray($lines[0]);
        static::assertIsArray($lines[1]);
        static::assertIsArray($lines[2]);
    }

    public function testReadAllWithHeader(): void
    {
        $reader = new Reader();
        $reader->open($this->filepath);
        $reader->getHeader();
        $lines = $reader->readAll();

        static::assertCount(2, $lines);
        static::assertIsArray($lines[0]);
        static::assertIsArray($lines[1]);
    }
}
