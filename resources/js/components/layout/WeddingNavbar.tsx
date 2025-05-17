import React, { useState, useEffect } from 'react';
import { Link, router } from '@inertiajs/react';
import { ChevronDown, X, ShoppingCart, User, Heart, Search, Menu, LogOut, Settings, Shield, Home, Grid, Image } from 'lucide-react';
import {
  NavigationMenu,
  NavigationMenuList,
  NavigationMenuItem,
  NavigationMenuLink
} from "@/components/ui/navigation-menu";
import {
  Sheet,
  SheetContent,
  SheetHeader,
  SheetTitle,
  SheetTrigger,
  SheetClose
} from "@/components/ui/sheet";
import {
  Collapsible,
  CollapsibleContent,
  CollapsibleTrigger,
} from "@/components/ui/collapsible";
import { cn } from "@/lib/utils";

// Define wedding categories - in a real app, these would come from the backend
const weddingCategories = [
  { name: 'Invitations', slug: 'invitations' },
  { name: 'Save the Dates', slug: 'save-the-dates' },
  { name: 'Thank You Cards', slug: 'thank-you-cards' },
  { name: 'Wedding Websites', slug: 'websites' },
  { name: 'Day-of Essentials', slug: 'day-of-essentials' },
  { name: 'Wedding Suites', slug: 'wedding-suites' }
];

export default function WeddingNavbar() {
  const [activeCategory, setActiveCategory] = useState<string | null>(null);
  const [open, setOpen] = useState(false);
  const [accountOpen, setAccountOpen] = useState(false);
  const [searchQuery, setSearchQuery] = useState('');

  // Close sheet on route change
  useEffect(() => {
    setOpen(false);
  }, [location]);

  const handleSearch = (e: React.FormEvent) => {
    e.preventDefault();
    if (searchQuery.trim()) {
      router.visit(`/search?q=${encodeURIComponent(searchQuery.trim())}`);
      setOpen(false);
    }
  };

  const handleLogout = (e: React.MouseEvent) => {
    e.preventDefault();
    router.post('/logout');
  };

  return (
    <div className="border-b shadow-sm">
      {/* Main navbar with logo and categories */}
      <div className="container mx-auto px-4">
        <div className="flex items-center justify-between py-4">
          {/* Logo */}
          <div className="flex-shrink-0">
            <Link href="/" className="text-2xl font-bold text-primary transition-colors hover:text-primary/90">
              minted<span className="text-3xl">.</span>
              <span className="text-base font-normal ml-1 text-gray-600">wedding</span>
            </Link>
          </div>

          {/* Category navigation - desktop only */}
          <nav className="hidden md:block">
            <NavigationMenu>
              <NavigationMenuList className="flex space-x-8">
                <NavigationMenuItem>
                  <NavigationMenuLink
                    href="/"
                    className={cn(
                      "relative px-3 py-2 font-medium transition-colors rounded-md",
                      "before:absolute before:bottom-0 before:left-0 before:right-0 before:h-[2px] before:bg-primary",
                      "before:transition-all before:duration-300",
                      "text-gray-700 before:w-0 hover:text-primary hover:bg-gray-50 hover:before:w-full"
                    )}
                  >
                    Home
                  </NavigationMenuLink>
                </NavigationMenuItem>
                <NavigationMenuItem>
                  <NavigationMenuLink
                    href="/wedding/themes"
                    className={cn(
                      "relative px-3 py-2 font-medium transition-colors rounded-md",
                      "before:absolute before:bottom-0 before:left-0 before:right-0 before:h-[2px] before:bg-primary",
                      "before:transition-all before:duration-300",
                      "text-gray-700 before:w-0 hover:text-primary hover:bg-gray-50 hover:before:w-full"
                    )}
                  >
                    Shop by Theme
                  </NavigationMenuLink>
                </NavigationMenuItem>
                <NavigationMenuItem>
                  <NavigationMenuLink
                    href="/wedding/products"
                    className={cn(
                      "relative px-3 py-2 font-medium transition-colors rounded-md",
                      "before:absolute before:bottom-0 before:left-0 before:right-0 before:h-[2px] before:bg-primary",
                      "before:transition-all before:duration-300",
                      "text-gray-700 before:w-0 hover:text-primary hover:bg-gray-50 hover:before:w-full"
                    )}
                  >
                    All Wedding Products
                  </NavigationMenuLink>
                </NavigationMenuItem>
              </NavigationMenuList>
            </NavigationMenu>
          </nav>

          {/* Mobile menu button using Sheet component */}
          <div className="md:hidden">
            <Sheet open={open} onOpenChange={setOpen}>
              <SheetTrigger asChild>
                <button
                  type="button"
                  className="inline-flex items-center justify-center rounded-md p-2 text-gray-700 hover:bg-gray-100 hover:text-primary transition-colors"
                  aria-label="Open menu"
                >
                  <Menu className="h-6 w-6" />
                </button>
              </SheetTrigger>
              <SheetContent side="right" className="w-full sm:max-w-md p-0">
                <div className="flex flex-col h-full">
                  <SheetHeader className="p-6 border-b">
                    <SheetTitle className="text-left">
                      <Link href="/" className="text-2xl font-bold text-primary">
                        minted<span className="text-3xl">.</span>
                        <span className="text-base font-normal ml-1 text-gray-600">wedding</span>
                      </Link>
                    </SheetTitle>
                  </SheetHeader>
                  
                  <div className="flex-1 overflow-y-auto">
                    <div className="p-6">
                      <form onSubmit={handleSearch} className="flex items-center space-x-2 py-2 mb-6">
                        <input
                          type="text"
                          placeholder="Search wedding items..."
                          className="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none transition-all"
                          value={searchQuery}
                          onChange={(e) => setSearchQuery(e.target.value)}
                        />
                        <button type="submit" className="rounded-full bg-primary p-2.5 text-white hover:bg-primary/90 transition-colors" aria-label="Search">
                          <Search size={18} />
                        </button>
                      </form>

                      <div className="space-y-8">
                        {/* Main Navigation */}
                        <div>
                          <h3 className="font-medium text-lg text-gray-900 mb-3">Menu</h3>
                          <div className="space-y-1">
                            <SheetClose asChild>
                              <Link 
                                href="/"
                                className="flex items-center w-full text-base text-gray-700 hover:text-primary hover:bg-gray-50 py-2 px-3 rounded-md transition-colors"
                              >
                                <Home className="h-4 w-4 mr-3" />
                                <span>Home</span>
                              </Link>
                            </SheetClose>
                            <SheetClose asChild>
                              <Link 
                                href="/wedding/themes"
                                className="flex items-center w-full text-base text-gray-700 hover:text-primary hover:bg-gray-50 py-2 px-3 rounded-md transition-colors"
                              >
                                <Grid className="h-4 w-4 mr-3" />
                                <span>Shop by Theme</span>
                              </Link>
                            </SheetClose>
                            <SheetClose asChild>
                              <Link 
                                href="/wedding/products"
                                className="flex items-center w-full text-base text-gray-700 hover:text-primary hover:bg-gray-50 py-2 px-3 rounded-md transition-colors"
                              >
                                <Image className="h-4 w-4 mr-3" />
                                <span>All Wedding Products</span>
                              </Link>
                            </SheetClose>
                          </div>
                        </div>

                        <div>
                          <h3 className="font-medium text-lg text-gray-900 mb-3">Wedding Categories</h3>
                          <div className="space-y-1">
                            {weddingCategories.map((category) => (
                              <SheetClose asChild key={category.slug}>
                                <Link 
                                  href={`/wedding/${category.slug}`}
                                  className="block w-full text-base text-gray-700 hover:text-primary hover:bg-gray-50 py-2 px-3 rounded-md transition-colors"
                                >
                                  {category.name}
                                </Link>
                              </SheetClose>
                            ))}
                          </div>
                        </div>

                        {/* User Navigation */}
                        <div className="pt-6 border-t border-gray-200">
                          <Collapsible
                            open={accountOpen}
                            onOpenChange={setAccountOpen}
                            className="w-full"
                          >
                            <CollapsibleTrigger className="flex items-center justify-between w-full text-base text-gray-900 hover:text-primary py-2 px-3 rounded-md transition-colors">
                              <div className="flex items-center">
                                <User className="h-4 w-4 mr-3" />
                                <span className="font-medium">My Account</span>
                              </div>
                              <ChevronDown
                                className={cn(
                                  "h-4 w-4 transition-transform duration-200",
                                  accountOpen ? "rotate-180" : ""
                                )}
                              />
                            </CollapsibleTrigger>
                            <CollapsibleContent className="mt-1 space-y-1 pl-10">
                              <SheetClose asChild>
                                <Link
                                  href="/account"
                                  className="block w-full text-base text-gray-700 hover:text-primary hover:bg-gray-50 py-2 px-3 rounded-md transition-colors"
                                >
                                  Account Management
                                </Link>
                              </SheetClose>
                              <SheetClose asChild>
                                <a
                                  href="/admin"
                                  className="block w-full text-base text-gray-700 hover:text-primary hover:bg-gray-50 py-2 px-3 rounded-md transition-colors"
                                >
                                  Admin Panel
                                </a>
                              </SheetClose>
                              <SheetClose asChild>
                                <button
                                  onClick={handleLogout}
                                  className="block w-full text-left text-base text-gray-700 hover:text-primary hover:bg-gray-50 py-2 px-3 rounded-md transition-colors"
                                >
                                  Logout
                                </button>
                              </SheetClose>
                            </CollapsibleContent>
                          </Collapsible>

                          <div className="space-y-1 mt-2">
                            <SheetClose asChild>
                              <Link href="/favorites" className="flex items-center text-base text-gray-700 hover:text-primary hover:bg-gray-50 py-2 px-3 rounded-md transition-colors">
                                <Heart className="h-4 w-4 mr-3" />
                                <span>Favorites</span>
                              </Link>
                            </SheetClose>
                            <SheetClose asChild>
                              <Link href="/cart" className="flex items-center text-base text-gray-700 hover:text-primary hover:bg-gray-50 py-2 px-3 rounded-md transition-colors">
                                <ShoppingCart className="h-4 w-4 mr-3" />
                                <span>Cart</span>
                              </Link>
                            </SheetClose>
                          </div>
                        </div>

                        {/* Other links */}
                        <div className="pt-6 border-t border-gray-200">
                          <h3 className="font-medium text-lg text-gray-900 mb-3">Other Sections</h3>
                          <div className="space-y-1">
                            <SheetClose asChild>
                              <Link href="/artwork" className="block w-full text-base text-gray-700 hover:text-primary hover:bg-gray-50 py-2 px-3 rounded-md transition-colors">
                                Artwork Collection
                              </Link>
                            </SheetClose>
                            <SheetClose asChild>
                              <Link href="/artist-community" className="block w-full text-base text-gray-700 hover:text-primary hover:bg-gray-50 py-2 px-3 rounded-md transition-colors">
                                Artist Community
                              </Link>
                            </SheetClose>
                          </div>
                        </div>

                        {/* Wedding planning resources */}
                        <div className="pt-6 border-t border-gray-200">
                          <h3 className="font-medium text-lg text-gray-900 mb-3">Wedding Planning</h3>
                          <div className="space-y-1">
                            <SheetClose asChild>
                              <Link href="/wedding/checklist" className="block w-full text-base text-gray-700 hover:text-primary hover:bg-gray-50 py-2 px-3 rounded-md transition-colors">
                                Wedding Checklist
                              </Link>
                            </SheetClose>
                            <SheetClose asChild>
                              <Link href="/wedding/budget" className="block w-full text-base text-gray-700 hover:text-primary hover:bg-gray-50 py-2 px-3 rounded-md transition-colors">
                                Budget Planner
                              </Link>
                            </SheetClose>
                            <SheetClose asChild>
                              <Link href="/wedding/inspiration" className="block w-full text-base text-gray-700 hover:text-primary hover:bg-gray-50 py-2 px-3 rounded-md transition-colors">
                                Inspiration Gallery
                              </Link>
                            </SheetClose>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div className="p-6 border-t border-gray-200 mt-auto">
                    <SheetClose asChild>
                      <Link 
                        href="/login" 
                        className="block w-full text-center bg-primary text-white py-2.5 px-4 rounded-md font-medium hover:bg-primary/90 transition-colors"
                      >
                        Sign In / Register
                      </Link>
                    </SheetClose>
                  </div>
                </div>
              </SheetContent>
            </Sheet>
          </div>
        </div>
      </div>

      {/* Promotional banner */}
      <div className="relative bg-[#f0ece3] py-3 text-center text-sm">
        <div className="container mx-auto px-4">
          <div className="flex items-center justify-between">
            <button className="absolute left-4 text-gray-700 hover:text-gray-900 transition-colors">
              <span className="sr-only">Previous promotion</span>
              <svg xmlns="http://www.w3.org/2000/svg" className="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
                <path strokeLinecap="round" strokeLinejoin="round" d="M15 19l-7-7 7-7" />
              </svg>
            </button>
            
            <div className="mx-auto">
              20% off all wedding invitations. Code: <span className="font-semibold">WEDDING2025</span>, ends Mon 4/28. 
              <Link href="/promotions" className="font-medium underline ml-1 hover:text-primary transition-colors">View all offers &rsaquo;</Link>
            </div>
            
            <button className="absolute right-4 text-gray-700 hover:text-gray-900 transition-colors">
              <span className="sr-only">Next promotion</span>
              <svg xmlns="http://www.w3.org/2000/svg" className="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
                <path strokeLinecap="round" strokeLinejoin="round" d="M9 5l7 7-7 7" />
              </svg>
            </button>
          </div>
        </div>
      </div>

      {/* Category chips for mobile scrolling */}
      <div className="md:hidden overflow-x-auto whitespace-nowrap py-3 px-4 border-t">
        <div className="inline-flex space-x-2">
          {weddingCategories.slice(0, 5).map((category) => (
            <Link 
              key={category.slug}
              href={`/wedding/${category.slug}`}
              className="rounded-full bg-gray-100 px-4 py-2 text-sm font-medium hover:bg-gray-200 transition-colors"
            >
              {category.name}
            </Link>
          ))}
        </div>
      </div>
    </div>
  );
} 