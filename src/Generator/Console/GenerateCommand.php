<?php

declare(strict_types = 1);

namespace Khazhinov\LaravelFlyDocs\Generator\Console;

use Illuminate\Console\Command;
use Khazhinov\LaravelFlyDocs\Generator\Generator;

/**
 * @template TKey of array-key
 * @template TValue
 */
class GenerateCommand extends Command
{
    protected $signature = 'fly-docs:generate {collection=default}';
    protected $description = 'Generate OpenAPI specification';

    public function handle(Generator $generator): void
    {
        /** @var array<mixed> $collections */
        $collections = config('openapi.collections');

        /** @var string $collection */
        $collection = $this->argument('collection');
        $collectionExists = collect($collections)->has($collection);

        if (! $collectionExists) {
            $this->error(sprintf('Collection "%s" does not exist.', $collection));

            return;
        }

        $this->line(
            $generator
                ->generate($collection)
                ->toJson(JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
        );
    }
}
