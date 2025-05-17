<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Ramsey\Collection\Collection;

class ProductSeeder extends Seeder
{
    const SHOPIFY_API_VERSION = '2025-04'; // Use your target version
    const BATCH_SIZE = 50; // Adjust batch size based on performance/limits
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $storeUrl = 'e3a74d-87.myshopify.com';
        $accessToken = '';

        $apiUrl = "https://{$storeUrl}/admin/api/" . self::SHOPIFY_API_VERSION . "/graphql.json";
        $headers = [
            'X-Shopify-Access-Token' => $accessToken,
            'Content-Type' => 'application/json', // Use application/json for GraphQL variables
        ];

        $hasNextPage = true;
        $afterCursor = null;
        $totalFetched = 0;

        // Define the GraphQL query (use the one from above)
        $query = <<<'GRAPHQL'
        query GetProducts($first: Int!, $after: String) {
          products(first: $first, after: $after) {
            edges {
              node {
                id
                legacyResourceId
                title
                handle
                descriptionHtml
                productType
                status
                tags
                createdAt
                updatedAt
                hasOnlyDefaultVariant
                tracksInventory
                totalInventory
                priceRangeV2 {
                  minVariantPrice {
                    amount
                    currencyCode
                  }
                  maxVariantPrice {
                    amount
                    currencyCode
                  }
                }
                variants(first: 100) {
                  edges {
                    node {
                      displayName
                      title
                      legacyResourceId
                      sku
                      barcode
                      price
                      compareAtPrice
                      availableForSale
                      inventoryPolicy
                      inventoryQuantity
                    }
                  }
                }
                category {
                  id
                  name
                }
                collections(first: 5) {
                  edges {
                    node {
                      legacyResourceId
                    }
                  }
                }
                featuredMedia {
                  ... on MediaImage {
                    preview {
                      image {
                        url
                        altText
                        width
                        height
                      }
                    }
                  }
                  ... on ExternalVideo {
                      embeddedUrl
                      host
                   }
                   ... on Model3d {
                      sources {
                         url
                         mimeType
                         filesize
                      }
                   }
                   ... on Video {
                       sources {
                          url
                          mimeType
                       }
                   }
                }
                media(first: 10) {
                  edges {
                    node {
                      ... on MediaImage {
                        mediaContentType
                        alt
                        id
                        preview {
                          image {
                            url
                            width
                            height
                          }
                        }
                      }
                     ... on ExternalVideo {
                          embeddedUrl
                          host
                       }
                       ... on Model3d {
                          sources {
                             url
                             mimeType
                             filesize
                          }
                       }
                       ... on Video {
                           originalSource {
                              url
                              mimeType
                           }
                       }
                    }
                  }
                }
                metafields(first: 20, namespace: "custom") {
                  edges {
                    node {
                      key
                      value
                      type
                      namespace
                    }
                  }
                }
                # Add other namespaces if needed
                translations(locale: "en") {
                  key
                  value
                }
                # Add more locales
              }
              cursor
            }
            pageInfo {
              hasNextPage
              endCursor
            }
          }
        }

        GRAPHQL;


        dump('Starting Shopify product sync...');

        while ($hasNextPage) {
            $variables = [
                'first' => self::BATCH_SIZE,
                'after' => $afterCursor,
            ];

                $response = Http::withHeaders($headers)->post($apiUrl, [
                    'query' => $query,
                    'variables' => $variables,
                ]);

                if ($response->failed()) {
                   dump('Shopify API request failed:', [
                        'status' => $response->status(),
                        'body' => $response->body()
                    ]);
                    break;
                }

                $data = $response->json();

                if (isset($data['errors'])) {
                    dump('Shopify GraphQL Errors:', $data['errors']);
                }

                $productsData = $data['data']['products']['edges'] ?? [];
                $pageInfo = $data['data']['products']['pageInfo'] ?? ['hasNextPage' => false];

                if (empty($productsData)) {
                    break;
                }

                foreach ($productsData as $edge) {
                    $shopifyProduct = $edge['node'];
                    $this->seedSingleProduct($shopifyProduct);
                }

                $totalFetched += count($productsData);
                dump("Fetched and processed " . count($productsData) . " products. Total: {$totalFetched}");

                $hasNextPage = $pageInfo['hasNextPage'];
                $afterCursor = $edge['cursor'] ?? null; // Use cursor from the last edge
        }
    }

    public function seedSingleProduct(array $shopifyProduct): void
    {
        $originalSlug = str($shopifyProduct['title'])->slug();
        $slug = $originalSlug;

        $index = 1;
        while (Product::query()->where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $index++;
        }

        /** @var Product $product */
        $product = Product::create([
            'shopify_id' => $shopifyProduct['legacyResourceId'],
            'type' => 'product',
            'name' => $shopifyProduct['title'],
            'slug' => $slug,
            'description' => $shopifyProduct['descriptionHtml'],
            'price' => $shopifyProduct['priceRangeV2']['minVariantPrice']['amount'],
            'is_activated' => true,
            'is_in_stock' => true,
            'is_shipped' => false,
            'is_trend' => false,
            'has_options' => true,
            'has_multi_price' => false,
            'has_unlimited_stock' => false,
            'has_max_cart' => false,
            'min_cart' => 0,
            'max_cart' => 0,
            'has_stock_alert' => false,
            'min_stock_alert' => 0,
            'max_stock_alert' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        if ($shopifyProduct['featuredMedia']) {
            if(isset($shopifyProduct['featuredMedia']['preview']['image']['url'])) {
                $product->addMediaFromUrl($shopifyProduct['featuredMedia']['preview']['image']['url'])->toMediaCollection('feature_image');
            }
        }

        $collections = collect($shopifyProduct['collections']['edges'])
            ->map(fn ($collection) => Category::query()->firstWhere('shopify_id', $collection['node']['legacyResourceId']))
            ->map(fn (Category $category) => DB::table('product_has_categories')->insert([
                'product_id' => $product->id,
                'category_id' => $category->id,
            ]));
//         gallery
        foreach ($shopifyProduct['media']['edges'] as $galleryImage) {
            if (isset($galleryImage['node']['preview']['image']['url'])) {
                $product->addMediaFromUrl($galleryImage['node']['preview']['image']['url'])->toMediaCollection('gallery');
            } elseif (isset($galleryImage['node']['originalSource']['url'])) {
//                $product->addMediaFromUrl($galleryImage['node']['originalSource']['url'])->toMediaCollection('gallery');
            }
        }

        $variants = $shopifyProduct['variants']['edges'] ?? [];
        $product->meta('options', [
            [
                'name' => 'Variation',
                'values' => [
                    ...collect($variants)->map(function ($variant) {
                        return [
                            'value' => $variant['node']['title'],
                            'has_custom_price' => true,
                            'price_for' => 'items',
                            'qty' => $variant['node']['inventoryQuantity'],
                            'price' => $variant['node']['price'],
                            'vat' => 0,
                            'discount' => 0,
                            'discount_to' => null,
                            'has_color' => false,
                            'color' => null,
                        ];
                    })->toArray(),
                ],
                'shopify' => $variants,
            ]
        ]);
        dump($product->id);
    }
}
