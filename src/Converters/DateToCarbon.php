<?php
namespace Wilgucki\PhpCsv\Converters;

use Carbon\Carbon;

class DateToCarbon implements ConverterInterface
{
    public function convert(string $input): Carbon
    {
        return Carbon::parse($input);
    }
}
