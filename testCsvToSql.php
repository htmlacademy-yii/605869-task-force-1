<?php
require_once('vendor/autoload.php');

use TaskForce\Exception\FileFormatException;
use TaskForce\Exception\SourceFileException;

use TaskForce\Import\ImportCsvToSql;

try {
    ImportCsvToSql::createSql(__DIR__ . '/data/categories.csv',
        ['name', 'icon'],
        'categories');
    ImportCsvToSql::createSql(__DIR__ . '/data/cities.csv',
        ['city', 'lat', 'long'],
        'cities');
    ImportCsvToSql::createSql(__DIR__ . '/data/opinions.csv',
        ['dt_add', 'rate', 'description', 'task_id'],
        'opinions');
    ImportCsvToSql::createSql(__DIR__ . '/data/profiles.csv',
        ['address', 'bd', 'about', 'phone', 'skype', 'user_id', 'city_id'],
        'profiles');
    ImportCsvToSql::createSql(__DIR__ . '/data/replies.csv',
        ['dt_add', 'price', 'description', 'task_id', 'user_id'],
        'replies');
    ImportCsvToSql::createSql(__DIR__ . '/data/tasks.csv',
        ['dt_add', 'category_id', 'description', 'expire', 'name', 'address', 'budget', 'lat', 'long'],
        'tasks');
    ImportCsvToSql::createSql(__DIR__ . '/data/users.csv',
        ['email', 'name', 'password', 'dt_add'],
        'users');
} catch (SourceFileException $e) {
    error_log("Не удалось обработать csv файл: " . $e->getMessage());
    print("Не удалось обработать csv файл: " . $e->getMessage());
} catch (FileFormatException $e) {
    error_log("Неверная форма файла импорта: " . $e->getMessage());
    print("Неверная форма файла импорта: " . $e->getMessage());
}