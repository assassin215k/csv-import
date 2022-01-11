<?php
/**
 * Created by PhpStorm.
 * Author: Ihor Fedan
 * Date: 14.12.21
 * Time: 16:45.
 */

namespace App\Service\Output;

use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * OutputService to save output stream and progress bar.
 */
class OutputService implements IOutputService
{
    private OutputInterface $output;
    private ProgressBar $bar;

    public function set(OutputInterface $output)
    {
        $this->output = $output;
    }

    public function get(): OutputInterface
    {
        return $this->output;
    }

    public function getProgressBar(): ProgressBar
    {
        if (empty($this->bar)) {
            $this->bar = new ProgressBar($this->output);
        }

        return $this->bar;
    }
}
