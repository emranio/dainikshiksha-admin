<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AuthorResource\Pages;
use App\Models\Author;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource as FResource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\TextColumn;
use RalphJSmit\Filament\MediaLibrary\Forms\Components\MediaPicker;
use RalphJSmit\Filament\MediaLibrary\Tables\Columns\MediaColumn;

class AuthorResource extends FResource
{
    protected static ?string $model = Author::class;
    protected static ?string $slug = 'authors';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')
                    ->label('Author Name')
                    ->columnSpanFull()
                    ->required(),

                Textarea::make('bio')
                    ->label('Author Bio')
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

                MediaPicker::make('thumbnail')
                        ->label('Author Photo/ Thumbnail')
                        ->columnSpanFull()
                        ->required(),

                Hidden::make('created_by')
                    ->default(auth()->user()->id)
                    ->required(),
            ])->extraAttributes(['class' => 'fi-has-media-picker']);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->sortable(),

                MediaColumn::make('photo')
                    ->circular()
                    ->size(40),

                ColorColumn::make('color')
                    ->label('Color'),

                TextColumn::make('language')
                    ->getStateUsing(fn (Author $record): string => $record->language == 'en' ? 'en' : 'bn')
                    ->badge()
                    ->color(fn (Author $record): string => $record->language == 'en' ? 'success' : 'warning'),

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
                \Filament\Tables\Filters\SelectFilter::make('language')
                    ->options([
                        'en' => 'English',
                        'bn' => 'Bangla',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->groupedBulkActions([
                Tables\Actions\DeleteBulkAction::make()
                    ->action(function () {
                        Notification::make()
                            ->title('Now, now, don\'t be cheeky, leave some records for others to play with!')
                            ->warning()
                            ->send();
                    }),
            ])
            ->paginated([25]);;
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
            'index' => Pages\ListAuthor::route('/'),
            'create' => Pages\CreateAuthor::route('/create'),
            'edit' => Pages\EditAuthor::route('/{record}/edit'),
        ];
    }
}
