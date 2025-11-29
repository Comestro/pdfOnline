<?php

namespace App\Filament\Resources\Documents\Schemas;

use App\Models\Document;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class DocumentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Document Details')
                    ->description('Enter the basic information about the document.')
                    ->icon('heroicon-o-document-text')
                    ->columns(2)
                    ->schema([
                        TextInput::make('title')
                            ->label('Title')
                            ->required()
                            ->columnSpanFull(),
                            
                        TextInput::make('document_type')
                            ->label('Document Type')
                            ->datalist(
                                Document::query()
                                    ->distinct()
                                    ->pluck('document_type')
                                    ->toArray()
                            )
                            ->required(),
                            
                        TextInput::make('district')
                            ->label('District')
                            ->datalist(
                                Document::query()
                                    ->distinct()
                                    ->pluck('district')
                                    ->toArray()
                            )
                            ->required(),
                            
                        TextInput::make('anchal')
                            ->label('Anchal')
                            ->datalist(
                                Document::query()
                                    ->distinct()
                                    ->pluck('anchal')
                                    ->toArray()
                            )
                            ->required(),
                            
                        TextInput::make('mauza')
                            ->label('Mauza')
                            ->datalist(
                                Document::query()
                                    ->distinct()
                                    ->pluck('mauza')
                                    ->toArray()
                            )
                            ->required(),
                            
                        TextInput::make('thana_no')
                            ->label('Thana No')
                            ->datalist(
                                Document::query()
                                    ->distinct()
                                    ->pluck('thana_no')
                                    ->toArray()
                            )
                            ->required(),
                            
                        Hidden::make('is_active')
                            ->default(true),
                    ]),

                Section::make('Document Files')
                    ->description('Upload the PDF files associated with this document.')
                    ->icon('heroicon-o-paper-clip')
                    ->schema([
                        Repeater::make('files')
                            ->relationship('files')
                            ->label('Files')
                            ->columns(3)
                            ->schema([
                                FileUpload::make('file_path')
                                    ->label('PDF File')
                                    ->disk('private')
                                    ->directory('documents')
                                    ->preserveFilenames()
                                    ->acceptedFileTypes(['application/pdf'])
                                    ->maxSize(512000)
                                    ->enableOpen()
                                    ->enableDownload()
                                    ->visibility('private')
                                    ->required()
                                    ->columnSpanFull(),
                                    
                                TextInput::make('title')
                                    ->label('File Name')
                                    ->required(),
                                    
                                TextInput::make('khata_no')
                                    ->label('Khata No')
                                    ->placeholder('Optional'),
                                    
                                TextInput::make('price')
                                    ->label('Price')
                                    ->numeric()
                                    ->prefix('₹')
                                    ->required(),
                                    
                                \Filament\Forms\Components\Checkbox::make('copy_price_to_all')
                                    ->label('Copy Price to All')
                                    ->live()
                                    ->afterStateUpdated(function ($get, $set, $state) {
                                        if ($state) {
                                            $currentPrice = $get('price');
                                            // Get the files array from the parent context
                                            // We need to go up levels. 
                                            // files.uuid.copy_price_to_all -> ../ -> files.uuid -> ../ -> files
                                            // So ../../files should work if we are at the root of the item.
                                            // However, $get('../../files') gets the 'files' component state from the root container.
                                            
                                            $files = $get('../../files');
                                            
                                            if (is_array($files)) {
                                                foreach ($files as $key => $file) {
                                                    $files[$key]['price'] = $currentPrice;
                                                }
                                                $set('../../files', $files);
                                            }
                                            
                                            $set('copy_price_to_all', false);
                                        }
                                    })
                                    ->dehydrated(false),
                            ])
                            ->defaultItems(1)
                            ->grid(1)
                            ->itemLabel(fn (array $state): ?string => $state['title'] ?? null),
                    ]),
            ]);
    }
}
