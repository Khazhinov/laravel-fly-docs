<?php

namespace Khazhinov\LaravelFlyDocs\Generator\Factories;

use GoldSpecDigital\ObjectOrientedOAS\Objects\SecurityScheme;

abstract class SecuritySchemeFactory
{
    abstract public function build(): SecurityScheme;
}
