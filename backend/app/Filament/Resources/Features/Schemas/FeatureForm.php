<?php

namespace App\Filament\Resources\Features\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class FeatureForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('feature_name')
                    ->label('Tên tính năng')
                    ->placeholder('Nhập tên tính năng (ví dụ: Bản đồ, Bluetooth...)')
                    ->required()
                    ->unique(ignoreRecord: true),
                TextInput::make('icon')
                    ->label('Đường dẫn ảnh biểu tượng (URL)')
                    ->placeholder('Nhập URL ảnh (ví dụ: https://res.cloudinary.com/...)')
                    ->url()
                    ->required(),
                Textarea::make('description')
                    ->label('Mô tả tính năng')
                    ->placeholder('Nhập chi tiết mô tả tính năng của xe')
                    ->required()
                    ->columnSpanFull(),
                \Filament\Forms\Components\Select::make('status')
                    ->label('Trạng thái')
                    ->options([
                        1 => 'Hoạt động',
                        0 => 'Không hoạt động',
                    ])
                    ->default(1)
                    ->required(),
            ]);
    }
}
