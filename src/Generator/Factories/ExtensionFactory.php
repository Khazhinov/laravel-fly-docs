<?php

namespace Khazhinov\LaravelFlyDocs\Generator\Factories;

abstract class ExtensionFactory
{
    abstract public function key(): string;

    /**
     * @return string|null|array
     */
    abstract public function value();
}
