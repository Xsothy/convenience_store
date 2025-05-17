<?php

namespace App\Data;

use Carbon\Carbon;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class TypeData extends Data
{
    public function __construct(
        public ?int $id = null,
        public ?int $parent_id = null,
        public ?int $order = null,
        public ?string $for = null,
        public ?string $name = null,
        public ?string $key = null,
        public ?string $type = null,
        public ?string $description = null,
        public ?string $color = null,
        public ?string $icon = null,
        public ?string $model_type = null,
        public ?int $model_id = null,
        public ?bool $is_activated = null,
        public ?Carbon $created_at = null,
        public ?Carbon $updated_at = null,
    ) {}

    public static function fromModel(\App\Models\Type $type): self
    {
        return new self(
            id: $type->id,
            parent_id: $type->parent_id,
            order: $type->order,
            for: $type->for,
            name: $type->name,
            key: $type->key,
            type: $type->type,
            description: $type->description,
            color: $type->color,
            icon: $type->icon,
            model_type: $type->model_type,
            model_id: $type->model_id,
            is_activated: $type->is_activated,
            created_at: $type->created_at,
            updated_at: $type->updated_at,
        );
    }
}
