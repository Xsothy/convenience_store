import { Carousel, CarouselContent, CarouselItem } from '@/components/ui/carousel';
import ArtworkController from '@/wayfinder/actions/App/Http/Controllers/ArtworkController';
import { Link } from '@inertiajs/react';
import React from 'react';

const FeaturedProduct = ({ products }: { products: App.Data.ProductData[] }) => {
    return (
        <Carousel>
            <section className="relative overflow-hidden">
                <div className={"container mx-auto px-4 py-16 md:py-24"}>
                    <CarouselContent className="overflow-hidden">
                        { products.filter(product => product.featured_image).map((product) => (
                            <CarouselItem>
                                <div className="grid grid-cols-1 items-center gap-8 md:grid-cols-2 md:gap-16">
                                    <div className="animate-slide-up order-2 md:order-1">

                                        <div className="max-w-xl">
                                            <span className="mb-2 inline-block text-sm tracking-widest text-gray-500 uppercase">Featured Product</span>
                                            <h2 className="mb-4 text-4xl leading-tight font-bold tracking-tight text-black sm:text-5xl">{product.name}</h2>
                                            <ul className={'flex gap-2'}>
                                                {product.category ? (
                                                    <li key={product.category.slug}>
                                                        {product.category.name}
                                                    </li>
                                                ) : "Uncategorized"}
                                            </ul>
                                            <p className="mb-6 text-xl font-medium">${product.price?.toFixed(2)}</p>
                                            <p className="mb-8 text-gray-600" dangerouslySetInnerHTML={{ __html: product.description  ?? ''}}></p>
                                            <div className="flex flex-wrap gap-4">
                                                <Link
                                                    href={ArtworkController.show({ slug: product.slug })}
                                                    className="rounded-full bg-black px-6 py-3 font-medium text-white transition-colors duration-300 hover:bg-gray-800"
                                                >
                                                    View Details
                                                </Link>
                                            </div>
                                        </div>
                                    </div>
                                    <div className="order-1 overflow-hidden rounded-2xl md:order-2">
                                        <img src={product.featured_image ?? ''} alt={product.name ?? ''} className="h-full w-full object-cover" />
                                    </div>
                                </div>
                            </CarouselItem>
                        ))}
                    </CarouselContent>
                </div>
            </section>
        </Carousel>
    );
};

export default FeaturedProduct;
