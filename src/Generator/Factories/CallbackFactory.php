<?php

namespace Khazhinov\LaravelFlyDocs\Generator\Factories;

use GoldSpecDigital\ObjectOrientedOAS\Objects\PathItem;

abstract class CallbackFactory
{
    abstract public function build(): PathItem;
}
