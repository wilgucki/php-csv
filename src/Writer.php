<?php
namespace Wilgucki\PhpCsv;

/**
 * CSV Writer class. Object oriented way of writing CSV files.
 *
 * @package Wilgucki\PhpCsv
 * @author Maciej Wilgucki <mwilgucki@gmail.com>
 * @license https://github.com/wilgucki/dbrepository/blob/master/LICENSE
 * @link https://github.com/wilgucki/csv
 */
class Writer extends AbstractCsv
{
    protected $handle = null;
    protected string $delimiter;
    protected string $enclosure;
    protected string $escape;

    /**
     * Open CSV file for writing.
     *
     * @param string $file File name for writing CSV data. If not provided CSV data will be written to memory
     * @param string $mode @link http://php.net/manual/en/function.fopen.php
     * @return $this
     */
    public function create(string $file = 'php://memory', string $mode = 'w+'): self
    {
        parent::open($file, $mode);
        return $this;
    }

    /**
     * Write line to CSV file.
     *
     * @param array $row
     * @return bool|int
     */
    public function writeLine(array $row): int
    {
        return $this->write($row);
    }

    /**
     * Write all lines to CSV file
     *
     * @param array $data
     */
    public function writeAll(array $data): void
    {
        foreach ($data as $row) {
            $this->writeLine($row);
        }
    }

    /**
     * Output all written data as string.
     *
     * @return string
     */
    public function flush(): string
    {
        rewind($this->handle);
        $out = stream_get_contents($this->handle);
        fseek($this->handle, 0, SEEK_END);
        return $out;
    }

    /**
     * Wrapper for fputcsv function
     *
     * @param array $row
     * @return bool|int
     */
    private function write(array $row): int
    {
        if ($this->encodingFrom !== null && $this->encodingTo !== null) {
            foreach ($row as $k => $v) {
                $row[$k] = iconv($this->encodingFrom, $this->encodingTo, $v);
            }
        }

        return fputcsv($this->handle, $row, $this->delimiter, $this->enclosure, $this->escape);
    }
}
