<?php

namespace App\Filament\Resources\Promotions\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;

class PromotionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns([
                'default' => 1,
                'lg' => 3,
            ])
            ->components([
                Section::make('Thông tin khuyến mãi')
                    ->columnSpan([
                        'default' => 1,
                        'lg' => 2,
                    ])
                    ->schema([
                        TextInput::make('code')
                            ->label('Mã khuyến mãi')
                            ->required()
                            ->maxLength(50),

                        TextInput::make('name')
                            ->label('Tên khuyến mãi')
                            ->required()
                            ->maxLength(255),

                        Textarea::make('description')
                            ->label('Mô tả')
                            ->rows(4)
                            ->columnSpanFull(),

                        Grid::make(2)->schema([
                            DatePicker::make('start_date')
                                ->label('Ngày bắt đầu')
                                ->required(),

                            DatePicker::make('end_date')
                                ->label('Ngày kết thúc')
                                ->required()
                                ->minDate(fn (callable $get) => $get('start_date')),
                        ]),
                    ]),

                Section::make('Thiết lập giảm giá')
                    ->columnSpan([
                        'default' => 1,
                        'lg' => 1,
                    ])
                    ->schema([
                        Select::make('discount_type')
                            ->label('Loại giảm giá')
                            ->options([
                                '0' => 'Giảm theo %',
                                '1' => 'Giảm tiền mặt',
                            ])
                            ->required()
                            ->dehydrateStateUsing(fn($state) => (string) $state),

                        TextInput::make('discount_value')
                            ->label('Giá trị giảm')
                            ->numeric()
                            ->required()
                            ->rule(function (callable $get) {
                                return function ($attribute, $value, $fail) use ($get) {
                                    if ($get('discount_type') == '0' && $value > 100) {
                                        $fail('Giảm theo % không được vượt quá 100%.');
                                    }
                                };
                            }),

                        TextInput::make('usage_limit')
                            ->label('Giới hạn sử dụng')
                            ->numeric()
                            ->placeholder('Không giới hạn'),

                        TextInput::make('per_user_limit')
                            ->label('Giới hạn mỗi user')
                            ->numeric()
                            ->placeholder('Không giới hạn'),

                        Select::make('status')
                            ->label('Trạng thái')
                            ->options([
                                '1' => 'Hoạt động',
                                '0' => 'Ẩn',
                            ])
                            ->default('1')
                            ->required(),
                    ]),

            ]);
    }
}
