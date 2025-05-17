
import React, { useState } from 'react';
import { ArrowRight } from 'lucide-react';
import { Link } from '@inertiajs/react';

interface FeaturedArtworkProps {
  id: number;
  title: string;
  artist: string;
  price: number;
  description: string;
  image: string;
}

const FeaturedArtwork: React.FC<FeaturedArtworkProps> = ({
  id,
  title,
  artist,
  price,
  description,
  image,
}) => {
  const [isImageLoaded, setIsImageLoaded] = useState(false);

  return (
    <section className="relative overflow-hidden">
      <div className="container mx-auto px-4 py-16 md:py-24">
        <div className="grid grid-cols-1 items-center gap-8 md:grid-cols-2 md:gap-16">
          <div className="order-2 animate-slide-up md:order-1">
            <div className="max-w-xl">
              <span className="mb-2 inline-block text-sm uppercase tracking-widest text-gray-500">
                Featured Artwork
              </span>
              <h2 className="mb-4 text-4xl font-bold leading-tight tracking-tight text-black sm:text-5xl">
                {title}
              </h2>
              <p className="mb-2 text-xl">By {artist}</p>
              <p className="mb-6 text-xl font-medium">${price}</p>
              <p className="mb-8 text-gray-600">
                {description}
              </p>
              <div className="flex flex-wrap gap-4">
                <Link
                  href={`/artwork/${id}`}
                  className="btn-primary inline-flex items-center"
                >
                  View Details
                  <ArrowRight size={18} className="ml-2" />
                </Link>
                <button className="rounded-full border border-black bg-white px-6 py-3 font-medium text-black transition-colors duration-300 hover:bg-gray-100">
                  Add to Cart
                </button>
              </div>
            </div>
          </div>
          <div className="order-1 overflow-hidden rounded-2xl md:order-2">
            <div
              className={`transform transition-all duration-500 ${isImageLoaded ? 'scale-100' : 'scale-105 blur-sm'}`}
            >
              <img
                src={image}
                alt={title}
                className="h-full w-full object-cover"
                onLoad={() => setIsImageLoaded(true)}
              />
            </div>
          </div>
        </div>
      </div>
    </section>
  );
};

export default FeaturedArtwork;
