<?php

namespace App\Filament\Pages\Settings;

use Filament\Forms;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Textarea;

class SiteSettings extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $slug = 'site-setting';
    protected static string $settings = SiteSettingsSpite::class;

    // protected static ?string $navigationLabel = 'Site Settings';
    protected static ?string $navigationGroup = 'System';
    public static function canAccess(): bool
    {
        return auth()->user()->hasRole(['admin']);
    }
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Settings')
                    ->tabs([
                        Tabs\Tab::make('English')
                            ->schema([
                                TextInput::make('site_title_en')
                                    ->label('Site Title English')
                                    ->string()
                                    ->maxWidth(50),
                                Textarea::make('site_tagline_en')
                                    ->label('Site Tagline English')
                                    ->maxWidth(50),
                                Forms\Components\MarkdownEditor::make('contacts_en')
                                    ->label('Contacts English')
                                    ->default(null),
                                Forms\Components\MarkdownEditor::make('email_en')
                                    ->label('Emails English')
                                    ->default(null),
                                Forms\Components\MarkdownEditor::make('ads_en')
                                    ->label('Ads English')
                                    ->default(null),
                                Repeater::make('menu_links_en')
                                    ->label('Menu Links English')
                                    ->schema([
                                        TextInput::make('label')->required(),
                                        Select::make('target')
                                            ->options([
                                                '_self' => 'Current Page (_self)',
                                                '_blank' => 'New Page (_blank)',
                                            ])
                                            ->default('_self'),
                                        TextInput::make('link')->required()
                                    ])
                                    ->collapsible()
                                    ->columns(3)
                                    ->collapseAllAction(
                                        fn (\Filament\Forms\Components\Actions\Action $action) => $action->label('Collapse all menu'),
                                    )
                                    ->itemLabel(fn (array $state): ?string => $state['label'] ?? null),
                                Repeater::make('social_links_en')
                                    ->schema([
                                        TextInput::make('label')->required(),
                                        Select::make('target')
                                            ->options([
                                                '_self' => 'Current Page (_self)',
                                                '_blank' => 'New Page (_blank)',
                                            ])
                                            ->default('_self'),
                                        TextInput::make('icon_class')->required(),
                                        TextInput::make('link')->required()
                                    ])
                                    ->collapsible()
                                    ->columns(3)
                                    ->collapseAllAction(
                                        fn (\Filament\Forms\Components\Actions\Action $action) => $action->label('Collapse all menu'),
                                    )
                                    ->itemLabel(fn (array $state): ?string => $state['label'] ?? null),
                                Repeater::make('footer_links_en')
                                    ->schema([
                                        TextInput::make('label')->required(),
                                        Select::make('target')
                                            ->options([
                                                '_self' => 'Current Page (_self)',
                                                '_blank' => 'New Page (_blank)',
                                            ])
                                            ->default('_self'),
                                        TextInput::make('link')->required()
                                    ])
                                    ->collapsible()
                                    ->columns(3)
                                    ->collapseAllAction(
                                        fn (\Filament\Forms\Components\Actions\Action $action) => $action->label('Collapse all menu'),
                                    )
                                    ->itemLabel(fn (array $state): ?string => $state['label'] ?? null),

                            ]),
                        Tabs\Tab::make('Bangla')
                            ->schema([
                                TextInput::make('site_title_bn')
                                    ->label('Site Title Bangla')
                                    ->default(''),
                                Textarea::make('site_tagline_bn')
                                    ->label('Site Tagline Bangla'),
                                Forms\Components\MarkdownEditor::make('contacts_bn')
                                    ->label('Contacts Bangla')
                                    ->default(null),
                                Forms\Components\MarkdownEditor::make('email_bn')
                                    ->label('Bangla Bangla')
                                    ->default(null),
                                Forms\Components\MarkdownEditor::make('ads_bn')
                                    ->label('Ads Bangla')
                                    ->default(null),
                                Repeater::make('menu_links_bn')
                                    ->schema([
                                        TextInput::make('label')->required(),
                                        Select::make('target')
                                            ->options([
                                                '_self' => 'Current Page (_self)',
                                                '_blank' => 'New Page (_blank)',
                                            ])
                                            ->default('_self'),
                                        TextInput::make('link')->required()
                                    ])
                                    ->collapsible()
                                    ->columns(3)
                                    ->collapseAllAction(
                                        fn (\Filament\Forms\Components\Actions\Action $action) => $action->label('Collapse all menu'),
                                    )
                                    ->itemLabel(fn (array $state): ?string => $state['label'] ?? null),
                                Repeater::make('social_links_bn')
                                    ->schema([
                                        TextInput::make('label')->required(),
                                        Select::make('target')
                                            ->options([
                                                '_self' => 'Current Page (_self)',
                                                '_blank' => 'New Page (_blank)',
                                            ])
                                            ->default('_self'),
                                        TextInput::make('icon_class')->required(),
                                        TextInput::make('link')->required()
                                    ])
                                    ->collapsible()
                                    ->columns(3)
                                    ->collapseAllAction(
                                        fn (\Filament\Forms\Components\Actions\Action $action) => $action->label('Collapse all menu'),
                                    )
                                    ->itemLabel(fn (array $state): ?string => $state['label'] ?? null),
                                Repeater::make('footer_links_bn')
                                    ->schema([
                                        TextInput::make('label')->required(),
                                        Select::make('target')
                                            ->options([
                                                '_self' => 'Current Page (_self)',
                                                '_blank' => 'New Page (_blank)',
                                            ])
                                            ->default('_self'),
                                        TextInput::make('link')->required()
                                    ])
                                    ->collapsible()
                                    ->columns(3)
                                    ->collapseAllAction(
                                        fn (\Filament\Forms\Components\Actions\Action $action) => $action->label('Collapse all menu'),
                                    )
                                    ->itemLabel(fn (array $state): ?string => $state['label'] ?? null),
                            ])
                    ])
                    ->columnSpan(2)
            ]);
    }
}
