<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class DividerWidget extends Widget
{
    protected string $view = 'filament.widgets.divider-widget';
    protected static ?int $sort = 0; // Letakkan di antara widget atas (sort -10) dan bawah (sort 1)
    protected int | string | array $columnSpan = 'full';
}
