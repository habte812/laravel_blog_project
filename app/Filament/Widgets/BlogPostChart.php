<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

class BlogPostChart extends ChartWidget
{
    protected ?string $heading = 'Blog Post Chart';
protected int | string | array $columnSpan = [
        'md' => 2,
        'xl' => 2,
    ];
    protected ?string $pollingInterval = '15s';
    protected static bool $isLazy = false;
    // protected ?string $maxHeight = '600px';
    protected bool $isCollapsible = true;
    
    protected function getData(): array
    {
         return [
            'datasets' => [
                [
                    'label' => 'Total posts',
                    'data' => [0, 10, 5, 2, 21, 32, 45, 74, 65, 45, 77, 89],
                ],
            ],
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
