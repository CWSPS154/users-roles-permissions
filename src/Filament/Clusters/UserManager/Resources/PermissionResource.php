<?php

/*
 * Copyright CWSPS154. All rights reserved.
 * @auth CWSPS154
 * @link  https://github.com/CWSPS154
 */

namespace CWSPS154\FilamentUsersRolesPermissions\Filament\Clusters\UserManager\Resources;

use CWSPS154\FilamentUsersRolesPermissions\Models\Permission;
use Filament\Clusters\Cluster;
use Filament\Facades\Filament;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\ExportAction;
use Filament\Tables\Actions\ImportAction;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;

class PermissionResource extends Resource
{
    protected static ?string $model = Permission::class;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->whereHas('children');
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('filament-users-roles-permissions::users-roles-permissions.permission.resource.form.name'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('identifier')
                    ->label(__('filament-users-roles-permissions::users-roles-permissions.permission.resource.form.identifier'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('panel_ids')
                    ->label(__('filament-users-roles-permissions::users-roles-permissions.permission.resource.form.panel-ids'))
                    ->badge()
                    ->searchable()
                    ->visible(function () {
                        return count(Filament::getPanels()) > 1;
                    }),
                Tables\Columns\IconColumn::make('status')
                    ->label(__('filament-users-roles-permissions::users-roles-permissions.permission.resource.form.status'))
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('filament-users-roles-permissions::users-roles-permissions.permission.resource.table.created-at'))
                    ->dateTime(static::$cluster::DEFAULT_DATETIME_FORMAT)
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('filament-users-roles-permissions::users-roles-permissions.permission.resource.table.updated-at'))
                    ->dateTime(static::$cluster::DEFAULT_DATETIME_FORMAT)
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->slideOver()->hiddenLabel(),
            ])
            ->headerActions(
                ActionGroup::make([
                    ExportAction::make()
                        ->exporter(config('filament-users-roles-permissions.export.permission'))->visible(function () {
                            return static::$cluster::checkAccess('getCanCreatePermission') && static::$cluster::checkAccess('getCanCreatePermission');
                        }),
                    ImportAction::make()
                        ->importer(config('filament-users-roles-permissions.import.permission'))->visible(function () {
                            return static::$cluster::checkAccess('getCanCreatePermission') && static::$cluster::checkAccess('getCanCreatePermission');
                        }),
                ])->icon('heroicon-o-circle-stack')
            );
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make([
                    TextEntry::make('name')
                        ->label(__('filament-users-roles-permissions::users-roles-permissions.permission.resource.form.name')),
                    TextEntry::make('identifier')
                        ->label(__('filament-users-roles-permissions::users-roles-permissions.permission.resource.form.identifier')),
                    TextEntry::make('panel_ids')
                        ->visible(function () {
                            return count(Filament::getPanels()) > 1;
                        })
                        ->label(__('filament-users-roles-permissions::users-roles-permissions.permission.resource.form.panel-ids')),
                    IconEntry::make('status')
                        ->label(__('filament-users-roles-permissions::users-roles-permissions.permission.resource.form.status'))
                        ->boolean(),
                ])->columns(4),
                Section::make(__('filament-users-roles-permissions::users-roles-permissions.permission.resource.form.children'))
                    ->schema([
                        RepeatableEntry::make('children')
                            ->hiddenLabel()
                            ->schema([
                                TextEntry::make('name')
                                    ->label(__('filament-users-roles-permissions::users-roles-permissions.permission.resource.form.name')),
                                TextEntry::make('identifier')
                                    ->label(__('filament-users-roles-permissions::users-roles-permissions.permission.resource.form.identifier')),
                                TextEntry::make('panel_ids')
                                    ->visible(function () {
                                        return count(Filament::getPanels()) > 1;
                                    })
                                    ->label(__('filament-users-roles-permissions::users-roles-permissions.permission.resource.form.panel-ids')),
                                IconEntry::make('status')
                                    ->boolean()
                                    ->label(__('filament-users-roles-permissions::users-roles-permissions.permission.resource.form.status')),
                            ])->columns(4),
                    ]),
            ])->columns(4);
    }

    /**
     * @return class-string<Cluster> | null
     */
    public static function getCluster(): ?string
    {
        return static::$cluster = config('filament-users-roles-permissions.cluster');
    }

    public static function getPages(): array
    {
        return [
            'index' => config('filament-users-roles-permissions.manager.permission')::route('/'),
        ];
    }

    public static function getNavigationLabel(): string
    {
        return __('filament-users-roles-permissions::users-roles-permissions.permission.resource.permission');
    }

    public static function getNavigationIcon(): string|Htmlable|null
    {
        return 'heroicon-o-finger-print';
    }

    public static function getNavigationSort(): ?int
    {
        return 3;
    }

    public static function canViewAny(): bool
    {
        return static::$cluster::checkAccess('getCanViewAnyPermission');
    }
}
