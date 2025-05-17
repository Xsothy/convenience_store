import VariationSelector from '@/components/variation-selector';
import { ArtworkLayout, AppLayout } from '@/layouts';
import { useCartStore } from '@/stores/useCartStore';
import { Link } from '@inertiajs/react';
import { Clock, Minus, Plus, ShoppingBag, ArrowLeft } from 'lucide-react';
import React, { useMemo, useState } from 'react';
import ProductCard from '@/components/ProductCard';
import { useTimeExpired } from '@/hooks/use-time-expired';
import { Carousel, CarouselContent, CarouselItem } from '@/components/ui/carousel';
import ArtworkController from '@/wayfinder/actions/App/Http/Controllers/ArtworkController';

const Show = ({ product }: { product: App.Data.ProductData }) => {
    const [selectedImage, setSelectedImage] = useState(0);
    const [quantity, setQuantity] = useState(1);
    const [isImageLoaded, setIsImageLoaded] = useState(false);
    const [selectedVariations, setSelectedVariations] = useState<Record<string, string>>({});
    const relatedProducts = product.category?.products?.filter(p => p.id !== product.id) || [];

    const [timeRemaining, isDiscountExpired] = useTimeExpired(product.discount_to);

    const cartStore = useCartStore();
    const addToCart = (id: number, quantity: number = 1) => {
        cartStore.addCart(id, quantity, selectedVariations['Variation']);
    }


    // --- Price Calculation Logic ---
    const { finalSellingPrice, originalPrice, isDiscountActive, savingPercentage } = useMemo(() => {
        // Find the selected variation details, if any
        const variationName = product.variations?.[0]?.name || '';
        const selectedValueKey = selectedVariations[variationName] || '';
        const selectedVariationValue = product.variations?.[0]?.value.find(
            (v) => v.value === selectedValueKey
        );

        // Determine the applicable ORIGINAL price (from base product or variation)
        // Assumes product.price and variation.price are the prices BEFORE discount
        const applicableOriginalPrice = parseFloat(
            (selectedVariationValue?.price ?? product.price).toFixed(2)
        );

        // Check if the timed percentage discount is active
        const discountPercent = product.discount ?? 0; // Get discount percentage
        const isDiscountActive =
            !isDiscountExpired &&          // Timer hasn't run out
            product.discount_to &&         // There is an end date
            discountPercent > 0;           // The discount percentage is valid

        // Calculate the final selling price based on the active discount
        let calculatedFinalPrice: number;
        if (isDiscountActive) {
            // Apply percentage discount to the original price
            calculatedFinalPrice = applicableOriginalPrice * (1 - discountPercent / 100);
        } else {
            // No active discount, selling price IS the original price
            calculatedFinalPrice = applicableOriginalPrice;
        }
        // Ensure final price is formatted correctly
        const finalSellingPrice = parseFloat(calculatedFinalPrice.toFixed(2));

        // Original price for display is always the applicable one before potential discount
        const originalPrice = applicableOriginalPrice;
        // Saving percentage is directly from the product data if the discount is active
        const savingPercentage = isDiscountActive ? Math.round(discountPercent) : 0; // Use Math.round for cleaner display

        return { finalSellingPrice, originalPrice, isDiscountActive, savingPercentage };
    }, [product, selectedVariations, isDiscountExpired]);
    // --- End Price Calculation Logic ---


    const increaseQuantity = () => setQuantity((prev) => prev + 1);
    const decreaseQuantity = () => setQuantity((prev) => (prev > 1 ? prev - 1 : 1));

    const handleVariationChange = (variationName: string, value: string) => {
        setSelectedVariations((prev) => ({
            ...prev,
            [variationName]: value,
        }));
    };

    const productImages = [product.feature_image, ...product.gallery_images];

    return (
        <>
            <div className="container mx-auto px-4 pt-32 md:pt-40">
                <Link
                    href={ArtworkController.index()}
                    className="inline-flex items-center text-sm text-gray-700 hover:underline mb-6"
                >
                    <ArrowLeft size={16} className="mr-2" />
                    Back to List
                </Link>
                {/* Product Detail Grid */}
                <div className="grid grid-cols-1 gap-12 lg:grid-cols-2">
                    {/* Product Images */}
                    <div className="space-y-4">
                        <div className="overflow-hidden rounded-lg bg-gray-50">
                            <div
                                className={`relative aspect-[3/4] transform transition-all duration-700 ${
                                    isImageLoaded ? 'blur-0 scale-100' : 'scale-105 blur-sm'
                                }`}
                            >
                                <img
                                    src={productImages[selectedImage] ?? ''}
                                    alt={product.name ?? ''}
                                    className="h-full w-full object-cover"
                                    onLoad={() => setIsImageLoaded(true)}
                                    loading="lazy" // Added lazy loading
                                />
                            </div>
                        </div>
                        <Carousel
                            opts={{
                                loop: true,
                                dragFree: true
                            }}
                        >
                            <CarouselContent>
                                {productImages.map((image, index) => (
                                    <CarouselItem key={index} className={"basis-1/4 h-40 w-20 overflow-hidden"}>
                                        <button
                                            onClick={() => setSelectedImage(index)}
                                            className={`flex-shrink-0 overflow-hidden rounded-lg ${selectedImage === index ? 'opacity-100' : 'opacity-70'}`}
                                        >
                                            <img src={image ?? ''} alt={`${product.name} - view ${index + 1}`} className="object-cover" loading="lazy" />
                                        </button>
                                    </CarouselItem>
                                ))}
                            </CarouselContent>
                        </Carousel>
                    </div>

                    {/* Product Info */}
                    <div className="lg:sticky lg:top-40"> {/* Adjusted sticky top */}
                        <div className="animate-slide-up space-y-5"> {/* Adjusted spacing */}
                            {/* Categories and Title */}
                            <div>
                                <div className="mb-1 flex flex-wrap gap-x-2 gap-y-1"> {/* Wrap categories */}
                                    {product.category ? (
                                        <Link
                                            href={`/collections/${product.category.slug}`}
                                            className="text-xs text-gray-500 hover:underline uppercase tracking-wider" // Adjusted style
                                            key={product.category.slug}
                                        >
                                            {product.category.name}
                                        </Link>
                                    ) : "Uncategorized"}
                                </div>
                                <h1 className="mb-2 text-3xl font-bold sm:text-4xl">{product.name}</h1>
                            </div>

                            {/* Artist Information */}
                            {product.artist && (
                                <div className="flex items-center space-x-3 mb-4">
                                    <span className="text-sm text-gray-600">By:</span>
                                    <span className="font-medium">{product.artist.name}</span>
                                </div>
                            )}

                            {/* Countdown Timer - Enhanced (Place before price) */}
                            {isDiscountActive && ( // Render countdown only if discount is active
                                <div className="rounded-md border border-orange-200 bg-orange-50 p-3 text-center shadow-sm">
                                    <div className="mb-2 flex items-center justify-center gap-1.5 text-sm font-semibold text-orange-700">
                                        <Clock size={16} />
                                        <span>Limited Time Offer Ends In:</span>
                                    </div>
                                    <div className="flex items-center justify-center space-x-1 text-gray-800 sm:space-x-2">
                                        {/* Days */}
                                        <div className="flex w-14 flex-col items-center rounded bg-white p-1.5 shadow-inner sm:w-16">
                                            <span className="text-lg font-bold leading-none sm:text-xl">
                                                {String(timeRemaining.days).padStart(2, '0')}
                                            </span>
                                            <span className="text-xs text-gray-500">Days</span>
                                        </div>
                                        <span className="text-lg font-bold text-orange-500">:</span>
                                        {/* Hours */}
                                        <div className="flex w-14 flex-col items-center rounded bg-white p-1.5 shadow-inner sm:w-16">
                                            <span className="text-lg font-bold leading-none sm:text-xl">
                                                {String(timeRemaining.hours).padStart(2, '0')}
                                            </span>
                                            <span className="text-xs text-gray-500">Hours</span>
                                        </div>
                                        <span className="text-lg font-bold text-orange-500">:</span>
                                        {/* Mins */}
                                        <div className="flex w-14 flex-col items-center rounded bg-white p-1.5 shadow-inner sm:w-16">
                                            <span className="text-lg font-bold leading-none sm:text-xl">
                                                {String(timeRemaining.minutes).padStart(2, '0')}
                                            </span>
                                            <span className="text-xs text-gray-500">Mins</span>
                                        </div>
                                        <span className="text-lg font-bold text-orange-500">:</span>
                                        {/* Secs */}
                                        <div className="flex w-14 flex-col items-center rounded bg-white p-1.5 shadow-inner sm:w-16">
                                            <span className="text-lg font-bold leading-none sm:text-xl">
                                                {String(timeRemaining.seconds).padStart(2, '0')}
                                            </span>
                                            <span className="text-xs text-gray-500">Secs</span>
                                        </div>
                                    </div>
                                </div>
                            )}
                            {/* End Countdown Timer */}


                            {/* Price Section - Enhanced */}
                            <div className="flex flex-wrap items-baseline gap-x-3 gap-y-1"> {/* Added flex-wrap */}
                                <p className={`text-3xl font-bold ${isDiscountActive ? 'text-red-600' : 'text-gray-900'}`}>
                                    ${finalSellingPrice.toFixed(2)}
                                </p>
                                {isDiscountActive && (
                                    <p className="text-xl font-medium text-gray-400 line-through">
                                        ${originalPrice.toFixed(2)}
                                    </p>
                                )}
                                {isDiscountActive && savingPercentage > 0 && (
                                    <span className="ml-auto self-center rounded-md bg-red-100 px-2 py-0.5 text-sm font-semibold text-red-700 md:ml-0"> {/* Adjusted margin for wrap */}
                                        Save {savingPercentage}%
                                    </span>
                                )}
                            </div>
                            {/* End Price Section */}


                            {/* Product Variations */}
                            {product.variations && product.variations.length > 0 && (
                                <div className="space-y-4"> {/* Reduced top margin if variations exist */}
                                    {product.variations.map((variation, index) => (
                                        <VariationSelector
                                            key={index}
                                            variation={variation}
                                            selectedValue={selectedVariations[variation.name || '']}
                                            onSelect={(value) => handleVariationChange(variation.name || '', value)}
                                        />
                                    ))}
                                </div>
                            )}

                            {/* Description */}
                            {product.description && ( // Conditionally render description
                                <div className="space-y-3 border-t border-gray-200 pt-4">
                                    <p className="text-base font-medium">Description</p> {/* Adjusted size */}
                                    <div className="prose prose-sm max-w-none text-gray-700" dangerouslySetInnerHTML={{ __html: product.description }}></div> {/* Used prose for styling */}
                                </div>
                            )}


                            {/* Actions */}
                            <div className="border-t border-gray-200 pt-5">
                                <div className="mb-4 flex items-center">
                                    {/* Quantity Selector */}
                                    <div className="mr-4 flex items-center rounded-full border border-gray-300">
                                        <button
                                            onClick={decreaseQuantity}
                                            className="flex h-10 w-10 items-center justify-center rounded-l-full transition-colors hover:bg-gray-100 disabled:cursor-not-allowed disabled:opacity-50 cursor-pointer"
                                            aria-label="Decrease quantity"
                                            disabled={quantity <= 1}
                                        >
                                            <Minus size={16} />
                                        </button>
                                        <span className="flex h-10 w-12 min-w-[3rem] items-center justify-center text-center text-sm font-medium">{quantity}</span>
                                        <button
                                            onClick={increaseQuantity}
                                            className="flex h-10 w-10 items-center justify-center rounded-r-full transition-colors hover:bg-gray-100 cursor-pointer"
                                            aria-label="Increase quantity"
                                        >
                                            <Plus size={16} />
                                        </button>
                                    </div>
                                    <button
                                        onClick={() => addToCart(product.id as number, quantity)}
                                        className="flex flex-1 items-center justify-center rounded-full bg-black px-6 py-3 text-sm font-medium text-white transition-all duration-300 hover:bg-gray-800 hover:shadow-md cursor-pointer"
                                    >
                                        <ShoppingBag className="mr-2" size={18} />
                                        Add to Cart
                                    </button>
                                </div>

                                {/* Cart/Buy Buttons */}
                                <div className="flex flex-col gap-3 sm:flex-row sm:items-center">

                                    {/* Removed Buy Now & Share for cleaner default - add back if needed */}
                                    {/* <button className="flex flex-1 items-center justify-center rounded-full border border-black bg-white px-6 py-3 font-medium text-black transition-all duration-300 hover:bg-gray-100 sm:flex-none">Buy Now</button> */}
                                    {/* <button className="flex h-12 w-12 items-center justify-center rounded-full border border-gray-300 hover:bg-gray-100" aria-label="Share"><Share2 size={18} /></button> */}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {/* Related Products */}
                {relatedProducts.length > 0 && (
                    <section className="py-16 md:py-24">
                        <div className="mb-8 md:mb-10"> {/* Adjusted margin */}
                            <h2 className="text-2xl font-bold sm:text-3xl">You May Also Like</h2>
                        </div>
                        <div className="grid grid-cols-2 gap-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 md:gap-6"> {/* Adjusted grid for smaller screens */}
                            {relatedProducts.map((relatedProduct) => ( // Renamed variable
                                <ProductCard key={relatedProduct.id} product={relatedProduct} />
                            ))}
                        </div>
                    </section>
                )}
            </div>
        </>
    );
};

Show.layout = (page) => (
    <AppLayout>
        <ArtworkLayout children={page} />
    </AppLayout>
)
export default Show;
