<?php
/**
 * Created by PhpStorm.
 * Author: Ihor Fedan
 * Date: 21.12.21
 * Time: 18:29
 */

namespace App\Validator;

use App\Entity\CustomConstraintInterface;
use App\Exception\UnexpectedClassException;
use Exception;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * Custom validator
 */
class CustomConstraintValidator extends ConstraintValidator
{

    /**
     * @throws Exception
     *
     * @param mixed      $value
     * @param Constraint $constraint
     */
    public function validate(mixed $value, Constraint $constraint)
    {
        if (!$value instanceof CustomConstraintInterface) {
            throw new UnexpectedClassException(CustomConstraintInterface::class);
        }

        if (!$constraint instanceof CustomConstraint) {
            throw new UnexpectedTypeException($constraint, CustomConstraint::class);
        }

        if ($value->isInvalid()) {
            $this->context->buildViolation($value->getInvalidMessage())->addViolation();
        }
    }
}
