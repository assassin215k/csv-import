<?php
/**
 * Created by PhpStorm.
 * Author: Ihor Fedan
 * Date: 28.12.21
 * Time: 12:54
 */

namespace App\Tests\Validator;

use App\Validator\CustomConstraint;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Constraint;

/**
 * CustomConstraintTest
 */
class CustomConstraintTest extends TestCase
{
    /**
     * @return void
     */
    public function testGetTargets()
    {
        $constraint = new CustomConstraint();

        $this->assertEquals('App\Validator\CustomConstraintValidator', $constraint->validatedBy());
    }

    /**
     * @return void
     */
    public function testValidatedBy()
    {
        $constraint = new CustomConstraint();

        $this->assertEquals('class', $constraint->getTargets());
    }
}
