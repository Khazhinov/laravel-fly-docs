<?php

declare(strict_types=1);

namespace Khazhinov\LaravelFlyDocs\Services\DTO\Config;

use Khazhinov\PhpSupport\DTO\DataTransferObject;
use Spatie\DataTransferObject\Attributes\CastWith;
use Spatie\DataTransferObject\Casters\ArrayCaster;

class FlyDocsConfigSingleDTO extends DataTransferObject
{
    public FlyDocsConfigSingleInfoDTO $info;

    /**
     * @var FlyDocsConfigSingleServerDTO[]
     */
    #[CastWith(ArrayCaster::class, itemType: FlyDocsConfigSingleServerDTO::class)]
    public array $servers = [];

    /**
     * @var FlyDocsConfigSingleTagDTO[]
     */
    #[CastWith(ArrayCaster::class, itemType: FlyDocsConfigSingleTagDTO::class)]
    public array $tags = [];

    public FlyDocsConfigSingleRoutesDTO $routes;

    public FlyDocsConfigSinglePathsDTO $paths;

    public FlyDocsConfigSingleSecurityDefinitionDTO $security_definitions;

    public FlyDocsConfigSingleUIDTO $ui;

    public FlyDocsConfigSingleLocationsDTO $locations;
}
