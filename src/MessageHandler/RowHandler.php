<?php
/**
 * Created by PhpStorm.
 * Author: Ihor Fedan
 * Date: 04.01.22
 * Time: 14:31
 */

namespace App\MessageHandler;

use App\Entity\Product;
use App\Message\RowMessage;
use App\Repository\ProductRepository;
use App\Service\ValidatorService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Exception\UnrecoverableMessageHandlingException;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

/**
 * RowHandler
 */
class RowHandler implements MessageHandlerInterface
{
    /**
     * @param EntityManagerInterface $manager
     * @param ProductRepository      $repository
     * @param ValidatorService       $validator
     */
    public function __construct(private EntityManagerInterface $manager, private ProductRepository $repository, private ValidatorService $validator)
    {
    }

    /**
     * @param RowMessage $row
     *
     * @return void
     */
    public function __invoke(RowMessage $row)
    {
        $product = $this->getProduct($row);

        if (!$this->validator->isValidProduct($product)) {
            throw new UnrecoverableMessageHandlingException();
        }

        $this->manager->persist($product);
        $this->manager->flush();
    }

    /**
     * @param RowMessage $row
     *
     * @return Product
     */
    private function getProduct(RowMessage $row): Product
    {
        $product = $this->repository->findOneBy(['code' => $row->getCode()]);
        if (!$product) {
            $product = new Product();
            $product->setCode($row->getCode());
        }

        $product->setCost($row->getCost());
        $product->setName($row->getName());
        $product->setDescription($row->getDescription());
        $product->setStock($row->getStock());
        $product->setDiscontinued($row->isDiscontinued());

        return $product;
    }
}
