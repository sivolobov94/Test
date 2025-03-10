<?php
declare(strict_types=1);

class Matrix
{
    private int $rows;
    private int $columns;
    private int $min;
    private int $max;
    private array $matrix = [];

    /**
     * @param int $rows
     * @param int $columns
     * @param int $min
     * @param int $max
     */
    public function __construct(int $rows = 5, int $columns = 7, int $min = 1, int $max = 1000)
    {
        $this->rows = $rows;
        $this->columns = $columns;
        $this->min = $min;
        $this->max = $max;

        $this->validateRange();
        $this->generateMatrix();
    }

    /**
     * Проверяет, что диапазон [min, max] содержит достаточно уникальных чисел.
     *
     * @throws InvalidArgumentException Если диапазон недостаточен.
     */
    private function validateRange(): void
    {
        if (($this->max - $this->min + 1) < ($this->rows * $this->columns)) {
            throw new InvalidArgumentException('Диапазон чисел недостаточен для заполнения матрицы уникальными значениями.');
        }
    }

    /**
     * Генерирует матрицу размером rows x columns, заполненную уникальными случайными числами.
     */
    private function generateMatrix(): void
    {
        $totalNumbers = $this->rows * $this->columns;
        $numbers = range($this->min, $this->max);
        shuffle($numbers);
        $selectedNumbers = array_slice($numbers, 0, $totalNumbers);

        for ($i = 0; $i < $this->rows; $i++) {
            $this->matrix[$i] = array_slice($selectedNumbers, $i * $this->columns, $this->columns);
        }
    }

    /**
     * @return array
     */
    public function getRowSums(): array
    {
        return array_map('array_sum', $this->matrix);
    }

    /**
     * @return array
     */
    public function getColumnSums(): array
    {
        $columnSums = array_fill(0, $this->columns, 0);
        foreach ($this->matrix as $row) {
            foreach ($row as $colIndex => $value) {
                $columnSums[$colIndex] += $value;
            }
        }
        return $columnSums;
    }

    /**
     * Выводит матрицу, суммы по строкам и столбцам.
     */
    public function printMatrixWithSums(): void
    {
        $rowSums = $this->getRowSums();
        $colSums = $this->getColumnSums();

        $flattenMatrix = array_merge(...$this->matrix);
        $allValues = array_merge($flattenMatrix, $rowSums, $colSums);

        $maxWidth = max(array_map(static fn($v) => strlen((string)$v), $allValues)) + 1;

        echo "Матрица:\n";
        foreach ($this->matrix as $i => $row) {
            foreach ($row as $value) {
                echo str_pad((string)$value, $maxWidth, " ", STR_PAD_LEFT);
            }
            // Вывод суммы строки
            echo " | " . str_pad((string)$rowSums[$i], $maxWidth, " ", STR_PAD_LEFT) . "\n";
        }

        // Разделительная строка между матрицей и суммами столбцов
        echo str_repeat('-', ($this->columns * $maxWidth) + $maxWidth + 3) . "\n";

        // Вывод сумм столбцов
        foreach ($colSums as $colSum) {
            echo str_pad((string)$colSum, $maxWidth, " ", STR_PAD_LEFT);
        }
        echo "\n";
    }
}

try {
    $matrix = new Matrix();
    $matrix->printMatrixWithSums();
} catch (Exception $e) {
    fwrite(STDERR, "Ошибка: " . $e->getMessage() . PHP_EOL);
    exit(1);
}
