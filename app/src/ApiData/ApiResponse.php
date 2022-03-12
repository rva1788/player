<?php

namespace App\ApiData;

use Symfony\Component\HttpFoundation\JsonResponse;
use Exception;

class ApiResponse
{
    private ?array $data = null;
    private ?string $error = null;

    public function setData(array $data): self
    {
        $this->data = $data;

        return $this;
    }

    public function setError(Exception $exception): self
    {
        $this->data = null;
        $this->error = $exception->getMessage();

        return $this;
    }

    public function toArray(): array
    {
        $array = ['result' => !$this->error];

        if ($this->data && !$this->error) {
            $array['data'] = $this->data;
        }

        if ($this->error) {
            $array['error'] = $this->error;
        }

        return $array;
    }

    public function toJsonResponse(): JsonResponse
    {
        return new JsonResponse($this->toArray());
    }
}