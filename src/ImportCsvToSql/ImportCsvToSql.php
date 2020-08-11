<?php
declare(strict_types=1);

namespace TaskForce\Import;
use TaskForce\Exception\FileFormatException;
use TaskForce\Exception\SourceFileException;
use TaskForce\Import\CsvReader;
use TaskForce\Import\SqlWriter;

class ImportCsvToSql
{
    /**
     * @param string $fileName
     * @param array $columns
     * @param string $tableName
     * @throws FileFormatException
     * @throws SourceFileException
     */
    static function createSql(string $fileName, array $columns, string $tableName): void
    {
        $loader = new CsvReader($fileName, $columns);
        $loader->checkFile();
        $records = $loader->getData();
        $sqlFileName = preg_replace('/csv$/', 'sql', $fileName);
        $writer = new SqlWriter($sqlFileName, $columns,$tableName);
        $writer->writeFile($records);
        print ("Файл " . $sqlFileName . " успешно создан");
        print ("\n");
    }
}