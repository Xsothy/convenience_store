import React from 'react';
import { ArtworkLayout, AppLayout } from '@/layouts';
import ProductGrid from '@/components/ProductGrid';

const Show = ({ category }: { category: App.Data.CategoryData }) => {
    const products = category.products;
    
    return (
      <>
          {/* Category Header with improved design */}
          <div className="relative py-16 bg-gray-50 mb-12 overflow-hidden">
              <div className="absolute top-0 inset-x-0 h-1/2 bg-gradient-to-b from-gray-100 to-transparent"></div>
              <div className="container max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
                  <div className="text-center max-w-3xl mx-auto">
                      <h1 className="text-4xl md:text-5xl font-bold text-gray-900 mb-4">{category.name}</h1>
                      <p className="text-lg md:text-xl text-gray-600 mx-auto max-w-2xl">
                          {category.description || 'Explore our collection of beautiful artwork in this category.'}
                      </p>
                  </div>
              </div>
          </div>

          {/* Products Grid */}
          <div className="container max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
              <ProductGrid 
                products={products}
                gridCols="grid-cols-3"
                emptyStateMessage="There are no products in this category yet."
              />
          </div>
      </>
  );
};

Show.layout = page => (
    <AppLayout>
        <ArtworkLayout children={page} />
    </AppLayout>
)

export default Show; 