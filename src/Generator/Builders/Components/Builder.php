<?php

namespace Khazhinov\LaravelFlyDocs\Generator\Builders\Components;

use Illuminate\Support\Collection;
use Khazhinov\LaravelFlyDocs\Generator\Attributes\Collection as CollectionAttribute;
use Khazhinov\LaravelFlyDocs\Generator\ClassMapGenerator;
use Khazhinov\LaravelFlyDocs\Services\DTO\Config\FlyDocsConfigDTO;
use ReflectionClass;

abstract class Builder
{
    protected function getAllClasses(string $needle_type, FlyDocsConfigDTO $config, string $documentation): Collection
    {
        return collect($config->documentations[$documentation]->locations->getClearDirectoriesByType($needle_type))
            ->map(function (string $directory) {
                $map = ClassMapGenerator::createMap($directory);

                return array_keys($map);
            })
            ->flatten()
            ->filter(function (string $class) use ($config, $documentation) {
                $reflectionClass = new ReflectionClass($class);
                $collectionAttributes = $reflectionClass->getAttributes(CollectionAttribute::class);

                if (count($collectionAttributes) === 0 && $documentation === $config->default) {
                    return true;
                }

                if (count($collectionAttributes) === 0) {
                    return false;
                }

                /** @var CollectionAttribute $collectionAttribute */
                $collectionAttribute = $collectionAttributes[0]->newInstance();

                return
                    $collectionAttribute->name === ['*'] ||
                    in_array($documentation, $collectionAttribute->name ?? [], true);
            });
    }
}
