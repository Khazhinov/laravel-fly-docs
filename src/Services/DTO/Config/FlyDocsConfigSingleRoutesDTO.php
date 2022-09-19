<?php

declare(strict_types=1);

namespace Khazhinov\LaravelFlyDocs\Services\DTO\Config;

use Khazhinov\LaravelLighty\DTO\DataTransferObject;

class FlyDocsConfigSingleRoutesDTO extends DataTransferObject
{
    /**
     * Ссылка для открытия UI для данной документации
     *
     * @var string
     */
    public string $docs_ui = '/fly-ui';

    /**
     * Ссылка для получения JSON файла OpenApi 3.0 для данной документации
     *
     * @var string
     */
    public string $api_docs = '/api-docs';

    /**
     * Callback OAuth 2.0, в случае его наличия
     *
     * @var string
     */
    public string $oauth2_callback = '/api/oauth2-callback';

    /**
     * Массив с настройками группы маршрутов для данной документации
     *
     * @var array<string, mixed>
     */
    public array $group_options = [];
}
