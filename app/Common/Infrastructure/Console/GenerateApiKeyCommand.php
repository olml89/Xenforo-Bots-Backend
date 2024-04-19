<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Common\Infrastructure\Console;

use Illuminate\Config\Repository;
use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\ApiKey\ApiKey;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\ApiKey\ApiKeyGenerator;

final class GenerateApiKeyCommand extends Command
{
    use ConfirmableTrait;

    private const string API_KEY_KEY = 'APP_API_KEY';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:api-key:generate
                    {--show : Display the key instead of modifying files}
                    {--force : Force the operation to run when in production}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set the application backend API key';

    public function handle(ApiKeyGenerator $apiKeyGenerator, Repository $config): void
    {
        $apiKey = $apiKeyGenerator->generate();

        if ($this->option('show')) {
            $this->line('<comment>'.$apiKey.'</comment>');
        }

        $currentApiKeyValue = $config->get('app.api_key');

        if (strlen($currentApiKeyValue) !== 0 && (!$this->confirmToProceed())) {
            return;
        }

        if (!$this->writeApiKeyToEnvFiles($apiKey, $currentApiKeyValue)) {
            return;
        }

        $this->components->info('Application backend API key set successfully.');
    }

    private function writeApiKeyToEnvFiles(ApiKey $apiKey, string $currentApiKeyValue): bool
    {
        $environmentFilePath = $this->laravel->environmentFilePath();

        $replaced = preg_replace(
            $this->apiKeyReplacementPattern($currentApiKeyValue),
            self::API_KEY_KEY.'='.$apiKey,
            $input = file_get_contents($environmentFilePath)
        );

        if ($replaced === $input || $replaced === null) {
            $this->error(
                sprintf(
                    'Unable to set application key. No %s variable was found in the %s file.',
                    self::API_KEY_KEY,
                    $environmentFilePath,
                )
            );

            return false;
        }

        file_put_contents($this->laravel->environmentFilePath(), $replaced);

        return true;
    }

    private function apiKeyReplacementPattern(string $currentApiKeyValue): string
    {
        $escaped = preg_quote(
            str: '='.$currentApiKeyValue,
            delimiter: '/'
        );

        return sprintf(
            '/^%s%s/m',
            self::API_KEY_KEY,
            $escaped,
        );
    }
}
