import React, { useState } from 'react';
import { Head, useForm } from '@inertiajs/react';
import { AccountLayout, ArtworkLayout, AppLayout } from '@/layouts';
import { Button } from '@/components/ui/button';
import {
  Card,
  CardContent,
  CardDescription,
  CardHeader,
  CardTitle,
} from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Pencil, RefreshCw } from 'lucide-react';
import { toast } from 'sonner';

interface AccountProps {
  user: {
    id: number;
    name: string;
    email: string;
    created_at: string;
  };
}

export default function Account({ user }: AccountProps) {
  const [editing, setEditing] = useState(false);
  const [loading, setLoading] = useState(false);

  // Form for user info
  const form = useForm({
    name: user.name,
    email: user.email,
  });

  // Format date helper
  const formatDate = (date: string | null) => {
    if (!date) return '';
    return new Date(date).toLocaleDateString('en-US', {
      year: 'numeric',
      month: 'long',
      day: 'numeric'
    });
  };

  const memberSince = formatDate(user.created_at);

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    setLoading(true);
    
    form.post(route('user.update'), {
      preserveScroll: true,
      onSuccess: () => {
        setEditing(false);
        setLoading(false);
        toast.success('Profile updated successfully');
      },
      onError: () => {
        setLoading(false);
        toast.error('Failed to update profile');
      }
    });
  };

  return (
    <>
      <Head title="Account Information" />
      
            <Card>
              <CardHeader>
                <div className="flex items-center justify-between">
                  <div>
                    <CardTitle>Personal Information</CardTitle>
                    <CardDescription>
                      Update your account details and personal information.
                    </CardDescription>
                  </div>
                  <Button 
                    variant={editing ? "default" : "outline"} 
                    size="sm"
                    onClick={() => setEditing(!editing)}
                  >
                    {editing ? (
                      <>
                        <RefreshCw className="h-4 w-4 mr-2" />
                        Cancel
                      </>
                    ) : (
                      <>
                        <Pencil className="h-4 w-4 mr-2" />
                        Edit
                      </>
                    )}
                  </Button>
                </div>
              </CardHeader>
              <CardContent>
                {editing ? (
                  <form onSubmit={handleSubmit}>
                    <div className="grid gap-4">
                      <div className="grid gap-2">
                        <Label htmlFor="name">Full Name</Label>
                        <Input 
                          id="name" 
                          value={form.data.name}
                          onChange={e => form.setData('name', e.target.value)}
                          placeholder="Your full name"
                        />
                        {form.errors.name && (
                          <p className="text-sm text-red-500">{form.errors.name}</p>
                        )}
                      </div>
                      
                      <div className="grid gap-2">
                        <Label htmlFor="email">Email Address</Label>
                        <Input 
                          id="email" 
                          type="email"
                          value={form.data.email}
                          onChange={e => form.setData('email', e.target.value)}
                          placeholder="Your email address"
                        />
                        {form.errors.email && (
                          <p className="text-sm text-red-500">{form.errors.email}</p>
                        )}
                      </div>
                      
                      <Button 
                        type="submit" 
                        className="w-full mt-2"
                        disabled={loading}
                      >
                        {loading && <RefreshCw className="mr-2 h-4 w-4 animate-spin" />}
                        Save Changes
                      </Button>
                    </div>
                  </form>
                ) : (
                  <div className="space-y-4">
                    <div className="grid grid-cols-3 gap-4">
                      <div className="space-y-1">
                        <p className="text-sm font-medium text-muted-foreground">Name</p>
                        <p>{user.name}</p>
                      </div>
                      <div className="space-y-1">
                        <p className="text-sm font-medium text-muted-foreground">Email</p>
                        <p>{user.email}</p>
                      </div>
                      <div className="space-y-1">
                        <p className="text-sm font-medium text-muted-foreground">Member Since</p>
                        <p>{memberSince}</p>
                      </div>
                    </div>
                  </div>
                )}
              </CardContent>
            </Card>

      
    </>
  );
}

Account.layout = page => (
  <AppLayout>
      <ArtworkLayout>
        <AccountLayout title={"Account Information"} description={"Update your personal information and account details."} children={page} />
      </ArtworkLayout>
  </AppLayout>
) 