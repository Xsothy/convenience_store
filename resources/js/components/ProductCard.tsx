import React, { useEffect, useState } from 'react';
import { Clock, Heart } from 'lucide-react';
import { Link } from '@inertiajs/react';
import { useTimeExpired } from '@/hooks/use-time-expired';
import ThemeController from '@/wayfinder/actions/App/Http/Controllers/ThemeController';
import ArtworkController from '@/wayfinder/actions/App/Http/Controllers/ArtworkController';

interface ProductCardProps {
    blurDataUrl?: string;
    className?: string;
    product: App.Data.ProductData;
}

const ProductCard: React.FC<ProductCardProps> = ({
    blurDataUrl,
    className = '',
    product
}) => {
    const [isLoaded, setIsLoaded] = useState(false);
    const [isFavorite, setIsFavorite] = useState(false);

    const [timeRemaining, isDiscountExpired] = useTimeExpired(product.discount_to);
    const toggleFavorite = (e: React.MouseEvent) => {
        e.preventDefault();
        e.stopPropagation();
        setIsFavorite(!isFavorite);
    };

    return (
        <div className={`product-card group animate-scale ${className}`}>
            <Link href={ArtworkController.show({ slug: product.slug })} className="block overflow-hidden" prefetch={'mount'}>
                <div
                    className={`blur-load relative aspect-[3/4] ${isLoaded ? 'loaded' : ''}`}
                    style={{ backgroundImage: blurDataUrl ? `url(${blurDataUrl})` : 'none' }}
                >
                    {/*{product.is_digital && (*/}
                    {/*    <div className="absolute left-4 top-4 z-10 rounded-full bg-white px-3 py-1 text-xs font-medium">*/}
                    {/*        New*/}
                    {/*    </div>*/}
                    {/*)}*/}
                    {product.discount ? (
                        <div className="absolute left-4 top-4 z-10 rounded-full bg-red-500 px-3 py-1 text-xs font-medium text-white">
                            -{product.discount}%
                        </div>
                    ) : null}
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

                    {/* Countdown overlay */}
                    {(product.discount && product.discount_to && !isDiscountExpired && timeRemaining.days >= 0) ? (
                        <div className="absolute bottom-0 left-0 right-0 z-10 bg-black bg-opacity-70 p-3 text-white">
                            <div className="flex items-center justify-center gap-1 mb-1">
                                <Clock size={14} className="mr-1" />
                                <span className="text-xs font-medium">Offer ends in:</span>
                            </div>
                            <div className="flex justify-center space-x-2 text-xs">
                                <div className="flex flex-col items-center">
                                    <span className="font-bold">{timeRemaining.days}</span>
                                    <span>Days</span>
                                </div>
                                <div className="font-bold">:</div>
                                <div className="flex flex-col items-center">
                                    <span className="font-bold">{timeRemaining.hours}</span>
                                    <span>Hrs</span>
                                </div>
                                <div className="font-bold">:</div>
                                <div className="flex flex-col items-center">
                                    <span className="font-bold">{timeRemaining.minutes}</span>
                                    <span>Min</span>
                                </div>
                                <div className="font-bold">:</div>
                                <div className="flex flex-col items-center">
                                    <span className="font-bold">{timeRemaining.seconds}</span>
                                    <span>Sec</span>
                                </div>
                            </div>
                        </div>
                    ) : null}

                    <img
                        src={product.feature_image ?? ''}
                        alt={product.name ?? ''}
                        className="h-full w-full object-cover"
                        loading="lazy"
                        onLoad={() => setIsLoaded(true)}
                    />
                </div>
            </Link>
            <div className="p-4">
                <div className="flex gap-2">
                    {product.category ? (
                        <Link href={ThemeController.show({slug: product.category.slug ?? ''})} className="mb-1 block text-xs text-gray-500 hover:underline" key={product.category.slug ?? ''} preserveScroll prefetch>
                            {product.category.name}
                        </Link>
                    ) : "Uncategorized"}
                </div>
                <h3 className="mb-1 text-lg font-medium leading-tight">
                    <Link href={ArtworkController.show({ slug: product.slug })} className="hover:underline" preserveScroll prefetch>
                        {product.name}
                    </Link>
                </h3>
                <div className="flex items-center justify-between">
                    <div>
                        {product.discount && product.price ? (
                            <div className="flex items-center gap-2">
                                <p className="text-lg font-medium">${(product.price * (1 - product.discount / 100)).toFixed(2)}</p>
                                <p className="text-sm text-gray-500 line-through">${product.price.toFixed(2)}</p>
                            </div>
                        ) : (
                            <p className="text-lg font-medium">${product.price?.toFixed(2)}</p>
                        )}
                    </div>
                    {/*<button*/}
                    {/*    className="flex items-center justify-center rounded-full bg-black p-2 text-white transition-transform hover:scale-105"*/}
                    {/*    aria-label="Add to cart"*/}
                    {/*>*/}
                    {/*    <ShoppingBag onClick={() => addToCart(product.id as number)} className="cursor-pointer" size={16} />*/}
                    {/*</button>*/}
                </div>
            </div>
        </div>
    );
};

export default ProductCard;
