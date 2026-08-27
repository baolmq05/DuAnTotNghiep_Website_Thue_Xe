<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Placeholder;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns([
                'default' => 1,
                'lg' => 3,
            ])
            ->components([
                Section::make('Thông tin tài khoản')
                    ->columnSpan([
                        'default' => 1,
                        'lg' => 2,
                    ])
                    ->schema([
                        TextInput::make('name')
                            ->label('Họ và tên')
                            ->placeholder('Nhập họ và tên')
                            ->required()
                            ->disabled(fn (string $operation): bool => $operation === 'edit'),
                        TextInput::make('email')
                            ->label('Email')
                            ->placeholder('Nhập email')
                            ->email()
                            ->required()
                            ->disabled(fn (string $operation): bool => $operation === 'edit'),
                        Select::make('role')
                            ->label('Vai trò')
                            ->options([
                                '1' => 'Quản trị viên',
                                '2' => 'Người mua',
                                '3' => 'Người bán',
                            ])
                            ->default('3')
                            ->required(),
                        Select::make('status')
                            ->label('Trạng thái')
                            ->options([
                                '0' => 'Bị khóa',
                                '1' => 'Đang hoạt động',
                            ])
                            ->default('1')
                            ->required(),
                    ]),

                Section::make('Giấy phép lái xe')
                    ->columnSpan([
                        'default' => 1,
                        'lg' => 1,
                    ])
                    ->schema([
                        Placeholder::make('driving_license_status')
                            ->label('Trạng thái GPLX')
                            ->content(function ($record) {
                                if (!$record || !$record->drivingLicense) {
                                    return new HtmlString('<span class="text-gray-500 italic">Chưa cập nhật</span>');
                                }
                                $status = $record->drivingLicense->status;
                                return match ((string) $status) {
                                    '0' => new HtmlString('<span style="color:#ca8a04; font-weight: 600;">Chờ duyệt</span>'),
                                    '1' => new HtmlString('<span style="color:#16a34a; font-weight: 600;">Đã duyệt</span>'),
                                    '2' => new HtmlString('<span style="color:#dc2626; font-weight: 600;">Bị từ chối</span>'),
                                    default => new HtmlString('<span class="text-gray-400">Không xác định</span>'),
                                };
                            }),

                        Placeholder::make('driving_license_action')
                            ->hiddenLabel()
                            ->content(function ($record) {
                                if (!$record || !$record->driving_license_id) {
                                    return '';
                                }
                                $url = route('filament.admin.resources.driving-licenses.view', ['record' => $record->driving_license_id]);
                                return new HtmlString('
                                    <a href="' . e($url) . '" 
                                       style="
                                            display: inline-flex;
                                            align-items: center;
                                            justify-content: center;
                                            padding: 0.5rem 1rem;
                                            font-size: 0.875rem;
                                            font-weight: 600;
                                            border-radius: 0.375rem;
                                            color: white;
                                            background-color: #3b82f6;
                                            text-decoration: none;
                                            transition: background-color 0.2s;
                                       "
                                       onmouseover="this.style.backgroundColor=\'#2563eb\'"
                                       onmouseout="this.style.backgroundColor=\'#3b82f6\'"
                                    >
                                        Xem chi tiết
                                    </a>
                                ');
                            })
                            ->visible(fn ($record) => (bool)($record?->driving_license_id)),
                    ]),
            ]);
    }
}
