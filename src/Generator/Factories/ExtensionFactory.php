<?php

namespace Khazhinov\LaravelFlyDocs\Generator\Factories;

abstract class ExtensionFactory
{
    abstract public function key(): string;

    /**
     * @return string|null|array<mixed>
     */
    abstract public function value(): string|array|null;
}
