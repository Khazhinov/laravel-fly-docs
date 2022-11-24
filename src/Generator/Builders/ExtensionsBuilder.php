<?php

namespace Khazhinov\LaravelFlyDocs\Generator\Builders;

use GoldSpecDigital\ObjectOrientedOAS\Objects\BaseObject;
use Illuminate\Support\Collection;
use Khazhinov\LaravelFlyDocs\Generator\Attributes\Extension as ExtensionAttribute;
use Khazhinov\LaravelFlyDocs\Generator\Factories\ExtensionFactory;

/**
 * @template TKey of array-key
 * @template TValue
 */
class ExtensionsBuilder
{
    /**
     * @param  BaseObject  $object
     * @param  Collection<TKey, TValue>  $attributes
     * @return void
     */
    public function build(BaseObject $object, Collection $attributes): void
    {
        $attributes
            ->filter(static fn (object $attribute) => $attribute instanceof ExtensionAttribute)
            ->each(static function (ExtensionAttribute $attribute) use ($object): void {
                if ($attribute->factory) {
                    /** @var ExtensionFactory $factory */
                    $factory = app($attribute->factory);
                    $key = $factory->key();
                    $value = $factory->value();
                } else {
                    $key = $attribute->key;
                    $value = $attribute->value;
                }

                /** @var string $key */

                $object->x(
                    $key,
                    $value
                );
            });
    }
}
