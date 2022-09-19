<?php

declare(strict_types=1);

namespace Khazhinov\LaravelFlyDocs\Services;

use Khazhinov\LaravelFlyDocs\Services\DTO\Config\FlyDocsConfigDTO;
use Khazhinov\LaravelFlyDocs\Services\DTO\Config\FlyDocsConfigSingleDTO;
use Khazhinov\LaravelLighty\Patterns\Singleton;
use ReflectionException;
use RuntimeException;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

/**
 * @method static ConfigFactory getInstance()
 * @author Vladislav Khazhinov <khazhinov@gmail.com>
 */
class ConfigFactory extends Singleton
{
    protected ?FlyDocsConfigDTO $config = null;

    /**
     * @return FlyDocsConfigDTO
     * @throws ReflectionException
     * @throws UnknownProperties
     */
    protected function generateConfig(): FlyDocsConfigDTO
    {
        $global_config = config('fly-docs');
        $default_documentation_body = helper_array_get($global_config, 'default_documentation_body', []);
        $documentations = helper_array_get($global_config, 'documentations', []);
        foreach ($documentations as $documentation_name => $documentation_body) {
            $global_config['documentations'][$documentation_name] = $this->mergeConfig($default_documentation_body, $documentation_body);
        }

        return new FlyDocsConfigDTO($global_config);
    }

    /**
     * @return FlyDocsConfigDTO
     * @throws ReflectionException
     * @throws UnknownProperties
     */
    public function getConfig(): FlyDocsConfigDTO
    {
        if (is_null($this->config)) {
            $this->config = $this->generateConfig();
        }

        return $this->config;
    }

    /**
     * Get documentation config.
     *
     * @param  string|null  $documentation
     * @return FlyDocsConfigSingleDTO
     *
     * @throws ReflectionException
     * @throws UnknownProperties
     */
    public function documentationConfig(?string $documentation = null): FlyDocsConfigSingleDTO
    {
        if (is_null($this->config)) {
            $this->config = $this->generateConfig();
        }

        if ($documentation === null) {
            $documentation = $this->config->default;
        }

        if (! array_key_exists($documentation, $this->config->documentations)) {
            throw new RuntimeException('Документация не найдена');
        }

        return $this->config->documentations[$documentation];
    }

    /**
     * @param  array<mixed>  $defaults
     * @param  array<mixed>  $config
     * @return array<mixed>
     */
    protected function mergeConfig(array $defaults, array $config): array
    {
        $merged = $defaults;

        foreach ($config as $key => &$value) {
            if (isset($defaults[$key])
                && $this->isAssociativeArray($defaults[$key])
                && $this->isAssociativeArray($value)
            ) {
                $merged[$key] = $this->mergeConfig($defaults[$key], $value);

                continue;
            }

            $merged[$key] = $value;
        }

        return $merged;
    }

    /**
     * @param mixed $value
     * @return bool
     */
    protected function isAssociativeArray(mixed $value): bool
    {
        return is_array($value) && count(array_filter(array_keys($value), 'is_string')) > 0;
    }
}
