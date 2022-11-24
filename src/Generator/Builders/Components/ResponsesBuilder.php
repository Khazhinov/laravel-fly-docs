<?php

namespace Khazhinov\LaravelFlyDocs\Generator\Builders\Components;

use Khazhinov\LaravelFlyDocs\Generator\Contracts\Reusable;
use Khazhinov\LaravelFlyDocs\Generator\Factories\ResponseFactory;
use Khazhinov\LaravelFlyDocs\Services\DTO\Config\FlyDocsConfigDTO;

class ResponsesBuilder extends Builder
{
    /**
     * @param  FlyDocsConfigDTO  $config
     * @param  string  $documentation
     * @return array<mixed>
     */
    public function build(FlyDocsConfigDTO $config, string $documentation): array
    {
        return $this->getAllClasses('responses', $config, $documentation)
            ->filter(static function ($class) {
                return
                    is_a($class, ResponseFactory::class, true) &&
                    is_a($class, Reusable::class, true);
            })
            ->map(static function ($class) {
                /** @var ResponseFactory $instance */
                $instance = app($class);

                return $instance->build();
            })
            ->values()
            ->toArray();
    }
}
