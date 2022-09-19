<?php

namespace Khazhinov\LaravelFlyDocs\Generator\Contracts;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Components;

interface ComponentMiddleware
{
    public function after(Components $components): void;
}
