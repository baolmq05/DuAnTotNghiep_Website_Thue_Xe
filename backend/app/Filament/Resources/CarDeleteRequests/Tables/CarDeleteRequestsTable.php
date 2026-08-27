<?php

namespace App\Filament\Resources\CarDeleteRequests\Tables;

use App\Models\Car;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Actions\Action;

class CarDeleteRequestsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('images.image_url')
                    ->label('Ảnh đại diện')
                    ->circular()
                    ->limit(1),

                TextColumn::make('owner.name')
                    ->label('Chủ xe')
                    ->searchable()
                    ->weight('bold')
                    ->color('primary'),

                TextColumn::make('owner.phone')
                    ->label('Số điện thoại')
                    ->searchable(),

                TextColumn::make('name')
                    ->label('Tên xe')
                    ->searchable(),

                TextColumn::make('license_plate')
                    ->label('Biển số')
                    ->searchable(),

                TextColumn::make('deletion_reason')
                    ->label('Lý do xóa')
                    ->wrap()
                    ->weight('medium')
                    ->color('danger'),

                TextColumn::make('updated_at')
                    ->label('Thời gian yêu cầu')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                Action::make('viewCar')
                    ->label('Xem chi tiết')
                    ->icon('heroicon-o-eye')
                    ->color('info')
                    ->url(fn (Car $record) => route('filament.admin.resources.quan-ly-xe.view', ['record' => $record->id])),

                Action::make('approveDelete')
                    ->label('Duyệt xóa')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Duyệt yêu cầu xóa xe')
                    ->modalDescription('Bạn có chắc chắn muốn duyệt yêu cầu xóa xe này? Hành động này sẽ xóa vĩnh viễn chiếc xe khỏi hệ thống và không thể khôi phục.')
                    ->modalSubmitActionLabel('Xác nhận xóa vĩnh viễn')
                    ->hidden(fn (Car $record) => $record->trashed())
                    ->action(function (Car $record) {
                        // Gửi thông báo cho chủ xe trước khi xóa xe khỏi DB
                        $owner = $record->owner;
                        if ($owner) {
                            \App\Models\Notification::create([
                                'user_id' => $owner->id,
                                'message' => "Yêu cầu xóa xe '{$record->name}' (Biển số: {$record->license_plate}) của bạn đã được ban quản trị phê duyệt. Xe của bạn đã được xóa khỏi hệ thống.",
                                'is_read' => '0',
                            ]);
                        }
                        
                        // Tiến hành xóa xe
                        $record->delete();
                    }),

                Action::make('rejectDelete')
                    ->label('Từ chối')
                    ->icon('heroicon-o-arrow-uturn-left')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->modalHeading('Từ chối yêu cầu xóa xe')
                    ->modalDescription('Bạn có chắc chắn muốn từ chối yêu cầu xóa xe này? Xe sẽ được chuyển về trạng thái dừng hoạt động và chủ xe sẽ nhận được thông báo.')
                    ->modalSubmitActionLabel('Từ chối xóa')
                    ->hidden(fn (Car $record) => $record->trashed())
                    ->action(function (Car $record) {
                        $record->update([
                            'status' => 0,
                            'deletion_reason' => null
                        ]);
                        
                        $owner = $record->owner;
                        if ($owner) {
                            \App\Models\Notification::create([
                                'user_id' => $owner->id,
                                'message' => "Yêu cầu xóa xe '{$record->name}' (Biển số: {$record->license_plate}) của bạn đã bị từ chối bởi ban quản trị. Xe đã được chuyển về trạng thái dừng hoạt động.",
                                'is_read' => '0',
                            ]);
                        }
                    }),
            ])
            ->toolbarActions([
                //
            ]);
    }
}
