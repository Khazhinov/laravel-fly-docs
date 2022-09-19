<?php

declare(strict_types=1);

namespace Khazhinov\LaravelFlyDocs\Services\DTO\Config;

use Khazhinov\LaravelLighty\DTO\DataTransferObject;

class FlyDocsConfigSingleServerVariableDTO extends DataTransferObject
{
    /**
     * @var array<string>|null
     */
    public ?array $enum = null;
    public string $default;
    public string $description = 'Variable description';
}
