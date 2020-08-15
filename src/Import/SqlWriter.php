<?php
declare(strict_types=1);

namespace TaskForce\Import;

use TaskForce\Exception\SourceFileException;
use SplFileObject;

class SqlWriter
{
    private $filename;
    private $columns;
    private $tableName;
    private $fileObject;

    /**
     * SqlWriter constructor.
     * @param string $filename
     * @param array $columns
     * @param string $tableName
     */
    public function __construct(string $filename, array $columns, string $tableName)
    {
        $this->filename = $filename;
        $this->columns = $columns;
        $this->tableName = $tableName;
    }

    /**
     * @param array $data
     * @throws SourceFileException
     */
    public function writeFile(array $data): void
    {
        try
        {
            $this->fileObject = new SplFileObject($this->filename, 'w');
        }
        catch (\RuntimeException $exception)
        {
            throw new SourceFileException("Не удалось открыть файл на чтение");
        }

        $text = "INSERT INTO " . $this->tableName . " (" . implode(",", $this->columns) . ") VALUES";

        foreach ($data as $row)
        {
            if (is_array($row))
            {
                $text .= "\n(";
                $values = "";
                for ($i =0; $i < count($row); $i++)
                {
                    $values .= "'" . $row[$i] . "'";
                }
                $values = substr($values, 0, strlen($values) - 1);
                $text .= $values . "),";
            }
        }

        $text = rtrim($text, ',') . ';';
        $this->fileObject->fwrite($text);
    }
}