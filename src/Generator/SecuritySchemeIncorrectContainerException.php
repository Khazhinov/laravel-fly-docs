<?php
declare(strict_types=1);

namespace Khazhinov\LaravelFlyDocs\Generator;

use RuntimeException;
use Throwable;

class SecuritySchemeIncorrectContainerException extends RuntimeException
{
    public function __construct(string $message = "Incorrect security scheme container contract.", int $code = 400, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}