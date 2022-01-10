<?php
/**
 * Created by PhpStorm.
 * Author: Ihor Fedan
 * Date: 10.01.22
 * Time: 11:01
 */

namespace App\Service\ReportService;

use App\Exception\DuplicateKeyReportException;
use App\Misc\ImportResponse;

class ReportService implements IReportService
{
    private array $reports = [];

    /**
     * @throws DuplicateKeyReportException
     *
     * @param int $key
     */
    public function createReport(int $key): void
    {
        if (!empty($this->reports[$key])) {
            throw new DuplicateKeyReportException();
        }

        $this->reports[$key] = new ImportResponse();
    }

    /**
     * @param int $key
     *
     * @return ImportResponse|null
     */
    public function getReport(int $key): ?ImportResponse
    {
        return empty($this->reports[$key]) ? null : $this->reports[$key];
    }

    /**
     * @param int $key
     *
     * @return void
     */
    public function removeReport(int $key): void
    {
        if (!empty($this->reports[$key])) {
            unset($this->reports[$key]);
        }
    }
}
