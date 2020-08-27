<?php
require_once('vendor/autoload.php');

use TaskForce\Exception\FileFormatException;
use TaskForce\Exception\SourceFileException;

use TaskForce\Converter\CsvToSqlConverter;

// массив csv файлов
foreach (glob('data/*.csv') as $file) {
    $filenames = ltrim($file, "data/");
}

// создание массива заголовков файлов csv
foreach ($filenames as $filename) {
    if (!file_exists(__DIR__ . '/data/' . $filename)) {
        throw new SourceFileException('Файл ' . $filename . ' не существует');
    }

    $fileObject = new SplFileObject(__DIR__ . '/data/' . $filename);
    $fileObject->rewind();
    $importTable[$filename] = $fileObject->fgetcsv();
}

foreach ($importTable as $filename => $columns) {
    $sqlFileName = preg_replace('/csv$/', 'sql', $filename);

    $csvToSqlConverter = new CsvToSqlConverter($filename, $columns);

    try {
        $csvToSqlConverter->checkFile(__DIR__ . '/data/' . $filename, $columns);
    } catch (FileFormatException $e) {
        error_log('Неверная форма файла импорта: ' . $e->getMessage());
        print('Неверная форма файла импорта: ' . $e->getMessage());
    } catch (SourceFileException $e) {
        error_log('Не удалось обработать csv файл' . $filename . ": " . $e->getMessage());
        print('Не удалось обработать csv файл' . $filename . ': ' . $e->getMessage());
    }

    try {
        $csvToSqlConverter->writeFile(__DIR__ . '/data/' . $sqlFileName);
    } catch (SourceFileException $e) {
        error_log('Не удалось обработать файл' . $filename . ': ' . $e->getMessage());
        print('Не удалось обработать файл' . $filename . ': ' . $e->getMessage());
    }
}