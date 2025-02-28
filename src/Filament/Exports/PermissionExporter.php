<?php

/*
 * Copyright CWSPS154. All rights reserved.
 * @auth CWSPS154
 * @link  https://github.com/CWSPS154
 */

namespace CWSPS154\UsersRolesPermissions\Filament\Exports;

use CWSPS154\UsersRolesPermissions\Models\Permission;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Builder;

class PermissionExporter extends Exporter
{
    protected static ?string $model = Permission::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id'),
            ExportColumn::make('name'),
            ExportColumn::make('identifier'),
            ExportColumn::make('panel_ids'),
            ExportColumn::make('route'),
            ExportColumn::make('parent.identifier'),
            ExportColumn::make('status'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = __('users-roles-permissions::users-roles-permissions.permission.export.completed', [
            'successful_rows' => number_format($export->successful_rows),
            'row' => str('row')->plural($export->successful_rows),
        ]);

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= __('users-roles-permissions::users-roles-permissions.permission.export.failed', [
                'failed_rows' => number_format($failedRowsCount),
                'row' => str('row')->plural($failedRowsCount),
            ]);
        }

        return $body;
    }

    public static function modifyQuery(Builder $query): Builder
    {
        return Permission::with('parent')
            ->whereJsonContains('panel_ids', Filament::getCurrentPanel()->getId())
            ->where('status', true);
    }
}
