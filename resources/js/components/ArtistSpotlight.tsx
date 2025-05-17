
import React from 'react';
import { Link } from '@inertiajs/react';

interface ArtistSpotlightProps {
  id: number;
  name: string;
  bio: string;
  image: string;
  artworks: {
    id: number;
    title: string;
    image: string;
  }[];
}

const ArtistSpotlight: React.FC<ArtistSpotlightProps> = ({
  id,
  name,
  bio,
  image,
  artworks,
}) => {
  return (
    <section className="bg-gray-50 py-16 md:py-24">
      <div className="container mx-auto px-4">
        <div className="mb-12 text-center">
          <span className="mb-2 inline-block text-sm uppercase tracking-widest text-gray-500">
            Artist Spotlight
          </span>
          <h2 className="text-3xl font-bold sm:text-4xl">Meet the Artist</h2>
        </div>

        <div className="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3">
          {/* Artist Profile */}
          <div className="row-span-2 animate-slide-up">
            <div className="overflow-hidden rounded-xl">
              <img
                src={image}
                alt={name}
                className="h-80 w-full object-cover object-center lg:h-96"
              />
            </div>
            <div className="mt-6">
              <h3 className="mb-2 text-2xl font-bold">{name}</h3>
              <p className="text-gray-600">{bio}</p>
              <Link
                href={`/artists/${id}`}
                className="mt-4 inline-flex items-center font-medium text-black underline-offset-4 hover:underline"
              >
                View Artist Profile
              </Link>
            </div>
          </div>

          {/* Artworks */}
          {artworks.map((artwork, index) => (
            <div
              key={artwork.id}
              className={`animate-scale [animation-delay:${index * 100}ms]`}
            >
              <Link href={`/artwork/${artwork.id}`} className="block overflow-hidden rounded-xl">
                <img
                  src={artwork.image}
                  alt={artwork.title}
                  className="h-80 w-full object-cover transition-transform duration-500 hover:scale-105"
                />
              </Link>
              <div className="mt-4">
                <Link
                  href={`/artwork/${artwork.id}`}
                  className="text-lg font-medium hover:underline"
                >
                  {artwork.title}
                </Link>
                <p className="text-gray-600">By {name}</p>
              </div>
            </div>
          ))}
        </div>
      </div>
    </section>
  );
};

export default ArtistSpotlight;
