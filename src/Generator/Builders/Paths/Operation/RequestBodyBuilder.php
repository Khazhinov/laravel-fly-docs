<?php

namespace Khazhinov\LaravelFlyDocs\Generator\Builders\Paths\Operation;

use GoldSpecDigital\ObjectOrientedOAS\Objects\RequestBody;
use Illuminate\Support\Collection;
use Khazhinov\LaravelFlyDocs\Generator\Attributes\RequestBody as RequestBodyAttribute;
use Khazhinov\LaravelFlyDocs\Generator\Contracts\Reusable;
use Khazhinov\LaravelFlyDocs\Generator\Factories\RequestBodyFactory;
use Khazhinov\LaravelFlyDocs\Generator\RouteInformation;

class RequestBodyBuilder
{
    public function build(RouteInformation $route): ?RequestBody
    {
        /** @var Collection $action_attributes */
        $action_attributes = $route->actionAttributes;

        /** @var RequestBodyAttribute|null $requestBody */
        $requestBody = $action_attributes->first(static fn (object $attribute) => $attribute instanceof RequestBodyAttribute);

        if ($requestBody) {
            /** @var RequestBodyFactory $requestBodyFactory */
            $requestBodyFactory = app($requestBody->factory);

            $requestBody = $requestBodyFactory->build();

            if ($requestBodyFactory instanceof Reusable) {
                return RequestBody::ref('#/components/requestBodies/'.$requestBody->objectId);
            }
        }

        return $requestBody;
    }
}
