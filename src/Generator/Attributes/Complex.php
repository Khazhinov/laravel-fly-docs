<?php

namespace Khazhinov\LaravelFlyDocs\Generator\Attributes;

use Attribute;
use InvalidArgumentException;
use Khazhinov\LaravelFlyDocs\Generator\Factories\ComplexFactory;

#[Attribute]
class Complex
{
    public string $factory;
    public array $arguments = [];

    public function __construct(string $factory, ...$arguments)
    {
        $this->factory = class_exists($factory) ? $factory : app()->getNamespace().'OpenApi\\Complexes\\'.$factory;

        if (! is_a($this->factory, ComplexFactory::class, true)) {
            throw new InvalidArgumentException('Factory class must be instance of ComplexFactory');
        }

        $this->arguments = $arguments;
    }
}
