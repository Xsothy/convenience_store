import React from 'react';
import { Head, Link } from '@inertiajs/react';
import { WeddingLayout } from '@/layouts';

interface WeddingCategory {
  id: number;
  title: string;
  slug: string;
  description: string;
  image?: string;
}

interface WeddingIndexProps {
  categories?: WeddingCategory[];
  products?: {
    data: App.Data.ProductData[];
  };
}

export default function WeddingIndex({ categories = [], products }: WeddingIndexProps) {
  // Sample categories if none provided
  const weddingCategories = categories.length > 0 ? categories : [
    {
      id: 1,
      title: 'Invitations',
      slug: 'invitations',
      description: 'Beautiful wedding invitations in a variety of styles.',
      image: 'https://images.unsplash.com/photo-1607344645866-009c320c5ab0?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&h=600&q=80'
    },
    {
      id: 2,
      title: 'Save The Dates',
      slug: 'save-the-dates',
      description: 'Make sure your guests mark their calendars with these beautiful save the date cards.',
      image: 'https://images.unsplash.com/photo-1510070112810-d4e9a46d9e91?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&h=600&q=80'
    },
    {
      id: 3,
      title: 'Wedding Websites',
      slug: 'websites',
      description: 'Share all your wedding details with a custom wedding website.',
      image: 'https://images.unsplash.com/photo-1587614382231-d11f9371a10f?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&h=600&q=80'
    },
    {
      id: 4,
      title: 'Thank You Cards',
      slug: 'thank-you-cards',
      description: 'Express your gratitude with elegant thank you cards.',
      image: 'https://images.unsplash.com/photo-1606503825008-909a67e63c3d?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&h=600&q=80'
    }
  ];

  return (
    <WeddingLayout title="Wedding Collection">
      <Head title="Wedding Collection" />

      <div className="py-12">
        <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <h1 className="text-3xl font-bold text-gray-900 mb-8">Wedding Collection</h1>

          <p className="text-lg text-gray-600 max-w-3xl mb-12">
            Make your special day even more memorable with our beautifully designed wedding stationery and websites, created by independent artists.
          </p>

          {/* Featured banner */}
          <div className="mb-16 relative overflow-hidden rounded-lg">
            <img
              src="https://images.unsplash.com/photo-1549116472-5813b4d6a10b?ixlib=rb-4.0.3&auto=format&fit=crop&w=1400&h=500&q=80"
              alt="Wedding collection"
              className="w-full h-[400px] object-cover"
            />
            <div className="absolute inset-0 bg-black bg-opacity-40 flex flex-col justify-center items-center text-center p-6">
              <h2 className="text-4xl font-bold text-white mb-4">Custom Wedding Websites</h2>
              <p className="text-xl text-white mb-6 max-w-2xl">Share your story and wedding details with a beautifully designed custom website.</p>
              <Link
                href="/wedding/websites"
                className="bg-white text-gray-900 px-8 py-3 rounded-md font-medium hover:bg-gray-100 transition duration-150"
              >
                Build Your Website
              </Link>
            </div>
          </div>

          {/* Wedding categories */}
          <div className="grid grid-cols-1 md:grid-cols-2 gap-8 mb-16">
            {weddingCategories.map((category) => (
              <Link key={category.id} href={`/wedding/${category.slug}`}>
                <div className="group relative overflow-hidden rounded-lg">
                  <img
                    src={category.image}
                    alt={category.title}
                    className="w-full h-64 object-cover transition duration-300 group-hover:scale-105"
                  />
                  <div className="absolute inset-0 bg-black bg-opacity-20 group-hover:bg-opacity-30 transition duration-300 flex flex-col justify-end p-6">
                    <h3 className="text-2xl font-bold text-white mb-2">{category.title}</h3>
                    <p className="text-white">{category.description}</p>
                  </div>
                </div>
              </Link>
            ))}
          </div>

          {/* Featured wedding products */}
          {products?.data && products.data.length > 0 && (
            <div className="mb-16">
              <h2 className="text-2xl font-bold text-gray-900 mb-8">Featured Wedding Products</h2>
              <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
                {products.data.slice(0, 4).map((product) => (
                  <div key={product.id} className="group">
                    <Link href={`/wedding/products/${product.slug}`}>
                      <div className="aspect-h-1 aspect-w-1 w-full overflow-hidden rounded-lg bg-gray-200 xl:aspect-h-8 xl:aspect-w-7">
                        <img
                          src={product.feature_image || 'https://images.unsplash.com/photo-1525909002-1b05e0c869d8?ixlib=rb-4.0.3&auto=format&fit=crop&w=375&h=375&q=80'}
                          alt={product.name as string}
                          className="h-full w-full object-cover object-center group-hover:opacity-75"
                        />
                      </div>
                      <h3 className="mt-4 text-lg font-medium text-gray-900">{product.name}</h3>
                      <div className="mt-1 flex items-center">
                        {product.discount > 0 ? (
                          <>
                            <p className="text-lg font-medium text-red-600">${(product.price - product.discount).toFixed(2)}</p>
                            <p className="ml-2 text-sm text-gray-500 line-through">${product.price.toFixed(2)}</p>
                          </>
                        ) : (
                          <p className="text-lg font-medium text-gray-900">${product.price.toFixed(2)}</p>
                        )}
                      </div>
                    </Link>
                  </div>
                ))}
              </div>
            </div>
          )}

          {/* Testimonials */}
          <div className="mt-20 bg-gray-50 rounded-lg p-8">
            <h3 className="text-2xl font-bold text-center mb-8">What Our Couples Say</h3>

            <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
              <div className="bg-white p-6 rounded-lg shadow-sm">
                <div className="flex items-center mb-4">
                  <div className="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 font-semibold mr-3">
                    J&A
                  </div>
                  <div>
                    <h4 className="font-medium">Jessica & Andrew</h4>
                    <p className="text-sm text-gray-500">Wedding: June 2023</p>
                  </div>
                </div>
                <p className="text-gray-600">"We loved our wedding website! It was so easy to create and our guests found it incredibly helpful. Highly recommend!"</p>
              </div>

              <div className="bg-white p-6 rounded-lg shadow-sm">
                <div className="flex items-center mb-4">
                  <div className="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 font-semibold mr-3">
                    S&M
                  </div>
                  <div>
                    <h4 className="font-medium">Sarah & Michael</h4>
                    <p className="text-sm text-gray-500">Wedding: September 2023</p>
                  </div>
                </div>
                <p className="text-gray-600">"The invitations we ordered were absolutely beautiful. The quality was outstanding and they arrived earlier than expected."</p>
              </div>

              <div className="bg-white p-6 rounded-lg shadow-sm">
                <div className="flex items-center mb-4">
                  <div className="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 font-semibold mr-3">
                    L&J
                  </div>
                  <div>
                    <h4 className="font-medium">Laura & James</h4>
                    <p className="text-sm text-gray-500">Wedding: August 2023</p>
                  </div>
                </div>
                <p className="text-gray-600">"The custom design service was worth every penny. They captured our vision perfectly and created stationery that matched our theme."</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </WeddingLayout>
  );
}
