<?php
/**
 * Created by PhpStorm.
 * Author: Ihor Fedan
 * Date: 21.12.21
 * Time: 18:29.
 */

namespace App\Validator\Product;

use App\Entity\PriceConstraintInterface;
use App\Exception\UnexpectedClassException;
use Exception;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * Custom validator.
 */
class PriceConstraintValidator extends ConstraintValidator
{
    /**
     * @throws Exception
     */
    public function validate(mixed $value, Constraint $constraint)
    {
        if (!$value instanceof PriceConstraintInterface) {
            throw new UnexpectedClassException(PriceConstraintInterface::class);
        }

        if (!$constraint instanceof PriceConstraint) {
            throw new UnexpectedTypeException($constraint, PriceConstraint::class);
        }

        if ($value->getCost() < 5 && $value->getStock() < 10) {
            $this->context->buildViolation($constraint::$message)->addViolation();
        }
    }
}
