<?php

namespace Khazhinov\LaravelFlyDocs\Generator\Builders;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Contact;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Info;
use GoldSpecDigital\ObjectOrientedOAS\Objects\License;
use Khazhinov\LaravelFlyDocs\Services\DTO\Config\FlyDocsConfigSingleDTO;
use Khazhinov\LaravelFlyDocs\Services\DTO\Config\FlyDocsConfigSingleInfoContactDTO;
use Khazhinov\LaravelFlyDocs\Services\DTO\Config\FlyDocsConfigSingleInfoLicenseDTO;

class InfoBuilder
{
    public function build(FlyDocsConfigSingleDTO $config): Info
    {
        $info = Info::create()
            ->title($config->info->title)
            ->description($config->info->description)
            ->version($config->info->version);

        if ($config->info->contact) {
            $info = $info->contact($this->buildContact($config->info->contact));
        }

        if ($config->info->license) {
            $info = $info->license($this->buildLicense($config->info->license));
        }

        foreach ($config->info->extensions as $key => $value) {
            $info->x($key, $value);
        }

        return $info;
    }

    protected function buildContact(FlyDocsConfigSingleInfoContactDTO $contact): Contact
    {
        return Contact::create()
            ->name($contact->name)
            ->email($contact->email)
            ->url($contact->url);
    }

    protected function buildLicense(FlyDocsConfigSingleInfoLicenseDTO $license): License
    {
        return License::create()
            ->name($license->name)
            ->url($license->url);
    }
}
