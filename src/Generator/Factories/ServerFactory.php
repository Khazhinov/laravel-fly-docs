<?php

namespace Khazhinov\LaravelFlyDocs\Generator\Factories;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Server;

abstract class ServerFactory
{
    abstract public function build(): Server;
}
