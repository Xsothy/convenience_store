import React from 'react';
import { Link } from '@inertiajs/react';
import { ChevronLeft, ChevronRight } from 'lucide-react';

interface PaginationProps {
  meta: {
    current_page: number;
    last_page: number;
    total: number;
    per_page: number;
  };
  baseUrl: string;
  filters?: Record<string, string | number | boolean | undefined>;
}

const Pagination: React.FC<PaginationProps> = ({ meta, baseUrl, filters = {} }) => {
  if (meta.last_page <= 1) {
    return null;
  }

  // Create the filtered query string
  const queryParams = new URLSearchParams();
  Object.entries(filters).forEach(([key, value]) => {
    if (value !== undefined && value !== '') {
      queryParams.set(key, String(value));
    }
  });
  const filterString = queryParams.toString() ? `&${queryParams.toString()}` : '';

  // Create array of page numbers to display
  let pages: (number | string)[] = [];
  
  if (meta.last_page <= 7) {
    // Show all pages if 7 or fewer
    pages = Array.from({ length: meta.last_page }, (_, i) => i + 1);
  } else {
    // Always include first and last page
    if (meta.current_page <= 3) {
      // Near the start
      pages = [1, 2, 3, 4, 5, '...', meta.last_page];
    } else if (meta.current_page >= meta.last_page - 2) {
      // Near the end
      pages = [1, '...', meta.last_page - 4, meta.last_page - 3, meta.last_page - 2, meta.last_page - 1, meta.last_page];
    } else {
      // Somewhere in the middle
      pages = [
        1, 
        '...', 
        meta.current_page - 1, 
        meta.current_page, 
        meta.current_page + 1, 
        '...', 
        meta.last_page
      ];
    }
  }

  const getPageLink = (page: number | string) => {
    if (typeof page === 'string') {
      return null; // Return null for ellipsis to render as non-link
    }
    
    return `${baseUrl}?page=${page}${filterString}`;
  };

  return (
    <div className="mt-8 flex items-center justify-between">
      <div className="text-sm text-gray-500">
        Showing {(meta.current_page - 1) * meta.per_page + 1} to{' '}
        {Math.min(meta.current_page * meta.per_page, meta.total)} of {meta.total} results
      </div>
      
      <nav className="flex items-center space-x-1">
        {/* Previous page button */}
        {meta.current_page > 1 ? (
          <Link
            href={getPageLink(meta.current_page - 1) || '#'}
            className="rounded border border-gray-300 px-2 py-1 text-sm text-gray-500 hover:bg-gray-50"
          >
            <ChevronLeft size={18} />
          </Link>
        ) : (
          <span className="rounded border border-gray-200 px-2 py-1 text-sm text-gray-300 cursor-not-allowed">
            <ChevronLeft size={18} />
          </span>
        )}

        {/* Page numbers */}
        {pages.map((page, index) => (
          <React.Fragment key={`${page}-${index}`}>
            {typeof page === 'string' ? (
              <span className="px-2 py-1 text-gray-500">...</span>
            ) : (
              <Link
                href={getPageLink(page) || '#'}
                className={`rounded px-3 py-1 text-sm ${
                  page === meta.current_page
                    ? 'bg-primary text-white'
                    : 'text-gray-500 hover:bg-gray-50'
                }`}
              >
                {page}
              </Link>
            )}
          </React.Fragment>
        ))}

        {/* Next page button */}
        {meta.current_page < meta.last_page ? (
          <Link
            href={getPageLink(meta.current_page + 1) || '#'}
            className="rounded border border-gray-300 px-2 py-1 text-sm text-gray-500 hover:bg-gray-50"
          >
            <ChevronRight size={18} />
          </Link>
        ) : (
          <span className="rounded border border-gray-200 px-2 py-1 text-sm text-gray-300 cursor-not-allowed">
            <ChevronRight size={18} />
          </span>
        )}
      </nav>
    </div>
  );
};

export default Pagination; 