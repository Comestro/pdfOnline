<?php

namespace App\Filament\Widgets;

use App\Models\Document;
use Filament\Actions\BulkActionGroup;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class LatestDocs extends TableWidget
{
    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = 'full';
    
    protected static ?string $heading = 'Latest Documents';
    
    public function table(Table $table): Table
    {
        return $table
            ->query(fn(): Builder => Document::query()->latest()->limit(5))
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->weight('bold')
                    ->icon('heroicon-o-document')
                    ->sortable(),
                    
                TextColumn::make('document_type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Registry' => 'success',
                        'Girdawari' => 'info',
                        'Khatauni' => 'warning',
                        default => 'gray',
                    })
                    ->searchable(),
                    
                TextColumn::make('district')
                    ->icon('heroicon-o-map-pin')
                    ->searchable()
                    ->toggleable(),
                    
                TextColumn::make('anchal')
                    ->label('Anchal')
                    ->searchable()
                    ->toggleable(),
                    
                TextColumn::make('mauza')
                    ->searchable()
                    ->toggleable(),
                    
                TextColumn::make('thana_no')
                    ->label('Thana No.')
                    ->searchable()
                    ->toggleable(),
                    
                TextColumn::make('price')
                    ->money('INR', true)
                    ->sortable()
                    ->weight('semibold')
                    ->color('success'),
                    
                IconColumn::make('is_active')
                    ->label('Status')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
                    
                TextColumn::make('created_at')
                    ->label('Added')
                    ->dateTime('M d, Y')
                    ->sortable()
                    ->toggleable(),
            ])
            ->paginated([5])
            ->defaultSort('created_at', 'desc');
    }
}
