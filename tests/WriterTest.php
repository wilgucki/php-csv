<?php
namespace Wilgucki\PhpCsv\Tests;

use Wilgucki\PhpCsv\Writer;
use PHPUnit\Framework\TestCase;

class WriterTest extends TestCase
{
    /**
     * @var string
     */
    protected string $filepath;
    /**
     * @var Writer
     */
    protected Writer $writer;

    public function setUp(): void
    {
        $this->filepath = tempnam(sys_get_temp_dir(), md5(uniqid('', true).time()));
        $this->writer = new Writer();
    }

    public function tearDown(): void
    {
        if (file_exists($this->filepath)) {
            unlink($this->filepath);
        }
        $this->writer->close();
    }

    public function testCreate(): void
    {
        $csv = $this->writer->create($this->filepath);
        static::assertInstanceOf(Writer::class, $csv);
    }

    public function testWriteLine(): void
    {
        $this->writer->create($this->filepath);
        $result = $this->writer->writeLine(['aaa', 'bbb', 'ccc']);
        static::assertIsInt($result);
    }

    public function testWriteAll(): void
    {
        $data = [
            ['aaa', 'bbb', 'ccc'],
            [111, 222, 333]
        ];
        $this->writer->create($this->filepath);
        $this->writer->writeAll($data);
        $savedData = $this->writer->flush();
        static::assertStringContainsString('aaa,bbb,ccc', $savedData);
        static::assertStringContainsString('111,222,333', $savedData);
    }

    public function testFlush(): void
    {
        $data = [
            ['aaa', 'bbb', 'ccc'],
            [111, 222, 333]
        ];
        $this->writer->create($this->filepath);
        $this->writer->writeAll($data);
        $flushed = $this->writer->flush();
        static::assertEquals('aaa,bbb,ccc'.PHP_EOL.'111,222,333'.PHP_EOL, $flushed);
    }
}
