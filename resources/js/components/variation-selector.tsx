import React from "react";
import { cn } from "@/lib/utils";
import { RadioGroup, RadioGroupItem } from "@/components/ui/radio-group";
import { Label } from "@/components/ui/label";
import { Form, FormControl, FormField, FormItem } from "@/components/ui/form";
import { useForm } from "react-hook-form";

interface VariationSelectorProps {
    variation: App.Data.VariationData;
    onSelect: (value: string) => void;
    selectedValue?: string;
    className?: string;
}

const VariationSelector = ({
                               variation,
                               onSelect,
                               selectedValue,
                               className,
                           }: VariationSelectorProps) => {
    const form = useForm({
        defaultValues: {
            [variation.name || 'variation']: selectedValue || variation.value[0]?.value || '',
        },
    });

    const handleValueChange = (value: string) => {
        onSelect(value);
    };

    return (
        <div className={cn("space-y-4", className)}>
            <h2 className="text-lg font-medium text-gray-700">{variation.name}</h2>

            <Form {...form}>
                <FormField
                    control={form.control}
                    name={variation.name || 'variation'}
                    render={({ field }) => (
                        <FormItem>
                            <FormControl>
                                <RadioGroup
                                    onValueChange={(value) => {
                                        field.onChange(value);
                                        handleValueChange(value);
                                    }}
                                    defaultValue={field.value}
                                    className="flex flex-wrap gap-3"
                                >
                                    {variation.value.map((option, index) => (
                                        <div key={index} className="flex items-center">
                                            <RadioGroupItem
                                                value={option.value || ''}
                                                id={`${variation.name}-${index}`}
                                                className="sr-only"
                                            />
                                            <Label
                                                htmlFor={`${variation.name}-${index}`}
                                                className={cn(
                                                    "flex cursor-pointer items-center justify-center rounded-lg border px-6 py-3 text-center transition-all",
                                                    field.value === option.value
                                                        ? "border-black bg-black text-white"
                                                        : "border-gray-200 hover:border-gray-300"
                                                )}
                                            >
                                                {option.value}
                                            </Label>
                                        </div>
                                    ))}
                                </RadioGroup>
                            </FormControl>
                        </FormItem>
                    )}
                />
            </Form>
        </div>
    );
};

export default VariationSelector;
