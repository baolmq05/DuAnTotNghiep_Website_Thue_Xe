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
use Filament\Schemas\Components\Utilities\Set;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class PostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(3)
                    ->columnSpan('full')
                    ->schema([
                        Grid::make(2)
                            ->columnSpan([
                                'default' => 1,
                                'lg' => 2,
                            ])
                            ->schema([
                                Section::make('Thông tin bài viết')
                                    ->schema([
                                        TextInput::make('title')
                                            ->label('Tiêu đề')
                                            ->required()
                                            ->maxLength(255)
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state))),

                                        TextInput::make('slug')
                                            ->label('Slug (Đường dẫn tĩnh)')
                                            ->required()
                                            ->maxLength(255)
                                            ->unique(table: 'posts', column: 'slug', ignoreRecord: true),

                                        Textarea::make('summary')
                                            ->label('Mô tả ngắn')
                                            ->required()
                                            ->rows(3)
                                            ->columnSpanFull(),

                                        RichEditor::make('content')
                                            ->label('Nội dung chi tiết')
                                            ->required()
                                            ->fileAttachmentsDisk('public')
                                            ->fileAttachmentsDirectory('blogs/' . date('Y/m'))
                                            ->columnSpanFull(),
                                    ]),
                            ]),

                        Grid::make(1)
                            ->columnSpan([
                                'default' => 1,
                                'lg' => 1,
                            ])
                            ->schema([
                                Section::make('Trạng thái')
                                    ->schema([
                                        Select::make('status')
                                            ->label('Trạng thái')
                                            ->options([
                                                '1' => 'Đã xuất bản',
                                                '0' => 'Bản nháp',
                                            ])
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
                                            ->disk('public')
                                            ->directory('blogs/' . date('Y/m'))
                                            ->getUploadedFileNameForStorageUsing(
                                                fn (TemporaryUploadedFile $file): string => date('Y-m-d-H-i-s') . '.' . $file->getClientOriginalExtension()
                                            )
                                            ->imageEditor()
                                            ->imageEditorViewportWidth('1920')
                                            ->imageEditorViewportHeight('1080'),
                                    ]),
                            ]),
                    ]),
            ]);
    }
}
