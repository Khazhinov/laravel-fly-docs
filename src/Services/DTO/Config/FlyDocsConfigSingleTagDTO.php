<?php

declare(strict_types=1);

namespace Khazhinov\LaravelFlyDocs\Services\DTO\Config;

use Khazhinov\LaravelLighty\DTO\DataTransferObject;

class FlyDocsConfigSingleTagDTO extends DataTransferObject
{
    public string $name = 'TagName';
    public string $description = 'Tag description';
}
