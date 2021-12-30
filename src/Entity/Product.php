<?php
/**
 * Created by PhpStorm.
 * Author: Ihor Fedan.
 */

namespace App\Entity;

use DateTime;
use DateTimeInterface;

/**
 * Product entity to import.
 */
class Product implements PriceConstraintInterface
{
    private ?int $id;

    private ?string $name;

    private ?string $description;

    private ?string $code;

    private ?DateTimeInterface $discontinued;

    private ?int $stock;

    private ?float $cost;

    private ?DateTimeInterface $createdAt;

    private ?DateTimeInterface $updateAt;

    /**
     * Set first value for timestampable fields.
     */
    public function __construct()
    {
        $this->createdAt = new DateTime();
        $this->updateAt = new DateTime();
    }

    public function __toString(): string
    {
        return $this->code ?? '';
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name ?? '';
    }

    /**
     * @return $this
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): string
    {
        return $this->description ?? '';
    }

    /**
     * @return $this
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCode(): string
    {
        return $this->code ?? '';
    }

    /**
     * @return $this
     */
    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getDiscontinued(): ?DateTimeInterface
    {
        return $this->discontinued;
    }

    /**
     * @return $this
     */
    public function setDiscontinued(bool $discontinued): self
    {
        $this->discontinued = $discontinued ? new DateTime() : null;

        return $this;
    }

    public function getCost(): float
    {
        return $this->cost ?? 0;
    }

    public function setCost(float $cost): Product
    {
        $this->cost = $cost;

        return $this;
    }

    public function getStock(): int
    {
        return $this->stock ?? 0;
    }

    public function setStock(int $stock): Product
    {
        $this->stock = $stock;

        return $this;
    }
}
