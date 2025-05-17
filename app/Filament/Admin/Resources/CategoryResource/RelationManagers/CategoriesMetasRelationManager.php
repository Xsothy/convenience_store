<?php

namespace App\Filament\Admin\Resources\CategoryResource\RelationManagers;

use App\Models\CategoriesMeta;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CategoriesMetasRelationManager extends RelationManager
{
    protected static string $relationship = 'categoriesMetas';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('category_id')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->required(),

                TextInput::make('model_id')
                    ->required()
                    ->integer(),

                TextInput::make('model_type')
                    ->required(),

                TextInput::make('key')
                    ->required(),

                TextInput::make('value')
                    ->required(),

                Placeholder::make('created_at')
                    ->label('Created Date')
                    ->content(fn (?CategoriesMeta $record): string => $record?->created_at?->diffForHumans() ?? '-'),

                Placeholder::make('updated_at')
                    ->label('Last Modified Date')
                    ->content(fn (?CategoriesMeta $record): string => $record?->updated_at?->diffForHumans() ?? '-'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                TextColumn::make('category.name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('model_id'),

                TextColumn::make('model_type'),

                TextColumn::make('key'),

                TextColumn::make('value'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
