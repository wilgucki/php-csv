<?php
namespace Wilgucki\PhpCsv\Converters;

use Carbon\Carbon;

class FormatDate implements ConverterInterface
{
    private string $format;

    /**
     * @param string $format
     * @codeCoverageIgnore
     */
    public function __construct(string $format)
    {
        $this->setFormat($format);
    }

    /**
     * @return string
     * @codeCoverageIgnore
     */
    public function getFormat(): string
    {
        return $this->format;
    }

    /**
     * @param string $format
     * @codeCoverageIgnore
     */
    public function setFormat(string $format): void
    {
        $this->format = $format;
    }

    public function convert(string $input): string
    {
        return Carbon::parse($input)->format($this->getFormat());
    }
}
