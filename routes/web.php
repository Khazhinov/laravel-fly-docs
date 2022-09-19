<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Khazhinov\LaravelFlyDocs\Services\ConfigFactory;

Route::group([], static function () {
    $configFactory = ConfigFactory::getInstance();
    $config = $configFactory->getConfig();

    Route::get("/", [
        'uses' => 'FlyDocsUIController@getDefaultDocumentation',
        'as' => 'default_documentation',
    ]);

    foreach (array_keys($config->documentations) as $documentation) {
        /** @var string $documentation */
        $documentation_config = $configFactory->documentationConfig($documentation);

        Route::group([
            "prefix" => "/{$documentation}",
            "as" => "{$documentation}.",
        ], static function () use ($documentation_config) {
            if ($documentation_config->routes->api_docs !== '') {
                Route::get($documentation_config->routes->api_docs, [
                    'uses' => 'FlyDocsUIController@getApiDocs',
                    'as' => 'api_docs',
                ]);

                if ($documentation_config->routes->docs_ui !== '') {
                    Route::get("{$documentation_config->routes->docs_ui}/asset/{asset}", [
                        'uses' => 'FlyDocsUIController@getAsset',
                        'as' => 'asset',
                    ]);

                    Route::get("/{$documentation_config->routes->docs_ui}", [
                        'uses' => 'FlyDocsUIController@showDocsUI',
                        'as' => 'docs_ui',
                    ]);
                }

                if ($documentation_config->routes->oauth2_callback !== '') {
                    Route::get($documentation_config->routes->oauth2_callback, [
                        'uses' => 'FlyDocsUIController@oauth2Callback',
                        'as' => 'oauth2_callback',
                    ]);
                }
            }
        });
    }
});
