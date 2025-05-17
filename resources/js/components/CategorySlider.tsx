import React, { useState, useEffect } from 'react';
import {
    Carousel,
    CarouselContent,
    CarouselItem,
    CarouselApi
} from '@/components/ui/carousel';
import { Link } from '@inertiajs/react';
import { cn } from '@/lib/utils';
import ThemeController from '@/wayfinder/actions/App/Http/Controllers/ThemeController';

interface CategorySliderProps {
    categories: App.Data.CategoryData[];
}

const CategorySlider: React.FC<CategorySliderProps> = ({ categories }) => {
    const [selectedIndex, setSelectedIndex] = useState(0);
    const [scrollSnaps, setScrollSnaps] = useState<number[]>([]);
    const [api, setApi] = React.useState<CarouselApi>()

    useEffect(() => {
        if (!api) return;

        setScrollSnaps(api.scrollSnapList());

        api.on('select', () => {
            setSelectedIndex(api.selectedScrollSnap());
        });
    }, [api]);

    return (
        <div className="relative">
            <Carousel className="w-full" setApi={setApi}>
                <div className="overflow-hidden">
                    <CarouselContent className="-ml-4">
                        {categories.map((category) => (
                            <CarouselItem key={category.id} className="pl-4 md:basis-1/3">
                                <Link
                                    href={ThemeController.show({slug: category.slug})}
                                    className="group block overflow-hidden rounded-lg"
                                >
                                    <div className="relative aspect-[3/2] overflow-hidden bg-gray-100">
                                        <img
                                            src={category.products.filter(product => product.feature_image)[0]?.feature_image ?? ''}
                                            alt={category.name || ''}
                                            className="h-full w-full object-cover transition-all duration-300 group-hover:scale-105"
                                        />
                                        <div className="absolute inset-x-0 bottom-0 flex items-end justify-center bg-primary bg-opacity-0 p-4 text-center text-white opacity-0 transition-all duration-300 group-hover:bg-opacity-50 group-hover:opacity-80">
                                            <span className="text-lg font-bold">{category.name}</span>
                                        </div>
                                    </div>
                                </Link>
                            </CarouselItem>
                        ))}
                    </CarouselContent>
                </div>
            </Carousel>

            {/* Custom Indicators */}
            <div className="mt-8 flex justify-center space-x-2">
                {scrollSnaps.map((_, index) => (
                    <button
                        key={index}
                        onClick={() => api?.scrollTo(index)}
                        className={cn(
                            "h-2.5 w-2.5 rounded-full transition-all duration-300",
                            index === selectedIndex ? "bg-primary w-6" : "bg-gray-300 hover:bg-gray-400",
                        )}
                        aria-label={`Go to testimonial ${index + 1}`}
                        aria-current={index === selectedIndex ? "true" : "false"}
                    />
                ))}
            </div>
        </div>
    );
};

export default CategorySlider;
