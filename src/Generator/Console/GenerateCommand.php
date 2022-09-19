<?php

declare(strict_types=1);

namespace Khazhinov\LaravelFlyDocs\Generator\Console;

use Illuminate\Console\Command;
use Khazhinov\LaravelFlyDocs\Generator\Generator;

class GenerateCommand extends Command
{
    protected $signature = 'fly-docs:generate {collection=default}';
    protected $description = 'Generate OpenAPI specification';

    public function handle(Generator $generator): void
    {
        $collectionExists = collect(config('openapi.collections'))->has($this->argument('collection'));

        if (! $collectionExists) {
            $this->error('Collection "'.$this->argument('collection').'" does not exist.');

            return;
        }

        $this->line(
            $generator
                ->generate($this->argument('collection'))
                ->toJson(JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
        );
    }
}
