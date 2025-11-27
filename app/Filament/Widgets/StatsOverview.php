<?php

namespace App\Filament\Widgets;

use App\Models\Document;
use App\Models\Purchase;
use App\Models\Transaction;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
    protected ?string $pollingInterval = '10s';
    
    protected function getStats(): array
    {
        $totalRevenue = Transaction::where('status', 'completed')->sum('amount');
        $pendingRevenue = Transaction::where('status', 'pending')->sum('amount');
        $totalPurchases = Purchase::count();
        $recentPurchases = Purchase::where('created_at', '>=', now()->subDays(7))->count();
        $totalDocuments = Document::count();
        $activeDocuments = Document::where('is_active', true)->count();
        
        return [
            Stat::make('Total Purchases', number_format($totalPurchases))
                ->description($recentPurchases . ' new this week')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart([7, 12, 9, 14, 18, 15, $recentPurchases])
                ->color('success')
                ->icon('heroicon-o-shopping-cart'),
                
            Stat::make('Total Documents', number_format($totalDocuments))
                ->description($activeDocuments . ' active documents')
                ->descriptionIcon('heroicon-m-check-circle')
                ->chart([10, 15, 12, 18, 20, 22, $activeDocuments])
                ->color('info')
                ->icon('heroicon-o-document-text'),
                
            Stat::make('Total Revenue', '₹' . number_format($totalRevenue, 2))
                ->description($pendingRevenue > 0 ? '₹' . number_format($pendingRevenue, 2) . ' pending' : 'All cleared')
                ->descriptionIcon($pendingRevenue > 0 ? 'heroicon-m-clock' : 'heroicon-m-check-badge')
                ->color('success')
                ->icon('heroicon-o-currency-rupee'),
        ];
    }
}
