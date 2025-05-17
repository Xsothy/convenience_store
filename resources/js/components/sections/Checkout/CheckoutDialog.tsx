import {
    Dialog,
    DialogContent,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from "@/components/ui/dialog"
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from "@/components/ui/select";
import { useState } from "react";

export default function CartDialog() {
    const [step, setStep] = useState(1);
    const [formData, setFormData] = useState({
        name: "",
        email: "",
        address: "",
        paymentMethod: "",
    });

    const handleNext = () => {
        if (step < 3) setStep(step + 1);
    };

    const handleBack = () => {
        if (step > 1) setStep(step - 1);
    };

    const handleChange = (field: string, value: string) => {
        setFormData(prev => ({ ...prev, [field]: value }));
    };

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        console.log("Order Placed", formData);
        // You can send this to your backend here
    };

    return (
        <Dialog>
            <DialogTrigger asChild>
                <Button className="block w-full rounded-lg bg-black py-2 text-center font-medium text-white transition-colors hover:bg-gray-800 cursor-pointer">
                    Checkout
                </Button>
            </DialogTrigger>
            <DialogContent className="max-w-md">
                <DialogHeader>
                    <DialogTitle>Checkout</DialogTitle>
                </DialogHeader>

                <form onSubmit={handleSubmit} className="space-y-4">
                    {/* Stepper UI */}
                    <div className="flex justify-between text-sm font-medium mb-4">
                        <span className={step === 1 ? "text-black" : "text-gray-500"}>1. Info</span>
                        <span className={step === 2 ? "text-black" : "text-gray-500"}>2. Shipping</span>
                        <span className={step === 3 ? "text-black" : "text-gray-500"}>3. Payment</span>
                    </div>

                    {/* Step 1: User Info */}
                    {step === 1 && (
                        <>
                            <div>
                                <Label htmlFor="name">Full Name</Label>
                                <Input
                                    id="name"
                                    value={formData.name}
                                    onChange={(e) => handleChange("name", e.target.value)}
                                    required
                                />
                            </div>
                            <div>
                                <Label htmlFor="email">Email</Label>
                                <Input
                                    id="email"
                                    type="email"
                                    value={formData.email}
                                    onChange={(e) => handleChange("email", e.target.value)}
                                    required
                                />
                            </div>
                        </>
                    )}

                    {/* Step 2: Shipping */}
                    {step === 2 && (
                        <div>
                            <Label htmlFor="address">Shipping Address</Label>
                            <Textarea
                                id="address"
                                value={formData.address}
                                onChange={(e) => handleChange("address", e.target.value)}
                                required
                            />
                        </div>
                    )}

                    {/* Step 3: Payment */}
                    {step === 3 && (
                        <div>
                            <Label>Payment Method</Label>
                            <Select
                                onValueChange={(value) => handleChange("paymentMethod", value)}
                                value={formData.paymentMethod}
                                required
                            >
                                <SelectTrigger>
                                    <SelectValue placeholder="Select payment method" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem value="credit">Credit Card</SelectItem>
                                    <SelectItem value="paypal">PayPal</SelectItem>
                                    <SelectItem value="cod">Cash on Delivery</SelectItem>
                                </SelectContent>
                            </Select>
                        </div>
                    )}

                    {/* Buttons */}
                    <div className="flex justify-between pt-4">
                        {step > 1 ? (
                            <Button variant="outline" type="button" onClick={handleBack}>
                                Back
                            </Button>
                        ) : <div />}

                        {step < 3 ? (
                            <Button type="button" onClick={handleNext}>
                                Next
                            </Button>
                        ) : (
                            <Button type="submit" className="bg-black text-white hover:bg-gray-800">
                                Place Order
                            </Button>
                        )}
                    </div>
                </form>
            </DialogContent>
        </Dialog>
    );
}
