<?php

declare(strict_types = 1);

namespace Khazhinov\LaravelFlyDocs\Services\DTO\Config;

use Khazhinov\PhpSupport\DTO\DataTransferObject;
use Spatie\DataTransferObject\Attributes\CastWith;
use Spatie\DataTransferObject\Casters\ArrayCaster;

class FlyDocsConfigDTO extends DataTransferObject
{
    /**
     * Данный параметр контролирует, какую документацию открывать по умолчанию
     *
     * @var string
     */
    public string $default = 'latest';

    /**
     * Массив с доступными конфигурациями
     *
     * @var array<string, FlyDocsConfigSingleDTO>
     */
    #[CastWith(ArrayCaster::class, itemType: FlyDocsConfigSingleDTO::class)]
    public array $documentations = [];

    /**
     * Тело документации по-умолчанию.
     *
     * @var FlyDocsConfigSingleDTO
     */
    public FlyDocsConfigSingleDTO $default_documentation_body;
}
