<?php

/*
 * Copyright CWSPS154. All rights reserved.
 * @auth CWSPS154
 * @link  https://github.com/CWSPS154
 */

declare(strict_types=1);

namespace CWSPS154\FilamentUsersRolesPermissions\Filament\Clusters\UserManager\Resources\UserResource\Pages;

use App\Models\User;
use CWSPS154\FilamentUsersRolesPermissions\Filament\Clusters\UserManager;
use Exception;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Form;
use Illuminate\Support\Facades\Http;
use Ysfkaya\FilamentPhoneInput\Forms\PhoneInput;
use Ysfkaya\FilamentPhoneInput\PhoneInputNumberType;

class EditProfile extends \Filament\Pages\Auth\EditProfile
{
    /**
     * @return array<int | string, string | Form>
     *
     * @throws Exception
     */
    protected function getForms(): array
    {
        return [
            'form' => $this->form(
                $this->makeForm()
                    ->schema([
                        Section::make()
                            ->schema([
                                $this->profileImageComponent(),
                                $this->getNameFormComponent(),
                                $this->getEmailFormComponent(),
                                $this->getMobileNumberComponent(),
                                $this->getPasswordFormComponent(),
                                $this->getPasswordConfirmationFormComponent(),
                            ])])
                    ->operation('edit')
                    ->model($this->getUser())
                    ->statePath('data')
                    ->inlineLabel(! static::isSimple()),
            ),
        ];
    }

    /**
     * Mobile number
     */
    protected function getMobileNumberComponent(): PhoneInput
    {
        return PhoneInput::make('mobile')
            ->label(__('filament-users-roles-permissions::users-roles-permissions.user.resource.form.mobile'))
            ->required()
            ->unique(User::class, 'mobile', ignoreRecord: true)
            ->rules(['phone'])
            ->ipLookup(function () {
                return rescue(fn () => Http::get('https://ipinfo.io/json')->json('country'), app()->getLocale(), report: false);
            })
            ->displayNumberFormat(PhoneInputNumberType::NATIONAL);
    }

    /**
     * Profile image
     */
    protected function profileImageComponent(): SpatieMediaLibraryFileUpload
    {
        return SpatieMediaLibraryFileUpload::make('media')
            ->collection('profile-images')
            ->conversion('avatar')
            ->image()
            ->maxSize(2048)
            ->label(__('filament-users-roles-permissions::users-roles-permissions.user.resource.form.profile-image'));
    }

    public static function canAccess(): bool
    {
        return UserManager::checkAccess('getCanAccessEditProfile');
    }
}
