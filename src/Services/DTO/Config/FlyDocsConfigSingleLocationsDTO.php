<?php

declare(strict_types=1);

namespace Khazhinov\LaravelFlyDocs\Services\DTO\Config;

use Illuminate\Support\Collection;
use Khazhinov\PhpSupport\DTO\DataTransferObject;
use RuntimeException;

class FlyDocsConfigSingleLocationsDTO extends DataTransferObject
{
    /**
     * @var array<string>
     */
    public array $callbacks = [];

    /**
     * @var array<string>
     */
    public array $request_bodies = [];

    /**
     * @var array<string>
     */
    public array $responses = [];

    /**
     * @var array<string>
     */
    public array $schemas = [];

    /**
     * @var array<string>
     */
    public array $security_schemes = [];

    /**
     * @var array<string>
     */
    public array $complexes = [];

    /**
     * @param  string  $type
     * @return array
     * @throws RuntimeException
     */
    public function getClearDirectoriesByType(string $type): array
    {
        if (! property_exists($this, $type)) {
            throw new RuntimeException(sprintf('Указанный тип директории (%s) для сущностей документации не найден', $type));
        }

        $directories = $this->$type;

        foreach ($directories as &$directory) {
            $directory = glob($directory, GLOB_ONLYDIR);
        }

        return (new Collection($directories))
            ->flatten()
            ->unique()
            ->toArray();
    }
}
