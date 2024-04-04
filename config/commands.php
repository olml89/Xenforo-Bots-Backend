<?php declare(strict_types=1);

use olml89\XenforoBotsBackend\Bot\Infrastructure\Console\SyncBotCommand;
use olml89\XenforoBotsBackend\Reply\Infrastructure\Console\PublishReplyCommand;
use olml89\XenforoBotsBackend\Bot\Infrastructure\Console\CancelBotSubscriptionCommand;
use olml89\XenforoBotsBackend\Bot\Infrastructure\Console\CreateBotCommand;
use olml89\XenforoBotsBackend\Bot\Infrastructure\Console\ShowBotSubscriptionCommand;
use olml89\XenforoBotsBackend\Bot\Infrastructure\Console\SubscribeBotCommand;
use olml89\XenforoBotsBackend\Bot\Infrastructure\Console\UpdateBotSubscriptionCommand;

return [
    CreateBotCommand::class,
    SyncBotCommand::class,
    SubscribeBotCommand::class,
    UpdateBotSubscriptionCommand::class,
    CancelBotSubscriptionCommand::class,
    ShowBotSubscriptionCommand::class,
    PublishReplyCommand::class,
];

