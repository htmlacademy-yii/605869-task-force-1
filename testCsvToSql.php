<?php
require_once('vendor/autoload.php');

use TaskForce\Exception\FileFormatException;
use TaskForce\Exception\SourceFileException;

use TaskForce\Import\ImportCsvToSql;

// массив csv файлов
$filenames = ['categories.csv', 'cities.csv', 'opinions.csv', 'profiles.csv', 'replies.csv', 'tasks.csv', 'users.csv'];

// создание массива заголовков файлов csv
foreach ($filenames as $filename) {
    if (!file_exists(__DIR__ . '/data/' . $filename)) {
        throw new SourceFileException("Файл " . $filename . " не существует");
    }

    $fileObject = new SplFileObject(__DIR__ . '/data/' . $filename);
    $fileObject->rewind();
    $importTable[$filename] = $fileObject->fgetcsv();
}

foreach ($importTable as $filename => $columns) {
    $sqlFileName = preg_replace('/csv$/', 'sql', $filename);

    $ImportCsvToSql = new ImportCsvToSql($filename, $columns);

    try {
        $ImportCsvToSql->checkFile(__DIR__ . '/data/' . $filename, $columns);
    } catch (FileFormatException $e) {
        error_log("Неверная форма файла импорта: " . $e->getMessage());
        print("Неверная форма файла импорта: " . $e->getMessage());
    } catch (SourceFileException $e) {
        error_log("Не удалось обработать csv файл" . $filename . ": " . $e->getMessage());
        print("Не удалось обработать csv файл" . $filename . ": " . $e->getMessage());
    }

    try {
        $ImportCsvToSql->writeFile(__DIR__ . '/data/' . $sqlFileName);
    } catch (SourceFileException $e) {
        error_log("Не удалось обработать файл" . $filename . ": " . $e->getMessage());
        print("Не удалось обработать файл" . $filename . ": " . $e->getMessage());
    }
}