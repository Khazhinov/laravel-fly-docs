<?php

namespace Khazhinov\LaravelFlyDocs\Generator\Console;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;

class ComplexFactoryMakeCommand extends GeneratorCommand
{
    protected $name = 'fly-docs:make-complex';
    protected $description = 'Create a new Complex factory class';
    protected $type = 'Complex';

    protected function getStub(): string
    {
        return __DIR__.'/stubs/complex.stub';
    }

    protected function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace.'\OpenApi\Complexes';
    }

    protected function qualifyClass($name): string
    {
        $name = parent::qualifyClass($name);

        if (Str::endsWith($name, 'Complex')) {
            return $name;
        }

        return $name.'Complex';
    }
}
