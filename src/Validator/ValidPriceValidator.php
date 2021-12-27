<?php
/**
 * Created by PhpStorm.
 * Author: Ihor Fedan
 * Date: 21.12.21
 * Time: 18:29
 */

namespace App\Validator;

use App\Entity\Product;
use Exception;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * Custom validator
 */
class ValidPriceValidator extends ConstraintValidator
{

    /**
     * @throws Exception
     *
     * @param mixed      $value
     * @param Constraint $constraint
     */
    public function validate(mixed $value, Constraint $constraint)
    {
        if (!$value instanceof Product) {
            throw new Exception(sprintf("Unexpected class, required %s", Product::class));
        }

        if (!$constraint instanceof ValidPrice) {
            throw new UnexpectedTypeException($constraint, ValidPrice::class);
        }

        if ($value->getCost() < 5 && $value->getStock() < 10) {
            $this->context->buildViolation($constraint->message)->addViolation();
        }
    }
}
