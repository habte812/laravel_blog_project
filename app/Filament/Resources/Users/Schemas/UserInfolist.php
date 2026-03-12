<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Support\Enums\FontWeight;

class UserInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->columns(2)
                    ->schema([
                        TextEntry::make('name')
                            ->weight(FontWeight::Bold),
                        TextEntry::make('email')
                            ->label('Email address')
                            ->icon('heroicon-m-envelope')
                            ->copyable()
                            ->color('gray'),
                    ]),

                ImageEntry::make('profile_picture_url')
                    ->label('Profile Image')
                    ->circular()
                    ->placeholder('No Image Uploaded')->extraImgAttributes([
                        'loading' => 'lazy',
                    ]),
                Section::make()
                    ->columns(2)
                    ->schema([
                        TextEntry::make('role')
                            ->badge()
                            ->color(fn(string $state): string => match ($state) {
                                'admin' => 'danger',
                                'author' => 'info',
                                default => 'gray',
                            }),

                        TextEntry::make('email_verified_at')
                            ->label('Verified On')
                            ->dateTime()
                            ->placeholder('Not Verified')
                            ->color(fn($state) => $state ? 'success' : 'warning'),

                        TextEntry::make('created_at')
                            ->label('Joined Date')
                            ->dateTime()
                            ->since(), 

                        TextEntry::make('updated_at')
                            ->label('Last Activity')
                            ->dateTime()
                            ->placeholder('-'),
                    ]),

            ]);
    }
}
