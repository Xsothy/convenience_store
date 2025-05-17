<?php

namespace Database\Seeders;

use App\Http\Services\ShopifyService;
use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function __construct(
        private readonly ShopifyService $shopifyService
    ) {}

    const SHOPIFY_API_VERSION = '2025-04'; // Use your target version

    const BATCH_SIZE = 50; // Adjust batch size based on performance/limits

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->seedCollection();
        //        $this->seedCategory();
    }

    public function seedCollection()
    {
        $hasNextPage = true;
        $afterCursor = null;
        $totalFetched = 0;

        $query = <<<'GRAPHQL'
        query GetCollections($first: Int!, $after: String) {
          collections(first: $first, after: $after) {
            edges {
              node {
                legacyResourceId
                title
                description
                descriptionHtml
                image { ... on Image { url } }
                translations(locale: "en") { key value }
                # Add more locales
              }
              cursor
            }
            pageInfo { hasNextPage endCursor }
          }
        }
        GRAPHQL;

        dump('Starting Shopify collection sync...');

        while ($hasNextPage) {
            $response = $this->shopifyService->request($query, [
                'first' => 50,
                'after' => $afterCursor,
            ]);

            if ($response->failed()) {
                dump('Shopify API request failed:', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                break;
            }

            $data = $response->json();

            $categoriesData = $data['data']['collections']['edges'] ?? [];
            $pageInfo = $data['data']['collections']['pageInfo'] ?? ['hasNextPage' => false];

            foreach ($categoriesData as $edge) {
                $shopifyCategory = $edge['node'];

                /** @var Category $category */
                $category = Category::create([
                    'shopify_id' => $shopifyCategory['legacyResourceId'],
                    'type' => 'category',
                    'name' => $shopifyCategory['title'],
                    'slug' => str($shopifyCategory['title'])->slug(),
                    'description' => $shopifyCategory['descriptionHtml'],
                    'is_active' => true,
                    'show_in_menu' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                if ($shopifyCategory['image']) {
                    $category->addMediaFromUrl($shopifyCategory['image']['url'])->toMediaCollection('feature_image');
                }
            }

            $totalFetched += count($categoriesData);
            dump('Fetched and processed '.count($categoriesData)." categories. Total: {$totalFetched}");

            $hasNextPage = $pageInfo['hasNextPage'];
            $afterCursor = $edge['cursor'] ?? null; // Use cursor from the last edge
        }
    }

    public function seedCategory()
    {
        $hasNextPage = true;
        $afterCursor = null;
        $totalFetched = 0;

        $query = <<<'GRAPHQL'
        query GetCollections($first: Int!, $after: String) {
          taxonomy {
            categories(first: $first, after: $after) {
              edges {
                node {
                  id
                  parentId
                  fullName
                  isArchived
                  name
                  childrenIds
                  ancestorIds
                }
                cursor
              }
              pageInfo { hasNextPage endCursor }
            }
          }
        }
        GRAPHQL;

        dump('Starting Shopify Category sync...');

        while ($hasNextPage) {
            $response = $this->shopifyService->request($query, [
                'first' => 50,
                'after' => $afterCursor,
            ]);

            if ($response->failed()) {
                dump('Shopify API request failed:', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                return;
            }

            $data = $response->json();

            $categoriesData = $data['data']['taxonomy']['categories']['edges'] ?? [];
            $pageInfo = $data['data']['taxonomy']['categories']['pageInfo'] ?? ['hasNextPage' => false];

            foreach ($categoriesData as $edge) {
                $shopifyCategory = $edge['node'];

                /** @var Category $category */
                $category = Category::create([
                    'shopify_id' => $shopifyCategory['id'],
                    'type' => 'category',
                    'name' => $shopifyCategory['name'],
                    'slug' => str($shopifyCategory['name'])->slug(),
                    'description' => '',
                    'is_active' => $shopifyCategory['isArchived'],
                    'show_in_menu' => false,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            $totalFetched += count($categoriesData);
            dump('Fetched and processed '.count($categoriesData)." categories. Total: {$totalFetched}");

            $hasNextPage = $pageInfo['hasNextPage'];
            $afterCursor = $edge['cursor'] ?? null; // Use cursor from the last edge
        }
    }
}
