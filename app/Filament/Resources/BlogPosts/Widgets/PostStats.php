<?php

namespace App\Filament\Resources\BlogPosts\Widgets;

use App\Models\BlogPost;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PostStats extends BaseWidget
{

    protected ?string $pollingInterval = '10s';
    protected ?string $description = 'An overview of some analytics.';
    protected static bool $isLazy = false;
    protected function getStats(): array
    {
        $postData = collect(range(6, 0))->map(function ($days) {
            return BlogPost::whereDate('created_at', now()->subDays($days))->count();
        })->toArray();

        return [

            Stat::make('Active Users', User::where('status', 'active')->count())
                ->description('Live on ThecNode')
                ->descriptionIcon('heroicon-m-user-group')
                ->chart([15, 4, 10, 2, 12, 4, 12])
                ->color('info'),
            Stat::make('Total Posts', BlogPost::count())
                ->description('Across all users')
                ->descriptionIcon('heroicon-m-document-text')
                ->chart($postData)
                ->color('success'),
            Stat::make('New Registrations', User::whereDate('created_at', today())->count())
                ->description('Joined in the last 24h')
                ->descriptionIcon('heroicon-m-sparkles')
                ->color('warning'),
        ];
    }
}
