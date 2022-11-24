<?php

namespace Khazhinov\LaravelFlyDocs\Generator\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD)]
class Collection
{
    /** @var string|array<string> */
    public array|string $name;

    /**
     * @param  string|array<mixed>  $name
     */
    public function __construct(string|array $name = 'default')
    {
        $this->name = $name;
    }
}
