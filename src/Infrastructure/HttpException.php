<?php

declare(strict_types=1);

namespace WeddingSite\Infrastructure;

use RuntimeException;
use Throwable;

final class HttpException extends RuntimeException
{
    public function __construct(
        string $message,
        private readonly int $statusCode = 500,
        int $code = 0,
        ?Throwable $previous = null,
    ) {
        parent::__construct($message, $code, $previous);
    }

    public function statusCode(): int
    {
        return $this->statusCode;
    }

    public static function badRequest(string $message = 'Bad Request'): self
    {
        return new self($message, 400);
    }

    public static function unauthorized(string $message = 'Unauthorized'): self
    {
        return new self($message, 401);
    }

    public static function forbidden(string $message = 'Forbidden'): self
    {
        return new self($message, 403);
    }

    public static function notFound(string $message = 'Not Found'): self
    {
        return new self($message, 404);
    }

    public static function methodNotAllowed(string $message = 'Method not allowed'): self
    {
        return new self($message, 405);
    }

    public static function internal(string $message = 'Internal Server Error', ?Throwable $previous = null): self
    {
        return new self($message, 500, 0, $previous);
    }
}
