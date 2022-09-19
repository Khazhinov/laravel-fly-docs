<?php

declare(strict_types=1);

use Spatie\DataTransferObject\Exceptions\UnknownProperties;

if (! function_exists('fly_docs_swagger_ui_dist_path')) {
    /**
     * @param  string  $documentation
     * @param  null|string  $asset
     * @return string|bool
     *
     * @throws ReflectionException
     * @throws UnknownProperties
     */
    function fly_docs_swagger_ui_dist_path(string $documentation, ?string $asset = null): string|bool
    {
        $config = \Khazhinov\LaravelFlyDocs\Services\ConfigFactory::getInstance();
        $documentation_config = $config->documentationConfig($documentation);

        $path = base_path($documentation_config->paths->swagger_ui_assets_path);

        if (! $asset) {
            return realpath($path);
        }

        return realpath($path.$asset);
    }
}

if (! function_exists('fly_docs_swagger_asset')) {
    /**
     * @param  string  $documentation
     * @param $asset
     * @return string
     * @throws ReflectionException
     * @throws UnknownProperties
     */
    function fly_docs_swagger_asset(string $documentation, $asset): string
    {
        $file = fly_docs_swagger_ui_dist_path($documentation, $asset);

        if (! file_exists($file)) {
            throw new RuntimeException(sprintf('Требуемый asset (%s) не найден', $asset));
        }

        return route('fly-docs.'.$documentation.'.asset', ['asset' => $asset]).'?v='.md5_file($file);
    }
}
