<?php

namespace App\Filament\Resources\Seos\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class SeoInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('post_id')
                    ->numeric(),
                TextEntry::make('meta_title'),
                TextEntry::make('meta_description')
                    ->columnSpanFull(),
                TextEntry::make('meta_keywords')
                    ->columnSpanFull(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
