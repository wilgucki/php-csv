<?php
namespace Wilgucki\PhpCsv\Tests\Converters;

use Wilgucki\PhpCsv\Converters\Generic;
use PHPUnit\Framework\TestCase;

class GenericTest extends TestCase
{
    /**
     * @param string $function
     * @param string $input
     * @param array $args
     * @param mixed $expected
     *
     * @dataProvider functionDataProvider
     */
    public function testConvert(string $function, string $input, array $args, $expected)
    {
        $generic = new Generic($function, ...$args);
        $result = $generic->convert($input);
        $this->assertEquals($expected, $result);
    }

    /**
     * @return array
     */
    public function functionDataProvider()
    {
        return [
            ['number_format', 10000.4567, [3, ',', ' '], '10 000,457'],
            ['substr', 'some-value', [0, 4], 'some'],
        ];
    }
}
