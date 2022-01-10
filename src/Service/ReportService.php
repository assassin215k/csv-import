<?php
/**
 * Created by PhpStorm.
 * Author: Ihor Fedan
 * Date: 10.01.22
 * Time: 11:01
 */

namespace App\Service;

use Predis\Client;

class ReportService
{
    private const SKIPPED = 'SKIPPED';
    private const INVALID = 'INVALID';
    private const SUCCESS = 'SUCCESS';
    private const QUEUE_LENGTH = 'QUEUE_LENGTH';
    private const CODE = 'CODE';

    private Client $client;
    private int $key;

    public function __construct(string $url)
    {
        $this->client = new Client($url);
    }

    public function init(int $key): void
    {
        $this->key = $key;
    }

    public function addSkipped(): void
    {
        $this->client->incr("$this->key[".self::SKIPPED."]");
    }

    public function getSkipped(): int
    {
        return $this->client->get("$this->key[".self::SKIPPED."]") ?? 0;
    }

    public function addInvalid(): void
    {
        $this->client->incr("$this->key[".self::INVALID."]");
    }

    public function getInvalid(): int
    {
        return $this->client->get("$this->key[".self::INVALID."]") ?? 0;
    }

    public function addSuccess(): void
    {
        $this->client->incr("$this->key[".self::SUCCESS."]");
    }

    public function getSuccess(): int
    {
        return $this->client->get("$this->key[".self::SUCCESS."]") ?? 0;
    }

    public function addCode(string $code): void
    {
        $this->client->set("$this->key[".self::CODE."][$code]", null);
    }

    public function hasCode(string $code): bool
    {
        return (bool) $this->client->exists("$this->key[".self::CODE."][$code]");
    }

    public function increaseQueueLength(): void
    {
        $this->client->incr("$this->key[".self::QUEUE_LENGTH."]");
    }

    public function decreaseQueueLength(): void
    {
        $this->client->decr("$this->key[".self::QUEUE_LENGTH."]");
    }

    public function getQueueLength(): int
    {
        return $this->client->get("$this->key[".self::QUEUE_LENGTH."]") ?? 0;
    }

    public function purge(): void
    {
        $this->client->del($this->client->keys($this->key));
    }
}
