<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PollResource\Pages;
use App\Models\Poll;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Illuminate\Support\Str;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PollResource extends Resource
{
    protected static ?string $model =   Poll::class;
    protected static ?string $slug = 'poll';
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'Activity';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('question')
                    ->label('Question')
                    ->columnSpanFull()
                    ->required(),

                Textarea::make('story')
                    ->label('Story')
                    ->columnSpanFull()
                    ->required(),

                Grid::make(2)
                    ->schema([
                        Select::make('language')
                            ->label('Language')
                            ->options([
                                'en' => 'English',
                                'bn' => 'Bangla',
                            ])
                            ->default('bn'),

                        ColorPicker::make('color')
                            ->label('Color')
                            ->default('#000000'),
                        
                        DateTimePicker::make('created_at')
                            ->label('Created Date / When the poll will be opened?')
                            ->default(now()->format('Y-m-d H:i:s'))
                            ->required(),

                        DateTimePicker::make('expire_at')
                            ->label('Expire Date/ When the poll will be closed?')
                            ->required(),
                        
                        Toggle::make('published')
                            ->label('Published')
                            ->default(false),
                        
                        Toggle::make('multiple_choice')
                            ->label('Enable Multiple Choice')
                            ->default(false),
                ]),

                Repeater::make('poll_options')
                    ->schema([
                        TextInput::make('option_label')
                            ->required(),

                        Hidden::make('option_key')->dehydrated()->default(fn () => Str::slug(Str::random(10))),
                    ])
                    ->addActionLabel('Add new option')
                    ->reorderableWithButtons()
                    ->itemLabel(fn (array $state): ?string => $state['name'] ?? null)
                    ->cloneable()
                    ->collapsible()
                    ->minItems(2)
                    ->maxItems(5)
                    ->deleteAction(
                        fn (Action $action) => $action->requiresConfirmation(),
                    )
                    ->columnSpanFull()
                    ->required(),

                Hidden::make('created_by')
                    ->default(auth()->id()),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('question')
                    ->searchable()
                    ->sortable(),

                ColorColumn::make('color')
                    ->label('Color'),

                TextColumn::make('language')
                    ->getStateUsing(fn (Poll $record): string => $record->language == 'en' ? 'en' : 'bn')
                    ->badge()
                    ->color(fn (Poll $record): string => $record->language == 'en' ? 'success' : 'warning'),

                TextColumn::make('expire_at')
                    ->label('Expire Date')
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
                        'bn' => 'Bangla',
                        'en' => 'English',
                    ])
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->paginated([25]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPoll::route('/'),
            'create' => Pages\CreatePoll::route('/create'),
            'edit' => Pages\EditPoll::route('/{record}/edit'),
        ];
    }
}
