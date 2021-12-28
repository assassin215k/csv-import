<?php
/**
 * Created by PhpStorm.
 * Author: Ihor Fedan
 * Date: 28.12.21
 * Time: 11:50
 */

namespace App\Tests\Validator;

use App\Entity\CustomConstraintInterface;
use App\Exception\UnexpectedClassException;
use App\Validator\CustomConstraint;
use App\Validator\CustomConstraintValidator;
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
 * CustomConstraintValidatorTest
 */
class CustomConstraintValidatorTest extends TestCase
{
    /**
     * @throws Exception
     *
     * @return void
     */
    public function testWrongTarget()
    {
        $constraint = new CustomConstraint();
        $validator = new CustomConstraintValidator();

        $this->expectException(UnexpectedClassException::class);
        $validator->validate(new \DateTime(), $constraint);
    }

    /**
     * @throws Exception
     *
     * @return void
     */
    public function testWrongConstraint()
    {
        $value = Mockery::mock(CustomConstraintInterface::class);
        $validator = new CustomConstraintValidator();

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
        $value = Mockery::mock(CustomConstraintInterface::class);

        $value->shouldReceive('isInvalid')->once()->andReturnTrue();
        $value->shouldReceive('getInvalidMessage')->once()->andReturn('error description');

        $constraintViolationList = Mockery::mock(ConstraintViolationList::class);
        $constraintViolationList->shouldReceive('count')->andReturn(1);

        $context = Mockery::mock(ExecutionContextInterface::class);

        $buildViolation = $context->shouldReceive('buildViolation');
        $buildViolation->once()->with($value->getInvalidMessage());
        $buildViolation->andReturn($this->getMockConstraintViolationBuilder());
        $context->shouldReceive('getViolations')->andReturn($constraintViolationList);

        $constraint = new CustomConstraint();
        $validator = new CustomConstraintValidator();
        $validator->initialize($context);

        $validator->validate($value, $constraint);
        $this->assertGreaterThan(0, count($constraintViolationList));
    }

    /**
     * @return MockInterface|ConstraintViolationBuilder|LegacyMockInterface
     */
    private function getMockConstraintViolationBuilder(): MockInterface|ConstraintViolationBuilder|LegacyMockInterface
    {
        $builder = Mockery::mock(ConstraintViolationBuilder::class);
        $builder->shouldReceive('setParameter')->andReturn($builder);
        $builder->shouldReceive('addViolation');

        return $builder;
    }

    /**
     * @throws Exception
     *
     * @return void
     */
    public function testValidEntity()
    {
        $value = Mockery::mock(CustomConstraintInterface::class);

        $value->shouldReceive('isInvalid')->once()->andReturnFalse();

        $constraintViolationList = Mockery::mock(ConstraintViolationList::class);
        $constraintViolationList->shouldReceive('count')->andReturn(0);

        $context = Mockery::mock(ExecutionContextInterface::class);
        $context->shouldReceive('getViolations')->andReturn($constraintViolationList);

        $constraint = new CustomConstraint();
        $validator = new CustomConstraintValidator();
        $validator->initialize($context);

        $validator->validate($value, $constraint);
        $this->assertEquals(0, count($constraintViolationList));
    }
}
