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
    protected $signature = 'fly-docs:generate {documentation?}';
    protected $description = 'Generate OpenAPI specification';

    public function handle(Generator $generator): void
    {
        /** @var array<mixed> $documentations */
        $documentations = config('fly-docs.documentations');

        /** @var string $documentation */
        $documentation = $this->argument('documentation') ?? config('fly-docs.default');
        $documentationExists = collect($documentations)->has($documentation);

        if (! $documentationExists) {
            $this->error(sprintf('Documentation "%s" does not exist.', $documentation));

            return;
        }

        $this->line(
            $generator
                ->generate($documentation)
                ->toJson(JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
        );
    }
}
