<?php
namespace Wilgucki\PhpCsv;


/**
 * Class AbstractCsv
 * @package Wilgucki\PhpCsv
 */
abstract class AbstractCsv
{
    protected $delimiter;
    protected $enclosure;
    protected $escape;
    protected $encodingFrom = null;
    protected $encodingTo = null;
    protected $handle = null;

    /**
     * @param string $delimiter @link http://php.net/manual/en/function.fgetcsv.php
     * @param string $enclosure @link http://php.net/manual/en/function.fgetcsv.php
     * @param string $escape @link http://php.net/manual/en/function.fgetcsv.php
     * @param string|null $encodingFrom Input encoding
     * @param string|null $encodingTo Output encoding
     */
    public function __construct(
        $delimiter = ',',
        $enclosure = '"',
        $escape = '\\',
        $encodingFrom = null,
        $encodingTo = null
    ) {
        $this->delimiter = $delimiter;
        $this->enclosure = $enclosure;
        $this->escape = $escape;
        $this->encodingFrom = $encodingFrom;
        $this->encodingTo = $encodingTo;
    }

    /**
     * Open CSV file
     *
     * @param string $file File name with path to open
     * @param string $mode @link http://php.net/manual/en/function.fopen.php
     */
    public function open($file, $mode)
    {
        $this->handle = fopen($file, $mode);
    }

    /**
     * Close file pointer
     */
    public function close()
    {
        fclose($this->handle);
    }
}
