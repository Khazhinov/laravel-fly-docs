<?php

namespace Khazhinov\LaravelFlyDocs\Generator\Factories;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Response;
use Khazhinov\LaravelFlyDocs\Generator\Concerns\Referencable;

abstract class ResponseFactory
{
    use Referencable;

    abstract public function build(): Response;
}
