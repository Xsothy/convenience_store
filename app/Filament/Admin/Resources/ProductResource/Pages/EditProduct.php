<?php

namespace App\Filament\Admin\Resources\ProductResource\Pages;

use App\Filament\Admin\Resources\ProductResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProduct extends EditRecord
{
    use EditRecord\Concerns\Translatable;

    public ?string $activeLocale = null;

    public static function getTranslatableLocales(): array
    {
        return ['en', 'km'];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['prices'] = $this->getRecord()->meta('prices') ?? [];
        $data['options'] = $this->getRecord()->meta('options') ?? [];

        return $data;
    }

    protected function afterSave()
    {
        $record = $this->getRecord();
        $data = $this->form->getState();

        if (isset($data['prices'])) {
            $record->meta('prices', $data['prices']);
        }
        if (isset($data['options'])) {
            $record->meta('options', $data['options']);
        }
    }

    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make()
                ->icon('heroicon-m-eye')
                ->color('info'),

            Actions\Action::make('view_on_frontend')
                ->label('View on Site')
                ->icon('heroicon-m-globe-alt')
                ->color('success')
                ->url(fn () => route('products.show', ['product' => $this->record]))
                ->openUrlInNewTab(),

            Actions\Action::make('clone')
                ->label('Clone Product')
                ->icon('heroicon-m-document-duplicate')
                ->color('gray')
                ->action(function () {
                    $record = $this->record;

                    // Clone the product
                    $clone = $record->replicate();
                    $clone->name = $record->name . ' (Copy)';
                    $clone->slug = Str::slug($clone->name);
                    $clone->is_activated = false; // Set the clone to inactive by default
                    $clone->shopify_id = null;
                    $clone->save();

                    // Clone relationships and media
                    $clone->categories()->attach($record->categories->pluck('id'));

                    // Clone media collections
                    foreach (['feature_image', 'gallery'] as $collection) {
                        foreach ($record->getMedia($collection) as $media) {
                            $media->copy($clone, $collection);
                        }
                    }

                    // Clone product metadata
                    foreach ($record->productMetas as $meta) {
                        $clone->meta($meta->key, $meta->value);
                    }

                    // Redirect to the edit page of the cloned product
                    return redirect()->to(ProductResource::getUrl('edit', ['record' => $clone]));
                }),

            Actions\DeleteAction::make()
                ->icon('heroicon-m-trash')
                ->color('danger'),

            Actions\LocaleSwitcher::make(),
        ];
    }
}
