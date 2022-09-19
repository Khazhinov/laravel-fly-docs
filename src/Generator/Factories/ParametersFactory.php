<?php

namespace Khazhinov\LaravelFlyDocs\Generator\Factories;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Parameter;
use Khazhinov\LaravelFlyDocs\Generator\Concerns\Referencable;

abstract class ParametersFactory
{
    use Referencable;

    /**
     * @return Parameter[]
     */
    abstract public function build(): array;
}
