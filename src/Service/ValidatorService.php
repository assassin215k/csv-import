<?php
/**
 * Created by PhpStorm.
 * Author: Ihor Fedan
 * Date: 16.12.21
 * Time: 08:30
 */

namespace App\Service;

use App\Entity\Product;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Validator class to validate Product before write to DB
 */
class ValidatorService
{

    private readonly ValidatorInterface $validator;

    /**
     * map yaml to validator
     */
    public function __Construct()
    {
        $this->validator = Validation::createValidatorBuilder()
            ->addYamlMapping(__DIR__.'/../Validator/product.yaml')
            ->getValidator();
    }

    /**
     * @param Product $product
     *
     * @return bool
     */
    public function isValidProduct(Product $product): bool
    {
        return !count($this->validator->validate($product));
    }
}
