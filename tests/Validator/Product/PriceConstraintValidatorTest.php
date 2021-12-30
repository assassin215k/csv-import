<?php
/**
 * Created by PhpStorm.
 * Author: Ihor Fedan
 * Date: 28.12.21
 * Time: 11:50.
 */

namespace App\Tests\Validator\Product;

use App\Entity\PriceConstraintInterface;
use App\Exception\UnexpectedClassException;
use App\Validator\Product\PriceConstraint;
use App\Validator\Product\PriceConstraintValidator;
use DateTime;
use Exception;
use Mockery;
use Mockery\LegacyMockInterface;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilder;

/**
 * CustomConstraintValidatorTest.
 */
class PriceConstraintValidatorTest extends TestCase
{
    /**
     * @throws Exception
     *
     * @return void
     */
    public function testWrongTarget()
    {
        $constraint = new PriceConstraint();
        $validator = new PriceConstraintValidator();

        $this->expectException(UnexpectedClassException::class);
        $validator->validate(new DateTime(), $constraint);
    }

    /**
     * @throws Exception
     *
     * @return void
     */
    public function testWrongConstraint()
    {
        $value = Mockery::mock(PriceConstraintInterface::class);
        $validator = new PriceConstraintValidator();

        $this->expectException(UnexpectedTypeException::class);
        $validator->validate($value, new NotNull());
    }

    /**
     * @throws Exception
     *
     * @return void
     */
    public function testInvalidEntity()
    {
        $constraint = new PriceConstraint();
        $value = Mockery::mock(PriceConstraintInterface::class);

        $value->shouldReceive('getCost')->once()->andReturn(3.5);
        $value->shouldReceive('getStock')->once()->andReturn(9);

        $constraintViolationList = Mockery::mock(ConstraintViolationList::class);
        $constraintViolationList->shouldReceive('count')->andReturn(1);

        $context = Mockery::mock(ExecutionContextInterface::class);

        $buildViolation = $context->shouldReceive('buildViolation');
        $buildViolation->once()->with($constraint::$message);
        $buildViolation->andReturn($this->getMockConstraintViolationBuilder());
        $context->shouldReceive('getViolations')->andReturn($constraintViolationList);

        $validator = new PriceConstraintValidator();
        $validator->initialize($context);

        $validator->validate($value, $constraint);
        $this->assertGreaterThan(0, count($constraintViolationList));
    }

    /**
     * @dataProvider priceValidProvider
     *
     * @throws Exception
     *
     * @return void
     */
    public function testValidEntity(float $cost, int $stock)
    {
        $value = Mockery::mock(PriceConstraintInterface::class);
        $value->shouldReceive('getCost')->once()->andReturn($cost);
        $value->shouldReceive('getStock')->once()->andReturn($stock);

        $constraintViolationList = Mockery::mock(ConstraintViolationList::class);
        $constraintViolationList->shouldReceive('count')->andReturn(0);

        $context = Mockery::mock(ExecutionContextInterface::class);
        $context->shouldReceive('getViolations')->andReturn($constraintViolationList);

        $constraint = new PriceConstraint();
        $validator = new PriceConstraintValidator();
        $validator->initialize($context);

        $validator->validate($value, $constraint);
        $this->assertEquals(0, count($constraintViolationList));
    }

    public function priceValidProvider(): array
    {
        return [
            [15, 11],
            [1.5, 11],
            [20, 1],
        ];
    }

    private function getMockConstraintViolationBuilder(): MockInterface|ConstraintViolationBuilder|LegacyMockInterface
    {
        $builder = Mockery::mock(ConstraintViolationBuilder::class);
        $builder->shouldReceive('setParameter')->andReturn($builder);
        $builder->shouldReceive('addViolation');

        return $builder;
    }
}
