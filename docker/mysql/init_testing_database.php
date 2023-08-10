<?php declare(strict_types=1);

use Dotenv\Parser\Parser;
use Illuminate\Config\Repository as Config;
use Illuminate\Contracts\Console\Kernel;

/**
 * Autoload dependencies
 */
require __DIR__.'/../../vendor/autoload.php';

/**
 * Get the development app environment (without loading it) to get the root password
 */
$dotEnvParser = new Parser();
$developmentEnvironment = [];

foreach ($dotEnvParser->parse(file_get_contents(__DIR__.'/../../.env')) as $dotEnvEntry) {
    $developmentEnvironment[$dotEnvEntry->getName()] = $dotEnvEntry->getValue()->get()->getChars();
}

$rootUser = 'root';
$rootPassword = $developmentEnvironment['MYSQL_ROOT_PASSWORD'] ?? throw new UnexpectedValueException(
    'Could not get the root password'
);

/**
 * Set APP_ENV in testing mode to get the correct name of the testing
 */
putenv('APP_ENV=testing');

/**
 * Initialize the application
 */
$app = require_once __DIR__.'/../../bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

/**
 * Check if the database exists.
 * If not, create a new one.
 */
$config = $app[Config::class];
$connectionName = $config['database']['default'];

if ($connectionName !== 'mysql') {
    throw new InvalidArgumentException(sprintf(
        'Invalid database connection, mysql expected, \'%s\' provided',
        $connectionName,
    ));
}

[
    'host' => $host,
    'username' => $user,
    'password' => $password,
    'database' => $database,
] = $config['database']['connections'][$connectionName];

// Log in as root
$creationConnection = new PDO(
    sprintf('mysql:host=%s', $host),
    $rootUser,
    $rootPassword,
);

// Create the database, or delete it and create it new if it already exists.
forceDatabaseCreation($creationConnection, $database);
echo sprintf('database %s created successfully'."\n", $database);

// Create the user with the correct credentials in this database, or delete it and create it new if it already exists.
// We need to be able to connect from the outside of the Docker container, so we set the host as %
forceUserCreation($creationConnection, '%', $database, $user, $password);
echo sprintf('user %s created successfully'."\n", $user);

// Attempt to connect with the needed user credentials
$checkingCreationConnection = new PDO(
    sprintf('mysql:host=%s;dbname=%s', $host, $database),
    $user,
    $password,
);

die ('Connection successful!'."\n");

function createDatabase(PDO $connection, string $database): void
{
    $connection->exec("
        CREATE DATABASE `$database`;
    ");
}

function dropDatabase(PDO $connection, string $database): void
{
    $connection->exec("
        DROP DATABASE `$database`;
    ");
}

function forceDatabaseCreation(PDO $connection, string $database): void
{
    try {
        createDatabase($connection, $database);
    }
    catch (PDOException $databaseCreationException) {
        // SQL 1007: Can't create database, already exists
        if (($databaseCreationException->errorInfo[1] ?? null) !== 1007) {
            throw $databaseCreationException;
        }

        dropDatabase($connection, $database);
        createDatabase($connection, $database);
    }
}

function createUser(PDO $connection, string $host, string $database, string $user, string $password): void
{
    $connection->exec("
        CREATE USER `$user`@`$host` IDENTIFIED BY '$password';
        GRANT ALL PRIVILEGES ON `$database`.* TO `$user`@`$host`;
        FLUSH PRIVILEGES;
    ");
}

function dropUser(PDO $connection, string $host, string $user): void
{
    $connection->exec("
        DROP USER `$user`@`$host`;
    ");
}

function forceUserCreation(PDO $connection, string $host, string $database, string $user, string $password): void
{
    try {
        createUser($connection, $host, $database, $user, $password);
    }
    catch (PDOException $userCreationException) {
        // SQL 1396: operation CREATE USER failed (probably because it already exists)
        if (($userCreationException->errorInfo[1] ?? null) !== 1396) {
            throw $userCreationException;
        }

        dropUser($connection, $host, $user);
        createUser($connection, $host, $database, $user, $password);
    }
}
