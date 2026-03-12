<?php

namespace App\Filament\Resources\Seos\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class SeoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('post_id')
                    ->required()
                    ->numeric(),
                TextInput::make('meta_title')
                    ->required(),
                Textarea::make('meta_description')
                    ->required()
                    ->columnSpanFull(),
                Textarea::make('meta_keywords')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }
}
