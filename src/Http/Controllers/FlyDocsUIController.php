<?php

declare(strict_types=1);

namespace Khazhinov\LaravelFlyDocs\Http\Controllers;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Response as ResponseFacade;
use Khazhinov\LaravelFlyDocs\Generator\Generator;
use ReflectionException;
use RuntimeException;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

class FlyDocsUIController extends Controller
{
    /**
     * @throws ReflectionException
     * @throws UnknownProperties
     */
    public function getDefaultDocumentation()
    {
        $config = \Khazhinov\LaravelFlyDocs\Services\ConfigFactory::getInstance();

        return redirect(route("fly-docs.{$config->getConfig()->default}.docs_ui"));
    }

    public function getApiDocs(Generator $generator, Request $request)
    {
        $documentation = $this->getDocumentationFromRequest($request);

        return $generator->generate($documentation);
    }

    /**
     * @param  Request  $request
     * @param  string  $asset
     * @return Response
     * @throws FileNotFoundException
     */
    public function getAsset(Request $request, string $asset): Response
    {
        $fileSystem = new Filesystem();
        $documentation = $this->getDocumentationFromRequest($request);

        try {
            $path = fly_docs_swagger_ui_dist_path($documentation, $asset);

            if (is_string($path)) {
                return new Response(
                    $fileSystem->get($path),
                    200,
                    [
                        'Content-Type' => pathinfo($asset, PATHINFO_EXTENSION) === 'css'
                            ? 'text/css'
                            : 'application/javascript',
                    ]
                );
            }
        } catch (RuntimeException $exception) {
            return abort(404, $exception->getMessage());
        }

        return abort(404);
    }

    /**
     * @param  Request  $request
     * @return Response
     */
    public function showDocsUI(Request $request): Response
    {
        $documentation = $this->getDocumentationFromRequest($request);

        /** @var string $content */
        $content = view('fly-docs::docs', [
            'documentation' => $documentation,
            'api_documentation_url' => $this->generateDocumentationURL($documentation),
        ]);

        return ResponseFacade::make(
            $content
        );
    }

    protected function getDocumentationFromRequest(Request $request): string
    {
        $request_route = $request->route();

        if ($request_route instanceof Route) {
            $action_info = $request_route->getAction();

            if (array_key_exists('as', $action_info)) {
                $route_binding = $action_info['as'];
                $exploded_route_binding = explode('.', $route_binding);

                if (count($exploded_route_binding) === 3) {
                    return $exploded_route_binding[1];
                }
            }
        }

        throw new RuntimeException('Ошибка обработки запроса. Не удалось распознать название документации.');
    }

    /**
     * @param  Request  $request
     * @return string
     * @throws FileNotFoundException
     */
    public function oauth2Callback(Request $request): string
    {
        $fileSystem = new Filesystem();
        /** @var string $documentation */
        $documentation = $this->getDocumentationFromRequest($request);

        return $fileSystem->get(fly_docs_swagger_ui_dist_path($documentation, 'oauth2-redirect.html'));
    }

    /**
     * @param  string  $documentation
     * @return string
     */
    protected function generateDocumentationURL(string $documentation): string
    {
        return route(
            'fly-docs.'.$documentation.'.api_docs',
        );
    }
}
