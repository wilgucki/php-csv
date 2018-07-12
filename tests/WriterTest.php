<?php
namespace Wilgucki\PhpCsv\Tests;

use Wilgucki\PhpCsv\Writer;
use PHPUnit\Framework\TestCase;

class WriterTest extends TestCase
{
    /**
     * @var string
     */
    protected $filepath;
    /**
     * @var Writer
     */
    protected $writer;


    public function setUp()
    {
        $this->filepath = tempnam(sys_get_temp_dir(), md5(uniqid().time()));
        $this->writer = new Writer();
    }

    public function tearDown()
    {
        if (file_exists($this->filepath)) {
            unlink($this->filepath);
        }
        $this->writer->close();
    }

    public function testCreate()
    {
        $csv = $this->writer->create($this->filepath);
        $this->assertTrue($csv instanceof Writer);
    }

    public function testWriteLine()
    {
        $this->writer->create($this->filepath);
        $result = $this->writer->writeLine(['aaa', 'bbb', 'ccc']);
        $this->assertTrue(is_int($result));
    }

    public function testWriteAll()
    {
        $data = [
            ['aaa', 'bbb', 'ccc'],
            [111, 222, 333]
        ];
        $this->writer->create($this->filepath);
        $this->writer->writeAll($data);
        $savedData = $this->writer->flush();
        $this->assertContains('aaa,bbb,ccc', $savedData);
        $this->assertContains('111,222,333', $savedData);
    }

    public function testFlush()
    {
        $data = [
            ['aaa', 'bbb', 'ccc'],
            [111, 222, 333]
        ];
        $this->writer->create($this->filepath);
        $this->writer->writeAll($data);
        $flushed = $this->writer->flush();
        $this->assertEquals('aaa,bbb,ccc'.PHP_EOL.'111,222,333'.PHP_EOL, $flushed);
    }
}
