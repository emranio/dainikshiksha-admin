<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CommentResource\Pages;
use App\Models\Comment;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class CommentResource extends Resource
{
    protected static ?string $model =   Comment::class;
    protected static ?string $slug = 'comments';
    protected static ?string $navigationGroup = 'Activity';
    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function form(Form $form): Form
    {
        return $form
            ->columns([
                'sm' => 1,
                'xl' => 3,
                '2xl' => 3,
            ])
            ->schema([
                Select::make('news')
                    ->relationship(name: 'news', titleAttribute: 'title')
                    ->disabled()
                    ->columnSpanFull(),

                Textarea::make('comment_body')
                    ->label('Comment')
                    ->required()
                    ->rows(7)
                    ->columnSpanFull(),

                Select::make('approved')
                    ->label('Approval Status')
                    ->required()
                    ->options([
                        '0' => 'Pending',
                        '1' => 'Approved',
                    ]),
                DateTimePicker::make('created_at')
                    ->disabled(),

                Select::make('created_by')
                    ->relationship(name: 'user', titleAttribute: 'email')
                    ->disabled()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('comment_body')
                    ->label('Comment')
                    ->words(10)
                    ->extraAttributes(['class' => 'long-text-cell'])
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();
                        return $state;
                    }),
                    
                TextColumn::make('news.title')
                    ->wrap()
                    // ->url(fn ($record) => route('news.show', $record->news->slug))
                    ->label('News'),
                
                ToggleColumn::make('approved')
                    ->label('Approved'),

                TextColumn::make('created_at')
                    ->label('Created')
                    ->sortable()
                    ->since(),
            ])
            ->filters([
                SelectFilter::make('language')
                    ->options([
                        'bn' => 'Bangla',
                        'en' => 'English',
                    ]),
                SelectFilter::make('approved')
                    ->label('Approval Status')
                    ->options([
                        '0' => 'Pending',
                        '1' => 'Approved',
                    ]),
                SelectFilter::make('news')
                    ->relationship('news', 'title')
                    ->searchable(),
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
            'index' => Pages\ManageComments::route('/'),
        ];
    }
}
