<?php

declare(strict_types=1);

namespace Khazhinov\LaravelFlyDocs\Services\DTO\Config;

use Khazhinov\LaravelLighty\DTO\DataTransferObject;
use Khazhinov\LaravelLighty\DTO\Validation\ArrayOfScalar;
use Khazhinov\LaravelLighty\Enums\ScalarTypeEnum;

class FlyDocsConfigSinglePathsDTO extends DataTransferObject
{
    /**
     * Абсолютные пути до папок, в которых требуется искать атрибуты OpenApi
     *
     * @var array<string>
     */
    #[ArrayOfScalar(ScalarTypeEnum::String)]
    public array $route_name_start_with = [];

    /**
     * Путь до папки с View для рендеринга страницы документации
     *
     * @var string
     */
    public string $views_folder = 'resources/views/vendor/fly-docs';

    /**
     * Путь до папки с дистрибутивом Swagger UI
     *
     * @var string
     */
    public string $swagger_ui_assets_path = 'vendor/swagger-api/swagger-ui/dist/';
}
