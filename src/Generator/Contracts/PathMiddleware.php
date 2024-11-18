<?php

namespace Khazhinov\LaravelFlyDocs\Generator\Contracts;

use GoldSpecDigital\ObjectOrientedOAS\Objects\PathItem;
use Khazhinov\LaravelFlyDocs\Generator\RouteInformation;

interface PathMiddleware
{
    public function before(RouteInformation $routeInformation): void; // @phpstan-ignore-line

    public function after(PathItem $pathItem): PathItem;
}
