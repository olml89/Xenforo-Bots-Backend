<?php

namespace olml89\XenforoBotsBackend\Common\Infrastructure\Doctrine\DBAL\Types;

interface CustomType
{
    public static function getTypeName() : string;
}
