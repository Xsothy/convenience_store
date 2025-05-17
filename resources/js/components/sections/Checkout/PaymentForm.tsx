"use client"

import type React from "react"

import { useState } from "react"
import { CreditCard, Wallet } from "lucide-react"
import { Card, CardHeader, CardTitle, CardContent } from "@/components/ui/card"
import { Label } from "@/components/ui/label"
import { RadioGroup, RadioGroupItem } from "@/components/ui/radio-group"

export default function PaymentForm() {
    const [paymentMethod, setPaymentMethod] = useState<"stripe" | "paypal">("stripe")

    return (
        <div className="max-w-4xl mx-auto">
                <Card>
                    <CardHeader className="py-3 px-4 sm:py-4 sm:px-6">
                        <CardTitle className="flex items-center gap-2 text-base sm:text-lg">
                            <Wallet className="h-4 w-4 sm:h-5 sm:w-5" />
                            Payment Method
                        </CardTitle>
                    </CardHeader>
                    <CardContent className="py-3 px-4 sm:py-4 sm:px-6">
                        <div className="flex flex-col md:flex-row gap-4 sm:gap-6">
                            {/* Payment Method Selection */}
                            <div className="md:w-1/3">
                                <RadioGroup
                                    value={paymentMethod}
                                    onValueChange={(value) => setPaymentMethod(value as "stripe" | "paypal")}
                                    className="flex flex-col gap-2 sm:gap-3"
                                >
                                    <div className="relative">
                                        <RadioGroupItem value="stripe" id="stripe" className="peer sr-only" />
                                        <Label
                                            htmlFor="stripe"
                                            className="flex items-center gap-2 sm:gap-3 rounded-md border-2 border-muted bg-popover p-3 sm:p-4 hover:bg-accent hover:text-accent-foreground peer-data-[state=checked]:border-primary [&:has([data-state=checked])]:border-primary"
                                        >
                                            <CreditCard className="h-4 w-4 sm:h-5 sm:w-5 flex-shrink-0" />
                                            <div>
                                                <p className="text-sm sm:text-base font-medium leading-none">Stripe</p>
                                                <p className="text-xs text-muted-foreground mt-1">Pay with Stripe</p>
                                            </div>
                                        </Label>
                                    </div>

                                    <div className="relative">
                                        <RadioGroupItem value="paypal" id="paypal" className="peer sr-only" />
                                        <Label
                                            htmlFor="paypal"
                                            className="flex items-center gap-2 sm:gap-3 rounded-md border-2 border-muted bg-popover p-3 sm:p-4 hover:bg-accent hover:text-accent-foreground peer-data-[state=checked]:border-primary [&:has([data-state=checked])]:border-primary"
                                        >
                                            <div className="h-4 sm:h-5 w-auto font-bold text-[#0070BA] flex-shrink-0">PayPal</div>
                                            <div>
                                                <p className="text-sm sm:text-base font-medium leading-none">PayPal</p>
                                                <p className="text-xs text-muted-foreground mt-1">Pay with PayPal</p>
                                            </div>
                                        </Label>
                                    </div>
                                </RadioGroup>
                            </div>

                            {/* Payment Details */}
                            <div className="md:w-2/3 border-t md:border-t-0 md:border-l border-border pt-4 md:pt-0 md:pl-4 sm:md:pl-6">
                                {paymentMethod === "stripe" ? (
                                    <div className="flex flex-col items-center justify-center py-4 sm:py-6 space-y-3 sm:space-y-4">
                                        <div className="bg-[#0070BA] text-white px-3 sm:px-4 py-1.5 sm:py-2 rounded-md font-bold text-lg sm:text-xl">
                                            Stripe
                                        </div>
                                        <p className="text-center text-xs sm:text-sm text-muted-foreground px-2 sm:px-0">
                                            You will be redirected to Stripe to complete your payment securely.
                                        </p>
                                    </div>
                                ) : (
                                    <div className="flex flex-col items-center justify-center py-4 sm:py-6 space-y-3 sm:space-y-4">
                                        <div className="bg-[#0070BA] text-white px-3 sm:px-4 py-1.5 sm:py-2 rounded-md font-bold text-lg sm:text-xl">
                                            Pay<span className="text-[#003087]">Pal</span>
                                        </div>
                                        <p className="text-center text-xs sm:text-sm text-muted-foreground px-2 sm:px-0">
                                            You will be redirected to PayPal to complete your payment securely.
                                        </p>
                                    </div>
                                )}
                            </div>
                        </div>
                    </CardContent>
                </Card>
        </div>
    )
}
