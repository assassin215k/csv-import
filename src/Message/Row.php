<?php
/**
 * Created by PhpStorm.
 * Author: Ihor Fedan
 * Date: 04.01.22
 * Time: 8:26
 */

namespace App\Message;
/**
 * Row
 */
class Row
{
    /**
     * @param array $content
     * @param int   $lineNumber
     */
    public function __construct(private readonly array $content, private readonly int $lineNumber)
    {
    }

    /**
     * @return array
     */
    public function getContent(): array
    {
        return $this->content;
    }

    /**
     * @return int
     */
    public function getLineNumber(): int
    {
        return $this->lineNumber;
    }
}
