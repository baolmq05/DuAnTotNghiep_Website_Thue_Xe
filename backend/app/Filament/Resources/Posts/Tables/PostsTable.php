<?php

namespace App\Filament\Resources\Posts\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use App\Enum\PostStatus;

class PostsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->searchable()->label("Tiêu đề"),
                TextColumn::make('category.name')->searchable()->label('Danh mục')->badge(),
                TextColumn::make('status')
                    ->label('Trạng thái')
                    ->badge()
                    ->color(fn($state) => $state instanceof PostStatus ? $state->getColor() : (PostStatus::tryFrom($state)?->getColor() ?? 'gray'))
                    ->formatStateUsing(fn($state) => $state instanceof PostStatus ? $state->getLabel() : (PostStatus::tryFrom($state)?->getLabel() ?? '')),
                TextColumn::make('created_at')->label('Thời gian tạo')->dateTime('d/m/Y H:i'),
                TextColumn::make('published_at')->label('Thời gian đăng tải')->dateTime('d/m/Y H:i'),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Trạng thái')
                    ->options([
                        0 => 'Nháp',
                        1 => 'Công khai',
                    ]),
                SelectFilter::make('post_category_id')
                    ->label('Danh mục')
                    ->relationship('category', 'name'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()->after(fn () => redirect(request()->header('Referer'))),
                RestoreAction::make()->after(fn () => redirect(request()->header('Referer'))),
                ForceDeleteAction::make()->after(fn () => redirect(request()->header('Referer'))),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()->after(fn () => redirect(request()->header('Referer'))),
                    RestoreBulkAction::make()->after(fn () => redirect(request()->header('Referer'))),
                    ForceDeleteBulkAction::make()->after(fn () => redirect(request()->header('Referer'))),
                ]),
            ]);
    }
}
