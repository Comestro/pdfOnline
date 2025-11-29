<?php

    namespace App\Filament\Resources\Documents\Tables;

    use Filament\Actions\BulkActionGroup;
    use Filament\Actions\DeleteBulkAction;
    use Filament\Actions\EditAction;
    use Filament\Actions\ViewAction;
    use Filament\Tables\Columns\IconColumn;
    use Filament\Tables\Columns\TextColumn;
    use Filament\Tables\Columns\ToggleColumn;
    use Filament\Tables\Filters\SelectFilter;
    use Filament\Tables\Filters\TernaryFilter;
    use Filament\Tables\Table;

    class DocumentsTable
    {
        public static function configure(Table $table): Table
        {
            return $table
                ->columns([
                    TextColumn::make('title')
                        ->searchable()
                        ->sortable()
                        ->weight('bold')
                        ->icon('heroicon-o-document')
                        ->wrap(),
                        
                    TextColumn::make('document_type')
                        ->badge()
                        ->color(fn (string $state): string => match ($state) {
                            'Registry' => 'success',
                            'Girdawari' => 'info',
                            'Khatauni' => 'warning',
                            default => 'gray',
                        })
                        ->searchable()
                        ->sortable(),
                        
                    TextColumn::make('district')
                        ->icon('heroicon-o-map-pin')
                        ->searchable()
                        ->sortable()
                        ->toggleable(),
                        
                    TextColumn::make('anchal')
                        ->label('Anchal')
                        ->searchable()
                        ->sortable()
                        ->toggleable(),
                        
                    TextColumn::make('mauza')
                        ->searchable()
                        ->toggleable(),
                        
                    TextColumn::make('thana_no')
                        ->label('Thana No.')
                        ->badge()
                        ->color('info')
                        ->searchable()
                        ->sortable(),
                        
                    TextColumn::make('price')
                        ->label('Price')
                        ->getStateUsing(fn ($record) => $record->additional_price ?? $record->price)
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
                        ->falseColor('danger')
                        ->sortable(),
                        
                    TextColumn::make('created_at')
                        ->label('Created')
                        ->dateTime('M d, Y H:i')
                        ->sortable()
                        ->toggleable(isToggledHiddenByDefault: true),
                        
                    TextColumn::make('updated_at')
                        ->label('Updated')
                        ->dateTime('M d, Y H:i')
                        ->sortable()
                        ->toggleable(isToggledHiddenByDefault: true),
                ])
                ->filters([
                    SelectFilter::make('document_type')
                        ->label('Document Type')
                        ->options([
                            'Registry' => 'Registry',
                            'Girdawari' => 'Girdawari',
                            'Khatauni' => 'Khatauni',
                        ]),
                    SelectFilter::make('district')
                        ->label('District'),
                    TernaryFilter::make('is_active')
                        ->label('Active Status')
                        ->placeholder('All documents')
                        ->trueLabel('Active only')
                        ->falseLabel('Inactive only'),
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
