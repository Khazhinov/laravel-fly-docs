<?php

declare(strict_types = 1);

namespace Khazhinov\LaravelFlyDocs\Generator;

use Attribute;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Routing\Route;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use phpDocumentor\Reflection\DocBlock;
use phpDocumentor\Reflection\DocBlockFactory;
use ReflectionAttribute;
use ReflectionClass;
use ReflectionException;
use ReflectionParameter;

/**
 * @template TKey of array-key
 * @template TValue
 */
final class RouteInformation
{
    public ?string $domain;
    public string $method;
    public string $uri;
    public ?string $name;
    public string $controller;
    public Collection $parameters;

    /** @var Collection<TKey, TValue>|Attribute[] */
    public Collection|array $controllerAttributes;

    public string $action;

    /** @var ReflectionParameter[] */
    public array $actionParameters;

    /** @var Collection<TKey, TValue>|Attribute[] */
    public Collection|array $actionAttributes;

    public ?DocBlock $actionDocBlock;

    /**
     * @param  Route  $route
     * @return RouteInformation
     *
     * @throws ReflectionException
     */
    public static function createFromRoute(Route $route): RouteInformation
    {
        return tap(new static(), static function (self $instance) use ($route): void {
            /** @var string $domain */
            $domain = $route->domain();

            /** @var string $method */
            $method = collect($route->methods())
                ->map(static fn ($value) => Str::lower($value))
                ->filter(static fn ($value) => ! in_array($value, ['head', 'options'], true))
                ->first();

            $actionNameParts = explode('@', $route->getActionName());

            if (count($actionNameParts) === 2) {
                [$controller, $action] = $actionNameParts;
            } else {
                [$controller] = $actionNameParts;
                $action = '__invoke';
            }

            preg_match_all('/{(.*?)}/', $route->uri, $parameters);
            /** @var Arrayable<TKey, TValue>|iterable<TKey, TValue>|null $_ */
            $_ = $parameters[1];
            $parameters = collect($_);

            if (count($parameters) > 0) {
                $parameters = $parameters->map(static fn ($parameter) => [
                    'name' => Str::replaceLast('?', '', $parameter),
                    'required' => ! Str::endsWith($parameter, '?'),
                ]);
            }

            /** @var class-string $controller */
            $reflectionClass = new ReflectionClass($controller);
            $reflectionMethod = $reflectionClass->getMethod($action);

            $docComment = $reflectionMethod->getDocComment();
            $docBlock = $docComment ? DocBlockFactory::createInstance()->create($docComment) : null;

            /** @var Collection|Attribute[] $controllerAttributes */
            $controllerAttributes = collect($reflectionClass->getAttributes())
                ->map(fn (ReflectionAttribute $attribute) => $attribute->newInstance());

            /** @var Collection|Attribute[] $actionAttributes */
            $actionAttributes = collect($reflectionMethod->getAttributes())
                ->map(fn (ReflectionAttribute $attribute) => $attribute->newInstance());

            $containsControllerLevelParamter = $actionAttributes->contains(fn ($value) => $value instanceof \Khazhinov\LaravelFlyDocs\Generator\Attributes\Parameters);

            $instance->domain = $domain;
            $instance->method = $method;
            $instance->uri = Str::start($route->uri(), '/');
            $instance->name = $route->getName();
            $instance->controller = $controller;
            $instance->parameters = $containsControllerLevelParamter ? collect([]) : $parameters;
            $instance->controllerAttributes = $controllerAttributes;
            $instance->action = $action;
            $instance->actionParameters = $reflectionMethod->getParameters();
            $instance->actionAttributes = $actionAttributes;
            $instance->actionDocBlock = $docBlock;
        });
    }
}
