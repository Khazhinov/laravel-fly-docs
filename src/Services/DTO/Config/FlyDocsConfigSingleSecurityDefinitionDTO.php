<?php

declare(strict_types=1);

namespace Khazhinov\LaravelFlyDocs\Services\DTO\Config;

use Khazhinov\LaravelLighty\DTO\DataTransferObject;

class FlyDocsConfigSingleSecurityDefinitionDTO extends DataTransferObject
{
    /**
     * @var array<string, mixed>
     */
    public array $security_schemes = [];
}
