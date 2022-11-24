<?php

namespace Khazhinov\LaravelFlyDocs\Generator;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;
use ReflectionNamedType;
use ReflectionType;

class SchemaHelpers
{
    public static function guessFromReflectionType(ReflectionType $reflectionType): Schema
    {
        /** @var ReflectionNamedType $reflectionType */
        return match ($reflectionType->getName()) {
            'int' => Schema::integer(),
            'bool' => Schema::boolean(),
            default => Schema::string(),
        };
    }
}
