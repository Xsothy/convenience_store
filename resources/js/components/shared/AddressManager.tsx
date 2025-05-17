import React, { useState, useEffect } from "react";
import { Plus, Pencil, MapPin, Trash2 } from "lucide-react";
import {
  Card,
  CardContent,
  CardDescription,
  CardHeader,
  CardTitle,
} from "@/components/ui/card";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import {
  Dialog,
  DialogContent,
  DialogHeader,
  DialogTitle,
  DialogFooter,
} from "@/components/ui/dialog";
import {
  AlertDialog,
  AlertDialogAction,
  AlertDialogCancel,
  AlertDialogContent,
  AlertDialogDescription,
  AlertDialogFooter,
  AlertDialogHeader,
  AlertDialogTitle,
} from "@/components/ui/alert-dialog";
import { router, usePage } from '@inertiajs/react';
import { toast } from 'sonner';

interface AddressManagerProps {
  addresses: App.Data.AddressData[];
  onAddressSelect?: (addressId: string) => void;
  selectedAddressId?: string | null;
  allowSelect?: boolean;
  allowEdit?: boolean;
  allowDelete?: boolean;
  allowAdd?: boolean;
  title?: string;
  description?: string;
  cardClassName?: string;
  // Route configuration for address actions
  routes?: {
    store: string;
    update: string;
    delete: string;
    setDefault?: string;
  };
}

// Define the address form type
interface AddressFormData {
  id: string | number | null;
  first_name: string;
  last_name: string;
  address: string;
  city: string;
  state: string;
  postal_code: string;
  phone: string;
  email?: string;
  country?: string;
  additional_info?: string;
  user?: App.Data.UserData | null;
  [key: string]: any; // Allow any string index
}

export default function AddressManager({
  addresses: initialAddresses,
  onAddressSelect,
  selectedAddressId = null,
  allowSelect = true,
  allowEdit = true,
  allowDelete = true,
  allowAdd = true,
  title = "Addresses",
  description = "Manage your saved addresses",
  cardClassName = "",
  routes = {
    store: 'cart.addresses.store',
    update: 'cart.addresses.update',
    delete: 'cart.addresses.delete',
    setDefault: 'cart.addresses.default'
  }
}: AddressManagerProps) {
  // State management
  const { errors } = usePage().props;
  const [addresses, setAddresses] = useState<App.Data.AddressData[]>(initialAddresses);
  const [isModalOpen, setIsModalOpen] = useState(false);
  const [isDeleteDialogOpen, setIsDeleteDialogOpen] = useState(false);
  const [addressToDeleteId, setAddressToDeleteId] = useState<string | null>(null);
  const [isEditing, setIsEditing] = useState(false);
  const [loading, setLoading] = useState(false);
  
  // Address form initial state
  const emptyAddress: AddressFormData = {
    id: null,
    first_name: '',
    last_name: '',
    address: '',
    city: '',
    state: '',
    postal_code: '',
    phone: '',
    email: '',
    country: '',
    additional_info: '',
  };
  
  // Form state for new/edit address
  const [addressForm, setAddressForm] = useState<AddressFormData>(emptyAddress);

  // Update addresses when props change
  useEffect(() => {
    setAddresses(initialAddresses);
  }, [initialAddresses]);

  // Handle input change for the address form
  const handleInputChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    const { name, value } = e.target;
    setAddressForm(prev => ({ ...prev, [name]: value }));
  };

  // Reset form to defaults
  const resetForm = () => {
    setAddressForm(emptyAddress);
    setIsEditing(false);
  };

  // Open modal for adding a new address
  const handleAddAddress = () => {
    resetForm();
    setIsModalOpen(true);
  };

  // Open modal for editing an existing address
  const handleEditAddress = (address: App.Data.AddressData) => {
    setAddressForm({
      id: address.id,
      first_name: address.first_name || '',
      last_name: address.last_name || '',
      address: address.address || '',
      city: address.city || '',
      state: address.state || '',
      postal_code: address.postal_code || '',
      phone: address.phone || '',
      email: address.email || '',
      country: address.country || '',
      additional_info: address.additional_info || '',
      user: address.user,
    });
    setIsEditing(true);
    setIsModalOpen(true);
  };

  // Confirm deletion of an address
  const handleConfirmDeleteAddress = (addressId: number | null) => {
    if (addressId !== null) {
      setAddressToDeleteId(String(addressId));
      setIsDeleteDialogOpen(true);
    }
  };

  // Delete an address
  const handleDeleteAddress = () => {
    if (!addressToDeleteId) return;
    
    setLoading(true);
    router.delete(route(routes.delete, { id: addressToDeleteId }), {
      preserveScroll: true,
      onSuccess: () => {
        setLoading(false);
        setIsDeleteDialogOpen(false);
        setAddressToDeleteId(null);
        // Reload address data
        router.reload({ only: ['address', 'addresses'] });
        toast.success('Address deleted successfully');
      },
      onError: () => {
        setLoading(false);
        toast.error('Failed to delete address');
      },
    });
  };

  // Save a new or edited address
  const handleSaveAddress = () => {
    setLoading(true);
    
    if (isEditing && addressForm.id) {
      // Update existing address
      router.put(route(routes.update, { id: addressForm.id }), addressForm, {
        preserveScroll: true,
        onSuccess: () => {
          setIsModalOpen(false);
          setLoading(false);
          resetForm();
          // Reload address data
          router.reload({ only: ['address', 'addresses'] });
          toast.success('Address updated successfully');
        },
        onError: () => {
          setLoading(false);
          toast.error('Failed to update address');
        },
      });
    } else {
      // Create new address
      router.post(route(routes.store), addressForm, {
        preserveScroll: true,
        onSuccess: (page) => {
          try {
            // Get the newly created address ID with safer type handling
            let newAddressId: string | null = null;
            
            // Try to get from custom_data if available
            if (page.props.custom_data && typeof page.props.custom_data === 'object') {
              const customData = page.props.custom_data as Record<string, any>;
              newAddressId = customData.address_id ? String(customData.address_id) : '';
            }
            
            // If not found, try to get from addresses array
            if (!newAddressId && page.props.addresses && Array.isArray(page.props.addresses)) {
              const addresses = page.props.addresses as App.Data.AddressData[];
              if (addresses.length > 0 && addresses[addresses.length - 1]?.id) {
                newAddressId = String(addresses[addresses.length - 1].id);
              }
            }
            
            if (newAddressId && onAddressSelect) {
              onAddressSelect(newAddressId);
            }
          } catch (error) {
            console.error('Error handling new address:', error);
          }
          
          setIsModalOpen(false);
          setLoading(false);
          resetForm();
          // Reload address data
          router.reload({ only: ['address', 'addresses'] });
          toast.success('Address added successfully');
        },
        onError: () => {
          setLoading(false);
          toast.error('Failed to add address');
        },
      });
    }
  };

  // Set an address as default
  const handleSetDefaultAddress = (addressId: number) => {
    if (routes.setDefault) {
      router.post(route(routes.setDefault, { id: addressId }), {}, {
        preserveScroll: true,
        onSuccess: () => {
          // Reload address data to refresh the UI with the new default address
          router.reload({ only: ['address', 'addresses'] });
          toast.success('Default address updated');
        },
        onError: () => {
          toast.error('Failed to set default address');
        },
      });
    }
  };

  return (
    <>
      <Card className={cardClassName}>
        <CardHeader className="flex flex-row items-center justify-between">
          <div>
            <CardTitle>{title}</CardTitle>
            <CardDescription>{description}</CardDescription>
          </div>
          {allowAdd && (
            <Button onClick={handleAddAddress} className="flex items-center gap-2">
              <Plus className="h-4 w-4" />
              Add New Address
            </Button>
          )}
        </CardHeader>
        
        <CardContent>
          {addresses.length === 0 ? (
            <div className="flex flex-col items-center justify-center py-8 text-center">
              <MapPin className="h-10 w-10 text-muted-foreground mb-3" />
              <p className="text-muted-foreground mb-4">You don't have any saved addresses yet</p>
              {allowAdd && (
                <Button onClick={handleAddAddress} className="flex items-center gap-2">
                  <Plus className="h-4 w-4" />
                  Add Your First Address
                </Button>
              )}
            </div>
          ) : (
            <div className="grid gap-4 md:grid-cols-2">
              {addresses.map((address) => (
                <div 
                  key={String(address.id)} 
                  className={`border rounded-lg p-4 relative ${
                    selectedAddressId === String(address.id) ? 'border-primary ring-1 ring-primary' : ''
                  } ${allowSelect ? 'cursor-pointer' : ''}`}
                  onClick={() => {
                    if (allowSelect && onAddressSelect) {
                      onAddressSelect(String(address.id));
                    }
                  }}
                >
                  <div className="absolute top-3 right-3 flex gap-2">
                    {allowEdit && (
                      <Button 
                        variant="ghost" 
                        size="icon" 
                        onClick={(e) => {
                          e.stopPropagation();
                          handleEditAddress(address);
                        }}
                        title="Edit address"
                      >
                        <Pencil className="h-4 w-4" />
                      </Button>
                    )}
                    {allowDelete && (
                      <Button 
                        variant="ghost" 
                        size="icon" 
                        onClick={(e) => {
                          e.stopPropagation();
                          handleConfirmDeleteAddress(address.id);
                        }}
                        title="Delete address"
                      >
                        <Trash2 className="h-4 w-4 text-destructive" />
                      </Button>
                    )}
                  </div>
                  
                  <div className="space-y-1 pr-16">
                    <p className="font-medium">
                      {address.first_name} {address.last_name}
                    </p>
                    {address.email && (
                      <p className="text-sm text-muted-foreground">
                        {address.email}
                      </p>
                    )}
                    <p className="text-sm text-muted-foreground">
                      {address.address}
                    </p>
                    <p className="text-sm text-muted-foreground">
                      {address.city}, {address.state} {address.postal_code}
                    </p>
                    {address.country && (
                      <p className="text-sm text-muted-foreground">
                        {address.country}
                      </p>
                    )}
                    {address.phone && (
                      <p className="text-sm text-muted-foreground">
                        {address.phone}
                      </p>
                    )}
                    {address.additional_info && (
                      <p className="text-sm text-muted-foreground italic mt-1">
                        {address.additional_info}
                      </p>
                    )}
                  </div>
                  
                  {routes.setDefault && !address.is_default && (
                    <div className="mt-3 pt-2 border-t">
                      <Button 
                        variant="ghost" 
                        size="sm" 
                        onClick={(e) => {
                          e.stopPropagation();
                          handleSetDefaultAddress(address.id as number);
                        }}
                        className="text-sm"
                      >
                        Set as Default
                      </Button>
                    </div>
                  )}
                </div>
              ))}
            </div>
          )}
        </CardContent>
      </Card>

      {/* Modal for adding/editing address */}
      <Dialog open={isModalOpen} onOpenChange={(open) => {
        setIsModalOpen(open);
        if (!open) resetForm();
      }}>
        <DialogContent className="sm:max-w-[500px]">
          <DialogHeader>
            <DialogTitle>{isEditing ? 'Edit Address' : 'Add New Address'}</DialogTitle>
          </DialogHeader>
          <div className="space-y-4 py-4">
            <div className="grid grid-cols-2 gap-4">
              <div className="space-y-2">
                <Label htmlFor="first_name">First Name</Label>
                <Input
                  id="first_name"
                  name="first_name"
                  value={addressForm.first_name}
                  onChange={handleInputChange}
                  placeholder="John"
                />
                {errors.first_name && <div className="text-red-500 text-sm">{errors.first_name}</div>}
              </div>
              <div className="space-y-2">
                <Label htmlFor="last_name">Last Name</Label>
                <Input
                  id="last_name"
                  name="last_name"
                  value={addressForm.last_name}
                  onChange={handleInputChange}
                  placeholder="Doe"
                />
                {errors.last_name && <div className="text-red-500 text-sm">{errors.last_name}</div>}
              </div>
            </div>
            
            <div className="grid grid-cols-2 gap-4">
              <div className="space-y-2">
                <Label htmlFor="email">Email</Label>
                <Input
                  id="email"
                  name="email"
                  type="email"
                  value={addressForm.email || ''}
                  onChange={handleInputChange}
                  placeholder="john.doe@example.com"
                />
                {errors.email && <div className="text-red-500 text-sm">{errors.email}</div>}
              </div>
              <div className="space-y-2">
                <Label htmlFor="phone">Phone Number</Label>
                <Input
                  id="phone"
                  name="phone"
                  value={addressForm.phone}
                  onChange={handleInputChange}
                  placeholder="123-456-7890"
                />
                {errors.phone && <div className="text-red-500 text-sm">{errors.phone}</div>}
              </div>
            </div>
            
            <div className="space-y-2">
              <Label htmlFor="address">Address</Label>
              <Input
                id="address"
                name="address"
                value={addressForm.address}
                onChange={handleInputChange}
                placeholder="123 Main St"
              />
              {errors.address && <div className="text-red-500 text-sm">{errors.address}</div>}
            </div>
            
            <div className="grid grid-cols-4 gap-4">
              <div className="space-y-2 col-span-2">
                <Label htmlFor="city">City</Label>
                <Input
                  id="city"
                  name="city"
                  value={addressForm.city}
                  onChange={handleInputChange}
                  placeholder="New York"
                />
                {errors.city && <div className="text-red-500 text-sm">{errors.city}</div>}
              </div>
              <div className="space-y-2">
                <Label htmlFor="state">State</Label>
                <Input 
                  id="state" 
                  name="state" 
                  value={addressForm.state} 
                  onChange={handleInputChange} 
                  placeholder="NY" 
                />
                {errors.state && <div className="text-red-500 text-sm">{errors.state}</div>}
              </div>
              <div className="space-y-2">
                <Label htmlFor="postal_code">Postal Code</Label>
                <Input 
                  id="postal_code" 
                  name="postal_code" 
                  value={addressForm.postal_code} 
                  onChange={handleInputChange} 
                  placeholder="10001" 
                />
                {errors.postal_code && <div className="text-red-500 text-sm">{errors.postal_code}</div>}
              </div>
            </div>
            
            <div className="space-y-2">
              <Label htmlFor="country">Country</Label>
              <Input
                id="country"
                name="country"
                value={addressForm.country || ''}
                onChange={handleInputChange}
                placeholder="United States"
              />
              {errors.country && <div className="text-red-500 text-sm">{errors.country}</div>}
            </div>
            
            <div className="space-y-2">
              <Label htmlFor="additional_info">Additional Information</Label>
              <Input
                id="additional_info"
                name="additional_info"
                value={addressForm.additional_info || ''}
                onChange={handleInputChange}
                placeholder="Apartment number, delivery instructions, etc."
              />
              {errors.additional_info && <div className="text-red-500 text-sm">{errors.additional_info}</div>}
            </div>
          </div>
          <DialogFooter>
            <Button variant="outline" onClick={() => setIsModalOpen(false)} disabled={loading}>
              Cancel
            </Button>
            <Button onClick={handleSaveAddress} disabled={loading}>
              {loading ? 'Saving...' : (isEditing ? 'Update Address' : 'Save Address')}
            </Button>
          </DialogFooter>
        </DialogContent>
      </Dialog>

      {/* Confirmation dialog for deleting address */}
      <AlertDialog open={isDeleteDialogOpen} onOpenChange={setIsDeleteDialogOpen}>
        <AlertDialogContent>
          <AlertDialogHeader>
            <AlertDialogTitle>Are you sure?</AlertDialogTitle>
            <AlertDialogDescription>
              This will permanently delete this address from your account.
            </AlertDialogDescription>
          </AlertDialogHeader>
          <AlertDialogFooter>
            <AlertDialogCancel disabled={loading}>Cancel</AlertDialogCancel>
            <AlertDialogAction 
              onClick={handleDeleteAddress} 
              disabled={loading}
              className="bg-destructive text-destructive-foreground hover:bg-destructive/90"
            >
              {loading ? 'Deleting...' : 'Delete Address'}
            </AlertDialogAction>
          </AlertDialogFooter>
        </AlertDialogContent>
      </AlertDialog>
    </>
  );
} 