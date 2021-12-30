<?php
/**
 * Created by PhpStorm.
 * Author: Ihor Fedan
 * Date: 29.12.21
 * Time: 09:02.
 */

namespace App\Misc;

/**
 * ImportResponse.
 */
class ImportResponse
{
    public int $successItems = 0;

    public array $skippedString = [];
    public array $invalidCode = [];

    public function __toString(): string
    {
        $response = "=====\r\n";
        $response .= "Success: $this->successItems\r\n";

        $skipped = count($this->skippedString);
        if ($skipped) {
            $response .= 'Skipped line numbers: ';
            $response .= join(',', $this->skippedString)."\r\n";
        }

        $invalid = count($this->invalidCode);
        if ($invalid) {
            $response .= 'Invalid codes: ';
            $response .= join(',', $this->invalidCode)."\r\n";
        }

        $response .= "=====\r\n";
        $total = $this->successItems + $skipped + $invalid;
        $response .= "Total records proceed: $total\r\n";

        $response .= "=====\r\n";

        return $response;
    }

    public function total(): int
    {
        return $this->successItems + count($this->invalidCode) + count($this->skippedString);
    }
}
