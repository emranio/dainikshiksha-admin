<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NewsResource\Pages;
use App\Models\Author;
use App\Models\News;
use Filament\Forms\Components\Textarea;
use Filament\Forms;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Split;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use FilamentTiptapEditor\TiptapEditor;
use RalphJSmit\Filament\MediaLibrary\Forms\Components\MediaPicker;
use Illuminate\Support\Str;

class NewsResource extends Resource
{
    protected static ?string $model = News::class;
    protected static ?string $slug = 'news';
    protected static ?string $breadcrumb = 'News';
    protected static ?string $navigationIcon = 'heroicon-s-pencil-square';

    public static function form(Form $form): Form
    {
        return $form
        ->columns([
            'sm' => 1,
            'xl' => 8,
            '2xl' => 8,
        ])
        ->schema([
            Split::make([
                Section::make([
                    TextInput::make('upper_title')
                        ->label('Upper Title/ Secondary Heading')
                        ->maxValue(255),

                    TextInput::make('sub_title')
                        ->label('Sub Title/ Secondary Heading')
                        ->maxValue(255),
                        
                    Select::make('position')
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
                        ]),

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

                    Checkbox::make('show_created_at')
                        ->id('show_created_at')
                        ->label('Show Created At'),
    
                    Checkbox::make('show_updated_at')
                        ->id('show_updated_at')
                        ->label('Show Update At')
                        ->inline(),
    
                    Checkbox::make('show_thumbnail')
                        ->id('show_thumbnail')
                        ->label('Show Featured Image')
                        ->inline(),
    
                    Select::make('status')
                        ->label('Publishing Status')
                        ->options([
                            'draft' => 'Draft',
                            'reviewing' => 'Reviewing',
                            'published' => 'Published',
                        ])
                        ->default('draft')
                        ->required(),
    
                    Select::make('language')
                        ->label('Language')
                        ->options([
                            'en' => 'English',
                            'bn' => 'Bangla',
                        ])
                        ->default('en')
                        ->required(),
    
                    Hidden::make('updated_by')
                        ->default(auth()->id())
                        ->required(),
                ])
                

            ])
            ->extraAttributes(['style' => 'display:block', 'id' => 'fi-news-form-left'])
            ->columnSpan(2),

            Split::make([
                Section::make([
                    TextInput::make('title')
                        ->label('News Title/ Main Heading')
                        ->required()
                        ->maxValue(255)
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn (string $operation, $state, Forms\Set $set) => $operation === 'create' ? $set('slug', Str::slug($state)) : null),

                    TextInput::make('social_title')
                        ->label('News Title/ Main Heading for Social Share & SEO')
                        ->maxValue(255)
                        ->live(onBlur: true)
                        ->required(),

                    TextInput::make('slug')
                        ->maxValue(255)
                        ->dehydrated()
                        ->required()
                        ->unique(News::class, 'slug', ignoreRecord: true),

                    Textarea::make('summary')
                        ->label('News Summary')
                        ->default(null)
                        ->required(),
    
                    Textarea::make('social_summary')
                        ->label('News Summary for Social Share & SEO')
                        ->default(null)
                        ->required(),
                    // Toggle::make('is_featured'),
                ]),

                Section::make([
                    MediaPicker::make('thumbnail')
                        ->label('News Thumbnail Image')
                        ->required(),
    
                    MediaPicker::make('social_thumbnail')
                        ->label('News Thumbnail Image for Social Share & SEO'),
                ])
                ->extraAttributes(['class' => 'fi-has-media-picker'])
                ->columns(2),

                Section::make([
                    TiptapEditor::make('news_body')
                        ->label('News Body')
                        ->maxFileSize(2048) // 2MB
                        ->maxContentWidth('5xl')
                        ->extraInputAttributes(['style' => 'min-height: 12rem;'])
                        ->columnSpan('full')
                        ->required(),
                ]),
            ])
            ->extraAttributes(['style' => 'display:block', 'id' => 'fi-news-form-right'])
            ->columnSpan(6)
        ])->extraAttributes(['id' => 'fi-news-form']);

    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->description(fn (News $record): string => Str::limit($record->description, 20, '...') ?? '...')
                    ->searchable(),

                Tables\Columns\TextColumn::make('language')
                    ->getStateUsing(fn (News $record): string => $record->language == 'en' ? 'en' : 'bn')
                    ->badge()
                    ->color(fn (News $record): string => $record->language == 'en' ? 'success' : 'warning'),

                TextColumn::make('created_at')
                    ->label('Last Created')
                    ->dateTime(),

                TextColumn::make('updated_at')
                    ->label('Updated')
                    ->dateTime(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Publishing Status')
                    ->options([
                        'draft' => 'Draft',
                        'reviewing' => 'Reviewing',
                        'published' => 'Published',
                    ]),

                SelectFilter::make('language')
                    ->options([
                        'en' => 'English',
                        'bn' => 'bangla',
                    ]),

                SelectFilter::make('category_id')
                    ->label('category')
                    ->multiple()
                    ->preload()
                    ->relationship('category', 'title'),

                SelectFilter::make('tag_id')
                    ->label('Tag')
                    ->multiple()
                    ->relationship('tags', 'title'),

                SelectFilter::make('author_id')
                    ->label('Author')
                    ->options(Author::all()->pluck('id', 'title'))
                    ->attribute('author.title'),
                ], layout: FiltersLayout::AboveContentCollapsible)
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([])
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
