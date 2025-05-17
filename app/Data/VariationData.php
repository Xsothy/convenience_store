<?php

namespace App\Data;

use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]

class VariationData extends Data
{
    public function __construct(
        public ?string $name,
        /** @var VariationValueData[] $value */
        #[DataCollectionOf(VariationValueData::class)]
        public DataCollection $value
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'] ?? null,
            value: VariationValueData::collect(array_map(fn ($value) => VariationValueData::fromArray($value), $data['values'] ?? []), DataCollection::class),
        );
    }
}
