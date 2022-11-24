<?php

declare(strict_types = 1);

namespace Khazhinov\LaravelFlyDocs\Services\DTO\Config;

/**
 * @see https://swagger.io/docs/open-source-tools/swagger-ui/usage/configuration/ Документация для конфигурации Swagger UI. Смотреть docExpansion в секции Display
 *
 * @author Vladislav Khazhinov <khazhinov@gmail.com>
 */
enum FlyDocsConfigSingleUIDisplayDocExpansionEnum: string
{
    case List = 'list';
    case Full = 'full';
    case None = 'none';
}
