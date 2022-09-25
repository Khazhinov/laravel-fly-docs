<?php

declare(strict_types=1);

namespace Khazhinov\LaravelFlyDocs\Services\DTO\Config;

use Khazhinov\PhpSupport\DTO\DataTransferObject;

class FlyDocsConfigSingleInfoContactDTO extends DataTransferObject
{
    public string $name = 'Vladislav Khazhinov';
    public string $email = 'khazhinov@gmail.com';
    public string $url = 'https://github.com/khazhinov';
}
