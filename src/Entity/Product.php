<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;
use Doctrine\ORM\Mapping\Table;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

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
	 *
	 * @Assert\NotBlank
	 * @Assert\Length(max=50)
	 */
	private ?string $name;
	
	/**
	 * @var string|null $description
	 *
	 * @ORM\Column(type="string", length=255, name="strProductDesc")
	 *
	 * @Assert\NotBlank
	 * @Assert\Length(max=255)
	 */
	private ?string $description;
	
	/**
	 * @var string|null $code
	 *
	 * @ORM\Column(type="string", length=10, name="strProductCode")
	 *
	 * @Assert\NotBlank
	 * @Assert\Length(max=10)
	 */
	private ?string $code;
	
	/**
	 * @var DateTimeInterface|null $isDiscontinued
	 *
	 * @ORM\Column(type="datetime", name="dtmDiscontinued", nullable="true")
	 */
	private ?DateTimeInterface $discontinued;
	
	/**
	 * @var int|null $stock
	 *
	 * @ORM\Column(type="integer", name="intProductStock", options={"unsigned"=true})
	 *
	 * @Assert\LessThanOrEqual(1000)
	 * @Assert\GreaterThan(0)
	 */
	private ?int $stock;
	
	/**
	 * @var float|null $cost
	 *
	 * @ORM\Column(type="decimal", precision="16", scale="2", name="numProductCost", options={"unsigned"=true})
	 *
	 * @Assert\GreaterThan(0)
	 */
	private ?float $cost;
	
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
	 * @ORM\Column(type="datetime", name="stmTimestamp", columnDefinition="timestamp default CURRENT_TIMESTAMP not null on update CURRENT_TIMESTAMP")
	 */
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
		$this->discontinued = $discontinued ? new DateTime(): null;
		
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
	
	/**
	 * Validate custom rules
	 *
	 * @param ExecutionContextInterface $context
	 *
	 * @return void
	 */
	public function validate(ExecutionContextInterface $context)
	{
		if ($this->cost > 1000) {
			$context->buildViolation('Cost is too high!')
			        ->atPath('cost')
			        ->addViolation();
		}
		if ($this->cost < 5 && $this->stock < 10) {
			$context->buildViolation('Not enough stock of the cheap product!')
			        ->atPath('stock')
			        ->addViolation();
		}
	}
}
