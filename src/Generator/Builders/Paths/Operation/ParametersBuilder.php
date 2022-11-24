<?php

namespace Khazhinov\LaravelFlyDocs\Generator\Builders\Paths\Operation;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Parameter;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Khazhinov\LaravelFlyDocs\Generator\Attributes\Parameters;
use Khazhinov\LaravelFlyDocs\Generator\Factories\ParametersFactory;
use Khazhinov\LaravelFlyDocs\Generator\RouteInformation;
use Khazhinov\LaravelFlyDocs\Generator\SchemaHelpers;
use phpDocumentor\Reflection\DocBlock;
use phpDocumentor\Reflection\DocBlock\Tags\Param;
use ReflectionParameter;

/**
 * @template TKey of array-key
 * @template TValue
 */
class ParametersBuilder
{
    /**
     * @param  RouteInformation  $route
     * @return array<mixed>
     */
    public function build(RouteInformation $route): array
    {
        $pathParameters = $this->buildPath($route);
        $attributedParameters = $this->buildAttribute($route);

        return $pathParameters->merge($attributedParameters)->toArray();
    }

    protected function buildPath(RouteInformation $route): Collection
    {
        return collect($route->parameters)
            ->map(static function (array $parameter) use ($route) {
                $schema = Schema::string();

                /** @var ReflectionParameter|null $reflectionParameter */
                $reflectionParameter = collect($route->actionParameters)
                    ->first(static fn (ReflectionParameter $reflectionParameter) => $reflectionParameter->name === $parameter['name']);

                if ($reflectionParameter) {
                    // The reflected param has no type, so ignore (should be defined in a ParametersFactory instead)
                    if ($reflectionParameter->getType() === null) {
                        return null;
                    }

                    $schema = SchemaHelpers::guessFromReflectionType($reflectionParameter->getType());
                }

                /** @var DocBlock $doc_block */
                $doc_block = $route->actionDocBlock;

                /** @var string $parameter_name */
                $parameter_name = $parameter['name'];

                /** @var (callable(TValue, TKey): bool) $first_function */
                $first_function = static function (Param $param) use ($parameter_name) {
                    /** @var string $_ */
                    $_ = $param->getVariableName();

                    return Str::snake($_) === Str::snake($parameter_name);
                };

                /** @var Param $description */
                $description = collect($doc_block->getTagsByName('param'))
                    ->first($first_function);

                return Parameter::path()->name($parameter['name'])
                    ->required()
                    ->description(optional(optional($description)->getDescription())->render())
                    ->schema($schema);
            })
            ->filter();
    }

    protected function buildAttribute(RouteInformation $route): Collection
    {
        /** @var Collection $action_attributes */
        $action_attributes = $route->actionAttributes;

        /** @var Parameters|null $parameters */
        $parameters = $action_attributes->first(static fn ($attribute) => $attribute instanceof Parameters, []);

        if ($parameters) {
            /** @var ParametersFactory $parametersFactory */
            $parametersFactory = app($parameters->factory);

            $parameters = $parametersFactory->build();
        }

        return collect($parameters);
    }
}
