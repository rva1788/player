<?php

namespace App\Meta;

use App\ApiData\ApiRequest;

class Meta
{
    private string $url;

    private int $page = 1;
    private int $total = 1;
    private int $perPage = 1;
    private array $filter = [];

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function setPage(int $page = 1): self
    {
        $this->page = $page;

        return $this;
    }

    public function setTotal(int $total): self
    {
        $this->total = $total;

        return $this;
    }

    public function setPerPage(int $perPage): self
    {
        $this->perPage = $perPage;

        return $this;
    }

    public function setFilter(ApiRequest $request): self
    {
        if ($request->has('name')) {
            $data['name'] = $request->get('name');
        }

        if ($request->has('position')) {
            $data['position'] = $request->get('position');
        }

        if ($request->has('country')) {
            $data['country'] = $request->get('country');
        }

        if ($request->has('page')) {
            $data['page'] = $request->get('page');
            $this->page = $request->get('page');
        }

        if (!empty($data)) {
            $this->filter = $data;
        }

        return $this;
    }

    public function toArray(): array
    {
        return [
            'page' => $this->page,
            'per_page' => $this->perPage,
            'total_count' => $this->total,
            'links' => (new MetaLinkBuilder(
                url: $this->url,
                data: $this->filter,
                total: $this->total
            ))->toArray()
        ];
    }
}