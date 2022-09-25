<?php

namespace Khazhinov\LaravelFlyDocs\Generator\Builders\Paths;

use GoldSpecDigital\ObjectOrientedOAS\Exceptions\InvalidArgumentException;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Operation;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Khazhinov\LaravelFlyDocs\Generator\Attributes\Operation as OperationAttribute;
use Khazhinov\LaravelFlyDocs\Generator\Builders\ExtensionsBuilder;
use Khazhinov\LaravelFlyDocs\Generator\Builders\Paths\Operation\CallbacksBuilder;
use Khazhinov\LaravelFlyDocs\Generator\Builders\Paths\Operation\ComplexBuilder;
use Khazhinov\LaravelFlyDocs\Generator\Builders\Paths\Operation\ParametersBuilder;
use Khazhinov\LaravelFlyDocs\Generator\Builders\Paths\Operation\RequestBodyBuilder;
use Khazhinov\LaravelFlyDocs\Generator\Builders\Paths\Operation\ResponsesBuilder;
use Khazhinov\LaravelFlyDocs\Generator\Builders\Paths\Operation\SecurityBuilder;
use Khazhinov\LaravelFlyDocs\Generator\Factories\ServerFactory;
use Khazhinov\LaravelFlyDocs\Generator\RouteInformation;

class OperationsBuilder
{
    public function __construct(
        protected CallbacksBuilder $callbacksBuilder,
        protected ParametersBuilder $parametersBuilder,
        protected RequestBodyBuilder $requestBodyBuilder,
        protected ResponsesBuilder $responsesBuilder,
        protected ExtensionsBuilder $extensionsBuilder,
        protected SecurityBuilder $securityBuilder,
        protected ComplexBuilder $complexBuilder,
    ) {
    }

    /**
     * @param  RouteInformation[]|Collection  $routes
     * @return array
     *
     * @throws InvalidArgumentException
     */
    public function build(array|Collection $routes): array
    {
        $operations = [];

        /** @var RouteInformation[] $routes */
        foreach ($routes as $route) {
            /** @var OperationAttribute|null $operationAttribute */
            $operationAttribute = $route->actionAttributes
                ->first(static fn (object $attribute) => $attribute instanceof OperationAttribute);

            $operationId = optional($operationAttribute)->id;
            $tags = $operationAttribute->tags ?? [];
            $servers = collect($operationAttribute->servers ?? [])
                ->filter(fn ($server) => app($server) instanceof ServerFactory)
                ->map(static fn ($server) => app($server)->build())
                ->toArray();

            $parameters = $this->parametersBuilder->build($route);
            $responses = $this->responsesBuilder->build($route);
            $callbacks = $this->callbacksBuilder->build($route);
            $security = $this->securityBuilder->build($route);
            $complexes = $this->complexBuilder->build($route);

            foreach ($complexes as $complex) {
                if ($complex->parameters) {
                    foreach ($complex->parameters as $parameter) {
                        $parameters[] = $parameter;
                    }
                }
                if ($complex->responses) {
                    foreach ($complex->responses as $response) {
                        $responses[] = $response;
                    }
                }
                if ($complex->callbacks) {
                    foreach ($complex->callbacks as $callback) {
                        $callbacks[] = $callback;
                    }
                }
                if ($complex->security) {
                    foreach ($complex->security as $security) {
                        $security[] = $security;
                    }
                }
            }

            $operation = Operation::create()
                ->action(Str::lower($operationAttribute->method) ?: $route->method)
                ->tags(...$tags)
                ->description($route->actionDocBlock->getDescription()->render() !== '' ? $route->actionDocBlock->getDescription()->render() : null)
                ->summary($route->actionDocBlock->getSummary() !== '' ? $route->actionDocBlock->getSummary() : null)
                ->operationId($operationId)
                ->parameters(...$parameters)
                ->responses(...$responses)
                ->callbacks(...$callbacks)
                ->servers(...$servers);

            if (! in_array($route->method, ['get', 'head'])) {
                $requestBody = $this->requestBodyBuilder->build($route);

                // Побеждает последнее тело запроса из комплекса
                // Подразумевается, что разработчик в здравом уме и твёрдой памяти
                // Иными словами - знает стандарт OpenApi
                foreach ($complexes as $complex) {
                    if ($complex->request_body) {
                        $requestBody = $complex->request_body;
                    }
                }

                $operation = $operation->requestBody($requestBody);
            }

            /** Not the cleanest code, we need to call notSecurity instead of security when our security has been turned off */
            if (count($security) === 1 && $security[0]->securityScheme === null) {
                $operation = $operation->noSecurity();
            } else {
                $operation = $operation->security(...$security);
            }

            $this->extensionsBuilder->build($operation, $route->actionAttributes);

            $operations[] = $operation;
        }

        return $operations;
    }
}
