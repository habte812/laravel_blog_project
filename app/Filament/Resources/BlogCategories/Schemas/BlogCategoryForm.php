<?php

namespace App\Filament\Resources\BlogCategories\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Utilities\Set;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BlogCategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                 TextInput::make('name')
                ->required()
                ->live(onBlur: true)
                ->afterStateUpdated(function (Set $set, ?string $state) {
                               if (!$state) return;
                               $slug = Str::slug($state) . '-' . Str::lower(Str::random(5));
                               $set('slug', $slug);}),
               TextInput::make('slug')
                ->unique(ignoreRecord: true) 
                ->maxLength(255)
                ->readOnly(),
                 FileUpload::make('category_image')
                    ->image()
                    ->disk('public')
                    ->directory('category_images')
                    ->deletable()
                    ->deleteUploadedFileUsing(function ($file){
                            Storage::disk('public')->delete($file);
                    })
                    ->imageEditor()
                    ->visibility('public'),
            ]);
    }
}
