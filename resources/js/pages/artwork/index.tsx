import React from 'react';
import { Head } from '@inertiajs/react';
import { AppLayout, ArtworkLayout } from '@/layouts';
import { PaginatedCollection } from '@/types';
import ProductGrid from '@/components/ProductGrid';
import Pagination from '@/components/Pagination';

interface ArtworkIndexProps {
  products: PaginatedCollection<App.Data.ProductData>;
}

export default function ArtworkIndex({ products }: ArtworkIndexProps) {
  // Convert meta.per_page to number to satisfy the Pagination component
  const paginationMeta = products.meta ? {
    ...products.meta,
    per_page: typeof products.meta.per_page === 'string'
      ? parseInt(products.meta.per_page, 10)
      : products.meta.per_page
  } : null;

  return (
    <>
      <Head title="Product Collection" />

      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        {/* Page header */}
        <div className="max-w-3xl mx-auto text-center mb-12">
          <h1 className="text-4xl font-bold text-gray-900 mb-4">All Products</h1>
        </div>

        {/* Products grid with improved styling */}
        <ProductGrid
          products={products.data}
          showCount={true}
          gridCols="grid-cols-3"
        />

        {/* Pagination */}
        {paginationMeta && (
          <div className="mt-16">
            <Pagination
              meta={paginationMeta}
              baseUrl="/products"
              filters={{}}
            />
          </div>
        )}
      </div>
    </>
  );
}

ArtworkIndex.layout = page => (
  <AppLayout>
    <ArtworkLayout children={page} />
  </AppLayout>
);
