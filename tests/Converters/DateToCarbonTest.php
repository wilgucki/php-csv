<?php /** @noinspection PhpUnhandledExceptionInspection */

namespace Wilgucki\PhpCsv\Tests\Converters;

use Carbon\Carbon;
use PHPUnit\Framework\TestCase;
use Wilgucki\PhpCsv\Converters\DateToCarbon;
use Wilgucki\PhpCsv\Exceptions\ReaderException;
use Wilgucki\PhpCsv\Reader;

class DateToCarbonTest extends TestCase
{
    public function testConvert(): void
    {
        $dir = __DIR__;
        $filepath = realpath($dir.'/../assets/test2.csv');

        $reader = new Reader();
        $reader->addConverter(0, new DateToCarbon());
        $reader->open($filepath);
        $csv = $reader->readLine();
        static::assertCount(3, $csv);
        static::assertInstanceOf(Carbon::class, $csv[0]);
    }

    public function testConvertWithHeader(): void
    {
        $dir = __DIR__;
        $filepath = realpath($dir.'/../assets/test3.csv');

        $reader = new Reader();
        $reader->addConverter(0, new DateToCarbon());
        $reader->open($filepath);
        $reader->getHeader();
        $csv = $reader->readLine();
        static::assertCount(3, $csv);
        static::assertInstanceOf(Carbon::class, $csv['Field 1']);
    }

    public function testMultipleConvertersOnSingleColumn(): void
    {
        $this->expectException(ReaderException::class);
        $this->expectExceptionMessage('Converter already assigned to column 0');

        $dir = __DIR__;
        $filepath = realpath($dir.'/../assets/test2.csv');

        $reader = new Reader();
        $reader->addConverter(0, new DateToCarbon());
        $reader->addConverter(0, new DateToCarbon());
        $reader->open($filepath);
        $reader->readLine();
    }
}
