<?php

namespace App\Filament\Resources\Transactions\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class TransactionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('User')
                    ->searchable()
                    ->sortable()
                    ->weight('medium')
                    ->icon('heroicon-o-user'),
                    
                TextColumn::make('document.title')
                    ->label('Document')
                    ->searchable()
                    ->sortable()
                    ->weight('medium')
                    ->icon('heroicon-o-document-text')
                    ->limit(30)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();
                        if (strlen($state) <= 30) {
                            return null;
                        }
                        return $state;
                    }),
                    
                TextColumn::make('amount')
                    ->money('INR', true)
                    ->sortable()
                    ->weight('semibold')
                    ->color('success')
                    ->icon('heroicon-o-currency-rupee'),
                    
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'completed' => 'success',
                        'pending' => 'warning',
                        'failed' => 'danger',
                        'refunded' => 'info',
                        default => 'gray',
                    })
                    ->icon(fn (string $state): string => match ($state) {
                        'completed' => 'heroicon-o-check-circle',
                        'pending' => 'heroicon-o-clock',
                        'failed' => 'heroicon-o-x-circle',
                        'refunded' => 'heroicon-o-arrow-path',
                        default => 'heroicon-o-question-mark-circle',
                    })
                    ->searchable()
                    ->sortable(),
                    
                TextColumn::make('gateway')
                    ->label('Gateway')
                    ->badge()
                    ->color('info')
                    ->searchable()
                    ->toggleable(),
                    
                TextColumn::make('gateway_order_id')
                    ->label('Order ID')
                    ->searchable()
                    ->copyable()
                    ->copyMessage('Order ID copied')
                    ->copyMessageDuration(1500)
                    ->toggleable()
                    ->limit(20),
                    
                TextColumn::make('gateway_payment_id')
                    ->label('Payment ID')
                    ->searchable()
                    ->copyable()
                    ->copyMessage('Payment ID copied')
                    ->copyMessageDuration(1500)
                    ->toggleable()
                    ->limit(20),
                    
                TextColumn::make('created_at')
                    ->label('Transaction Date')
                    ->dateTime('M d, Y H:i')
                    ->sortable()
                    ->since()
                    ->description(fn ($record) => $record->created_at->format('M d, Y H:i')),
                    
                TextColumn::make('updated_at')
                    ->label('Last Updated')
                    ->dateTime('M d, Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'completed' => 'Completed',
                        'pending' => 'Pending',
                        'failed' => 'Failed',
                        'refunded' => 'Refunded',
                    ])
                    ->multiple(),
                SelectFilter::make('gateway')
                    ->label('Payment Gateway')
                    ->options([
                        'razorpay' => 'Razorpay',
                        'stripe' => 'Stripe',
                        'paypal' => 'PayPal',
                    ]),
                Filter::make('high_value')
                    ->label('High Value (>₹500)')
                    ->query(fn (Builder $query): Builder => $query->where('amount', '>', 500)),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->striped()
            ->poll('30s');
    }
}
