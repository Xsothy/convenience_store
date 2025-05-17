import { useEffect, useState } from "react"
import { Star, Quote } from "lucide-react"
import { Avatar, AvatarFallback, AvatarImage } from "@/components/ui/avatar"
import { Card, CardContent } from "@/components/ui/card"
import {
  Carousel,
  CarouselContent,
  CarouselItem,
  CarouselNext,
  CarouselPrevious,
  type CarouselApi,
} from "@/components/ui/carousel"
import { cn } from "@/lib/utils"

type Testimonial = {
  id?: number
  quote: string
  author: string
  role: string
  rating: number
  image?: string
}

interface TestimonialSliderProps {
  testimonials?: Testimonial[]
}

// Default testimonials to use if none are provided
const defaultTestimonials: Testimonial[] = [
  {
    id: 1,
    quote:
        "The quality of products I received exceeded my expectations. The customer service was also outstanding. I'll definitely be shopping here again!",
    author: "Jessica Miller",
    role: "Returning Customer",
    rating: 5,
    image: "/placeholder.svg?height=80&width=80",
  },
  {
    id: 2,
    quote:
        "I was skeptical about ordering online, but the products arrived exactly as described and ahead of schedule. Very impressed with the entire experience.",
    author: "Michael Chen",
    role: "First-time Buyer",
    rating: 5,
    image: "/placeholder.svg?height=80&width=80",
  },
  {
    id: 3,
    quote:
        "Great selection of quality products at reasonable prices. The shipping was fast and the packaging was excellent. Highly recommend!",
    author: "Sarah Johnson",
    role: "Loyal Customer",
    rating: 4,
    image: "/placeholder.svg?height=80&width=80",
  },
]

export default function TestimonialSlider({ testimonials = defaultTestimonials }: TestimonialSliderProps) {
  const [api, setApi] = useState<CarouselApi>()
  const [current, setCurrent] = useState(0)
  const [isPaused, setIsPaused] = useState(false)

  // Set up the carousel API
  useEffect(() => {
    if (!api) return

    const handleSelect = () => {
      setCurrent(api.selectedScrollSnap())
    }

    api.on("select", handleSelect)
    return () => {
      api.off("select", handleSelect)
    }
  }, [api])

  // Auto-play functionality
  useEffect(() => {
    if (!api || isPaused) return

    const interval = setInterval(() => {
      api.scrollNext()
    }, 6000)

    return () => clearInterval(interval)
  }, [api, isPaused])

  return (
      <div
          className="relative mx-auto max-w-5xl px-4 py-8"
          onMouseEnter={() => setIsPaused(true)}
          onMouseLeave={() => setIsPaused(false)}
      >
        <Carousel
            setApi={setApi}
            className="w-full"
            opts={{
              align: "center",
              loop: true,
            }}
        >
          <CarouselContent>
            {testimonials.map((testimonial, index) => (
                <CarouselItem key={testimonial.id || index} className="md:basis-full">
                  <Card className="border-none shadow-lg">
                    <CardContent className="flex flex-col items-center justify-center p-8 text-center md:p-12">
                      <Quote className="mb-6 h-12 w-12 text-[#c1b18a]" />

                      <p className="mb-8 text-lg leading-relaxed text-gray-700 md:text-xl">{testimonial.quote}</p>

                      <div className="mb-6 flex justify-center">
                        {[...Array(5)].map((_, i) => (
                            <Star
                                key={i}
                                size={20}
                                className={i < testimonial.rating ? "fill-[#c1b18a] text-[#c1b18a]" : "text-gray-300"}
                            />
                        ))}
                      </div>

                      <div className="flex items-center gap-4">
                        <Avatar className="h-14 w-14 border-2 border-[#c1b18a]">
                          <AvatarImage src={testimonial.image} alt={testimonial.author} />
                          <AvatarFallback className="bg-[#c1b18a]/10 text-[#c1b18a]">
                            {testimonial.author
                                .split(" ")
                                .map((n) => n[0])
                                .join("")}
                          </AvatarFallback>
                        </Avatar>

                        <div className="text-left">
                          <p className="font-medium text-lg text-[#c1b18a]">{testimonial.author}</p>
                          <p className="text-gray-500">{testimonial.role}</p>
                        </div>
                      </div>
                    </CardContent>
                  </Card>
                </CarouselItem>
            ))}
          </CarouselContent>

          <CarouselPrevious className="left-4 border-[#c1b18a] text-[#c1b18a] hover:bg-[#c1b18a]/10" />
          <CarouselNext className="right-4 border-[#c1b18a] text-[#c1b18a] hover:bg-[#c1b18a]/10" />
        </Carousel>

        {/* Custom Indicators */}
        <div className="mt-8 flex justify-center space-x-2">
          {testimonials.map((_, index) => (
              <button
                  key={index}
                  onClick={() => api?.scrollTo(index)}
                  className={cn(
                      "h-2.5 w-2.5 rounded-full transition-all duration-300",
                      index === current ? "bg-[#c1b18a] w-6" : "bg-gray-300 hover:bg-gray-400",
                  )}
                  aria-label={`Go to testimonial ${index + 1}`}
                  aria-current={index === current ? "true" : "false"}
              />
          ))}
        </div>
      </div>
  )
}

