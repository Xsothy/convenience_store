// Checkout page
import { Package, GitBranchPlusIcon, TagIcon, CreditCard, Loader2, Download } from 'lucide-react';
import { Button } from "@/components/ui/button"
import { Card, CardContent, CardFooter, CardHeader, CardTitle } from "@/components/ui/card"
import { Input } from "@/components/ui/input"
import { Separator } from "@/components/ui/separator"
import { useForm, usePage, router } from '@inertiajs/react';
import { AppLayout, ArtworkLayout } from '@/layouts';
import ShippingForm from '@/components/sections/Checkout/ShippingForm';
import PaymentForm from '@/components/sections/Checkout/PaymentForm';
import { toast } from 'sonner';
import CheckoutController from '@/wayfinder/actions/App/Http/Controllers/CheckoutController';
import { Badge } from "@/components/ui/badge"
import { useEffect } from 'react';

const Index = ({ order, token, activeSubscription }: {
    order: App.Data.OrderData,
    token: string,
    activeSubscription?: App.Data.SubscribeData
}) => {
    const products = order.ordersItems;
    const allAddress: App.Data.AddressData[] = usePage().props.address as App.Data.AddressData[];
    
    // Refresh address data when component loads to ensure we have the latest addresses
    useEffect(() => {
        router.reload({ only: ['address'] });
    }, []);
    
    const subtotal = products.reduce((acc, product) => acc + (product.price || 0) * (product.qty || 0), 0)

    // Apply subscription discount if available
    const subscriptionDiscount = order.subscription_discount_percent
        ? (subtotal * order.subscription_discount_percent / 100)
        : 0;

    // Use free shipping from subscription if applicable
    const shipping = order.has_free_shipping === true ? 0 : 4.99;

    const tax = subtotal * 0.07
    let total = subtotal - subscriptionDiscount + shipping + tax

    if(order.coupon) {
        if(order.coupon.type === 'percentage_coupon'){
            total = total - (total * order.coupon.amount / 100)
        } else {
            total = total - order.coupon.amount
        }
    }

    const couponForm = useForm({
        coupon: order.coupon?.code || '',
        token: token,
    });

    const form = useForm<{
        coupon: string;
        token: string;
        address_id: string | null;
    }>({
        coupon: order.coupon?.code || '',
        token: token,
        address_id: order.address_id ? String(order.address_id) : (allAddress[0]?.id ? String(allAddress[0].id) : null)
    });

    const handleProcessOrder = () => {
        if(!form.data.address_id){
            toast.error('Please Select Address');
            return;
        }
        form.submit(CheckoutController.orderProcess(), {
            preserveScroll: true,
        })
    }

    const handleApplyCoupon = () => {
        couponForm.submit(CheckoutController.applyCoupon(), {
            preserveScroll: true,
        })
    }

    const handleRemoveCoupon = () => {
        couponForm.submit(CheckoutController.removeCoupon(), {
            preserveScroll: true,
        })
    }

    // Count downloadable products
    const downloadableProductsCount = products.filter(item => item?.is_digital_download).length;
    return (
        <div className="bg-white mt-4">
            <div className="container mx-auto pb-6 px-6">
                <div className="grid gap-10 lg:grid-cols-12">
                    <div className="lg:col-span-6 space-y-6">
                        <div className="flex items-center gap-2 mb-8">
                            <h1 className="text-3xl font-bold tracking-tight">Checkout</h1>
                        </div>

                        {activeSubscription && activeSubscription.plan && (
                            <Card className="bg-gradient-to-r from-blue-50 to-indigo-50 border-blue-200">
                                <CardHeader>
                                    <CardTitle className="flex items-center justify-between">
                                        <div className="flex items-center gap-2">
                                            <CreditCard className="h-5 w-5 text-blue-600" />
                                            <span>Active Subscription</span>
                                        </div>
                                        <Badge variant="outline" className="bg-blue-100 text-blue-800 border-blue-200">
                                            {activeSubscription.plan.name} Plan
                                        </Badge>
                                    </CardTitle>
                                </CardHeader>
                                <CardContent>
                                    <div className="text-sm text-blue-700">
                                        <p>Your subscription benefits applied to this order:</p>
                                        <ul className="list-disc pl-5 mt-2 space-y-1">
                                            {(order.subscription_discount_percent ?? 0) > 0 ? (
                                                <li>{order.subscription_discount_percent}% discount on your purchase</li>
                                            ) : ""}
                                            {order.has_free_shipping === true ? (
                                                <li>Free shipping on eligible items</li>
                                            ) : ""}
                                            {activeSubscription.digital_downloads_remaining && activeSubscription.digital_downloads_remaining > 0 ? (
                                                <li>Digital downloads remaining: {activeSubscription.digital_downloads_remaining}</li>
                                            ) : ""}
                                        </ul>
                                    </div>
                                </CardContent>
                            </Card>
                        )}

                        {downloadableProductsCount > 0 && (
                            <Card className="bg-gradient-to-r from-purple-50 to-indigo-50 border-purple-200">
                                <CardHeader>
                                    <CardTitle className="flex items-center gap-2">
                                        <Download className="h-5 w-5 text-purple-600" />
                                        <span>Digital Products</span>
                                    </CardTitle>
                                </CardHeader>
                                <CardContent>
                                    <div className="text-sm text-purple-700">
                                        <p>You have {downloadableProductsCount} digital products in your cart.</p>
                                        <p className="mt-1">These will be available for download in your account after purchase.</p>

                                        {activeSubscription && activeSubscription.digital_downloads_remaining && activeSubscription.digital_downloads_remaining > 0 && (
                                            <div className="mt-3 p-2 bg-blue-100 rounded-md text-blue-800">
                                                <p className="font-medium">Subscription Benefits</p>
                                                <p>You can use your subscription to get free digital downloads!</p>
                                            </div>
                                        )}
                                    </div>
                                </CardContent>
                            </Card>
                        )}

                        <ShippingForm
                            allAddress={allAddress}
                            form={form}
                        />

                        <PaymentForm />

                        <div className="flex justify-end">
                            <Button className="cursor-pointer" onClick={handleProcessOrder}>
                                {form.processing ? <Loader2 className="h-4 w-4 mr-2 animate-spin" /> : <GitBranchPlusIcon className="h-4 w-4 mr-2 " />}
                                Process
                            </Button>
                        </div>
                    </div>

                    <div className="lg:col-span-6">
                        <Card className="sticky top-14">
                            <CardHeader>
                                <CardTitle className="flex items-center gap-2">
                                    <Package className="h-5 w-5" />
                                    Order Summary
                                </CardTitle>
                            </CardHeader>
                            <CardContent className="space-y-4">
                                {products.map((product) => (
                                    <div key={product.id} className="flex items-center gap-4">
                                        <img
                                            src={product?.product?.feature_image || "/placeholder.svg"}
                                            alt={product?.product?.slug || "Product"}
                                            className="h-20 w-20 rounded-md object-cover"
                                        />
                                        <div className="flex-1">
                                            <div className="flex items-center">
                                                <h3 className="font-medium">{(typeof product.product?.name === 'object' ? product.product?.name?.en : product.product?.name)}</h3>
                                                {product?.is_downloadable && (
                                                    <Badge variant="outline" className="ml-2 bg-purple-100 text-purple-800 border-purple-200">
                                                        <Download className="h-3 w-3 mr-1" />
                                                        Digital
                                                    </Badge>
                                                )}
                                                {('variant' in product.options) && 
                                                    <Badge variant="outline" className="ml-2 bg-gray-100 text-gray-800 border-gray-200">
                                                        Size: {String(product.options.variant)}
                                                    </Badge>
                                                }
                                            </div>
                                            <p className="text-sm text-muted-foreground">Quantity: {product?.qty}</p>

                                            {/* Show if product uses subscription benefit */}
                                            {product.apply_subscription_digital_print && (
                                                <Badge className="mt-1 bg-blue-100 text-blue-800 border-0">
                                                    Free with subscription
                                                </Badge>
                                            )}
                                        </div>
                                        <div className="text-right">
                                            {product.original_price ? (
                                                <div>
                                                    <p className="font-medium">${(product?.price || 0).toFixed(2)}</p>
                                                    <p className="text-sm line-through text-muted-foreground">
                                                        ${product.original_price.toFixed(2)}
                                                    </p>
                                                </div>
                                            ) : (
                                                <p className="font-medium">${(product?.price || 0).toFixed(2)}</p>
                                            )}
                                        </div>
                                    </div>
                                ))}
                                <Separator />
                                <div className="space-y-1.5">
                                    <div className="flex justify-between">
                                        <span className="text-muted-foreground">Subtotal</span>
                                        <span>${subtotal.toFixed(2)}</span>
                                    </div>
                                    {(order.subscription_discount_percent ?? 0) > 0 && (
                                        <div className="flex justify-between text-blue-600">
                                            <span className="flex items-center">
                                                <CreditCard className="h-4 w-4 mr-1" />
                                                Subscription Discount
                                            </span>
                                            <span>-${subscriptionDiscount.toFixed(2)} ({order.subscription_discount_percent}%)</span>
                                        </div>
                                    )}
                                    {!! order.coupon && (
                                        <div className="flex justify-between text-green-600">
                                            <span className="flex items-center">
                                                <TagIcon className="h-4 w-4 mr-1" />
                                                Discount {order.coupon.code && `(${order.coupon.code})`}
                                            </span>
                                            {
                                                order.coupon.type === 'percentage_coupon' ? (
                                                    <span>-${(subtotal * order.coupon.amount / 100).toFixed(2)} (${order.coupon.amount}%)</span>
                                                ) : (
                                                    <span>-${order.coupon.amount.toFixed(2)}</span>
                                                )
                                            }
                                        </div>
                                    )}
                                    <div className="flex justify-between">
                                        <span className="text-muted-foreground">Shipping</span>
                                        {order.has_free_shipping === true ? (
                                            <span className="text-blue-600">Free</span>
                                        ) : (
                                            <span>${shipping.toFixed(2)}</span>
                                        )}
                                    </div>
                                    <div className="flex justify-between">
                                        <span className="text-muted-foreground">Tax</span>
                                        <span>${tax.toFixed(2)}</span>
                                    </div>
                                </div>
                                <Separator />
                                <div className="flex justify-between font-medium text-lg">
                                    <span>Total</span>
                                    <span>${total.toFixed(2)}</span>
                                </div>
                            </CardContent>
                            <CardFooter>
                                <div className="space-y-2 w-full">
                                    <div className="relative">
                                        <div className="absolute inset-0 flex items-center">
                                            <span className="w-full border-t" />
                                        </div>
                                        <div className="relative flex justify-center text-xs uppercase">
                                            <span className="bg-white px-2 text-muted-foreground">Promo Code</span>
                                        </div>
                                    </div>
                                    { !order.coupon ? (
                                        <div className="flex gap-2">
                                            <Input
                                                placeholder="Enter code"
                                            className="flex-1"
                                            value={couponForm.data.coupon}
                                            onChange={(e) => couponForm.setData("coupon", e.target.value)}
                                        />
                                        <Button variant="success" onClick={handleApplyCoupon}>
                                            {couponForm.processing ? <Loader2 className="h-4 w-4 mr-2 animate-spin" /> : ''}
                                            Apply
                                        </Button>
                                        </div>
                                    ) : (
                                        <div className="flex gap-2">
                                            <Input
                                                placeholder="Enter code"
                                            className="flex-1"
                                            value={couponForm.data.coupon}
                                            readOnly
                                        />
                                        <Button variant="destructive" disabled={form.processing} onClick={handleRemoveCoupon}>
                                            {couponForm.processing ? <Loader2 className="h-4 w-4 mr-2 animate-spin" /> : ''}
                                            Remove
                                        </Button>
                                        </div>
                                    )}
                                </div>
                            </CardFooter>
                        </Card>
                    </div>
                </div>
            </div>
        </div>
    )
};

Index.layout = page => (
    <AppLayout>
        <ArtworkLayout children={page} />
    </AppLayout>
)

export default Index;
