<?php

namespace App\Filament\Admin\Pages;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Pages\Actions\Action;
use Filament\Pages\SettingsPage;
use TomatoPHP\FilamentSettingsHub\Traits\UseShield;

class ContentSettings extends SettingsPage
{
    use UseShield;

    protected static ?string $navigationIcon = 'heroicon-o-cog';

    protected static string $settings = \App\Settings\ContentSettings::class;

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public function getTitle(): string
    {
        return 'Content Settings';
    }

    protected function getActions(): array
    {
        return [
            Action::make('back')->action(fn () => redirect()->route('filament.' . filament()->getCurrentPanel()->getId() . '.pages.settings-hub'))->color('danger')->label(trans('filament-settings-hub::messages.back')),
        ];
    }

    protected function getFormSchema(): array
    {
        return [
            Section::make('Hero Section')
                ->schema([
                    Repeater::make('hero_slides')
                        ->schema([
                            TextInput::make('title')
                                ->required()
                                ->label('Title'),
                            Textarea::make('subtitle')
                                ->required()
                                ->label('Subtitle'),
                            TextInput::make('button_text')
                                ->required()
                                ->label('Button Text'),
                            TextInput::make('button_link')
                                ->required()
                                ->label('Button Link'),
                            FileUpload::make('image')
                                ->image()
                                ->required()
                                ->label('Background Image')
                                ->directory('hero-images'),
                        ])
                        ->itemLabel(fn (array $state): ?string => $state['title'] ?? null)
                        ->collapsible()
                        ->defaultItems(1)
                        ->minItems(1)
                        ->maxItems(5)
                        ->reorderable(),
                ]),
            
            Section::make('Categories Section')
                ->schema([
                    TextInput::make('categories_title')
                        ->required()
                        ->label('Title'),
                    Textarea::make('categories_subtitle')
                        ->required()
                        ->label('Subtitle'),
                ]),
            
            Section::make('Services Section')
                ->schema([
                    TextInput::make('services_title')
                        ->required()
                        ->label('Title'),
                    Textarea::make('services_subtitle')
                        ->required()
                        ->label('Subtitle'),
                    
                    Repeater::make('services')
                        ->schema([
                            TextInput::make('title')
                                ->required()
                                ->label('Title'),
                            Textarea::make('description')
                                ->required()
                                ->label('Description'),
                            Select::make('icon')
                                ->required()
                                ->options([
                                    'Palette' => 'Palette',
                                    'RefreshCcw' => 'Refresh',
                                    'Percent' => 'Percent',
                                    'Printer' => 'Printer',
                                    'Heart' => 'Heart',
                                    'ShoppingCart' => 'Shopping Cart',
                                    'Truck' => 'Truck',
                                    'Shield' => 'Shield',
                                ])
                                ->label('Icon'),
                        ])
                        ->itemLabel(fn (array $state): ?string => $state['title'] ?? null)
                        ->collapsible()
                        ->defaultItems(4)
                        ->minItems(1)
                        ->maxItems(8)
                        ->reorderable(),
                ]),
            
            Section::make('Featured Products Section')
                ->schema([
                    TextInput::make('featured_products_title')
                        ->required()
                        ->label('Title'),
                    Textarea::make('featured_products_subtitle')
                        ->required()
                        ->label('Subtitle'),
                ]),
            
            Section::make('Testimonials Section')
                ->schema([
                    TextInput::make('testimonials_title')
                        ->required()
                        ->label('Title'),
                    Textarea::make('testimonials_subtitle')
                        ->required()
                        ->label('Subtitle'),
                    
                    Repeater::make('testimonials')
                        ->schema([
                            Textarea::make('quote')
                                ->required()
                                ->label('Quote'),
                            TextInput::make('author')
                                ->required()
                                ->label('Author Name'),
                            TextInput::make('role')
                                ->required()
                                ->label('Author Role'),
                            Select::make('rating')
                                ->required()
                                ->options([
                                    1 => '1 Star',
                                    2 => '2 Stars',
                                    3 => '3 Stars',
                                    4 => '4 Stars',
                                    5 => '5 Stars',
                                ])
                                ->default(5)
                                ->label('Rating'),
                            FileUpload::make('image')
                                ->image()
                                ->label('Author Image')
                                ->directory('testimonial-images'),
                        ])
                        ->itemLabel(fn (array $state): ?string => $state['author'] ?? null)
                        ->collapsible()
                        ->defaultItems(3)
                        ->minItems(1)
                        ->maxItems(10)
                        ->reorderable(),
                ]),
            
            Section::make('Discounted Products Section')
                ->schema([
                    TextInput::make('discounted_products_title')
                        ->required()
                        ->label('Title'),
                    Textarea::make('discounted_products_subtitle')
                        ->required()
                        ->label('Subtitle'),
                ]),
        ];
    }
}
