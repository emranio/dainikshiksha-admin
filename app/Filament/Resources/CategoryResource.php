<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;

class CategoryResource extends Resource
{
    protected static ?string $model =   Category::class;
    protected static ?string $slug = 'categories';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')
                    ->required()
                    ->maxValue(255)
                    ->columnSpanFull()
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (string $operation, $state, Forms\Set $set) => $operation === 'create' ? $set('slug', Str::slug($state)) : null),

                TextInput::make('slug')
                    ->disabled()
                    ->dehydrated()
                    ->required()
                    ->unique(Category::class, 'slug', ignoreRecord: true),
                
                TextInput::make('seo_title')
                    ->label('Title for Social Share & SEO')
                    ->required(),

                Textarea::make('seo_description')
                    ->label('Catchy Description for Social Share & SEO')
                    ->columnSpanFull()
                    ->required(),

                ColorPicker::make('color')
                    ->required(),

                Select::make('language')
                    ->options([
                        'en' => 'English',
                        'bn' => 'Bangla',
                    ])
                    ->default('bn')
                    ->required(),
                
                Hidden::make('created_by')
                    ->default(auth()->user()->id)
                    ->required(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->sortable(),

                ColorColumn::make('color'),
                    
                TextColumn::make('language')
                    ->getStateUsing(fn (Category $record): string => $record->language == 'en' ? 'en' : 'bn')
                    ->badge()
                    ->color(fn (Category $record): string => $record->language == 'en' ? 'success' : 'warning'),

                TextColumn::make('updated_at')
                    ->label('Last Updated')
                    ->sortable()
                    ->date(),

                TextColumn::make('created_at')
                    ->label('Created')
                    ->sortable()
                    ->date(),
            ])
            ->filters([
                SelectFilter::make('language')
                    ->options([
                        'en' => 'English',
                        'bn' => 'Bangla',
                    ])
                ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->paginated([25]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageCategories::route('/'),
        ];
    }
}
