<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;
use Doctrine\ORM\Mapping\Table;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @Table(name="tblProductData", uniqueConstraints={
 *     @UniqueConstraint(name="strProductCode", columns={"strProductCode"})
 * })
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 */
class Product {
	
	/**
	 * @var int|null $id
	 *
	 * @ORM\Id
	 * @ORM\GeneratedValue
	 * @ORM\Column(type="integer", name="intProductDataId", options={"unsigned"=true})
	 */
	private ?int $id;
	
	/**
	 * @var string|null $name
	 *
	 * @ORM\Column(type="string", length=50, name="strProductName")
	 */
	private ?string $name;
	
	/**
	 * @var string|null $description
	 *
	 * @ORM\Column(type="string", length=255, name="strProductDesc")
	 */
	private ?string $description;
	
	/**
	 * @var string|null $code
	 *
	 * @ORM\Column(type="string", length=10, name="strProductCode")
	 */
	private ?string $code;
	
	/**
	 * @var bool|null $isDiscontinued
	 *
	 * @ORM\Column(type="boolean", name="blnDiscontinued", options={"default"=false}, columnDefinition="boolean default
	 *                             false not null")
	 */
	private ?bool $isDiscontinued;
	
	/**
	 * @var int|null $stock
	 *
	 * @ORM\Column(type="integer", name="intProductStock", options={"unsigned"=true})
	 */
	private ?int $stock;
	
	/**
	 * @var float|null $cost
	 *
	 * @ORM\Column(type="decimal", precision="16", scale="2", name="numProductCost", options={"unsigned"=true})
	 */
	private ?float $cost;
	
	/**
	 * @var bool|null $isDeleted
	 *
	 * @ORM\Column(type="boolean", name="blnIsDeleted", options={"default"=false}, columnDefinition="boolean default
	 *                             false not null")
	 */
	private ?bool $isDeleted;
	
	/**
	 * @var DateTimeInterface|null $createdAt
	 *
	 * @Gedmo\Timestampable(on="create")
	 * @ORM\Column(type="datetime", name="dtmAdded", columnDefinition="timestamp default CURRENT_TIMESTAMP not null")
	 */
	private ?DateTimeInterface $createdAt;
	
	/**
	 * @var DateTimeInterface|null $updateAt
	 *
	 * @Gedmo\Timestampable(on="update")
	 * @ORM\Column(type="datetime", name="stmTimestamp", columnDefinition="timestamp default CURRENT_TIMESTAMP not null
	 *                              on update CURRENT_TIMESTAMP")
	 */
	private ?DateTimeInterface $updateAt;
	
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
	
	public function isDiscontinued(): bool {
		return $this->isDiscontinued ?? false;
	}
	
	public function setIsDiscontinued( bool $isDiscontinued ): self {
		$this->isDiscontinued = $isDiscontinued;
		
		return $this;
	}
	
	public function isDeleted(): bool {
		return $this->isDeleted ?? false;
	}
	
	public function setIsDeleted( bool $isDeleted ): self {
		$this->isDeleted = $isDeleted;
		
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
	public function setStock( int $stock ): self {
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
	public function setCost( float $cost ): self {
		$this->cost = $cost;
		
		return $this;
	}
	
	public function getUpdateAt(): ?DateTimeInterface {
		return $this->updateAt;
	}
	
	public function getCreatedAt(): ?DateTimeInterface {
		return $this->createdAt;
	}
}
