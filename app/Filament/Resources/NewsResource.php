<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NewsResource\Pages;
use App\Models\News;
use DateTime;
use Filament\Forms\Components\Textarea;
use Filament\Forms;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Split;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use FilamentTiptapEditor\TiptapEditor;
use RalphJSmit\Filament\MediaLibrary\Forms\Components\MediaPicker;
use Illuminate\Support\Str;

class NewsResource extends Resource
{
    protected static ?string $model = News::class;
    protected static ?string $slug = 'news';
    protected static ?string $breadcrumb = 'News';

    public static function form(Form $form): Form
    {
        return $form
        ->columns([
            'sm' => 1,
            'lg' => 10,
            'xl' => 10,
        ])
        ->schema([
            Split::make([
                Section::make([
                    Select::make('title_size')
                        ->label('Title/ Heading Size')
                        ->options([
                            '.96' => '0.96rem',
                            '1' => '1.00rem',
                            '1.04' => '1.04rem',
                            '1.08' => '1.08rem',
                            '1.12' => '1.12rem',
                            '1.16' => '1.16rem',
                            '1.20' => '1.20rem',
                            '1.24' => '1.24rem - default',
                            '1.25' => '1.25rem',
                            '1.28' => '1.28rem',
                            '1.32' => '1.32rem',
                            '1.36' => '1.36rem',
                            '1.48' => '1.48rem',
                            '1.52' => '1.52rem',
                            '1.56' => '1.56rem',
                            '1.60' => '1.60rem',
                            '1.64' => '1.64rem',
                        ])
                        ->default('1.24'),

                    ColorPicker::make('title_color')
                        ->label('Text Color')
                        ->inlineLabel(), 
                        
                    TextInput::make('upper_title')
                        ->label('Upper Title/ Secondary Heading')
                        ->maxValue(255),

                    ColorPicker::make('upper_title_color')
                        ->label('Text Color')
                        ->inlineLabel(),

                    TextInput::make('sub_title')
                        ->label('Sub Title/ Secondary Heading')
                        ->maxValue(255),

                    ColorPicker::make('sub_title_color')
                        ->label('Text Color')
                        ->inlineLabel(),
                ]),

                Section::make([
                    Select::make('status')
                        ->label('Publishing Status')
                        ->hiddenLabel()
                        ->options([
                            'draft' => 'Draft',
                            'reviewing' => 'Reviewing',
                            'published' => 'Published',
                        ])
                        ->default('draft')
                        ->required(),
    
                    Select::make('language')
                        ->hiddenLabel()
                        ->label('Language')
                        ->options([
                            'en' => 'English',
                            'bn' => 'Bangla',
                        ])
                        ->default('bn')
                        ->required(),
                    
                    Select::make('author_id')
                        ->relationship(name: 'author', titleAttribute: 'title')
                        ->required()
                        ->preload()
                        ->searchable(),
    
                    Select::make('category_id')
                        ->multiple()
                        ->relationship(name: 'categories', titleAttribute: 'title')
                        ->preload()
                        ->required(),
    
                    Select::make('tag_id')
                        ->multiple()
                        ->relationship(name: 'tags', titleAttribute: 'title')
                        ->preload()
                        ->required(),

                    Checkbox::make('show_created_at')
                        ->default(true)
                        ->label('Show Created Date'),
    
                    Checkbox::make('show_updated_at')
                        ->label('Show Updated Date')
                        ->inline(),
    
                    Checkbox::make('show_thumbnail')
                        ->default(true)
                        ->label('Show Featured Image')
                        ->inline(),
    
                    Hidden::make('updated_by')
                        ->default(auth()->id())
                        ->required(),
                ])
                
            ])
            ->extraAttributes(['style' => 'display:block', 'id' => 'fi-news-form-left'])
            ->columnSpan(3),

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

                    Grid::make(2)
                        ->schema([
                        DateTimePicker::make('created_at')
                            ->default((new DateTime())->format('Y-m-d H:i:s'))
                            ->label('Created Date')
                            ->columnSpan(1)
                            ->required(),
                            
                        Select::make('position')
                            ->multiple()
                            ->label('News Position in Site Layout')
                            ->required()
                            ->columnSpan(1)
                            ->options(\config('app.news_position')),
                    ]),

                    Textarea::make('summary')
                        ->label('News Summary')
                        ->default(null)
                        ->required(),
    
                    Textarea::make('social_summary')
                        ->label('News Summary for Social Share & SEO')
                        ->default(null)
                        ->required(),
                ]),

                Section::make([
                    MediaPicker::make('thumbnail')
                        ->label('News Thumbnail Image'),
    
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
            ->columnSpan(7)
        ])->extraAttributes(['id' => 'fi-news-form']);

    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->description(fn (News $record): string => Str::limit($record->description, 20, '...') ?? '...')
                    ->searchable(),

                TextColumn::make('language')
                    ->getStateUsing(fn (News $record): string => $record->language == 'en' ? 'en' : 'bn')
                    ->badge()
                    ->color(fn (News $record): string => $record->language == 'en' ? 'success' : 'warning'),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (News $record): string => $record->status == 'published' ? 'success' : 'danger'),
            
                TextColumn::make('position')
                ->wrap(),

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
                    ->preload()
                    ->searchable()
                    ->relationship('categories', 'title'),

                SelectFilter::make('tag_id')
                    ->label('Tag')
                    ->preload()
                    ->searchable()
                    ->relationship('tags', 'title'),

                SelectFilter::make('author_id')
                    ->label('Author')
                    ->relationship('author', 'title')
                    ->preload()
                    ->searchable(),
                ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([])
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
            'index' => Pages\ListNews::route('/'),
            'create' => Pages\CreateNews::route('/create'),
            'edit' => Pages\EditNews::route('/{record}/edit'),
        ];
    }
}
