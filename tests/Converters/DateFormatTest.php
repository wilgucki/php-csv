<?php
namespace Wilgucki\PhpCsv\Tests\Converters;

use Carbon\Carbon;
use PHPUnit\Framework\TestCase;
use Wilgucki\PhpCsv\Converters\DateToCarbon;
use Wilgucki\PhpCsv\Exceptions\ReaderException;
use Wilgucki\PhpCsv\Reader;

class DateFormatTest extends TestCase
{
    private $filepath;

    public function setUp()
    {
        $dir = __DIR__;
        $this->filepath = realpath($dir.'/../assets/test2.csv');
    }

    public function testConvert()
    {
        $reader = new Reader();
        $reader->addConverter(0, new DateToCarbon());
        $reader->open($this->filepath);
        $csv = $reader->readLine();
        static::assertCount(3, $csv);
        static::assertInstanceOf(Carbon::class, $csv[0]);
    }

    public function testMultipleConvertersOnSingleColumn()
    {
        $this->expectException(ReaderException::class);
        $this->expectExceptionMessage('Converter already assigned to column 0');

        $reader = new Reader();
        $reader->addConverter(0, new DateToCarbon());
        $reader->addConverter(0, new DateToCarbon());
        $reader->open($this->filepath);
        $reader->readLine();
    }
}
