<?php

declare(strict_types=1);

namespace QuickNotes;

class Request
{
    private array $get;
    private array $post;

    public function __construct(array $get, array $post)
    {
        $this->get = $get;
        $this->post = $post;
    }

    public function getGetArray(string $name, string $default = null): ?string
    {
        return $this->get[$name] ?? $default;
    }

    public function getPostArray(string $name, string $default = null): ?string
    {
        return $this->post[$name] ?? $default;
    }
}