<?php
namespace Wilgucki\PhpCsv\Converters;

use Carbon\Carbon;

class DateFormat implements ConverterInterface
{
    private $format;

    public function __construct(string $format)
    {
        $this->format = $format;
    }

    public function convert($input)
    {
        return Carbon::parse($input);
    }
}
