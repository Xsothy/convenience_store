import React, { useState } from 'react';
import { router } from '@inertiajs/react';
import { Filter, X, ChevronDown } from 'lucide-react';

interface FilterOption {
  label: string;
  value: string;
}

interface PriceRange {
  min: string;
  max: string;
}

interface FilterState {
  category?: string;
  style?: string;
  color?: string;
  price?: string;
  size?: string;
  [key: string]: string | undefined;
}

interface ProductFilterProps {
  categories: FilterOption[];
  styles?: FilterOption[];
  colors?: FilterOption[];
  sizes?: FilterOption[];
  currentFilters: FilterState;
  baseUrl: string;
}

const ProductFilter: React.FC<ProductFilterProps> = ({
  categories,
  styles = [],
  colors = [],
  sizes = [],
  currentFilters,
  baseUrl,
}) => {
  const [filters, setFilters] = useState<FilterState>(currentFilters);
  const [priceRange, setPriceRange] = useState<PriceRange>(() => {
    const range = filters.price?.split('-') || ['', ''];
    return { min: range[0] || '', max: range[1] || '' };
  });
  const [mobileFiltersOpen, setMobileFiltersOpen] = useState(false);

  // Common price ranges
  const priceRanges = [
    { label: 'Under $50', value: '0-50' },
    { label: '$50 to $100', value: '50-100' },
    { label: '$100 to $200', value: '100-200' },
    { label: '$200 to $500', value: '200-500' },
    { label: 'Over $500', value: '500-' },
  ];

  const handleFilterChange = (key: string, value: string) => {
    const newFilters = { ...filters };
    
    // Clear the filter if selecting the empty option
    if (value === '') {
      if (key in newFilters) {
        delete newFilters[key];
      }
    } else {
      newFilters[key] = value;
    }
    
    setFilters(newFilters);
    applyFilters(newFilters);
  };

  const handlePriceRangeChange = (range: string) => {
    // Update price range state
    const [min, max] = range.split('-');
    setPriceRange({ min: min || '', max: max || '' });
    
    // Update filters
    const newFilters = { ...filters };
    if (range === '') {
      if ('price' in newFilters) {
        delete newFilters.price;
      }
    } else {
      newFilters.price = range;
    }
    
    setFilters(newFilters);
    applyFilters(newFilters);
  };

  const handleCustomPriceRange = () => {
    if (!priceRange.min && !priceRange.max) {
      // If both are empty, clear the price filter
      const newFilters = { ...filters };
      if ('price' in newFilters) {
        delete newFilters.price;
      }
      setFilters(newFilters);
      applyFilters(newFilters);
      return;
    }
    
    const range = `${priceRange.min || '0'}-${priceRange.max || ''}`;
    const newFilters = { ...filters, price: range };
    setFilters(newFilters);
    applyFilters(newFilters);
  };

  const clearFilters = () => {
    setFilters({});
    setPriceRange({ min: '', max: '' });
    applyFilters({});
  };

  const applyFilters = (newFilters: FilterState) => {
    // Convert filters to URL query params
    router.visit(`${baseUrl}?${new URLSearchParams(newFilters as Record<string, string>).toString()}`);
  };

  const hasActiveFilters = Object.keys(filters).length > 0;

  // Filter sections for desktop view
  const FilterSection = ({ 
    title, 
    options, 
    filterKey 
  }: { 
    title: string; 
    options: FilterOption[]; 
    filterKey: string; 
  }) => (
    <div className="border-b border-gray-200 py-6">
      <h3 className="mb-2 flex items-center justify-between text-sm font-medium text-gray-900">
        <span>{title}</span>
        <ChevronDown size={16} className="text-gray-500" />
      </h3>
      <div className="space-y-2 pt-2">
        <div className="flex items-center">
          <input
            id={`${filterKey}-all`}
            name={filterKey}
            value=""
            type="radio"
            checked={!filters[filterKey]}
            onChange={() => handleFilterChange(filterKey, '')}
            className="h-4 w-4 rounded border-gray-300 text-primary focus:ring-primary"
          />
          <label htmlFor={`${filterKey}-all`} className="ml-3 text-sm text-gray-600">
            All
          </label>
        </div>
        
        {options.map((option) => (
          <div key={option.value} className="flex items-center">
            <input
              id={`${filterKey}-${option.value}`}
              name={filterKey}
              value={option.value}
              type="radio"
              checked={filters[filterKey] === option.value}
              onChange={() => handleFilterChange(filterKey, option.value)}
              className="h-4 w-4 rounded border-gray-300 text-primary focus:ring-primary"
            />
            <label htmlFor={`${filterKey}-${option.value}`} className="ml-3 text-sm text-gray-600">
              {option.label}
            </label>
          </div>
        ))}
      </div>
    </div>
  );

  return (
    <div className="bg-white">
      {/* Mobile filter dialog */}
      <div 
        className={`fixed inset-0 z-40 flex ${mobileFiltersOpen ? 'block' : 'hidden'}`} 
        onClick={() => setMobileFiltersOpen(false)}
      >
        <div className="fixed inset-0 bg-black bg-opacity-25" aria-hidden="true" />
        
        <div className="relative ml-auto flex h-full w-full max-w-xs flex-col overflow-y-auto bg-white py-4 pb-6 shadow-xl sm:max-w-sm" onClick={e => e.stopPropagation()}>
          <div className="flex items-center justify-between px-4">
            <h2 className="text-lg font-medium text-gray-900">Filters</h2>
            <button
              type="button"
              className="-mr-2 flex h-10 w-10 items-center justify-center rounded-md p-2 text-gray-500 hover:bg-gray-100"
              onClick={() => setMobileFiltersOpen(false)}
            >
              <X size={20} aria-hidden="true" />
            </button>
          </div>

          {/* Mobile filter sections */}
          <div className="mt-4 border-t border-gray-200">
            {categories.length > 0 && (
              <FilterSection title="Categories" options={categories} filterKey="category" />
            )}
            
            {styles.length > 0 && (
              <FilterSection title="Styles" options={styles} filterKey="style" />
            )}
            
            <div className="border-b border-gray-200 py-6">
              <h3 className="mb-2 flex items-center justify-between text-sm font-medium text-gray-900">
                <span>Price</span>
                <ChevronDown size={16} className="text-gray-500" />
              </h3>
              <div className="space-y-2 pt-2">
                <div className="flex items-center">
                  <input
                    id="price-all"
                    name="price"
                    value=""
                    type="radio"
                    checked={!filters.price}
                    onChange={() => handlePriceRangeChange('')}
                    className="h-4 w-4 rounded border-gray-300 text-primary focus:ring-primary"
                  />
                  <label htmlFor="price-all" className="ml-3 text-sm text-gray-600">
                    Any price
                  </label>
                </div>
                
                {priceRanges.map((range) => (
                  <div key={range.value} className="flex items-center">
                    <input
                      id={`price-${range.value}`}
                      name="price"
                      value={range.value}
                      type="radio"
                      checked={filters.price === range.value}
                      onChange={() => handlePriceRangeChange(range.value)}
                      className="h-4 w-4 rounded border-gray-300 text-primary focus:ring-primary"
                    />
                    <label htmlFor={`price-${range.value}`} className="ml-3 text-sm text-gray-600">
                      {range.label}
                    </label>
                  </div>
                ))}
                
                {/* Custom price range */}
                <div className="mt-4 flex flex-col space-y-2">
                  <label className="text-sm font-medium text-gray-700">Custom range</label>
                  <div className="flex items-center space-x-2">
                    <div className="relative mt-1 rounded-md shadow-sm flex-1">
                      <div className="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                        <span className="text-gray-500 sm:text-sm">$</span>
                      </div>
                      <input
                        type="number"
                        name="price-min"
                        id="price-min"
                        value={priceRange.min}
                        onChange={(e) => setPriceRange(prev => ({ ...prev, min: e.target.value }))}
                        onBlur={handleCustomPriceRange}
                        className="block w-full rounded-md border-gray-300 pl-7 pr-2 py-1 focus:border-primary focus:ring-primary text-sm"
                        placeholder="Min"
                      />
                    </div>
                    <span className="text-gray-500">-</span>
                    <div className="relative mt-1 rounded-md shadow-sm flex-1">
                      <div className="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                        <span className="text-gray-500 sm:text-sm">$</span>
                      </div>
                      <input
                        type="number"
                        name="price-max"
                        id="price-max"
                        value={priceRange.max}
                        onChange={(e) => setPriceRange(prev => ({ ...prev, max: e.target.value }))}
                        onBlur={handleCustomPriceRange}
                        className="block w-full rounded-md border-gray-300 pl-7 pr-2 py-1 focus:border-primary focus:ring-primary text-sm"
                        placeholder="Max"
                      />
                    </div>
                  </div>
                </div>
              </div>
            </div>
            
            {colors.length > 0 && (
              <FilterSection title="Colors" options={colors} filterKey="color" />
            )}
            
            {sizes?.length > 0 && (
              <FilterSection title="Sizes" options={sizes} filterKey="size" />
            )}
          </div>
        </div>
      </div>

      {/* Desktop filter panel */}
      <section aria-labelledby="filter-heading" className="border-b border-gray-200 pb-4">
        <h2 id="filter-heading" className="sr-only">Product filters</h2>

        <div className="flex items-center justify-between py-4">
          <div className="flex items-center space-x-8">
            <button
              type="button"
              className="flex items-center space-x-2 text-sm font-medium text-gray-700 hover:text-gray-900"
              onClick={() => setMobileFiltersOpen(true)}
            >
              <Filter size={16} aria-hidden="true" />
              <span>Filters</span>
            </button>
            
            {/* Active filters */}
            {hasActiveFilters && (
              <div className="flex flex-wrap items-center gap-2">
                {Object.entries(filters).map(([key, value]) => (
                  <button
                    key={key}
                    onClick={() => handleFilterChange(key, '')}
                    className="inline-flex items-center rounded-full bg-gray-100 py-1 pl-2.5 pr-1 text-xs font-medium text-gray-700"
                  >
                    {key}: {key === 'price' 
                      ? formatPriceFilter(value || '') 
                      : getOptionLabel(key, value || '')}
                    <X size={14} className="ml-1" aria-hidden="true" />
                  </button>
                ))}
                
                <button
                  onClick={clearFilters}
                  className="text-sm font-medium text-primary hover:text-indigo-800"
                >
                  Clear all
                </button>
              </div>
            )}
          </div>
          
          {/* Sort options (placeholder) */}
          <div className="hidden sm:block">
            <select
              id="sort-by"
              name="sort-by"
              className="block w-full rounded-md border-gray-300 py-1.5 pr-8 text-sm focus:border-primary focus:ring-primary"
              defaultValue="featured"
            >
              <option value="featured">Featured</option>
              <option value="bestselling">Best Selling</option>
              <option value="newest">Newest</option>
              <option value="price-asc">Price: Low to High</option>
              <option value="price-desc">Price: High to Low</option>
            </select>
          </div>
        </div>
      </section>
    </div>
  );
  
  // Helper function to format price filter for display
  function formatPriceFilter(priceFilter: string): string {
    const [min, max] = priceFilter.split('-');
    if (min && !max) return `$${min}+`;
    if (!min && max) return `Up to $${max}`;
    return `$${min} - $${max}`;
  }
  
  // Helper function to get option label from value
  function getOptionLabel(filterKey: string, value: string): string {
    let options: FilterOption[] = [];
    
    switch (filterKey) {
      case 'category':
        options = categories;
        break;
      case 'style':
        options = styles;
        break;
      case 'color':
        options = colors;
        break;
      case 'size':
        options = sizes;
        break;
    }
    
    const option = options.find(opt => opt.value === value);
    return option?.label || value;
  }
};

export default ProductFilter; 