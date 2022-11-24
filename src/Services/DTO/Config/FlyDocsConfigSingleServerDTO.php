<?php

declare(strict_types = 1);

namespace Khazhinov\LaravelFlyDocs\Services\DTO\Config;

use Khazhinov\PhpSupport\DTO\DataTransferObject;
use Spatie\DataTransferObject\Attributes\CastWith;
use Spatie\DataTransferObject\Casters\ArrayCaster;

class FlyDocsConfigSingleServerDTO extends DataTransferObject
{
    public string $url = 'http://localhost:8000';
    public string $description = 'Development server';

    /**
     * @var array<string, FlyDocsConfigSingleServerVariableDTO>|null
     */
    #[CastWith(ArrayCaster::class, itemType: FlyDocsConfigSingleServerVariableDTO::class)]
    public ?array $variables = null;
}
