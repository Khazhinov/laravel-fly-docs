<?php

declare(strict_types = 1);

namespace Khazhinov\LaravelFlyDocs\Services\DTO\Config;

use Khazhinov\PhpSupport\DTO\DataTransferObject;

class FlyDocsConfigSingleInfoLicenseDTO extends DataTransferObject
{
    public string $name = 'MIT';
    public string $url = 'https://opensource.org/licenses/MIT';
}
