<?php

namespace Khazhinov\LaravelFlyDocs\Generator\Builders\Components;

use Khazhinov\LaravelFlyDocs\Generator\Factories\SecuritySchemeFactory;
use Khazhinov\LaravelFlyDocs\Services\DTO\Config\FlyDocsConfigDTO;

class SecuritySchemesBuilder extends Builder
{
    public function build(FlyDocsConfigDTO $config, string $documentation): array
    {
        return $this->getAllClasses('security_schemes', $config, $documentation)
            ->filter(static function ($class) {
                return is_a($class, SecuritySchemeFactory::class, true);
            })
            ->map(static function ($class) {
                /** @var SecuritySchemeFactory $instance */
                $instance = app($class);

                return $instance->build();
            })
            ->values()
            ->toArray();
    }
}
