<?php

namespace App\Filament\Resources\Reports\Schemas;

use App\Enum\ReportStatus;
use App\Enum\ReportType;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ReportForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Thông tin Báo cáo')
                    ->schema([
                        TextInput::make('title')
                            ->label('Tiêu đề')
                            ->disabled(),

                        Select::make('report_type')
                            ->label('Loại báo cáo')
                            ->options([
                                ReportType::WrongCar->value => 'Giao sai xe',
                                ReportType::NoShow->value => 'Không đến giao/nhận xe',
                                ReportType::Fraud->value => 'Gian lận',
                                ReportType::Other->value => 'Khác',
                            ])
                            ->disabled(),

                        Textarea::make('description')
                            ->label('Mô tả chi tiết')
                            ->rows(4)
                            ->disabled(),

                        Select::make('status')
                            ->label('Trạng thái xử lý')
                            ->options([
                                ReportStatus::Pending->value => 'Chờ xử lý',
                                ReportStatus::Resolved->value => 'Đã giải quyết',
                                ReportStatus::Rejected->value => 'Từ chối',
                                ReportStatus::Cancelled->value => 'Thu hồi',
                            ])
                            ->required(),

                        Textarea::make('admin_note')
                            ->label('Ghi chú của Admin')
                            ->rows(3)
                            ->placeholder('Nhập ghi chú hoặc nguyên nhân giải quyết...'),
                    ]),
            ]);
    }
}
