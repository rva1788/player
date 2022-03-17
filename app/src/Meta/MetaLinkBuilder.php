<?php

namespace App\Meta;

use App\Repository\PlayerRepository;

class MetaLinkBuilder
{
    private array $meta;

    private string $url;
    private ?int $lastPage;

    public function __construct(string $url, array $data, int $total)
    {
        $this->url = $url;
        $this->lastPage = round($total/PlayerRepository::LIMIT, 0, PHP_ROUND_HALF_UP);

        $this
            ->buildSelf($data)
            ->buildFirst($data)
            ->buildPrevious($data)
            ->buildNext($data)
            ->buildNext($data);
    }

    public function toArray(): array
    {
        return $this->meta;
    }

    private function buildSelf(array $data): self
    {
        $this->meta['self'] = $this->buildUrl($data);

        return $this;
    }

    private function buildFirst(array $data): self
    {
        $data['page'] = 1;
        $this->meta['first'] = $this->buildUrl($data);

        return $this;
    }

    private function buildPrevious(array $data): self
    {
        if (!empty($data['page']) && $data['page'] > 1) {
            $data['page']--;
            $this->meta['previous'] = $this->buildUrl($data);
        }

        return $this;
    }

    private function buildNext(array $data): self
    {
        $data['page'] = !empty($data['page']) ? $data['page'] : 1;
        if (!empty($data['page']) && $data['page'] < $this->lastPage) {
            $data['page']++;
            $this->meta['next'] = $this->buildUrl($data);
        }

        return $this;
    }

    private function buildLast(array $data)
    {
        if (!empty($data['page']) && $data['page'] != $this->lastPage) {
            $this->meta['last'] = $this->buildUrl($data);
        }
    }

    private function buildUrl(array $data = []): string
    {
        return $this->url . '/' . http_build_query($data);
    }
}