<?php

namespace Khazhinov\LaravelFlyDocs\Generator\Attributes;

use Attribute;
use InvalidArgumentException;
use Khazhinov\LaravelFlyDocs\Generator\Factories\CallbackFactory;

#[Attribute]
class Callback
{
    public string $factory;

    public function __construct(string $factory)
    {
        $this->factory = class_exists($factory) ? $factory : app()->getNamespace().'OpenApi\\Callbacks\\'.$factory;

        if (! is_a($this->factory, CallbackFactory::class, true)) {
            throw new InvalidArgumentException('Factory class must be instance of CallbackFactory');
        }
    }
}
