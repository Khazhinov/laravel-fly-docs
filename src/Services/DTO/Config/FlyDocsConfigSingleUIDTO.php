<?php

declare(strict_types = 1);

namespace Khazhinov\LaravelFlyDocs\Services\DTO\Config;

use Khazhinov\PhpSupport\DTO\Custer\EnumCaster;
use Khazhinov\PhpSupport\DTO\DataTransferObject;
use Spatie\DataTransferObject\Attributes\CastWith;

/**
 * @see https://swagger.io/docs/open-source-tools/swagger-ui/usage/configuration/ Документация для конфигурации Swagger UI.
 *
 * @author Vladislav Khazhinov <khazhinov@gmail.com>
 */
class FlyDocsConfigSingleUIDTO extends DataTransferObject
{
    /**
     * Управляет настройкой расширения по умолчанию для операций и тегов.
     *
     * @var FlyDocsConfigSingleUIDisplayDocExpansionEnum
     */
    #[CastWith(EnumCaster::class, enumType: FlyDocsConfigSingleUIDisplayDocExpansionEnum::class)]
    public FlyDocsConfigSingleUIDisplayDocExpansionEnum $display_doc_expansion = FlyDocsConfigSingleUIDisplayDocExpansionEnum::None;

    /**
     * Если установлено, включает фильтрацию. На верхней панели появится поле редактирования,
     * которое можно использовать для фильтрации отображаемых операций с тегами.
     * Может быть логическим значением для включения или отключения или строкой,
     * и в этом случае фильтрация будет включена с использованием этой строки
     * в качестве выражения фильтра. Фильтрация чувствительна к регистру и соответствует
     * выражению фильтра в любом месте внутри тега.
     *
     * @var string|bool
     */
    public string|bool $display_filter = true;

    /**
     * Если установлено значение true, данные авторизации сохраняются и не теряются при закрытии/обновлении браузера.
     *
     * @var bool
     */
    public bool $persist_authorization = false;
}
