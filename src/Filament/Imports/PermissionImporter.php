<?php

/*
 * Copyright CWSPS154. All rights reserved.
 * @auth CWSPS154
 * @link  https://github.com/CWSPS154
 */

namespace CWSPS154\UsersRolesPermissions\Filament\Imports;

use CWSPS154\UsersRolesPermissions\Models\Permission;
use CWSPS154\UsersRolesPermissions\Rules\RouteHas;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PermissionImporter extends Importer
{
    protected static ?string $model = Permission::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('name')
                ->requiredMapping(),
            ImportColumn::make('identifier')
                ->helperText(__('users-roles-permissions::users-roles-permissions.permission.import.helper-text.identifier'))
                ->rules(fn ($record) => [Rule::unique('permissions', 'identifier')->ignore($record->id)])
                ->castStateUsing(function (string $state): ?string {
                    return Str::slug($state);
                })
                ->requiredMapping(),
            ImportColumn::make('panel_ids')
                ->helperText(__('users-roles-permissions::users-roles-permissions.permission.import.helper-text.panel-ids'))
                ->castStateUsing(function (string $state): ?array {
                    return explode(',', preg_replace('/\s*,\s*/', ',', $state));
                })
                ->requiredMapping(),
            ImportColumn::make('route')
                ->helperText(__('users-roles-permissions::users-roles-permissions.permission.import.helper-text.route'))
                ->rules([new RouteHas])
                ->requiredMapping(),
            ImportColumn::make('parent')
                ->helperText(__('users-roles-permissions::users-roles-permissions.permission.import.helper-text.parent'))
                ->relationship(resolveUsing: 'identifier')
                ->requiredMapping(),
            ImportColumn::make('status')
                ->boolean()
                ->requiredMapping(),
        ];
    }

    public function resolveRecord(): ?Permission
    {
        return Permission::firstOrNew([
            'identifier' => Str::slug($this->data['identifier']),
        ]);
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = __('users-roles-permissions::users-roles-permissions.permission.import.completed', [
            'successful_rows' => number_format($import->successful_rows),
            'row' => str('row')->plural($import->successful_rows),
        ]);

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= __('users-roles-permissions::users-roles-permissions.permission.import.failed', [
                'failed_rows' => number_format($failedRowsCount),
                'row' => str('row')->plural($failedRowsCount),
            ]);
        }

        return $body;
    }
}
