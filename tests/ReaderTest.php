<?php
namespace Wilgucki\PhpCsv\Tests;

use PHPUnit\Framework\TestCase;
use Wilgucki\PhpCsv\Reader;

class ReaderTest extends TestCase
{
    /**
     * @var string
     */
    protected $filepath;
    /**
     * @var Reader
     */
    protected $reader;

    public function setUp()
    {
        $this->filepath = dirname(__FILE__).'assets/test1.csv';
    }

    public function testOpen()
    {
        $reader = new Reader();
        $csv = $reader->open($this->filepath);
        static::assertTrue($csv instanceof Reader);
    }

    /**
     * @expectedException \Wilgucki\PhpCsv\Exceptions\FileException
     */
    public function testOpenNonExistingFile()
    {
        $filepath = md5(uniqid().microtime()).'.csv';
        $reader = new Reader();
        $reader->open($filepath);
    }

    public function testGetHeader()
    {
        $reader = new Reader();
        $reader->open($this->filepath);
        $header = $reader->getHeader();

        static::assertCount(3, $header);
        static::assertTrue(in_array('Field 1', $header));
        static::assertTrue(in_array('Field 2', $header));
        static::assertTrue(in_array('Field 3', $header ));
    }

    public function testReadLine()
    {
        $reader = new Reader();
        $reader->open($this->filepath);
        $line = $reader->readLine();

        static::assertEquals('Field 1', $line[0]);
        static::assertEquals('Field 2', $line[1]);
        static::assertEquals('Field 3', $line[2]);
        static::assertCount(3, $line);
    }

    public function testReadSecondLine()
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

    public function testReadLineWithHeader()
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

    public function testReadAll()
    {
        $reader = new Reader();
        $reader->open($this->filepath);
        $lines = $reader->readAll();

        static::assertCount(3, $lines);
        static::assertTrue(is_array($lines[0]));
        static::assertTrue(is_array($lines[1]));
        static::assertTrue(is_array($lines[2]));
    }

    public function testReadAllWithHeder()
    {
        $reader = new Reader();
        $reader->open($this->filepath);
        $reader->getHeader();
        $lines = $reader->readAll();

        static::assertCount(2, $lines);
        static::assertTrue(is_array($lines[0]));
        static::assertTrue(is_array($lines[1]));
    }
}
