<?php

namespace Khazhinov\LaravelFlyDocs\Generator\Builders\Components;

use Khazhinov\LaravelFlyDocs\Generator\Contracts\Reusable;
use Khazhinov\LaravelFlyDocs\Generator\Factories\SchemaFactory;
use Khazhinov\LaravelFlyDocs\Services\DTO\Config\FlyDocsConfigDTO;

class SchemasBuilder extends Builder
{
    public function build(FlyDocsConfigDTO $config, string $documentation): array
    {
        return $this->getAllClasses('schemas', $config, $documentation)
            ->filter(static function ($class) {
                return
                    is_a($class, SchemaFactory::class, true) &&
                    is_a($class, Reusable::class, true);
            })
            ->map(static function ($class) {
                /** @var SchemaFactory $instance */
                $instance = app($class);

                return $instance->build();
            })
            ->values()
            ->toArray();
    }
}
