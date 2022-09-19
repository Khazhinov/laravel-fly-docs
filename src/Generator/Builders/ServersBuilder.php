<?php

namespace Khazhinov\LaravelFlyDocs\Generator\Builders;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Server;
use GoldSpecDigital\ObjectOrientedOAS\Objects\ServerVariable;
use Khazhinov\LaravelFlyDocs\Services\DTO\Config\FlyDocsConfigSingleDTO;

class ServersBuilder
{
    /**
     * @return Server[]
     */
    public function build(FlyDocsConfigSingleDTO $config): array
    {
        $servers = [];
        foreach ($config->servers as $server) {
            $server_variables = [];
            if ($server->variables) {
                foreach ($server->variables as $variable_name => $variable_body) {
                    $server_variable = ServerVariable::create($variable_name)
                        ->default($variable_body->default)
                        ->description($variable_body->description)
                    ;
                    if ($variable_body->enum) {
                        $server_variable = $server_variable->enum(...$variable_body->enum);
                    }

                    $server_variables[] = $server_variable;
                }
            }

            $servers[] = Server::create()
                ->url($server->url)
                ->description($server->description)
                ->variables(...$server_variables)
            ;
        }

        return $servers;
    }
}
