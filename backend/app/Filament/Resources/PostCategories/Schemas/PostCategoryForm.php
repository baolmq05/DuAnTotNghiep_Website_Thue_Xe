<?php

namespace App\Filament\Resources\PostCategories\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class PostCategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Tên danh mục bài viết')
                    ->required(),
                Select::make('status')
                    ->label('Trạng thái')
                    ->options([
                        1 => 'Hiển thị',
                        0 => 'Ẩn',
                    ])
                    ->placeholder('Chọn trạng thái')
                    ->required(),
            ]);
    }
}
