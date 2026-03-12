<?php

namespace App\Filament\Resources\BlogPosts\Schemas;


use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Support\Enums\FontWeight;
use Filament\Schemas\Schema;
use Filament\Support\Enums\TextSize;

use Filament\Schemas\Components\Section;

class BlogPostInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Blog Profile')
                    ->schema([
                        TextEntry::make('title')
                            ->weight(FontWeight::ExtraBold)
                            ->size(TextSize::Large),

                        TextEntry::make('slug')
                            ->color('gray')
                            ->icon('heroicon-m-link')
                            ->copyable(),

                        ImageEntry::make('thumbnail')
                            ->hiddenLabel()
                            ->imageHeight(250)
                            ->disk('public')
                            ->extraImgAttributes(['class' => 'rounded-xl shadow-sm'])
                            ->placeholder('No Image Uploaded'),


                    ])->collapsible(),
                Section::make('Blog Content')
                ->collapsed()
                    ->schema([
                        TextEntry::make('content')
                            ->html()
                            ->prose()
                            ->columnSpanFull(),
                    ]),
                Section::make('Short Summary')
                    
                    ->schema([
                        TextEntry::make('excerpt')
                            ->hiddenLabel()
                            ->placeholder('No summary provided.'),
                    ])->collapsible(),




                Section::make('Publishing Details')->columns(2)
                    ->schema([
                        TextEntry::make('author.name')
                            ->label('Written By')
                            ->icon('heroicon-m-user'),
                        TextEntry::make('status')
                            ->badge()
                            ->color(fn(string $state): string => match ($state) {
                                'published' => 'success',
                                'draft' => 'warning',
                                'archived' => 'danger',
                                default => 'gray',
                            }),

                        TextEntry::make('category.name')->label('Category')
                            ->icon('heroicon-m-tag'),

                        TextEntry::make('published_at')
                            ->label('Published')
                            ->since(),
                    ])->collapsible(),

                Section::make('Engagement Stats')
                    ->columns(2) 
                    ->schema([
                        TextEntry::make('view_count')->label('Views')->icon('heroicon-m-eye')->color('gray'),
                        TextEntry::make('like_count')->label('Likes')->icon('heroicon-m-heart')->color('danger'),
                        TextEntry::make('share_count')->label('Shares')->icon('heroicon-m-share')->color('info'),
                        TextEntry::make('commet_count')->label('Comments')->icon('heroicon-m-chat-bubble-left')->color('primary'),
                    ])->collapsible(),

                Section::make('Timestamps')->columns(2)
                    ->schema([
                        TextEntry::make('created_at')->dateTime()->size(TextSize::Small),
                        TextEntry::make('updated_at')->dateTime()->size(TextSize::Small),
                    ])->collapsible(),




            ]);
    }
}
