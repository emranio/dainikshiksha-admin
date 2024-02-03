<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NewsResource\Pages;
use App\Models\Author;
use App\Models\News;
use Filament\Forms;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use FilamentTiptapEditor\TiptapEditor;
use RalphJSmit\Filament\MediaLibrary\Forms\Components\MediaPicker;

class NewsResource extends Resource
{
    protected static ?string $model = News::class;
    protected static ?string $slug = 'admin/news';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $navigationGroup = '';

    protected static ?string $navigationIcon = 'heroicon-s-pencil-square';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->label('Title')
                    ->required()
                    ->maxValue(50)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (string $operation, $state, Forms\Set $set) => $operation === 'create' ? $set('slug', \Str::slug($state)) : null),

                Forms\Components\TextInput::make('slug')
                    ->disabled()
                    ->dehydrated()
                    ->required()
                    ->unique(News::class, 'slug', ignoreRecord: true),
                Forms\Components\TextInput::make('social_title')
                    ->label('Seo Title')
                    ->maxValue(50)
                    ->live(onBlur: true)
                    ->required(),
                Forms\Components\TextInput::make('sub_title')
                    ->label('Sub Title')
                    ->maxValue(50)
                    ->live(onBlur: true),
                Forms\Components\TextInput::make('upper_title')
                    ->label('Upper Title')
                    ->maxValue(50)
                    ->live(onBlur: true),
                // Forms\Components\MarkdownEditor::make('news_body')
                //     ->label('News Body')
                //     ->default(null)
                //     ->columnSpan('full')
                //     ->required(),
                TiptapEditor::make('news_body')
                    ->maxFileSize(2048) // optional, defaults to config setting
                    // ->output(TiptapOutput::Html) // optional, change the format for saved data, default is html
                    ->maxContentWidth('5xl')
                    ->columnSpan('full')
                    ->required(),
                \Filament\Forms\Components\Textarea::make('summery')
                    ->label('Description')
                    ->default(null)
                    ->required(),
                \Filament\Forms\Components\Textarea::make('social_summery')
                    ->label('Seo Description')
                    ->default(null)
                    ->required(),
                Select::make('author_id')
                    ->relationship(name: 'author', titleAttribute: 'title')
                    ->required()
                    ->preload(),
                Select::make('category_id')
                    ->multiple()
                    ->relationship(name: 'category', titleAttribute: 'title')
                    ->preload()
                    ->required(),
                Select::make('tag_id')
                    ->multiple()
                    ->relationship(name: 'tags', titleAttribute: 'title')
                    ->preload()
                    ->required(),
                Select::make('lead_position')
                    ->multiple()
                    ->required()
                    ->options([
                        'lead_1' => 'Lead 1',
                        'lead_2' => 'Lead 2',
                        'lead_3' => 'Lead 3',
                        'lead_4' => 'Lead 4',
                        'lead_5' => 'Lead 5',
                        'lead_6' => 'Lead 6',
                        'category_lead' => 'Category Lead',
                        'ticker' => 'Ticker',
                    ])
                // ->afterStateUpdated(function (string $operation, $state, Forms\Set $set) {
                //     if ($operation === 'create' || $operation === 'update' || $operation === 'edit') {
                //         $leadPosition = is_array($state) ? implode(',', $state) : $state;
                //         $set('lead_position', $leadPosition);
                //     }
                // })
                ,
                MediaPicker::make('featured_image')
                    ->multiple()
                    ->label('Featured Image')
                    ->required(),
                MediaPicker::make('social_featured_image')
                    ->multiple()
                    ->label('Social Featured Image'),
                Checkbox::make('show_created_at')
                    ->id('show_created_at')
                    ->label('Show Created At'),
                // ->inline(),
                Checkbox::make('show_updated_at')
                    ->id('show_updated_at')
                    ->label('Show Update At')
                    ->inline(),
                Checkbox::make('show_featured_image')
                    ->id('show_featured_image')
                    ->label('Show Featured Image')
                    ->inline(),
                Checkbox::make('is_published')
                    ->label('Publish')
                    ->inline(),
                \Filament\Forms\Components\Select::make('language')
                    ->label('Language')
                    ->options([
                        'en' => 'English',
                        'bn' => 'Bangla',
                    ])
                    ->default('en')
                    ->id('language')
                    ->required(),
                Hidden::make('updated_by')
                    ->default(auth()->id())
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->description(fn (News $record): string => \Str::limit($record->description, 20, '...') ?? '...')
                    ->searchable(),
                Tables\Columns\TextColumn::make('language')
                    ->getStateUsing(fn (News $record): string => $record->language == 'en' ? 'en' : 'bn')
                    ->badge()
                    ->color(fn (News $record): string => $record->language == 'en' ? 'success' : 'warning'),
                TextColumn::make('created_at')
                    ->dateTime(),
                TextColumn::make('updated_at')
                    ->dateTime(),
            ])
            ->filters([
                Filter::make('title')
                    ->label('Title'),
                Filter::make('is_published')
                    ->label('Is Published'),
                SelectFilter::make('language')
                    ->options([
                        'en' => 'English',
                        'bn' => 'bangla',
                    ]),
                SelectFilter::make('category_id')
                    ->label('category')
                    ->multiple()
                    ->relationship('category', 'title')
                    ->preload(),
                SelectFilter::make('tag_id')
                    ->label('Tag')
                    ->multiple()
                    ->relationship('tags', 'title')
                    ->preload(),
                SelectFilter::make('author_id')
                    ->label('Author')
                    ->options(Author::all()->pluck('id', 'title'))
                    ->attribute('author.title'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->paginated([25]);
    }

    public static function getRelations(): array
    {
        return [
            // CategoryRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListNews::route('/'),
            'create' => Pages\CreateNews::route('/create'),
            'edit' => Pages\EditNews::route('/{record}/edit'),
        ];
    }
}
