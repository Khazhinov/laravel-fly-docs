<?php

namespace Khazhinov\LaravelFlyDocs\Generator\Builders;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Components;
use Khazhinov\LaravelFlyDocs\Generator\Builders\Components\CallbacksBuilder;
use Khazhinov\LaravelFlyDocs\Generator\Builders\Components\RequestBodiesBuilder;
use Khazhinov\LaravelFlyDocs\Generator\Builders\Components\ResponsesBuilder;
use Khazhinov\LaravelFlyDocs\Generator\Builders\Components\SchemasBuilder;
use Khazhinov\LaravelFlyDocs\Generator\Builders\Components\SecuritySchemesBuilder;
use Khazhinov\LaravelFlyDocs\Services\DTO\Config\FlyDocsConfigDTO;

class ComponentsBuilder
{
    protected CallbacksBuilder $callbacksBuilder;
    protected RequestBodiesBuilder $requestBodiesBuilder;
    protected ResponsesBuilder $responsesBuilder;
    protected SchemasBuilder $schemasBuilder;
    protected SecuritySchemesBuilder $securitySchemesBuilder;

    public function __construct(
        CallbacksBuilder $callbacksBuilder,
        RequestBodiesBuilder $requestBodiesBuilder,
        ResponsesBuilder $responsesBuilder,
        SchemasBuilder $schemasBuilder,
        SecuritySchemesBuilder $securitySchemesBuilder
    ) {
        $this->callbacksBuilder = $callbacksBuilder;
        $this->requestBodiesBuilder = $requestBodiesBuilder;
        $this->responsesBuilder = $responsesBuilder;
        $this->schemasBuilder = $schemasBuilder;
        $this->securitySchemesBuilder = $securitySchemesBuilder;
    }

    public function build(FlyDocsConfigDTO $config, string $documentation): ?Components
    {
        /** @var \GoldSpecDigital\ObjectOrientedOAS\Objects\PathItem[] $callbacks */
        $callbacks = $this->callbacksBuilder->build($config, $documentation);
        /** @var \GoldSpecDigital\ObjectOrientedOAS\Objects\RequestBody[] $requestBodies */
        $requestBodies = $this->requestBodiesBuilder->build($config, $documentation);
        /** @var \GoldSpecDigital\ObjectOrientedOAS\Objects\Response[] $responses */
        $responses = $this->responsesBuilder->build($config, $documentation);
        /** @var \GoldSpecDigital\ObjectOrientedOAS\Contracts\SchemaContract[] $schemas */
        $schemas = $this->schemasBuilder->build($config, $documentation);
        /** @var \GoldSpecDigital\ObjectOrientedOAS\Objects\SecurityScheme[] $securitySchemes */
        $securitySchemes = $this->securitySchemesBuilder->build($config, $documentation);

        $components = Components::create();

        $hasAnyObjects = false;

        if (count($callbacks) > 0) {
            $hasAnyObjects = true;

            $components = $components->callbacks(...$callbacks);
        }

        if (count($requestBodies) > 0) {
            $hasAnyObjects = true;

            $components = $components->requestBodies(...$requestBodies);
        }

        if (count($responses) > 0) {
            $hasAnyObjects = true;
            $components = $components->responses(...$responses);
        }

        if (count($schemas) > 0) {
            $hasAnyObjects = true;
            $components = $components->schemas(...$schemas);
        }

        if (count($securitySchemes) > 0) {
            $hasAnyObjects = true;
            $components = $components->securitySchemes(...$securitySchemes);
        }

        if (! $hasAnyObjects) {
            return null;
        }

        return $components;
    }
}
