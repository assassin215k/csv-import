<?php
/**
 * Created by PhpStorm.
 * Author: Ihor Fedan
 * Date: 28.12.21
 * Time: 12:54
 */

namespace App\Tests\Validator\Product;

use App\Validator\Product\PriceConstraint;
use PHPUnit\Framework\TestCase;

/**
 * CustomConstraintTest
 */
class PriceConstraintTest extends TestCase
{
    /**
     * @return void
     */
    public function testGetTargets()
    {
        $constraint = new PriceConstraint();

        $this->assertEquals('App\Validator\Product\PriceConstraintValidator', $constraint->validatedBy());
    }

    /**
     * @return void
     */
    public function testValidatedBy()
    {
        $constraint = new PriceConstraint();

        $this->assertEquals('class', $constraint->getTargets());
    }

    /**
     * @return void
     */
    public function testMessage()
    {
        $constraint = new PriceConstraint();

        $this->assertSame('If the price less then 5 that stock must be more then 10.', $constraint::$message);
    }
}
