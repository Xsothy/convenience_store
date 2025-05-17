
import React, { useState, useEffect } from 'react';
import { Heart } from 'lucide-react';
import { Link } from '@inertiajs/react';

interface ArtworkCardProps {
  id: number;
  title: string;
  artist: string;
  price: number;
  image: string;
  blurDataUrl?: string;
  isNew?: boolean;
  className?: string;
}

const ArtworkCard: React.FC<ArtworkCardProps> = ({
  id,
  title,
  artist,
  price,
  image,
  blurDataUrl,
  isNew = false,
  className = '',
}) => {
  const [isLoaded, setIsLoaded] = useState(false);
  const [isFavorite, setIsFavorite] = useState(false);

  const toggleFavorite = (e: React.MouseEvent) => {
    e.preventDefault();
    e.stopPropagation();
    setIsFavorite(!isFavorite);
  };

  return (
    <div className={`artwork-card group animate-scale ${className}`}>
      <Link href={`/artwork/${id}`} className="block overflow-hidden">
        <div
          className={`blur-load relative aspect-[3/4] ${isLoaded ? 'loaded' : ''}`}
          style={{ backgroundImage: blurDataUrl ? `url(${blurDataUrl})` : 'none' }}
        >
          {isNew && (
            <div className="absolute left-4 top-4 z-10 rounded-full bg-white px-3 py-1 text-xs font-medium">
              New
            </div>
          )}
          <button
            onClick={toggleFavorite}
            className="absolute right-4 top-4 z-10 rounded-full bg-white p-2 opacity-0 shadow-sm transition-opacity duration-300 hover:bg-gray-100 group-hover:opacity-100"
            aria-label={isFavorite ? 'Remove from favorites' : 'Add to favorites'}
          >
            <Heart
              size={16}
              className={isFavorite ? 'fill-black text-black' : 'text-gray-600'}
            />
          </button>
          <img
            src={image}
            alt={title}
            className="h-full w-full object-cover"
            loading="lazy"
            onLoad={() => setIsLoaded(true)}
          />
        </div>
      </Link>
      <div className="p-4">
        <h3 className="mb-1 text-lg font-medium leading-tight">
          <Link href={`/artwork/${id}`} className="hover:underline">
            {title}
          </Link>
        </h3>
        <p className="mb-2 text-sm text-gray-600">By {artist}</p>
        <p className="text-lg font-medium">${price}</p>
      </div>
    </div>
  );
};

export default ArtworkCard;
