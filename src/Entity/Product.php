<?php
/**
 * Created by PhpStorm.
 * Author: Ihor Fedan
 */

namespace App\Entity;

use DateTime;
use DateTimeInterface;
use JetBrains\PhpStorm\Pure;

/**
 * Product entity to import
 */
class Product implements CustomConstraintInterface
{
    /**
     * @var string The message to use in validation error
     */
    private static string $invalidMessage = 'If the product cost less then 5 that store must be more then 10';

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
     * Set first value for timestampable fields
     */
    public function __construct()
    {
        $this->createdAt = new DateTime();
        $this->updateAt = new DateTime();
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->code ?? '';
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string $description
     *
     * @return $this
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCode(): ?string
    {
        return $this->code;
    }

    /**
     * @param string $code
     *
     * @return $this
     */
    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @param bool $discontinued
     *
     * @return $this
     */
    public function setDiscontinued(bool $discontinued): self
    {
        $this->discontinued = $discontinued ? new DateTime() : null;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getStock(): ?int
    {
        return $this->stock;
    }

    /**
     * @param int $stock
     *
     * @return Product
     */
    public function setStock(int $stock): Product
    {
        $this->stock = $stock;

        return $this;
    }

    /**
     * @return float|null
     */
    public function getCost(): ?float
    {
        return $this->cost;
    }

    /**
     * @param float $cost
     *
     * @return Product
     */
    public function setCost(float $cost): Product
    {
        $this->cost = $cost;

        return $this;
    }

    /**
     * @return bool
     */
    #[Pure]
    public function isInvalid(): bool
    {
        return $this->getCost() < 5 && $this->getStock() < 10;
    }

    /**
     * @return string
     */
    public function getInvalidMessage(): string
    {
        return self::$invalidMessage;
    }
}
