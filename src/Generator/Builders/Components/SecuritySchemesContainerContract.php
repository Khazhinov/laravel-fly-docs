<?php
declare(strict_types=1);

namespace Khazhinov\LaravelFlyDocs\Generator\Builders\Components;

interface SecuritySchemesContainerContract
{
    /**
     * @return array<string, mixed>
     */
    public static function getSecuritySchemes(): array;
}