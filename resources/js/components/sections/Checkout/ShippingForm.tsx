import React from "react"
import { Truck } from "lucide-react"
import { Card, CardHeader, CardTitle, CardContent } from "@/components/ui/card"
import { InertiaFormProps } from '@inertiajs/react';
import AddressManager from "@/components/shared/AddressManager";
import { addressRoutes } from '@/utils/addressRoutes';

interface ShippingFormProps {
    allAddress: App.Data.AddressData[];
    form: InertiaFormProps<{
        address_id: string | null;
        coupon: string;
        token: string;
    }>;
    allowAddNew?: boolean;
    standalone?: boolean;
}

export default function ShippingForm({
    allAddress,
    form,
    allowAddNew = true,
    standalone = false
}: ShippingFormProps) {
    const selectedAddress = form.data.address_id;
    
    // Determine if authenticated by checking if any address has a user_id
    const isAuthenticated = allAddress.some(address => address.user_id !== null);
    
    // Get the appropriate routes based on authentication status
    const routes = isAuthenticated ? addressRoutes.authenticated : addressRoutes.unauthenticated;
    
    // Handle address selection
    const handleAddressSelect = (addressId: string) => {
        form.setData("address_id", addressId);
    };

    return (
        <>
            <Card className={standalone ? "mb-6" : ""}>
                <CardHeader>
                    <CardTitle className="flex items-center gap-2">
                        <Truck className="h-5 w-5" />
                        Shipping Information
                    </CardTitle>
                </CardHeader>
                <CardContent>
                    <AddressManager 
                        addresses={allAddress}
                        onAddressSelect={handleAddressSelect}
                        selectedAddressId={selectedAddress}
                        allowAdd={allowAddNew}
                        title=""
                        description=""
                        cardClassName="border-0 shadow-none p-0 m-0"
                        routes={routes}
                    />
                </CardContent>
            </Card>
        </>
    );
}
