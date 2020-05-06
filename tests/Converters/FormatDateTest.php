<?php
namespace Wilgucki\PhpCsv\Tests\Converters;

use Exception;
use Wilgucki\PhpCsv\Converters\FormatDate;
use PHPUnit\Framework\TestCase;

class FormatDateTest extends TestCase
{
    /**
     * @param string $date
     * @param string $expectedResult
     * @dataProvider datesDataProvider
     */
    public function testConvert(string $date, string $expectedResult): void
    {
        $converter = new FormatDate('Y-m-d');
        $result = $converter->convert($date);

        static::assertEquals($expectedResult, $result);
    }

    /**
     * @param string $date
     * @dataProvider invalidDatesDataProvider
     */
    public function testConvertInvalidDate(string $date): void
    {
        $this->expectException(Exception::class);
        $converter = new FormatDate('Y-m-d');
        $converter->convert($date);
    }

    public function datesDataProvider(): array
    {
        return [
            ['April 1st 2020', '2020-04-01'],
            ['3/15/2008', '2008-03-15'],
            ['24-02-2010', '2010-02-24'],
        ];
    }

    public function invalidDatesDataProvider(): array
    {
        return [
            ['some text'],
            ['11111-88-99'],
            ['2020-33-33'],
            ['2020-01-44'],
            ['20-20-20'],
            ['May 40th 1980']
        ];
    }
}
