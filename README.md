# PHP-CSV

PHP-CSV is a package that allows you to manage CSV files in an object-oriented way.

## Installation

There's not much to do, just run this composer command

```
composer require wilgucki/php-csv
```

## Usage

Both, Reader and Writer classes are easy to use. In fact the classes are just a wrappers for built-in PHP functions.

### Reader

Reader class will help you with reading existing CSV files (no surprise here).

To read CSV file you need to open it first.

```php
$reader = new Reader();
$reader->open('/path/to/file.csv');
```

Constructor accepts optional arguments you can use to describe your CSV file. The arguments are:

* `$delimiter`
* `$enclosure`
* `$escape`
* `$encodingFrom`
* `$encodingTo`

First three arguments are exactly the same as fgetcsv function arguments described in the manual - http://php.net/manual/en/function.fgetcsv.php

Other two helps you define encoding - `$encodingFrom` is the encoding of CSV file and `$encodingTo` is encoding we get after file is read.

To read data from the CSV file we can use two functions: `readLine` and `readAll`. The former will read and return current row while the latter will
read whole CSV file and return it as an array of arrays (each line will be represented by an array).

If the CSV file has a header row, you can use `getHeader` function. This function will take the first row from CSV file and use it to set array keys
for `readLine` and `readAll` functions. This means that instead of numeric keys you can use labels defined in the first row.

#### Converters

Sometimes data available in csv file need to be converterd into more suitable format, e.g. convert dates into Carbon objects.
Converters make this task much easier. All you need to do is to create converter object and specify the column you want to
convert.

```php
$reader = new Reader();
$reader->open('/path/to/file.csv');
$reader->addConverter(3, new DateToCarbon());
$data = $reader->readLine();
```

You can assigon only one converter per column.

**Examples**

CSV file example
```
user_id,name
1,john
2,jane
```


```php
$reader = new Reader();
$reader->open('/path/to/file.csv');
print_r($reader->readLine());
/*
Array
(
    [0] => user_id
    [1] => name
)
*/
print_r($reader->readLine());
/*
Array
(
    [0] => 1
    [1] => john
)
*/

```

```php
$reader = new Reader();
$reader->open('/path/to/file.csv');
print_r($reader->readAll());
/*
Array
(
    [0] => Array
        (
            [0] => user_id
            [1] => name
        )

    [1] => Array
        (
            [0] => 1
            [1] => john
        )

    [2] => Array
        (
            [0] => 2
            [1] => jane
        )

)
*/
```

```php
$reader = new Reader();
$reader->open('/path/to/file.csv');
print_r($reader->getHeader());
/*
Array
(
    [0] => user_id
    [1] => name
)
*/

```

```php
$reader = new Reader();
$reader->open('/path/to/file.csv');
$reader->getHeader();
print_r($reader->readLine());
/*
Array
(
    [user_id] => 1
    [name] => john
)
*/

```

```php
$reader = new Reader();
$reader->open('/path/to/file.csv');
$reader->getHeader();
print_r($reader->readAll());
/*
Array
(
    [0] => Array
        (
            [user_id] => 1
            [name] => john
        )

    [1] => Array
        (
            [user_id] => 2
            [name] => jane
        )

)
*/

```

Don't forget to close CSV file after you are done.

```php
$reader->close();
```

### Writer

For creating/updating CSV files you can use Writer class. If you want to create file, you need to provide only a writable path.
For updating existing file, you have to use optional `$mode` argument. All available modes are described in the manual
(https://secure.php.net/manual/en/function.fopen.php) but only 'w' and 'a' values are usable in this case.

```php
$writer = new Writer();

// create new file
$writer->create('/path/to/file.csv');

// update existing file
$writer->create('/path/to/file.csv', 'a+');
```

`Writer` contructor accepts the same arguments as the `Reader` constructor. Only difference is that `$encodingFrom` refers to the input encoding and the 
`$encodingTo` refers to CSV file encoding.

There are two ways of writing CSV files - `writeLine` and `writeAll`. First method will write a single line to a CSV file, while the second method will
write multiple lines. If for some reason you need to access data you have written to a CSV file you can use `flush` method.

**Examples**

```php
// write a single line to the CSV file

$writer = new Writer();
$writer->create('/path/to/file.csv');
$writer->writeLine(['abc', 'def']);
```

```php
// write multiple lines to the CSV file

$writer = new Writer();
$writer->create('/path/to/file.csv');
$writer->writeAll([
    ['abc', 'def'],
    [123, 234]
]);
```

```php
// display added data

$writer = new Writer();
$writer->create('/path/to/file.csv');
$writer->writeAll([
    ['abc', 'def'],
    [123, 234]
]);
echo $writer->flush();
/*
abc,def
123,234
*/
```

Don't forget to close CSV file after you are done.

```php
$writer->close();
```

## TODO

- processors - process csv data and return the results
