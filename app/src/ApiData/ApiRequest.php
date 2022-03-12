<?php

namespace App\ApiData;

use Symfony\Component\HttpFoundation\Request;
use Exception;

class ApiRequest
{
    private array $data = [];

    /**
     * @param Request $request
     * @throws Exception
     */
    public function __construct(Request $request)
    {
        if (!$request->headers->has("Content-Type")
            || $request->headers->get("Content-Type") != 'application/json'
        ) {
            throw new Exception("Invalid request");
        }

        if (!empty($request->getContent())) {
            $this->data = json_decode($request->getContent(), true);
        }

        if ($request->getRealMethod() !== $request::METHOD_POST && !empty($request->get('data'))) {
            $this->data = json_decode($request->get('data'), true);
        }

        if ($request->getRealMethod() === $request::METHOD_GET) {
            $this->data = array_merge($this->data, $request->query->all());
        }

        foreach ($this->data as $item) {
            if (preg_match('/[\'\^£$%&\*\(\)\}\{@#~\?\<\>,\|=\+¬]/u', $item)) {
                throw new Exception("Bad input format");
            }
        }
    }

    public function has(string $key): bool
    {
        return !empty($this->data[$key]);
    }

    public function get(string $key): mixed
    {
        return $this->has($key) ? $this->data[$key] : null;
    }

    public function toArray(): array
    {
        return $this->data;
    }
}
