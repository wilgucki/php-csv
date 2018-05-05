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
        $this->filepath = dirname(__FILE__).DIRECTORY_SEPARATOR.'assets/test1.csv';
    }

    public function testOpen()
    {
        $reader = new Reader();
        $csv = $reader->open($this->filepath);
        $this->assertTrue($csv instanceof Reader);
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

        $this->assertCount(3, $header);
        $this->assertTrue(in_array('Field 1', $header));
        $this->assertTrue(in_array('Field 2', $header));
        $this->assertTrue(in_array('Field 3', $header ));
    }

    public function testReadLine()
    {
        $reader = new Reader();
        $reader->open($this->filepath);

        $line = $reader->readLine();

        $this->assertEquals('Field 1', $line[0]);
        $this->assertEquals('Field 2', $line[1]);
        $this->assertEquals('Field 3', $line[2]);

        $this->assertCount(3, $line);
    }

    public function testReadSecondLine()
    {
        $reader = new Reader();
        $reader->open($this->filepath);

        $reader->readLine();
        $line = $reader->readLine();

        $this->assertEquals('aaa', $line[0]);
        $this->assertEquals('bbb', $line[1]);
        $this->assertEquals('ccc', $line[2]);

        $this->assertCount(3, $line);
    }

    public function testReadLineWithHeader()
    {
        $reader = new Reader();
        $reader->open($this->filepath);
        $reader->getHeader();

        $line = $reader->readLine();
        $this->assertArrayHasKey('Field 1', $line);
        $this->assertArrayHasKey('Field 2', $line);
        $this->assertArrayHasKey('Field 3', $line);

        $this->assertEquals('aaa', $line['Field 1']);
        $this->assertEquals('bbb', $line['Field 2']);
        $this->assertEquals('ccc', $line['Field 3']);

        $this->assertCount(3, $line);
    }

    public function testReadAll()
    {
        $reader = new Reader();
        $reader->open($this->filepath);

        $lines = $reader->readAll();
        $this->assertCount(3, $lines);
        $this->assertTrue(is_array($lines[0]));
        $this->assertTrue(is_array($lines[1]));
        $this->assertTrue(is_array($lines[2]));
    }

    public function testReadAllWithHeder()
    {
        $reader = new Reader();
        $reader->open($this->filepath);
        $reader->getHeader();

        $lines = $reader->readAll();
        $this->assertCount(2, $lines);
        $this->assertTrue(is_array($lines[0]));
        $this->assertTrue(is_array($lines[1]));
    }
}
