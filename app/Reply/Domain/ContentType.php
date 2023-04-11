<?php declare(strict_types=1);

namespace olml89\XenforoBots\Reply\Domain;

enum ContentType: string
{
    case POST = 'post';
    case CONVERSATION_MESSAGE = 'conversation-message';
}

