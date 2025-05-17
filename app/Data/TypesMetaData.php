<?php

namespace App\Data;

use App\Models\TypesMeta;
use Carbon\Carbon;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class TypesMetaData extends Data
{
    public function __construct(
        public ?int $id = null,
        public ?int $type_id = null,
        public ?int $model_id = null,
        public ?string $model_type = null,
        public ?string $key = null,
        public mixed $value = null,
        public ?Carbon $created_at = null,
        public ?Carbon $updated_at = null,
        public ?TypeData $type = null,
    ) {}

    public static function fromModel(TypesMeta $typesMeta): self
    {
        return new self(
            id: $typesMeta->id,
            type_id: $typesMeta->type_id,
            model_id: $typesMeta->model_id,
            model_type: $typesMeta->model_type,
            key: $typesMeta->key,
            value: $typesMeta->value,
            created_at: $typesMeta->created_at,
            updated_at: $typesMeta->updated_at,
            type: $typesMeta->type ? TypeData::fromModel($typesMeta->type) : null,
        );
    }
}
