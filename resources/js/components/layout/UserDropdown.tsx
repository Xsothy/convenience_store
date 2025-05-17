import React from 'react';
import { Link, usePage, router } from '@inertiajs/react';
import { User, LogOut, Settings } from 'lucide-react';
import { SharedData } from '@/types';
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuSeparator,
  DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { Avatar, AvatarFallback } from '@/components/ui/avatar';
import login from '@/wayfinder/routes/login';
import logout from '@/wayfinder/routes/logout';

export default function UserDropdown() {
  const { auth } = usePage<SharedData>().props;
  const isAuthenticated = !!auth.user;
  
  const handleLogout = (e: React.MouseEvent) => {
    e.preventDefault();
    router.post(logout.url());
  };

  // Display login button if not authenticated
  if (!isAuthenticated) {
    return (
      <Link 
        href={login.url()} 
        className="flex items-center gap-1 text-sm text-gray-800 hover:text-primary transition-colors"
      >
        <User className="h-5 w-5" />
        <span className="hidden sm:inline">Log in</span>
      </Link>
    );
  }

  // We know user is not null at this point
  const user = auth.user!;
  const userName = user.name || 'User';
  const userInitials = userName.substring(0, 2).toUpperCase();

  // Display user dropdown if authenticated
  return (
    <DropdownMenu>
      <DropdownMenuTrigger className="outline-none">
        <div className="flex items-center gap-2 cursor-pointer">
          <Avatar className="h-8 w-8">
            <AvatarFallback>
              {userInitials}
            </AvatarFallback>
          </Avatar>
          <span className="hidden sm:inline text-sm font-medium">
            {userName}
          </span>
        </div>
      </DropdownMenuTrigger>
      
      <DropdownMenuContent align="end" className="w-48">
        <div className="px-2 py-1.5 text-sm font-medium">
          {user.email}
        </div>
        
        <DropdownMenuSeparator />
        
        <DropdownMenuItem asChild>
          <Link href="/account" className="cursor-pointer">
            <User className="mr-2 h-4 w-4" />
            <span>Account</span>
          </Link>
        </DropdownMenuItem>
        
        <DropdownMenuItem asChild>
          <a href="/admin" className="cursor-pointer">
            <Settings className="mr-2 h-4 w-4" />
            <span>Admin</span>
          </a>
        </DropdownMenuItem>
        
        <DropdownMenuSeparator />
        
        <DropdownMenuItem onClick={handleLogout} className="cursor-pointer">
          <LogOut className="mr-2 h-4 w-4" />
          <span>Log out</span>
        </DropdownMenuItem>
      </DropdownMenuContent>
    </DropdownMenu>
  );
} 