<?php
/**
 * Created by PhpStorm.
 * Author: Ihor Fedan
 * Date: 29.12.21
 * Time: 09:02.
 */

namespace App\Misc;

use JetBrains\PhpStorm\Pure;
use Symfony\Component\Console\Helper\ProgressBar;

/**
 * ImportResponse.
 */
class ImportResponse
{
    public int $skipped = 0;
    public int $invalid = 0;
    public int $inQueue = 0;

    public array $alreadyAdded = [];

    private ProgressBar $progressBar;

    /**
     * @return string
     */
    #[Pure]
    public function __toString(): string
    {
        $response = "=====\r\n";
        $response .= "Report\r\n";
        $response .= "\r\n";
        $response .= "Added/Updated products: ".$this->success()."\r\n";

        if ($this->skipped) {
            $response .= "Skipped rows: $this->skipped \r\n";
        }

        if ($this->invalid) {
            $response .= "Invalid records: $this->invalid\r\n";
        }

        $response .= "\r\n";
        $total = $this->success() + $this->skipped + $this->invalid;
        $response .= "Total records proceed: $total\r\n";

        $response .= "=====\r\n";

        return $response;
    }

    public function isAdded(string $code): bool
    {
        return array_key_exists($code, $this->alreadyAdded);
    }

    public function addCode(string $code): void
    {
        if (array_key_exists($code, $this->alreadyAdded)) {
            return;
        }

        $this->alreadyAdded[$code] = '';
    }

    public function setProgressBar(ProgressBar $progressBar):void {
        $this->progressBar = $progressBar;
    }

    public function getProgressBar():ProgressBar {
        return $this->progressBar;
    }

    private function success(): int
    {
        return count($this->alreadyAdded);
    }
}
