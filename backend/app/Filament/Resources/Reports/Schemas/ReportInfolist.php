<?php

namespace App\Filament\Resources\Reports\Schemas;

use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ReportInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                // 1. Thông tin Báo cáo & Khiếu nại
                Section::make('Thông tin Báo cáo & Khiếu nại')
                    ->icon('heroicon-o-exclamation-triangle')
                    ->description('Chi tiết khiếu nại hoặc báo cáo vi phạm do người dùng gửi.')
                    ->schema([
                        Grid::make(3)->schema([
                            TextEntry::make('title')
                                ->label('Tiêu đề báo cáo')
                                ->weight('bold')
                                ->color('primary'),

                            TextEntry::make('report_type')
                                ->label('Loại báo cáo')
                                ->badge(),

                            TextEntry::make('status')
                                ->label('Trạng thái')
                                ->badge(),

                            TextEntry::make('created_at')
                                ->label('Thời gian gửi')
                                ->dateTime('d/m/Y H:i:s'),

                            TextEntry::make('description')
                                ->label('Nội dung chi tiết')
                                ->columnSpanFull(),
                        ]),
                    ]),

                // 2. Đối tượng liên quan (Người báo cáo & Chuyến đi)
                Section::make('Thông tin Liên quan')
                    ->icon('heroicon-o-user-group')
                    ->description('Thông tin chi tiết về người báo cáo và chuyến đi phát sinh sự cố.')
                    ->schema([
                        Grid::make(2)->schema([
                            Section::make('Người báo cáo')
                                ->schema([
                                    TextEntry::make('reporter.name')
                                        ->label('Họ và tên')
                                        ->weight('bold'),
                                    TextEntry::make('reporter.email')
                                        ->label('Email'),
                                    TextEntry::make('reporter.phone')
                                        ->label('Số điện thoại')
                                        ->copyable(),
                                ])->columnSpan(1),

                            Section::make('Chuyến đi liên quan')
                                ->schema([
                                    TextEntry::make('trip.id')
                                        ->label('Mã chuyến đi')
                                        ->formatStateUsing(fn ($state) => '#' . $state)
                                        ->weight('bold'),
                                    TextEntry::make('trip.car.name')
                                        ->label('Phương tiện')
                                        ->placeholder('Không xác định'),
                                    TextEntry::make('trip.car.owner.name')
                                        ->label('Chủ xe')
                                        ->placeholder('Không xác định'),
                                ])->columnSpan(1),
                        ]),
                    ]),

                // 3. Hình ảnh bằng chứng
                Section::make('Hình ảnh Bằng chứng')
                    ->icon('heroicon-o-photo')
                    ->description('Ảnh chụp màn hình, hình ảnh sự cố hoặc bằng chứng người dùng đã tải lên.')
                    ->schema([
                        ImageEntry::make('images.image_url')
                            ->label('')
                            ->placeholder('Không có hình ảnh bằng chứng nào được tải lên.')
                            ->extraImgAttributes([
                                'class' => 'rounded-lg shadow-md border object-cover',
                                'style' => 'aspect-ratio: 16/9; max-height: 220px; display: inline-block; margin: 4px;',
                            ])
                            ->columnSpanFull(),
                    ]),

                // 4. Thông tin xử lý của Admin
                Section::make('Kết quả Xử lý của Admin')
                    ->icon('heroicon-o-clipboard-document-check')
                    ->description('Thông tin quản trị viên tiếp nhận và xử lý báo cáo này.')
                    ->schema([
                        Grid::make(3)->schema([
                            TextEntry::make('resolver.name')
                                ->label('Quản trị viên xử lý')
                                ->placeholder('Chưa xử lý')
                                ->weight('bold'),

                            TextEntry::make('resolved_at')
                                ->label('Thời gian hoàn tất')
                                ->dateTime('d/m/Y H:i:s')
                                ->placeholder('N/A'),

                            TextEntry::make('status')
                                ->label('Trạng thái hiện tại')
                                ->badge(),

                            TextEntry::make('admin_note')
                                ->label('Ghi chú / Kết luận của Admin')
                                ->placeholder('Chưa có ghi chú xử lý nào.')
                                ->columnSpanFull(),
                        ]),
                    ]),
            ]);
    }
}
