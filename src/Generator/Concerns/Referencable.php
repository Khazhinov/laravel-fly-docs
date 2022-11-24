<?php

namespace Khazhinov\LaravelFlyDocs\Generator\Concerns;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;
use InvalidArgumentException;
use Khazhinov\LaravelFlyDocs\Generator\Contracts\Reusable;
use Khazhinov\LaravelFlyDocs\Generator\Factories\CallbackFactory;
use Khazhinov\LaravelFlyDocs\Generator\Factories\ParametersFactory;
use Khazhinov\LaravelFlyDocs\Generator\Factories\RequestBodyFactory;
use Khazhinov\LaravelFlyDocs\Generator\Factories\ResponseFactory;
use Khazhinov\LaravelFlyDocs\Generator\Factories\SchemaFactory;
use Khazhinov\LaravelFlyDocs\Generator\Factories\SecuritySchemeFactory;

trait Referencable
{
    public static function ref(?string $objectId = null): Schema
    {
        /** @var CallbackFactory|ParametersFactory|RequestBodyFactory|ResponseFactory|SchemaFactory|SecuritySchemeFactory $instance */
        $instance = app(static::class);

        if (! $instance instanceof Reusable) {
            throw new InvalidArgumentException('"'.static::class.'" must implement "'.Reusable::class.'" in order to be referencable.');
        }

        $baseRef = null;

        if ($instance instanceof CallbackFactory) {
            $baseRef = '#/components/callbacks/';
        } elseif ($instance instanceof ParametersFactory) {
            $baseRef = '#/components/parameters/';
        } elseif ($instance instanceof RequestBodyFactory) {
            $baseRef = '#/components/requestBodies/';
        } elseif ($instance instanceof ResponseFactory) {
            $baseRef = '#/components/responses/';
        } elseif ($instance instanceof SchemaFactory) {
            $baseRef = '#/components/schemas/';
        } elseif ($instance instanceof SecuritySchemeFactory) {
            $baseRef = '#/components/securitySchemes/';
        }

        /** @phpstan-ignore-next-line */
        return Schema::ref($baseRef.$instance->build()->objectId, $objectId);
    }
}
