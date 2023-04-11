<?php declare(strict_types=1);

namespace olml89\XenforoBots\Reply\Infrastructure\Console;

use Illuminate\Console\Command;
use olml89\XenforoBots\Reply\Application\Publish\PublishReplyUseCase;
use olml89\XenforoBots\Reply\Domain\ReplyPublicationException;
use olml89\XenforoBots\Reply\Domain\ReplyStorageException;

final class PublishReplyCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reply:publish';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publishes the next publishable Reply';
    
    /**
     * @throws ReplyPublicationException | ReplyStorageException
     */
    public function handle(PublishReplyUseCase $publishReply): void
    {
        $publishReplyResult = $publishReply->send();

        $outputMessage = is_null($publishReplyResult)
            ? 'No replies to publish'
            : sprintf(
                'Reply <%s> published successfully to \'%s\'',
                $publishReplyResult->id,
                $publishReplyResult->bot->subscription->xenforo_url,
            );

        $this->output->success($outputMessage);

        if (!is_null($publishReplyResult)) {
            $this->output->write(json_encode($publishReplyResult, JSON_PRETTY_PRINT));
        }
    }
}
