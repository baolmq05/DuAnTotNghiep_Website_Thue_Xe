<?php

namespace App\Filament\Resources\Posts\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\FileUpload;
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
            ->columns([
                'default' => 1,
                'lg' => 3,
            ])
            ->components([
                Section::make('Thông tin bài viết')
                    ->columnSpan([
                        'default' => 1,
                        'lg' => 2,
                    ])
                    ->schema([
                        TextInput::make('title')
                            ->label('Tiêu đề')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn(Set $set, ?string $state) => $set('slug', Str::slug($state))),

                        RichEditor::make('content')
                            ->label('Nội dung chi tiết')
                            ->required()
                            ->fileAttachmentsDisk('public')
                            ->fileAttachmentsDirectory('blogs/' . date('Y/m'))
                            ->columnSpanFull(),

                        TextInput::make('slug')
                            ->label('Slug (Đường dẫn tĩnh)')
                            ->required()
                            ->maxLength(255)
                            ->unique(table: 'posts', column: 'slug', ignoreRecord: true),

                        TextInput::make('seo_keywords')
                            ->label('Từ khóa chính (SEO)')
                            ->placeholder('Nhập từ khóa SEO bài viết (ví dụ: thue xe tu lai, meo thue xe)...')
                            ->maxLength(255),

                        Textarea::make('excerpt')
                            ->label('Mô tả ngắn')
                            ->required()
                            ->rows(3)
                            ->columnSpanFull(),
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
                                        '1' => 'Xuất bản',
                                        '0' => 'Bản nháp',
                                    ])
                                    ->default(1),
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
                                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'image/x-webp', 'image/gif', 'image/svg+xml', 'image/jpg'])
                                    ->disk('public')
                                    ->directory('blogs/' . date('Y/m'))
                                    ->visibility('public')
                                    ->imagePreviewHeight('250')
                                    ->getUploadedFileNameForStorageUsing(
                                        fn(TemporaryUploadedFile $file): string => date('Y-m-d-H-i-s') . '.' . $file->getClientOriginalExtension()
                                    ),

                                TextInput::make('thumbnail_alt')
                                    ->label('Thẻ ALT (Mô tả ảnh đại diện)')
                                    ->placeholder('Nhập mô tả thẻ ALT cho ảnh đại diện (phục vụ SEO)...')
                                    ->maxLength(255),
                            ]),
                    ])
            ]);
    }
}
