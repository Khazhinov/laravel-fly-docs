<?php

namespace Khazhinov\LaravelFlyDocs\Generator\Builders\Paths\Operation;

use GoldSpecDigital\ObjectOrientedOAS\Objects\PathItem;
use Illuminate\Support\Collection;
use Khazhinov\LaravelFlyDocs\Generator\Attributes\Callback as CallbackAttribute;
use Khazhinov\LaravelFlyDocs\Generator\Contracts\Reusable;
use Khazhinov\LaravelFlyDocs\Generator\RouteInformation;

class CallbacksBuilder
{
    /**
     * @param  RouteInformation  $route
     * @return array<mixed>
     */
    public function build(RouteInformation $route): array
    {
        /** @var Collection $action_attributes */
        $action_attributes = $route->actionAttributes;

        return $action_attributes
            ->filter(static fn (object $attribute) => $attribute instanceof CallbackAttribute)
            ->map(static function (CallbackAttribute $attribute) {
                $factory = app($attribute->factory);
                $pathItem = $factory->build();

                if ($factory instanceof Reusable) {
                    return PathItem::ref('#/components/callbacks/'.$pathItem->objectId);
                }

                return $pathItem;
            })
            ->values()
            ->toArray();
    }
}
