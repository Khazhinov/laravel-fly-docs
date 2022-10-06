<?php

namespace Khazhinov\LaravelFlyDocs\Generator\Builders;

use GoldSpecDigital\ObjectOrientedOAS\Objects\PathItem;
use Illuminate\Routing\Route;
use Illuminate\Routing\Router;
use Illuminate\Support\Collection;
use Khazhinov\LaravelFlyDocs\Generator\Attributes;
use Khazhinov\LaravelFlyDocs\Generator\Attributes\Collection as CollectionAttribute;
use Khazhinov\LaravelFlyDocs\Generator\Builders\Paths\OperationsBuilder;
use Khazhinov\LaravelFlyDocs\Generator\RouteInformation;
use Khazhinov\LaravelFlyDocs\Services\DTO\Config\FlyDocsConfigDTO;
use Khazhinov\LaravelFlyDocs\Services\DTO\Config\FlyDocsConfigSingleDTO;

class PathsBuilder
{
    protected OperationsBuilder $operationsBuilder;

    public function __construct(
        OperationsBuilder $operationsBuilder
    ) {
        $this->operationsBuilder = $operationsBuilder;
    }

    public function build(FlyDocsConfigDTO $config, string $documentation): array
    {
        return $this->routes($config->documentations[$documentation])
            ->groupBy(static fn (RouteInformation $routeInformation) => $routeInformation->uri)
            ->map(function (Collection $routes, $uri) {
                $pathItem = PathItem::create()->route($uri);

                $operations = $this->operationsBuilder->build($routes);

                return $pathItem->operations(...$operations);
            })
            ->values()
            ->toArray();
    }

    protected function routes(FlyDocsConfigSingleDTO $documentation_config): Collection
    {
        /** @noinspection CollectFunctionInCollectionInspection */
        return collect(app(Router::class)->getRoutes())
            ->filter(static fn (Route $route) => $route->getActionName() !== 'Closure')
            ->map(static fn (Route $route) => RouteInformation::createFromRoute($route))
            ->filter(static function (RouteInformation $route) use ($documentation_config) {
                $pathItem = $route->controllerAttributes
                    ->first(static fn (object $attribute) => $attribute instanceof Attributes\PathItem);

                $operation = $route->actionAttributes
                    ->first(static fn (object $attribute) => $attribute instanceof Attributes\Operation);

                if ($pathItem && $operation) {
                    foreach ($documentation_config->paths->route_name_start_with as $route_start_with) {
                        if (str_starts_with($route->name, $route_start_with)) {
                            return true;
                        }
                    }
                }

                return false;
            });
    }
}
