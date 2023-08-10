<?php declare(strict_types=1);

namespace Tests;

trait PreparesDatabase
{
    public function migrate(): void
    {
        $this->artisan('doctrine:migrations:migrate', ['--no-interaction' => true]);
    }

    public function resetMigrations(): void
    {
        $this->artisan('doctrine:migrations:reset');
    }
}
