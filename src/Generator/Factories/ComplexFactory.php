<?php

namespace Khazhinov\LaravelFlyDocs\Generator\Factories;

abstract class ComplexFactory
{
    /**
     * @param  mixed  ...$arguments
     * @return ComplexFactoryResult
     */
    abstract public function build(...$arguments): ComplexFactoryResult;
}
