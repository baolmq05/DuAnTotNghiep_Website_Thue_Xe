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

                // 2. Đối tượng liên quan (Người báo cáo, Chủ xe bị báo cáo & Chuyến đi)
                Section::make('Thông tin Liên quan')
                    ->icon('heroicon-o-user-group')
                    ->description('Thông tin chi tiết về người báo cáo, chủ xe bị báo cáo và chuyến đi phát sinh sự cố.')
                    ->schema([
                        Grid::make(3)->schema([
                            Section::make('Người báo cáo')
                                ->schema([
                                    TextEntry::make('reporter.name')
                                        ->label('Họ và tên')
                                        ->weight('bold')
                                        ->placeholder('Không xác định'),
                                    TextEntry::make('reporter.email')
                                        ->label('Email')
                                        ->placeholder('N/A'),
                                    TextEntry::make('reporter.phone')
                                        ->label('Số điện thoại')
                                        ->copyable()
                                        ->placeholder('N/A'),
                                ])->columnSpan(1),

                            Section::make('Chủ xe (Người bị báo cáo)')
                                ->schema([
                                    TextEntry::make('trip.car.owner.name')
                                        ->label('Họ và tên chủ xe')
                                        ->weight('bold')
                                        ->placeholder('Không xác định'),
                                    TextEntry::make('trip.car.owner.email')
                                        ->label('Email')
                                        ->placeholder('N/A'),
                                    TextEntry::make('trip.car.owner.phone')
                                        ->label('Số điện thoại')
                                        ->copyable()
                                        ->placeholder('N/A'),
                                    TextEntry::make('trip.car.owner.status')
                                        ->label('Trạng thái tài khoản')
                                        ->formatStateUsing(fn ($state) => (int) $state === 1 ? 'Hoạt động' : 'Bị khóa')
                                        ->badge()
                                        ->color(fn ($state) => (int) $state === 1 ? 'success' : 'danger')
                                        ->placeholder('N/A'),
                                    TextEntry::make('owner_strikes')
                                        ->label('Vi phạm (90 ngày qua)')
                                        ->getStateUsing(function ($record) {
                                            $ownerId = $record->trip?->car?->user_id;
                                            if (!$ownerId) return '0 lần';
                                            $count = \App\Models\OwnerPenalty::where('user_id', $ownerId)
                                                ->where('created_at', '>=', now()->subDays(90))
                                                ->count();
                                            return $count . ' lần vi phạm';
                                        })
                                        ->badge()
                                        ->color(function ($record) {
                                            $ownerId = $record->trip?->car?->user_id;
                                            if (!$ownerId) return 'gray';
                                            $count = \App\Models\OwnerPenalty::where('user_id', $ownerId)
                                                ->where('created_at', '>=', now()->subDays(90))
                                                ->count();
                                            return $count > 0 ? 'warning' : 'success';
                                        }),
                                ])->columnSpan(1),

                            Section::make('Chuyến đi liên quan')
                                ->schema([
                                    TextEntry::make('trip.id')
                                        ->label('ID Chuyến đi')
                                        ->formatStateUsing(fn ($state) => '#' . $state)
                                        ->weight('bold'),
                                    TextEntry::make('trip.trip_code')
                                        ->label('Mã chuyến đi')
                                        ->placeholder('N/A'),
                                    TextEntry::make('trip.car.name')
                                        ->label('Phương tiện')
                                        ->placeholder('Không xác định'),
                                    TextEntry::make('trip.car.license_plate')
                                        ->label('Biển số xe')
                                        ->placeholder('N/A'),
                                ])->columnSpan(1),
                        ]),
                    ]),

                // 3. Hình ảnh bằng chứng
                Section::make('Hình ảnh Bằng chứng')
                    ->icon('heroicon-o-photo')
                    ->description('Ảnh chụp màn hình, hình ảnh sự cố hoặc bằng chứng người dùng đã tải lên.')
                    ->schema([
                        ImageEntry::make('images.image_url')
                            ->hiddenLabel()
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

                            TextEntry::make('penalty.penalty_type')
                                ->label('Hình thức xử phạt áp dụng')
                                ->badge()
                                ->placeholder('Chưa/Không có hình phạt')
                                ->visible(fn ($record) => $record->penalty !== null),

                            TextEntry::make('penalty.reason')
                                ->label('Lý do vi phạm đã ghi nhận')
                                ->placeholder('N/A')
                                ->visible(fn ($record) => $record->penalty !== null)
                                ->columnSpan(2),
                        ]),
                    ]),
            ]);
    }
}
