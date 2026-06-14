<?php

namespace App\Filament\Resources\CarBrands\RelationManagers;

use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\BulkActionGroup;

class CarTypesRelationManager extends RelationManager
{
    protected static string $relationship = 'carTypes';
    
    protected static ?string $title = 'Danh sách loại xe'; 

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('type_name')
                    ->label('Tên loại xe')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('type_name')
            ->columns([
                TextColumn::make('type_name')
                    ->label('Tên loại xe')
                    ->searchable() 
                    ->weight('bold')
                    ->color('primary'),
                
                TextColumn::make('cars_count')
                    ->label('Số xe')
                    ->state(fn ($record) => $record->cars()->count())
                    ->badge()
                    ->color('success'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make()->label('Thêm loại xe mới'), 
            ])
            ->recordActions([
                EditAction::make()->iconButton(),
                DeleteAction::make()->iconButton(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultPaginationPageOption(5);
    }
}