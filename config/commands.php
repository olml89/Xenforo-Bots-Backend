<?php declare(strict_types=1);

use olml89\XenforoBots\Bot\Infrastructure\Console\CancelBotSubscriptionCommand;
use olml89\XenforoBots\Bot\Infrastructure\Console\CreateBotCommand;
use olml89\XenforoBots\Bot\Infrastructure\Console\SubscribeBotCommand;
use olml89\XenforoBots\Bot\Infrastructure\Console\UpdateBotSubscriptionCommand;

return [
    CreateBotCommand::class,
    SubscribeBotCommand::class,
    UpdateBotSubscriptionCommand::class,
    CancelBotSubscriptionCommand::class,
];

