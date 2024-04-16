<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Common\Infrastructure\Doctrine\DBAL\Driver;

use Doctrine\DBAL\Driver\AbstractMySQLDriver;
use Doctrine\DBAL\Driver\PDO\Connection;
use InvalidArgumentException;
use Override;
use PDO;
use SensitiveParameter;

final class PersistentMySQLDriver extends AbstractMySQLDriver
{
    #[Override]
    public function connect(#[SensitiveParameter] array $params): Connection
    {
        if (empty($params['pdo'])) {
            throw new InvalidArgumentException('Invalid parameters: \'pdo\' parameter is not set');
        }

        if (!($params['pdo'] instanceof PDO)) {
            throw new InvalidArgumentException(
                sprintf(
                'Invalid parameters: \'pdo\' is not an instance of %s',
                    PDO::class,
                )
            );
        }

        return new Connection($params['pdo']);
    }
}
