<?php

declare(strict_types = 1);

namespace Khazhinov\LaravelFlyDocs\Generator\Objects;

use GoldSpecDigital\ObjectOrientedOAS\Objects\MediaType;

class MediaTypeWithFormData extends MediaType
{
    const string MEDIA_TYPE_FORM_DATA = 'multipart/form-data';

    /**
     * @param string|null $objectId
     * @return static
     */
    public static function formData(string $objectId = null): self
    {
        return static::create($objectId)
            ->mediaType(static::MEDIA_TYPE_FORM_DATA);
    }
}
