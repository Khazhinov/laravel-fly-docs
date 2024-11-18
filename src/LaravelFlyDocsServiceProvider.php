<?php

declare(strict_types = 1);

namespace Khazhinov\LaravelFlyDocs;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Khazhinov\LaravelFlyDocs\Generator\Builders\ComponentsBuilder;
use Khazhinov\LaravelFlyDocs\Generator\Builders\InfoBuilder;
use Khazhinov\LaravelFlyDocs\Generator\Builders\PathsBuilder;
use Khazhinov\LaravelFlyDocs\Generator\Builders\ServersBuilder;
use Khazhinov\LaravelFlyDocs\Generator\Builders\TagsBuilder;
use Khazhinov\LaravelFlyDocs\Generator\Console\CallbackFactoryMakeCommand;
use Khazhinov\LaravelFlyDocs\Generator\Console\ComplexFactoryMakeCommand;
use Khazhinov\LaravelFlyDocs\Generator\Console\ExtensionFactoryMakeCommand;
use Khazhinov\LaravelFlyDocs\Generator\Console\GenerateCommand;
use Khazhinov\LaravelFlyDocs\Generator\Console\ParametersFactoryMakeCommand;
use Khazhinov\LaravelFlyDocs\Generator\Console\RequestBodyFactoryMakeCommand;
use Khazhinov\LaravelFlyDocs\Generator\Console\ResponseFactoryMakeCommand;
use Khazhinov\LaravelFlyDocs\Generator\Console\SchemaFactoryMakeCommand;
use Khazhinov\LaravelFlyDocs\Generator\Console\SecuritySchemeFactoryMakeCommand;
use Khazhinov\LaravelFlyDocs\Generator\Generator;

class LaravelFlyDocsServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->registerRoutes();
        $this->registerPublishing();
        $this->registerResources();
    }

    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/fly-docs.php', 'fly-docs');

        $this->registerBinds();
        $this->registerCommands();
    }

    protected function registerBinds(): void
    {
        $this->app->singleton(Generator::class, static function (Application $app) {
            return new Generator(
                $app->make(InfoBuilder::class), // @phpstan-ignore-line
                $app->make(ServersBuilder::class), // @phpstan-ignore-line
                $app->make(TagsBuilder::class), // @phpstan-ignore-line
                $app->make(PathsBuilder::class), // @phpstan-ignore-line
                $app->make(ComponentsBuilder::class) // @phpstan-ignore-line
            );
        });
    }

    protected function registerResources(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'fly-docs');
    }

    protected function registerPublishing(): void
    {
        $this->publishes([
            __DIR__.'/../config/fly-docs.php' => config_path('fly-docs.php'),
        ], 'config');

        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/fly-docs'),
        ], 'views');
    }

    protected function registerRoutes(): void
    {
        Route::group([
            'as' => 'fly-docs.',
            'prefix' => config('fly-docs.path', 'fly-docs'),
            'namespace' => 'Khazhinov\LaravelFlyDocs\Http\Controllers',
        ], function () {
            $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        });
    }

    protected function registerCommands(): void
    {
        $this->app->bind('command.fly-docs:make-callback', CallbackFactoryMakeCommand::class);
        $this->app->bind('command.fly-docs:make-extension', ExtensionFactoryMakeCommand::class);
        $this->app->bind('command.fly-docs:generate', GenerateCommand::class);
        $this->app->bind('command.fly-docs:make-parameters', ParametersFactoryMakeCommand::class);
        $this->app->bind('command.fly-docs:make-requestbody', RequestBodyFactoryMakeCommand::class);
        $this->app->bind('command.fly-docs:make-response', ResponseFactoryMakeCommand::class);
        $this->app->bind('command.fly-docs:make-schema', SchemaFactoryMakeCommand::class);
        $this->app->bind('command.fly-docs:make-security-scheme', SecuritySchemeFactoryMakeCommand::class);
        $this->app->bind('command.fly-docs:make-complex', ComplexFactoryMakeCommand::class);

        $this->commands([
            'command.fly-docs:make-callback',
            'command.fly-docs:make-extension',
            'command.fly-docs:generate',
            'command.fly-docs:make-parameters',
            'command.fly-docs:make-requestbody',
            'command.fly-docs:make-response',
            'command.fly-docs:make-schema',
            'command.fly-docs:make-security-scheme',
            'command.fly-docs:make-complex',
        ]);
    }
}
