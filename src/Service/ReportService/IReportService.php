<?php
/**
 * Created by PhpStorm.
 * Author: Ihor Fedan
 * Date: 10.01.22
 * Time: 11:19
 */

namespace App\Service\ReportService;

use App\Misc\ImportResponse;

/**
 * IReportService
 */
interface IReportService
{
    public function createReport(int $key): void;

    public function getReport(int $key): ?ImportResponse;

    public function removeReport(int $key): void;
}
