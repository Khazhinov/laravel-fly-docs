<?php

namespace Khazhinov\LaravelFlyDocs\Generator\Builders\Paths\Operation;

use Khazhinov\LaravelFlyDocs\Generator\Attributes\Complex as ComplexAttribute;
use Khazhinov\LaravelFlyDocs\Generator\Factories\ComplexFactoryResult;
use Khazhinov\LaravelFlyDocs\Generator\RouteInformation;

class ComplexBuilder
{
    /**
     * @param  RouteInformation  $route
     * @return ComplexFactoryResult[]
     */
    public function build(RouteInformation $route): array
    {
        return $route->actionAttributes
            ->filter(static fn (object $attribute) => $attribute instanceof ComplexAttribute)
            ->map(static function (ComplexAttribute $attribute) {
                return app($attribute->factory)->build(...$attribute->arguments);
            })
            ->values()
            ->toArray();
    }
}
