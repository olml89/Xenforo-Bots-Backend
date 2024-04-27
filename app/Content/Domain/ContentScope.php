<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Content\Domain;

enum ContentScope: string
{
    case public = 'public';
    case private = 'private';
}
