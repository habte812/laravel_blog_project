<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Forms\Components\FileUpload;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required(),
                DateTimePicker::make('email_verified_at'),
                Select::make('role')
                    ->options(['admin' => 'Admin', 'author' => 'Author', 'reader' => 'Reader'])
                    ->required()->disabled(function ($record) {
                        $user = Auth::user();
                        if (!$record) return false;
                        return $record->id === $user->id;
                    })->helperText(function ($record) {
                        $user = Auth::user();
                        return $record && $record->id === $user->id
                            ? 'You cannot change your own role to prevent losing admin access.'
                            : 'Assign the appropriate level of access.';
                    }),

                Select::make('status')
                ->options(['active' => 'Active', 'banned' => 'Banned'])
                ->disabled(function ($record){
                    $user= Auth::user();
                    if(!$record)return false;
                    return $record->id === $user->id;
                })->helperText(function ($record){
                     $user = Auth::user();
                    return $record && $record->id === $user->id
                            ? 'You cannot change your own status to prevent losing admin access.'
                            : 'Assign the appropriate status.';
                }),
                FileUpload::make('profile_picture')
                    ->image()
                    ->disk('public')
                    ->directory('profile')
                    ->deletable()
                    ->deleteUploadedFileUsing(function ($file) {
                        Storage::disk('public')->delete($file);
                    })
                    ->imageEditor()
                    ->visibility('public'),

            ]);
    }
}
