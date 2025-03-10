<?php
declare(strict_types=1);

class LadderPrinter
{
    public const MAX_NUMBERS = 100;

    /**
     * Генерирует строки для лесенки чисел.
     *
     * @return Generator<int, string>
     */
    private function getLadderLines(): Generator
    {
        $number = 1;
        $line = 1;
        $maxWidth = strlen((string) self::MAX_NUMBERS); // Определяем ширину самого длинного числа

        while ($number <= self::MAX_NUMBERS) {
            $lineNumbers = [];

            for ($i = 0; $i < $line && $number <= self::MAX_NUMBERS; $i++, $number++) {
                $lineNumbers[] = str_pad((string) $number, $maxWidth, " ", STR_PAD_LEFT);
            }

            yield implode(" ", $lineNumbers);
            $line++;
        }
    }

    /**
     * Выводит числа лесенкой.
     */
    public function printNumberLadder(): void
    {
        foreach ($this->getLadderLines() as $line) {
            echo $line . PHP_EOL;
        }
    }
}

// Использование
$printer = new LadderPrinter();
$printer->printNumberLadder();
