<?php declare(strict_types=1);

namespace Tests\Helpers;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Illuminate\Foundation\Testing\Concerns\InteractsWithTestCaseLifecycle;

trait DoctrineTransactions
{
    use InteractsWithTestCaseLifecycle;

    private readonly Connection $doctrineConnection;

    /**
     * @throws Exception
     */
    public function beginDoctrineTransaction(): void
    {
        if (!isset($this->doctrineConnection)) {
            $this->doctrineConnection = $this->resolve(EntityManagerInterface::class)->getConnection();
        }

        $this->doctrineConnection->beginTransaction();

        $this->beforeApplicationDestroyed(function(): void {
            $this->doctrineConnection->rollBack();
        });
    }
}
