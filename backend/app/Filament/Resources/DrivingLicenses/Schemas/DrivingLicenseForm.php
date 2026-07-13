<?php

namespace App\Filament\Resources\DrivingLicenses\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;
use Filament\Actions\Action;
use Filament\Schemas\Components\Actions;

class DrivingLicenseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(3)
                    ->columnSpan('full')
                    ->schema([
                        // ===== LEFT COLUMN (2/3 width) =====
                        Grid::make(1)
                            ->columnSpan(2)
                            ->schema([
                                Section::make('Thông tin giấy phép lái xe')
                                    ->schema([
                                        Placeholder::make('license_image')
                                            ->label('Ảnh GPLX')
                                            ->content(
                                                fn($record) =>
                                                $record?->image
                                                    ? new HtmlString(
                                                        '<div style="
                                                            width:100%;   
                                                            min-height:420px;  
                                                            display:flex;  
                                                            justify-content:center;  
                                                            align-items:center;   
                                                            background:#f8fafc; 
                                                            border:1px solid #e5e7eb;  
                                                            border-radius:12px;
                                                            padding:12px; 
                                                        ">
                                                            <img src="' . e($record->image) . '"      
                                                            style="            
                                                               max-width:100%;
                                                               max-height:550px;
                                                               width:auto;
                                                               height:auto;
                                                               object-fit:contain;
                                                              border-radius:8px;     
                                                            ">
                                                        </div>'
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
                            ]),

                        // ===== RIGHT COLUMN (1/3 width) =====
                        Grid::make(1)
                            ->columnSpan(1)
                            ->schema([
                                Section::make('Duyệt giấy phép')
                                    ->schema([
                                        Placeholder::make('current_status')
                                            ->label('Trạng thái')
                                            ->content(fn($record) => match ($record?->status) {
                                                0 => new HtmlString('<span style="color:#ca8a04;">Chờ duyệt</span>'),
                                                1 => new HtmlString('<span style="color:#16a34a;">Đã duyệt</span>'),
                                                2 => new HtmlString('<span style="color:#dc2626;">Từ chối</span>'),
                                                default => '-',
                                            }),

                                        Actions::make([
                                            Action::make('approve')
                                                ->label('Duyệt')
                                                ->icon('heroicon-o-check-circle')
                                                ->color('success')
                                                // ->requiresConfirmation()
                                                ->action(function ($record) {
                                                    $record->update([
                                                        'status' => 1,
                                                    ]);
                                                }),

                                            Action::make('reject')
                                                ->label('Từ chối')
                                                ->icon('heroicon-o-x-circle')
                                                ->color('danger')
                                                // ->requiresConfirmation()
                                                ->action(function ($record) {
                                                    $record->update([
                                                        'status' => 2,
                                                    ]);
                                                }),
                                        ])
                                            ->visible(fn($record) => $record?->status == 0)
                                    ]),

                                Section::make('Thông tin tài khoản')
                                    ->schema([
                                        Placeholder::make('account_name')
                                            ->label('Tên người dùng')
                                            ->content(fn($record) => $record?->user?->name ?? 'Không tìm thấy'),

                                        Placeholder::make('account_email')
                                            ->label('Email')
                                            ->content(fn($record) => $record?->user?->email ?? 'Không tìm thấy'),

                                        Placeholder::make('account_phone')
                                            ->label('Số điện thoại')
                                            ->content(fn($record) => $record?->user?->phone ?? 'Chưa cập nhật'),

                                        Placeholder::make('account_action')
                                            ->label('')
                                            ->content(function ($record) {
                                                $user = $record?->user;
                                                if (!$user) {
                                                    return '';
                                                }
                                                $url = route('filament.admin.resources.users.edit', ['record' => $user->id]);
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
                                                        Xem hồ sơ người dùng
                                                    </a>
                                                ');
                                            })
                                            ->visible(fn($record) => (bool) ($record?->user)),
                                    ]),
                            ]),
                    ]),
            ]);
    }
}
