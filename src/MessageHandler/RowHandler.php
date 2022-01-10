<?php
/**
 * Created by PhpStorm.
 * Author: Ihor Fedan
 * Date: 04.01.22
 * Time: 14:31
 */

namespace App\MessageHandler;

use App\Entity\Product;
use App\Exception\MissedReportException;
use App\Message\RowMessage;
use App\Misc\ImportResponse;
use App\Service\ReportService\IReportService;
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
     * @param IReportService         $reportService
     * @param LoggerInterface        $logger
     */
    public function __construct(private EntityManagerInterface $manager, private ValidatorService $validator, private readonly IReportService $reportService, private LoggerInterface $logger)
    {
    }

    /**
     * @throws MissedReportException
     *
     * @param RowMessage $row
     *
     * @return void
     */
    public function __invoke(RowMessage $row)
    {
        $report = $this->reportService->getReport($row->getReportKey());

        if (!$report) {
            throw new MissedReportException();
        }

        if ($report->isAdded($row->getCode())) {
            $report->skipped++;

            return;
        }

        $this->createProduct($row, $report);
    }

    /**
     * @param RowMessage     $row
     * @param ImportResponse $report
     */
    private function createProduct(RowMessage $row, ImportResponse $report): void
    {
        $report->inQueue--;

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

        $report->getProgressBar()->advance();

        if (!$this->validator->isValidProduct($product)) {
            $report->invalid++;

            throw new UnrecoverableMessageHandlingException();
        }

        try {
            $this->manager->persist($product);
            $this->manager->flush();
        } catch (\Exception $e) {
            $this->logger->critical($e->getMessage());
        }

        $report->addCode($row->getCode());
    }
}
