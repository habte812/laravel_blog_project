<?php

namespace App\Filament\Resources\BlogPosts\Schemas;


use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Set;
use Illuminate\Support\Str;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Storage;

class BlogPostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required()
                    ->live(onBlur: true) // Auto-generate slug when title changes
                    ->afterStateUpdated(fn(Set $set, ?string $state) => $set('slug', Str::slug($state))),

                TextInput::make('slug')
                    ->unique(ignoreRecord: true),

                RichEditor::make('content') // Better for formatting blog posts
                    ->required()
                    ->columnSpanFull(),

                Textarea::make('excerpt')
                    ->rows(3)
                    ->columnSpanFull(),


                FileUpload::make('thumbnail') // Real image uploader
                    ->image()
                    ->disk('public')
                    ->directory('blog_thumbnail')
                    ->deletable()
                    ->deleteUploadedFileUsing(function ($file){
                            Storage::disk('public')->delete($file);
                    })
                    ->imageEditor()
                    ->visibility('public'),

                Select::make('user_id')
                    ->relationship('author', 'name') // Nice dropdown instead of manual ID
                    ->searchable()
                    ->required(),

                Select::make('category_id')
                    ->relationship('category', 'name') // Assumes you have a Category model
                    ->searchable()
                    ->required(),

                Select::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'published' => 'Published',
                        'archived' => 'Archived', // Fixed spelling of "archived"
                    ])
                    ->default('draft')
                    ->required()
                    ->native(false),

                DateTimePicker::make('published_at')
                    ->default(now())
                    ->required(),
                TextInput::make('view_count')->numeric()->default(0)->disabled(), // Admins shouldn't manually edit these
                TextInput::make('like_count')->numeric()->default(0)->disabled(),
                TextInput::make('share_count')->numeric()->default(0)->disabled(),
                TextInput::make('commet_count')->numeric()->default(0)->disabled(),
            ]);
    }
}
