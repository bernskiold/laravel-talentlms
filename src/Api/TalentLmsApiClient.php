<?php

namespace Bernskiold\LaravelTalentLms\Api;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class TalentLmsApiClient
{
    public PendingRequest $request;

    public function __construct(
        protected string $apiKey,
        protected string $domain,
        protected string $version,
    )
    {
        $this->request = Http::withBasicAuth($this->apiKey, '')
            ->acceptJson();
    }

    public static function fromConfig(array $config): self
    {
        return new self(
            apiKey: $config['api_key'],
            domain: $config['domain'],
            version: $config['version'] ?? '1',
        );
    }

    public function get(string $endpoint, array $query = []): object|array|null
    {
        return $this->request
            ->throw()
            ->get($this->buildEndpointUrl($endpoint, $query))
            ->object();
    }

    public function post(string $endpoint, array $data = []): object|array|null
    {
        return $this->request
            ->throw()
            ->post($this->buildEndpointUrl($endpoint), $data)
            ->object();
    }

    protected function buildEndpointUrl(string $endpoint, array $query): string
    {
        $baseRequest = "{$this->domain}/api/v{$this->version}{$endpoint}";

        if (empty($query)) {
            return $baseRequest;
        }

        $urlQuery = collect($query)
            ->map(fn($value, $key) => "{$key}:{$value}")
            ->implode(',');

        return "{$baseRequest}/{$urlQuery}";
    }
}
