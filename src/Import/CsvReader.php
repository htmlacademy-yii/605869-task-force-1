<?php
declare(strict_types=1);

namespace TaskForce\Import;

use TaskForce\Exception\FileFormatException;
use TaskForce\Exception\SourceFileException;
use SplFileObject;

class CsvReader
{
    private $filename;
    private $columns;
    private $fileObject;
    private $result = [];

    /**
     * CsvReader constructor.
     * @param string $filename
     * @param array $columns
     */
    public function __construct(string $filename, array $columns)
    {
        $this->filename = $filename;
        $this->columns = $columns;
    }

    /**
     * @throws FileFormatException
     * @throws SourceFileException
     */
    public function checkFile(): void
    {
        if (!$this->validateColumns($this->columns)) {
            throw new FileFormatException("Заданы неверные заголовки столбцов");
        }
        if (!file_exists($this->filename)) {
            throw new SourceFileException("Файл не существует");
        }
        try {
            $this->fileObject = new SplFileObject($this->filename);
        } catch (\RuntimeException $exception) {
            throw new SourceFileException("Не удалось открыть файл на чтение");
        }
        if ($this->fileObject->getExtension() !== 'csv') {
            throw new SourceFileException("Недопустимое расширение файла");
        }
        if ($this->fileObject->getSize() === 0) {
            throw new SourceFileException("пустой файл");
        }

        $header_data = $this->getHeaderData();

        if ($header_data !== $this->columns) {
            throw new FileFormatException("Исходный файл не содержит необходимых столбцов");
        }

        foreach ($this->getNextLine() as $line) {
            $this->result = $line;
        }
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->result;
    }

    /**
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
     * @param $columns
     * @return bool
     */
    private function validateColumns($columns): bool
    {
        $result = true;
        if (count($columns)) {
            foreach ($columns as $column) {
                if (!is_string($column)) {
                    $result = false;
                }
            }
        } else {
            $result = false;
        }
        return $result;
    }
}