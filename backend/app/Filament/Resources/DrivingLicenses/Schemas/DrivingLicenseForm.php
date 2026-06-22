<?php

namespace App\Filament\Resources\DrivingLicenses\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;

class DrivingLicenseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns([
                'default' => 1,
                'lg' => 3,
            ])
            ->components([
                Section::make('Thông tin giấy phép lái xe')
                    ->columnSpan([
                        'default' => 1,
                        'lg' => 2,
                    ])
                    ->schema([

                        Placeholder::make('license_image')
                            ->label('Ảnh GPLX')
                            ->content(
                                fn($record) =>
                                $record?->image
                                    ? new HtmlString(
                                        '<img src="' . e($record->image) . '"
                                             style="
                                                max-width:100%;
                                                max-height:350px;
                                                border-radius:12px;
                                                border:1px solid #ddd;
                                                box-shadow:0 2px 8px rgba(0,0,0,.1);
                                             ">'
                                    )
                                    : new HtmlString(
                                        '<span class="text-gray-400 italic">
                                            Không có ảnh
                                        </span>'
                                    )
                            ),

                        TextInput::make('full_name')
                            ->label('Họ và tên')
                            ->disabled(),

                        TextInput::make('driving_license_number')
                            ->label('Số GPLX')
                            ->disabled(),

                        DatePicker::make('DOB')
                            ->label('Ngày sinh')
                            ->disabled(),
                    ]),

                // ===== RIGHT =====
                Section::make('Duyệt giấy phép')
                    ->columnSpan([
                        'default' => 1,
                        'lg' => 1,
                    ])
                    ->schema([
                        Placeholder::make('current_status')
                            ->label('Trạng thái')
                            ->content(fn($record) => match ($record?->status) {
                                0 => new HtmlString('<span style="color:#ca8a04;">Chờ duyệt</span>'),
                                1 => new HtmlString('<span style="color:#16a34a;">Đã duyệt</span>'),
                                2 => new HtmlString('<span style="color:#dc2626;">Từ chối</span>'),
                            }),

                        Select::make('status')
                            ->label('Duyệt GPLX')
                            ->options([
                                1 => 'Đã duyệt',
                                2 => 'Từ chối',
                            ])
                            ->visible(fn($record) => $record?->status == 0),
                    ]),
            ]);
    }
}
