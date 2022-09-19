<?php

namespace Khazhinov\LaravelFlyDocs\Generator\Builders;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Tag;
use Khazhinov\LaravelFlyDocs\Services\DTO\Config\FlyDocsConfigSingleDTO;

class TagsBuilder
{
    /**
     * @return Tag[]
     */
    public function build(FlyDocsConfigSingleDTO $config): array
    {
        $tags = [];
        foreach ($config->tags as $tag) {
            $tags[] = Tag::create()
                ->name($tag->name)
                ->description($tag->description)
            ;
        }

        return $tags;
    }
}
