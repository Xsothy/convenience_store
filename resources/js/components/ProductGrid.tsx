import React from 'react';
import ProductCard from '@/components/ProductCard';

interface ProductGridProps {
  products: App.Data.ProductData[];
  showCount?: boolean;
  gridCols?: 'grid-cols-2' | 'grid-cols-3' | 'grid-cols-4';
  emptyStateMessage?: string;
}

const ProductGrid: React.FC<ProductGridProps> = ({
  products,
  showCount = true,
  gridCols = 'grid-cols-4',
  emptyStateMessage = 'No products found',
}) => {
  if (!products || products.length === 0) {
    return (
      <div className="py-8 sm:py-12 text-center">
        <h2 className="mb-2 text-xl sm:text-2xl font-bold">{emptyStateMessage}</h2>
        <p className="text-gray-600 text-sm sm:text-base">Try adjusting your filters or check back later.</p>
      </div>
    );
  }

  return (
    <div>
      {showCount && (
        <p className="mb-4 md:mb-6 text-xs sm:text-sm text-gray-600">Showing {products.length} products</p>
      )}

      <div className={`grid grid-cols-2 gap-x-3 gap-y-6 sm:gap-x-4 sm:gap-y-8 md:gap-6 lg:gap-8 ${
        gridCols === 'grid-cols-2' ? 'md:grid-cols-2 lg:grid-cols-2' : 
        gridCols === 'grid-cols-3' ? 'md:grid-cols-3 lg:grid-cols-3' : 
        'md:grid-cols-3 lg:grid-cols-4'
      }`}>
        {products.map((product) => (
          <ProductCard 
            key={product.id || 0} 
            product={product}
            className="h-full"
          />
        ))}
      </div>
    </div>
  );
};

export default ProductGrid; 