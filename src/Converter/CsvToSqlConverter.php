<?php
declare(strict_types=1);

namespace TaskForce\Converter;

use TaskForce\Exception\FileFormatException;
use TaskForce\Exception\SourceFileException;
use SplFileObject;

class CsvToSqlConverter
{
    /**
     * @var SplFileObject
     */
    private $fileObject;

    /**
     * @var SplFileObject
     */
    private $fileObjectSql;

    /**
     * содержимое таблицы
     * @var array
     */

    /**
     * @var bool
     */
    private $result;

    /**
     * содержимое таблицы
     * @var array
     */
    private $data;

    /**
     * @var string|string[]|null
     */
    private $sqlFileName;

    /**
     * @var array
     */
    private $columns;

    /**
     * @var string
     */
    private $tableName;

    /**
     * ImportCsvToSql constructor.
     * @param string $filename
     * @param array $columns
     */
    public function __construct(string $filename, array $columns)
    {
        $this->filename = $filename;
        $this->columns = $columns;
        $this->sqlFileName = preg_replace('/csv$/', 'sql', $filename);
        $this->tableName = rtrim($filename, '.csv');
    }

    /**
     * @param string $filename
     * @param array $columns
     * @throws FileFormatException
     * @throws SourceFileException
     */
    public function checkFile(string $filename, array $columns): void
    {
        if (!$this->validateColumns($columns)) {
            throw new FileFormatException('Заданы неверные заголовки столбцов');
        }

        if (!file_exists($filename)) {
            throw new SourceFileException('Файл (' . $filename . ') не существует');
        }

        try {
            $this->fileObject = new SplFileObject($filename);
        } catch (\RuntimeException $exception) {
            throw new SourceFileException('Не удалось открыть файл (' . $filename . ') на чтение');
        }

        if ($this->fileObject->getExtension() !== 'csv') {
            throw new SourceFileException('Недопустимое расширение файла (' . $filename . ')');
        }

        if ($this->fileObject->getSize() === 0) {
            throw new SourceFileException('пустой файл(' . $filename . ')');
        }

        $headerData = $this->getHeaderData();

        if ($headerData !== $columns) {
            throw new FileFormatException('Исходный файл(' . $filename . ') не содержит необходимых столбцов');
        }

        foreach ($this->getNextLine() as $line) {
            $val = $line;
            if(implode($val) == null) {
                continue;
            } else {
                $dataString = implode(', ', array_map(function($item) {
                    return "'{$item}'";
                }, $val));
                $dataString = sprintf('(%s)', $dataString);
                $this->data[] = $dataString;
            }
        }
    }

    /**
     * @return array
     */
    private function getData(): array
    {
        return $this->data;
    }

    /**
     * получение названий колонок
     * @return array
     */
    private function getHeaderData(): array
    {
        $this->fileObject->rewind();
        return $this->fileObject->fgetcsv();
    }

    /**
     * @return iterable|null
     */
    private function getNextLine(): ?iterable
    {
        $result = null;
        while (!$this->fileObject->eof()) {
            yield $this->fileObject->fgetcsv();
        }
    }

    /**
     * проверка колонок
     * @param $columns
     * @return bool
     */
    private function validateColumns($columns): bool
    {
        $result = true;

        if (!count($columns)) {
            $result = false;
        }

        foreach ($columns as $column) {
            if (!is_string($column)) {
                $result = false;
            }
        }

        return $result;
    }

    /**
     * запись в файл
     * @param $sqlFileName
     * @throws SourceFileException
     */
    public function writeFile($sqlFileName): void
    {
        try {
            $this->fileObjectSql = new SplFileObject($sqlFileName, 'w');
        } catch (\RuntimeException $exception) {
            throw new SourceFileException('Не удалось открыть файл (' . $sqlFileName . ') на запись');
        }

//        $value = array_map(function ($row) {
//            $values = "";
//            if (is_array($row)) {
//                for ($i = 0; $i < count($row); $i++) {
//                    $values .= "'" . $row[$i] . "'";
//                }
//            }
//            return $values;
//            },
//            $this->data
//        );
//        foreach ($value as $val) {
//            print ($val);
//            print ("\n");
//        }

//        создание запроса на добавление в таблицу
        $sqlQuery = sprintf(
            "INSERT INTO %s (%s) " . PHP_EOL ."VALUES " . PHP_EOL . "%s;",
            $this->tableName,
            implode(',', $this->columns),
            implode(', ' . PHP_EOL, $this->data)
        );

        $this->fileObjectSql->fwrite($sqlQuery);

        print ('Файл (' . $sqlFileName . ') успешно создан');
        print ("\n");
    }
}