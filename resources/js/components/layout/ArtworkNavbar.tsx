import React, { useState, useEffect } from 'react';
import { Link, usePage, router } from '@inertiajs/react';
import { ChevronDown,Search, Menu } from 'lucide-react';
import {
  NavigationMenu,
  NavigationMenuList,
  NavigationMenuItem,
  NavigationMenuLink,
  NavigationMenuContent,
  NavigationMenuTrigger
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
import type { SharedData } from '@/types';
import ThemeController from '@/wayfinder/actions/App/Http/Controllers/ThemeController';
import ArtworkController from '@/wayfinder/actions/App/Http/Controllers/ArtworkController';

export default function ArtworkNavbar() {
  const [open, setOpen] = useState(false);
  const [accountOpen, setAccountOpen] = useState(false);
  const [shopByThemeOpen, setShopByThemeOpen] = useState(false);
  const [searchQuery, setSearchQuery] = useState('');
  const [isScrolled, setIsScrolled] = useState(false);
  const { url } = usePage();
  const categories = usePage<SharedData>().props.categoriesNoProducts || [];

  // Handle scrolling effect
  useEffect(() => {
    const handleScroll = () => {
      setIsScrolled(window.scrollY > 10);
    };

    window.addEventListener('scroll', handleScroll);
    return () => window.removeEventListener('scroll', handleScroll);
  }, []);

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
    <header className={`bg-white top-0 z-50 ${isScrolled ? 'shadow-sm' : ''}`}>
      {/* Main navbar with logo and categories */}
      <div className="container mx-auto px-2 py-3 md:px-4">
        <div className="flex items-center justify-between">
          {/* Logo */}
          <div className="flex-shrink-0">
            <Link href="/" className="text-2xl font-bold text-primary transition-colors hover:text-primary/90 hidden md:block">
                Convenience <span className="text-3xl">.</span><span className="text-gray-600">Store</span>
            </Link>
          </div>

          {/* Category navigation - desktop only */}
          <nav className="hidden md:flex flex-1 items-center justify-center">
            <NavigationMenu>
              <NavigationMenuList className="flex space-x-8">
                <NavigationMenuItem>
                  <NavigationMenuLink
                    href="/"
                    data-active={url === "/"}
                  >
                    Home
                  </NavigationMenuLink>
                </NavigationMenuItem>

                <NavigationMenuItem>
                  <NavigationMenuLink
                    href={ArtworkController.index()}
                    data-active={url === "/products"}
                  >
                    All Products
                  </NavigationMenuLink>
                </NavigationMenuItem>
                
                <NavigationMenuItem>
                  <NavigationMenuTrigger className={"hover:bg-white data-[state=open]:bg-white"}>
                    <NavigationMenuLink
                      href={'#'}
                      data-active={url.startsWith("/category")}
                    >
                      Shop by Category
                    </NavigationMenuLink>
                  </NavigationMenuTrigger>
                  <NavigationMenuContent>
                    <div className="grid grid-cols-2 gap-3 p-4 w-[400px]">
                      {categories
                        .filter(category => category.show_in_menu)
                        .map(category => (
                          <Link
                            key={category.slug}
                            href={ThemeController.show({slug: category.slug})}
                            className="flex items-center p-2 rounded-md hover:bg-gray-50 transition-colors"
                          >
                            {/* <div className="w-8 h-8 mr-2 rounded-md overflow-hidden bg-gray-100 flex items-center justify-center">
                              {category.icon ? (
                                <img src={category.icon} alt={category.name} className="w-6 h-6 object-contain" />
                              ) : (
                                <Grid className="w-4 h-4 text-gray-500" />
                              )}
                            </div> */}
                            <span>{category.name}</span>
                          </Link>
                        ))}
                      <Link
                        href="/themes"
                        className="flex items-center p-2 rounded-md hover:bg-gray-50 transition-colors col-span-2 mt-2 border-t pt-3"
                      >
                        <span className="font-medium">View all themes →</span>
                      </Link>
                    </div>
                  </NavigationMenuContent>
                </NavigationMenuItem>

                {/*<NavigationMenuItem>*/}
                {/*<NavigationMenuLink*/}
                {/*    href={SubscribeController.plan()}*/}
                {/*    data-active={url === "/subscribe/plan"}*/}
                {/*  >*/}
                {/*    Membership*/}
                {/*  </NavigationMenuLink>*/}
                {/*</NavigationMenuItem>*/}
              </NavigationMenuList>
            </NavigationMenu>
          </nav>
          <div></div>

          {/* Mobile menu button using Sheet component */}
          <div className="md:hidden">
            <Sheet open={open} onOpenChange={setOpen}>
              <SheetTrigger asChild>
                <button
                  type="button"
                  className="inline-flex items-center justify-center rounded-md p-2 text-gray-700 hover:bg-gray-100 hover:text-primary transition-colors cursor-pointer"
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
                          Convenience<span className="text-3xl">.</span>
                        <span className="text-gray-600">Store</span>
                      </Link>
                    </SheetTitle>
                  </SheetHeader>
                  
                  <div className="flex-1 overflow-y-auto">
                    <div className="p-6">
                      <form onSubmit={handleSearch} className="flex items-center space-x-2 py-2 mb-6">
                        <input
                          type="text"
                          placeholder="Search artwork..."
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
                                <span>Home</span>
                              </Link>
                            </SheetClose>
                            <SheetClose asChild>
                              <Link 
                                href={ArtworkController.index()}
                                className="flex items-center w-full text-base text-gray-700 hover:text-primary hover:bg-gray-50 py-2 px-3 rounded-md transition-colors"
                              >
                                <span>All Products</span>
                              </Link>
                            </SheetClose>
                            <div>
                              <Collapsible 
                                open={shopByThemeOpen}
                                onOpenChange={setShopByThemeOpen}
                                className="w-full"
                              >
                                <CollapsibleTrigger className="flex items-center justify-between w-full text-base font-medium text-gray-700 py-2 px-3 rounded-md hover:bg-gray-50 transition-color cursor-pointer">
                                  <div className="flex items-center">
                                    <span>Shop by Category</span>
                                  </div>
                                  <ChevronDown 
                                    className={cn([
                                      "h-4 w-4 transition-transform",
                                      shopByThemeOpen && 'rotate-180'
                                    ])}
                                  />
                                </CollapsibleTrigger>
                                <CollapsibleContent className="mt-1 space-y-1 pl-4">
                                  {categories
                                    .filter(category => category.show_in_menu)
                                    .map((category) => (
                                      <SheetClose asChild key={category.slug}>
                                        <Link 
                                          href={ThemeController.show({slug: category.slug})}
                                          className="block w-full text-base text-gray-700 hover:text-primary hover:bg-gray-50 py-2 px-3 rounded-md transition-colors"
                                        >
                                          {category.name}
                                        </Link>
                                      </SheetClose>
                                    ))}
                                </CollapsibleContent>
                              </Collapsible>
                            </div>
                          </div>
                        </div>
                        {/* User Navigation */}
                        <div className="pt-6 border-t border-gray-200">
                          <Collapsible
                            open={accountOpen}
                            onOpenChange={setAccountOpen}
                            className="w-full"
                          >
                            <CollapsibleTrigger className="flex items-center justify-between w-full text-base text-gray-700 hover:text-primary py-2 px-3 rounded-md transition-colors cursor-pointer">
                              <div className="flex items-center">
                                <span className="font-medium">My Account</span>
                              </div>
                              <ChevronDown
                                className={cn(
                                  "h-4 w-4 transition-transform duration-200",
                                  accountOpen ? "rotate-180" : ""
                                )}
                              />
                            </CollapsibleTrigger>
                            <CollapsibleContent className="mt-1 space-y-1 pl-4">
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
                                <span>Favorites</span>
                              </Link>
                            </SheetClose>
                            <SheetClose asChild>
                              <Link href="/cart" className="flex items-center text-base text-gray-700 hover:text-primary hover:bg-gray-50 py-2 px-3 rounded-md transition-colors">
                                <span>Cart</span>
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

      {/* Promotional banner
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
              10% off all artwork. Code: <span className="font-semibold">ART2025</span>, ends Mon 4/21. 
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
      </div> */}

      {/* Category chips for mobile scrolling */}
      {/* <div className="md:hidden overflow-x-auto whitespace-nowrap py-3 px-4 border-t">
        <div className="inline-flex space-x-2">
          {categories
            .filter(category => category.show_in_menu)
            .slice(0, 5)
            .map((category) => (
              <Link 
                key={category.slug}
                href={`/themes/${category.slug}`}
                className="rounded-full bg-gray-100 px-4 py-2 text-sm font-medium hover:bg-gray-200 transition-colors"
              >
                {category.name}
              </Link>
            ))}
        </div>
      </div> */}
    </header>
  );
} 