<?php
namespace Wilgucki\PhpCsv\Converters;

/**
 * Wrapper for built-in PHP functions
 *
 * @package Wilgucki\PhpCsv\Converters
 */
class Generic implements ConverterInterface
{
    /**
     * @var string
     */
    private string $function;
    /**
     * @var array
     */
    private array $args;

    /**
     * @param string $function
     * @param array $args
     */
    public function __construct(string $function, ...$args)
    {
        $this->function = $function;
        $this->args = $args;
    }

    /**
     * @param string $input
     * @return mixed
     */
    public function convert(string $input)
    {
        array_unshift($this->args, $input);
        return call_user_func_array($this->function, $this->args);
    }
}
