<?php declare(strict_types=1);

namespace olml89\XenforoBots\Answer\Infrastructure\Console;

use Illuminate\Console\Command;
use olml89\XenforoBots\Answer\Application\Send\SendAnswerUseCase;
use olml89\XenforoBots\Answer\Domain\AnswerPublicationException;
use olml89\XenforoBots\Answer\Domain\AnswerStorageException;

final class SendAnswerCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'answer:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends the next Answer ready to be delivered';


    /**
     * @throws AnswerPublicationException | AnswerStorageException
     */
    public function handle(SendAnswerUseCase $sendAnswer): void
    {
        $sendAnswerResult = $sendAnswer->send();

        $outputMessage = is_null($sendAnswerResult)
            ? 'No answers to send'
            : sprintf(
                'Answer <%s> published successfully to \'%s\'',
                $sendAnswerResult->id,
                $sendAnswerResult->bot->subscription->xenforo_url,
            );

        $this->output->success($outputMessage);

        if (!is_null($sendAnswerResult)) {
            $this->output->write(json_encode($sendAnswerResult, JSON_PRETTY_PRINT));
        }
    }
}
