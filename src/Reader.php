<?php
namespace Wilgucki\PhpCsv;

use Wilgucki\PhpCsv\Converters\ConverterInterface;
use Wilgucki\PhpCsv\Exceptions\FileException;
use Wilgucki\PhpCsv\Exceptions\ReaderException;

/**
 * CSV Reader class. Object oriented way of reading CSV files.
 *
 * @package Wilgucki\PhpCsv
 * @author Maciej Wilgucki <mwilgucki@gmail.com>
 * @license https://github.com/wilgucki/php-csv/blob/master/LICENSE
 * @link https://github.com/wilgucki/php-csv
 */
class Reader extends AbstractCsv
{
    private bool $withHeader = false;
    private ?array $header = [];
    private array $converters = [];

    /**
     * Assigns converter to the specified column
     *
     * @param int $columnNo
     * @param ConverterInterface $converter
     * @throws ReaderException
     */
    public function addConverter(int $columnNo, ConverterInterface $converter)
    {
        if (isset($this->converters[$columnNo])) {
            throw new ReaderException('Converter already assigned to column '.$columnNo);
        }
        $this->converters[$columnNo] = $converter;
    }

    /**
     * Open CSV file for reading
     *
     * @param string $file File name with path to open
     * @param string $mode @link http://php.net/manual/en/function.fopen.php
     * @return $this
     * @throws FileException
     */
    public function open(string $file, string $mode = 'r+'): self
    {
        if (!file_exists($file)) {
            throw new FileException('CSV file does not exist');
        }

        parent::open($file, $mode);
        return $this;
    }

    /**
     * Get CSV header. Usually it's the first line in file.
     *
     * @return array
     */
    public function getHeader(): ?array
    {
        $this->withHeader = true;
        if (ftell($this->handle) == 0) {
            $this->header = $this->read();
        }
        return $this->header;
    }

    /**
     * Read current line from CSV file
     *
     * @return array|false
     */
    public function readLine()
    {
        $line = $this->read();
        if ($line === false) {
            return $line;
        }

        $out = [];

        foreach ($line as $columnNo => $value) {
            if (isset($this->converters[$columnNo])) {
                $out[$columnNo] = $this->converters[$columnNo]->convert($value);
            } else {
                $out[$columnNo] = $line[$columnNo];
            }
        }

        if ($this->withHeader && is_array($out)) {
            $out = array_combine($this->header, $out);
        }
        return $out;
    }

    /**
     * Read all lines from CSV file
     *
     * @return array
     */
    public function readAll(): array
    {
        $out = [];
        while (($row = $this->readLine()) !== false) {
            $out[] = $row;
        }
        return $out;
    }

    /**
     * Wrapper for fgetcsv function
     *
     * @return array|null|false
     */
    private function read()
    {
        $out = fgetcsv($this->handle, null, $this->delimiter, $this->enclosure);

        if (!is_array($out)) {
            return $out;
        }

        if ($this->encodingFrom !== null && $this->encodingTo !== null) {
            foreach ($out as $k => $v) {
                $out[$k] = iconv($this->encodingFrom, $this->encodingTo, $v);
            }
        }

        return $out;
    }
}
