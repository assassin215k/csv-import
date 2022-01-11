<?php
/**
 * Created by PhpStorm.
 * Author: Ihor Fedan
 * Date: 11.01.22
 * Time: 12:54
 */

namespace App\Service\Output;

use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * IOutputService
 */
interface IOutputService
{
    public function set(OutputInterface $output);

    public function get(): OutputInterface;

    public function getProgressBar(): ProgressBar;
}
