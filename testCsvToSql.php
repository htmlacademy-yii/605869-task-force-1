<?php
require_once('vendor/autoload.php');

use TaskForce\Exception\FileFormatException;
use TaskForce\Exception\SourceFileException;
use TaskForce\Converter\CsvToSqlConverter;

// массив csv файлов
$filenamesWithPath = glob('data/*.csv');
$arraySize = count($filenamesWithPath);

// создание массива заголовков файлов csv
for ($i = 0; $i < $arraySize; $i++) {
    $filenames = array();
    $filenames[$i] = ltrim($filenamesWithPath[$i], 'data/');
    $fileObject[$i] = new SplFileObject($filenamesWithPath[$i]);
    $fileObject[$i]->rewind();
    $columns = $fileObject[$i]->fgetcsv();

    $sqlFileName = preg_replace('/csv$/', 'sql', $filenamesWithPath[$i]);

    $csvToSqlConverter = new CsvToSqlConverter($filenamesWithPath[$i], $columns);

    try {
        $csvToSqlConverter->checkFile($filenamesWithPath[$i], $columns);
    } catch (FileFormatException $e) {
        error_log('Неверная форма файла импорта: ' . $e->getMessage());
        print('Неверная форма файла импорта: ' . $e->getMessage());
    } catch (SourceFileException $e) {
        error_log('Не удалось обработать csv файл' . $filenamesWithPath[$i] . ": " . $e->getMessage());
        print('Не удалось обработать csv файл' . $filenamesWithPath[$i] . ': ' . $e->getMessage());
    }

    try {
        $csvToSqlConverter->writeFile($sqlFileName);
    } catch (SourceFileException $e) {
        error_log('Не удалось обработать файл' . $sqlFileName . ': ' . $e->getMessage());
        print('Не удалось обработать файл' . $sqlFileName . ': ' . $e->getMessage());
    }
}