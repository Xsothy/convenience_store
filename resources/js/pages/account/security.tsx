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
import { KeyRound, Lock, RefreshCw } from 'lucide-react';
import { toast } from 'sonner';

export default function Security() {
  const [changePassword, setChangePassword] = useState(false);
  const [loading, setLoading] = useState(false);

  // Form for password change
  const passwordForm = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
  });

  const handlePasswordSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    setLoading(true);
    
    passwordForm.post(route('user.password.update'), {
      preserveScroll: true,
      onSuccess: () => {
        setChangePassword(false);
        setLoading(false);
        passwordForm.reset();
        toast.success('Password changed successfully');
      },
      onError: () => {
        setLoading(false);
        toast.error('Failed to change password');
      }
    });
  };

  return (
    <>
      <Head title="Account Security" />
      
      <Card>
        <CardHeader>
          <div className="flex items-center justify-between">
            <div>
              <CardTitle>Security Settings</CardTitle>
              <CardDescription>
                Update your password and security preferences
              </CardDescription>
            </div>
            <Button 
              variant={changePassword ? "default" : "outline"} 
              size="sm"
              onClick={() => setChangePassword(!changePassword)}
            >
              {changePassword ? (
                <>
                  <RefreshCw className="h-4 w-4 mr-2" />
                  Cancel
                </>
              ) : (
                <>
                  <KeyRound className="h-4 w-4 mr-2" />
                  Change Password
                </>
              )}
            </Button>
          </div>
        </CardHeader>
        <CardContent>
          {changePassword ? (
            <form onSubmit={handlePasswordSubmit}>
              <div className="grid gap-4">
                <div className="grid gap-2">
                  <Label htmlFor="current_password">Current Password</Label>
                  <Input 
                    id="current_password" 
                    type="password"
                    value={passwordForm.data.current_password}
                    onChange={e => passwordForm.setData('current_password', e.target.value)}
                    placeholder="Your current password"
                  />
                  {passwordForm.errors.current_password && (
                    <p className="text-sm text-red-500">{passwordForm.errors.current_password}</p>
                  )}
                </div>
                
                <div className="grid gap-2">
                  <Label htmlFor="password">New Password</Label>
                  <Input 
                    id="password" 
                    type="password"
                    value={passwordForm.data.password}
                    onChange={e => passwordForm.setData('password', e.target.value)}
                    placeholder="Your new password"
                  />
                  {passwordForm.errors.password && (
                    <p className="text-sm text-red-500">{passwordForm.errors.password}</p>
                  )}
                </div>
                
                <div className="grid gap-2">
                  <Label htmlFor="password_confirmation">Confirm New Password</Label>
                  <Input 
                    id="password_confirmation" 
                    type="password"
                    value={passwordForm.data.password_confirmation}
                    onChange={e => passwordForm.setData('password_confirmation', e.target.value)}
                    placeholder="Confirm your new password"
                  />
                </div>
                
                <Button 
                  type="submit" 
                  className="w-full mt-2"
                  disabled={loading}
                >
                  {loading && <RefreshCw className="mr-2 h-4 w-4 animate-spin" />}
                  Update Password
                </Button>
              </div>
            </form>
          ) : (
            <div className="flex items-center gap-4 py-4">
              <div className="rounded-full bg-primary/10 p-4">
                <Lock className="h-6 w-6 text-primary" />
              </div>
              <div>
                <h3 className="text-lg font-medium">Password Protection</h3>
                <p className="text-sm text-muted-foreground">
                  Your password was last changed on [password change date]. It's recommended to 
                  change your password regularly for security.
                </p>
              </div>
            </div>
          )}
        </CardContent>
      </Card>
    </>
  );
}

Security.layout = page => (
  <AppLayout>
    <ArtworkLayout>
      <AccountLayout title={"Security Settings"} description={"Update your password and security preferences."} children={page} />
    </ArtworkLayout>
  </AppLayout>
)
