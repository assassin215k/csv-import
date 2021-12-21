<?php

namespace App\Entity;

use DateTime;
use DateTimeInterface;
use Symfony\Component\Validator\Constraints as Assert;

class Product {
	
	private ?int $id;
	
	private ?string $name;
	
	private ?string $description;
	
	private ?string $code;
	
	private ?DateTimeInterface $discontinued;
	
	private ?int $stock;
	
	private ?float $cost;
	
	private ?DateTimeInterface $createdAt;
	
	private ?DateTimeInterface $updateAt;
	
	public function __construct() {
		$this->createdAt = new DateTime();
		$this->updateAt  = new DateTime();
	}
	
	public function __toString(): string {
		return $this->code ?? '';
	}
	
	public function getId(): ?int {
		return $this->id;
	}
	
	public function getName(): ?string {
		return $this->name;
	}
	
	public function setName( string $name ): self {
		$this->name = $name;
		
		return $this;
	}
	
	public function getDescription(): ?string {
		return $this->description;
	}
	
	public function setDescription( string $description ): self {
		$this->description = $description;
		
		return $this;
	}
	
	public function getCode(): ?string {
		return $this->code;
	}
	
	public function setCode( string $code ): self {
		$this->code = $code;
		
		return $this;
	}
	
	public function setDiscontinued( bool $discontinued ): self {
		$this->discontinued = $discontinued ? new DateTime() : null;
		
		return $this;
	}
	
	/**
	 * @return int|null
	 */
	public function getStock(): ?int {
		return $this->stock;
	}
	
	/**
	 * @param int $stock
	 *
	 * @return Product
	 */
	public function setStock( int $stock ): Product {
		$this->stock = $stock;
		
		return $this;
	}
	
	/**
	 * @return float|null
	 */
	public function getCost(): ?float {
		return $this->cost;
	}
	
	/**
	 * @param float $cost
	 *
	 * @return Product
	 */
	public function setCost( float $cost ): Product {
		$this->cost = $cost;
		
		return $this;
	}
}
