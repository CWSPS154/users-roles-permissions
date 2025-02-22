<?php

/*
 * Copyright CWSPS154. All rights reserved.
 * @auth CWSPS154
 * @link  https://github.com/CWSPS154
 */

namespace CWSPS154\FilamentUsersRolesPermissions\Filament\Clusters\UserManager\Resources;

use CodeWithDennis\FilamentSelectTree\SelectTree;
use CWSPS154\FilamentUsersRolesPermissions\Models\Role;
use Filament\Clusters\Cluster;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class RoleResource extends Resource
{
    protected static ?string $model = Role::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('role')
                    ->label(__('filament-users-roles-permissions::users-roles-permissions.role.resource.form.name'))
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn ($state, Forms\Set $set) => $set('identifier', Str::slug($state))),
                Forms\Components\TextInput::make('identifier')
                    ->label(__('filament-users-roles-permissions::users-roles-permissions.role.resource.form.identifier'))
                    ->required()
                    ->disabled()
                    ->maxLength(255)
                    ->dehydrated()
                    ->unique(Role::class, 'identifier', ignoreRecord: true),
                Forms\Components\Toggle::make('all_permission')
                    ->label(__('filament-users-roles-permissions::users-roles-permissions.role.resource.form.all-permission'))
                    ->default(true)
                    ->live()
                    ->afterStateUpdated(function (Get $get, $state, Forms\Set $set) {
                        if ($get('all_permission')) {
                            $set('permission_id', []);
                        }
                    })
                    ->required(),
                Forms\Components\Toggle::make('is_active')
                    ->label(__('filament-users-roles-permissions::users-roles-permissions.role.resource.form.is-active'))
                    ->required()
                    ->default(true),
                SelectTree::make('permission_id')
                    ->label(__('filament-users-roles-permissions::users-roles-permissions.role.resource.form.permissions'))
                    ->relationship('permissions', 'permission_with_panel_ids', 'parent_id', function ($query) {
                        return $query->where('status', true);
                    }, function ($query) {
                        return $query->where('status', true);
                    })
                    ->live()
                    ->afterStateUpdated(function (Get $get, $state, Forms\Set $set) {
                        if ($get('all_permission')) {
                            $set('all_permission', false);
                        }
                    })
                    ->searchable()
                    ->defaultOpenLevel(2)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('role')
                    ->label(__('filament-users-roles-permissions::users-roles-permissions.role.resource.form.name'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('identifier')
                    ->label(__('filament-users-roles-permissions::users-roles-permissions.role.resource.form.identifier'))
                    ->searchable(),
                Tables\Columns\IconColumn::make('all_permission')
                    ->label(__('filament-users-roles-permissions::users-roles-permissions.role.resource.form.all-permission'))
                    ->boolean(),
                Tables\Columns\TextColumn::make('permissions.permission_with_panel_ids')
                    ->label(__('filament-users-roles-permissions::users-roles-permissions.role.resource.form.permissions'))
                    ->default('-')
                    ->badge()
                    ->listWithLineBreaks()
                    ->limitList(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label(__('filament-users-roles-permissions::users-roles-permissions.role.resource.form.is-active'))
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('filament-users-roles-permissions::users-roles-permissions.role.resource.table.created-at'))
                    ->dateTime(static::$cluster::DEFAULT_DATETIME_FORMAT)
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('filament-users-roles-permissions::users-roles-permissions.role.resource.table.updated-at'))
                    ->dateTime(static::$cluster::DEFAULT_DATETIME_FORMAT)
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions(
                ActionGroup::make([
                    Tables\Actions\EditAction::make()->slideOver(),
                    Tables\Actions\DeleteAction::make(),
                ])
            )
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->visible(function () {
                        return static::$cluster::checkAccess('getCanDeleteRole');
                    }),
                ]),
            ]);
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
            'index' => config('filament-users-roles-permissions.manager.role')::route('/'),
        ];
    }

    public static function getNavigationLabel(): string
    {
        return __('filament-users-roles-permissions::users-roles-permissions.role.resource.role');
    }

    public static function getNavigationIcon(): string|Htmlable|null
    {
        return 'heroicon-o-academic-cap';
    }

    public static function getNavigationSort(): ?int
    {
        return 2;
    }

    public static function canViewAny(): bool
    {
        return static::$cluster::checkAccess('getCanViewAnyRole');
    }

    public static function canCreate(): bool
    {
        return static::$cluster::checkAccess('getCanCreateRole');
    }

    public static function canEdit(Model $record): bool
    {
        return static::$cluster::checkAccess('getCanEditRole', $record);
    }

    public static function canDelete(Model $record): bool
    {
        return static::$cluster::checkAccess('getCanDeleteRole', $record);
    }
}
