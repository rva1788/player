<?php

namespace App\ApiData;

use App\Meta\Meta;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;

class ApiResponse
{
    private ?array $data = null;
    private ?string $error = null;
    private ?Meta $meta = null;

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

    public function setMeta(Meta $meta): self
    {
        $this->meta = $meta;

        return $this;
    }

    public function toArray(): array
    {
        $array = ['result' => !$this->error];

        if ($this->data && !$this->error) {
            $array['data'] = $this->data;
            if ($this->meta) {
                $array['_metadata'] = $this->meta->toArray();
            }
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