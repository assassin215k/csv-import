<?php
/**
 * Created by PhpStorm.
 * Author: Ihor Fedan
 * Date: 04.01.22
 * Time: 8:26
 */

namespace App\Message;
use App\Enum\Row;

/**
 * Row
 */
class RowMessage
{
    private string $code;
    private string $name;
    private string $description;
    private float $cost;
    private int $stock;
    private bool $discontinued;

    /**
     * @param array $content
     * @param int   $reportKey
     */
    public function __construct(array $content, private readonly int $reportKey)
    {
        $this->code = (string) $content[Row::CODE];
        $this->name = (string) $content[Row::NAME];
        $this->description = (string) $content[Row::DESC];
        $this->cost = (float) $content[Row::COST];
        $this->stock = (int) $content[Row::STOCK];
        $this->discontinued = (bool) $content[Row::DISC];
    }

    /**
     * @return int
     */
    public function getReportKey(): int
    {
        return $this->reportKey;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return float
     */
    public function getCost(): float
    {
        return $this->cost;
    }

    /**
     * @return int
     */
    public function getStock(): int
    {
        return $this->stock;
    }

    /**
     * @return bool
     */
    public function isDiscontinued(): bool
    {
        return $this->discontinued;
    }
}
