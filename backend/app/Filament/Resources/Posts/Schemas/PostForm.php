<?php

namespace App\Filament\Resources\Posts\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\DateTimePicker;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Forms\Set;
use Illuminate\Support\Str;

class PostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(3)
                    ->columnSpan('full')
                    ->schema([
                        // Cột chính (2/3 chiều rộng) - Giao diện soạn thảo
                        Grid::make(1)
                            ->columnSpan(2)
                            ->schema([
                                Section::make('Nội dung bài viết')
                                    ->schema([
                                        TextInput::make('title')
                                            ->required()
                                            ->label("Tiêu đề bài viết")
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state))),
                                        
                                        TextInput::make('slug')
                                            ->required()
                                            ->label("Slug / Đường dẫn tĩnh")
                                            ->disabled()
                                            ->dehydrated()
                                            ->unique(ignoreRecord: true),
                                        
                                        RichEditor::make('content')
                                            ->toolbarButtons([
                                                ['bold', 'italic', 'underline', 'strike', 'subscript', 'superscript'],
                                                ['h2', 'h3'],
                                                ['alignStart', 'alignCenter', 'alignEnd'],
                                                ['blockquote', 'bulletList', 'orderedList'],
                                                ['table', 'attachFiles'],
                                                ['undo', 'redo'],
                                            ])
                                            ->label("Nội dung bài viết")
                                            ->required(),
                                    ]),
                                
                                Section::make('Tóm tắt bài viết')
                                    ->schema([
                                        Textarea::make('excerpt')
                                            ->label("Mô tả ngắn (Excerpt)")
                                            ->rows(3)
                                            ->required(),
                                    ]),
                            ]),

                        // Cột Sidebar (1/3 chiều rộng) - Thiết lập bài viết
                        Grid::make(1)
                            ->columnSpan(1)
                            ->schema([
                                Section::make('Đăng tải & Trạng thái')
                                    ->schema([
                                        Select::make('status')
                                            ->label('Trạng thái')
                                            ->options([
                                                0 => 'Nháp',
                                                1 => 'Công khai',
                                            ])
                                            ->required()
                                            ->default(1),

                                        DateTimePicker::make('published_at')
                                            ->label('Ngày đăng tải')
                                            ->default(now()),
                                    ]),

                                Section::make('Chuyên mục')
                                    ->schema([
                                        Select::make('post_category_id')
                                            ->label('Danh mục')
                                            ->relationship('category', 'name')
                                            ->searchable()
                                            ->preload()
                                            ->required(),
                                    ]),

                                Section::make('Ảnh đại diện')
                                    ->schema([
                                        FileUpload::make('thumbnail')
                                            ->label("Ảnh đại diện")
                                            ->image()
                                            ->imageEditor()
                                            ->imageEditorViewportWidth('1920')
                                            ->imageEditorViewportHeight('1080'),
                                    ]),
                            ]),
                    ]),
            ]);
    }
}
