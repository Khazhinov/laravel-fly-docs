<?php

namespace Khazhinov\LaravelFlyDocs\Generator\Contracts;

use GoldSpecDigital\ObjectOrientedOAS\Objects\PathItem;
use Khazhinov\LaravelFlyDocs\Generator\RouteInformation;

interface PathMiddleware
{
    public function before(RouteInformation $routeInformation): void;

    public function after(PathItem $pathItem): PathItem;
}
