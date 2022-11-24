<?php

namespace Khazhinov\LaravelFlyDocs\Generator\Builders\Components;

use Khazhinov\LaravelFlyDocs\Generator\Contracts\Reusable;
use Khazhinov\LaravelFlyDocs\Generator\Factories\RequestBodyFactory;
use Khazhinov\LaravelFlyDocs\Services\DTO\Config\FlyDocsConfigDTO;

class RequestBodiesBuilder extends Builder
{
    /**
     * @param  FlyDocsConfigDTO  $config
     * @param  string  $documentation
     * @return array<mixed>
     */
    public function build(FlyDocsConfigDTO $config, string $documentation): array
    {
        return $this->getAllClasses('request_bodies', $config, $documentation)
            ->filter(static function ($class) {
                return
                    is_a($class, RequestBodyFactory::class, true) &&
                    is_a($class, Reusable::class, true);
            })
            ->map(static function ($class) {
                /** @var RequestBodyFactory $instance */
                $instance = app($class);

                return $instance->build();
            })
            ->values()
            ->toArray();
    }
}
