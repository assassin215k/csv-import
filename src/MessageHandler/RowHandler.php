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
use App\Service\ReportService;
use App\Service\ValidatorService;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Exception\UnrecoverableMessageHandlingException;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

/**
 * RowHandler
 */
class RowHandler implements MessageHandlerInterface
{
    /**
     * @param EntityManagerInterface $manager
     * @param ValidatorService       $validator
     * @param LoggerInterface        $logger
     * @param ReportService          $reportService
     */
    public function __construct(private EntityManagerInterface $manager, private ValidatorService $validator, private LoggerInterface $logger, private readonly ReportService $reportService)
    {
    }

    /**
     * @param RowMessage $row
     *
     * @return void
     */
    public function __invoke(RowMessage $row)
    {
        $this->reportService->init($row->getReportKey());
        $this->reportService->decreaseQueueLength();

        if ($this->reportService->hasCode($row->getCode())) {
            $this->reportService->addSkipped();

            return;
        }

        $this->createProduct($row);
    }

    /**
     * @param RowMessage     $row
     */
    private function createProduct(RowMessage $row): void
    {
        $repository = $this->manager->getRepository(Product::class);
        $product = $repository->findOneBy(['code' => $row->getCode()]);
        if (!$product) {
            $product = new Product();
            $product->setCode($row->getCode());
        }

        $product->setCost($row->getCost());
        $product->setName($row->getName());
        $product->setDescription($row->getDescription());
        $product->setStock($row->getStock());
        $product->setDiscontinued($row->isDiscontinued());

        if (!$this->validator->isValidProduct($product)) {
            $this->reportService->addInvalid();

            throw new UnrecoverableMessageHandlingException();
        }

        try {
            $this->manager->persist($product);
            $this->manager->flush();

            $this->reportService->addSuccess();
            $this->reportService->addCode($row->getCode());
        } catch (\Exception $e) {
            $this->reportService->addInvalid();

            $this->logger->critical($e->getMessage());
        }
    }
}
