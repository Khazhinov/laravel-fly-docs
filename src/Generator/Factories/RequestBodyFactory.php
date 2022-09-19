<?php

namespace Khazhinov\LaravelFlyDocs\Generator\Factories;

use GoldSpecDigital\ObjectOrientedOAS\Objects\RequestBody;
use Khazhinov\LaravelFlyDocs\Generator\Concerns\Referencable;

abstract class RequestBodyFactory
{
    use Referencable;

    abstract public function build(): RequestBody;
}
