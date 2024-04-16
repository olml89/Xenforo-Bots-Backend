<?php declare(strict_types=1);

namespace Tests\Helpers;

interface ExecutesDoctrineTransactions
{
    public function beginDoctrineTransaction(): void;
}
