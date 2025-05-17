<?php

namespace App\Http\Services;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class ShopifyService
{
    private PendingRequest $pendingRequest;

    const SHOPIFY_API_VERSION = '2025-04'; // Use your target version

    const BATCH_SIZE = 50; // Adjust batch size based on performance/limits

    public function __construct()
    {
        $storeUrl = 'e3a74d-87.myshopify.com';
        $accessToken = '';

        $apiUrl = "https://{$storeUrl}/admin/api/".self::SHOPIFY_API_VERSION.'/graphql.json';
        $headers = [
            'X-Shopify-Access-Token' => $accessToken,
            'Content-Type' => 'application/json',
        ];

        $this->pendingRequest = Http::withHeaders($headers)->baseUrl($apiUrl);
    }

    /**
     * @throws ConnectionException
     */
    public function request(string $query, array $variables = []): \GuzzleHttp\Promise\PromiseInterface|\Illuminate\Http\Client\Response
    {
        return $this->pendingRequest->post('', [
            'query' => $query,
            'variables' => $variables,
        ]);
    }
}
