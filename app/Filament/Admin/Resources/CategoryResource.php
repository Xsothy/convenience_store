<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\CategoryResource\Pages;
use App\Models\Category;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ForceDeleteAction;
use Filament\Tables\Actions\ForceDeleteBulkAction;
use Filament\Tables\Actions\RestoreAction;
use Filament\Tables\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CategoryResource extends Resource
{
    use Translatable;

    protected static ?string $model = Category::class;

    //    protected static ?string $modelLabel = 'Collection';
    //    protected static ?string $pluralModelLabel = 'Collections';

    //    protected static ?string $slug = 'collections';

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    protected static ?int $navigationSort = 20;

    public static function getNavigationGroup(): ?string
    {
        return 'Product Management';
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('parent_id')
                    ->searchable()
                    ->options(Category::query()
                        ->where('for', 'product')
                        ->where('type', 'category')
                        ->pluck('name', 'id')
                        ->toArray()
                    ),

                Hidden::make('for')
                    ->default('product')
                    ->required(),

                Hidden::make('type')
//                    ->options([
//                        'category' => 'Category',
//                        'tag' => 'Tag',
//                    ])
                    ->default('category')
                    ->required(),

                TextInput::make('name')
                    ->translateLabel()
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state))),

                TextInput::make('slug')
                    ->translateLabel()
                    ->required()
                    ->unique(Category::class, 'slug', fn ($record) => $record),

                TextInput::make('description')
                    ->required(),

                //                TextInput::make('icon')
                //                    ->required(),

                //                ColorPicker::make('color')
                //                    ->required(),

                Toggle::make('is_active'),

                Toggle::make('show_in_menu'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //                TextColumn::make('parent_id'),
                //
                //                TextColumn::make('for'),
                //
                //                TextColumn::make('type'),

                TextColumn::make('name')
                    ->translateLabel()
                    ->searchable()
                    ->sortable(),

                TextColumn::make('slug')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('products_count')
                    ->label('Products')
                    ->counts('products')
                    ->sortable(),

                TextColumn::make('description')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->translateLabel(),

                //                TextColumn::make('icon'),
                //
                //                TextColumn::make('color'),

                ToggleColumn::make('is_active')
                    ->disabled(fn (Category $category) => ! Auth::user()->can('update', $category)),

                ToggleColumn::make('show_in_menu')
                    ->disabled(fn (Category $category) => ! Auth::user()->can('update', $category)),

            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
                RestoreAction::make(),
                ForceDeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('products_count', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            //            CategoriesMetasRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'slug'];
    }
}
