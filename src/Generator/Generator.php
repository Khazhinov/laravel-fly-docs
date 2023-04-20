<?php

namespace Khazhinov\LaravelFlyDocs\Generator;

use GoldSpecDigital\ObjectOrientedOAS\OpenApi;
use Khazhinov\LaravelFlyDocs\Generator\Builders\ComponentsBuilder;
use Khazhinov\LaravelFlyDocs\Generator\Builders\InfoBuilder;
use Khazhinov\LaravelFlyDocs\Generator\Builders\PathsBuilder;
use Khazhinov\LaravelFlyDocs\Generator\Builders\SecuritySchemesContainerContract;
use Khazhinov\LaravelFlyDocs\Generator\Builders\ServersBuilder;
use Khazhinov\LaravelFlyDocs\Generator\Builders\TagsBuilder;
use ReflectionException;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

class Generator
{
    public function __construct(
        protected InfoBuilder $infoBuilder,
        protected ServersBuilder $serversBuilder,
        protected TagsBuilder $tagsBuilder,
        protected PathsBuilder $pathsBuilder,
        protected ComponentsBuilder $componentsBuilder
    ) {
    }

    /**
     * @throws UnknownProperties
     * @throws ReflectionException
     */
    public function generate(string $documentation): OpenApi
    {
        $config_factory = \Khazhinov\LaravelFlyDocs\Services\ConfigFactory::getInstance();
        $documentation_config = $config_factory->documentationConfig($documentation);

        $info = $this->infoBuilder->build($documentation_config);
        $servers = $this->serversBuilder->build($documentation_config);
        $tags = $this->tagsBuilder->build($documentation_config);
        $paths = $this->pathsBuilder->build($config_factory->getConfig(), $documentation);
        $components = $this->componentsBuilder->build($config_factory->getConfig(), $documentation);

        $security_schemes = [];
        foreach ($documentation_config->security_definitions->security_schemes as $security_scheme_container_class) {
            if (! is_a($security_scheme_container_class, Builders\Components\SecuritySchemesContainerContract::class, true)) {
                throw new SecuritySchemeIncorrectContainerException();
            }

            $security_schemes += $security_scheme_container_class::getSecuritySchemes();
        }

        $openApi = OpenApi::create()
            ->openapi(OpenApi::OPENAPI_3_0_2)
            ->info($info)
            ->servers(...$servers)
            ->tags(...$tags)
            ->paths(...$paths)
            ->components($components)
            ->security(...$security_schemes)
        ;

        foreach ($documentation_config->info->extensions as $key => $value) {
            $openApi = $openApi->x($key, $value);
        }

        return $openApi;
    }
}
