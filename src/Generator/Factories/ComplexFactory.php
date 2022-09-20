<?php

namespace Khazhinov\LaravelFlyDocs\Generator\Factories;

abstract class ComplexFactory
{
    /**
     * @return ComplexFactoryResult
     */
    abstract public function build(...$arguments): ComplexFactoryResult;
}
