<?php

declare(strict_types=1);

namespace Khazhinov\LaravelFlyDocs\Services\DTO\Config;

use Khazhinov\LaravelLighty\DTO\DataTransferObject;

class FlyDocsConfigSingleInfoDTO extends DataTransferObject
{
    public string $title = 'FlyDocs Swagger UI';
    public string $description = 'Some description for api';
    public string $version = '1.0.0';

    /** @var array<string, mixed> */
    public array $extensions = [];

    public ?FlyDocsConfigSingleInfoContactDTO $contact = null;
    public ?FlyDocsConfigSingleInfoLicenseDTO $license = null;
}
