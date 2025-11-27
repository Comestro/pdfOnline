<?php

namespace App\Filament\Resources\Purchases\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PurchasesTable
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
                    
                TextColumn::make('user.email')
                    ->label('Email')
                    ->searchable()
                    ->toggleable()
                    ->color('gray'),
                    
                TextColumn::make('document.title')
                    ->label('Document')
                    ->searchable()
                    ->sortable()
                    ->weight('medium')
                    ->icon('heroicon-o-document-text')
                    ->wrap(),
                    
                TextColumn::make('document.document_type')
                    ->label('Type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Registry' => 'success',
                        'Girdawari' => 'info',
                        'Khatauni' => 'warning',
                        default => 'gray',
                    })
                    ->sortable(),
                    
                TextColumn::make('transaction.amount')
                    ->label('Amount')
                    ->money('INR', true)
                    ->sortable()
                    ->weight('semibold')
                    ->color('success'),
                    
                TextColumn::make('transaction.status')
                    ->label('Payment Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'completed' => 'success',
                        'pending' => 'warning',
                        'failed' => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),
                    
                IconColumn::make('downloaded_at')
                    ->label('Downloaded')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('gray')
                    ->getStateUsing(fn ($record) => $record->downloaded_at !== null)
                    ->sortable(),
                    
                TextColumn::make('created_at')
                    ->label('Purchase Date')
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
                SelectFilter::make('document_type')
                    ->label('Document Type')
                    ->relationship('document', 'document_type'),
                SelectFilter::make('payment_status')
                    ->label('Payment Status')
                    ->relationship('transaction', 'status')
                    ->options([
                        'completed' => 'Completed',
                        'pending' => 'Pending',
                        'failed' => 'Failed',
                    ]),
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
            ->striped();
    }
}
