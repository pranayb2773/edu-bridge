<?php

namespace App\Filament\Pages\Auth;

use App\Enums\UserRoles;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\ToggleButtons;
use Filament\Pages\Auth\Register as BaseRegister;

class Register extends BaseRegister
{
    protected function getForms(): array
    {
        return [
            'form' => $this->form(
                $this->makeForm()
                    ->schema([
                        $this->getNameFormComponent(),
                        $this->getEmailFormComponent(),
                        $this->getPasswordFormComponent(),
                        $this->getPasswordConfirmationFormComponent(),
                        $this->getRoleFormComponent(),
                    ])
                    ->statePath('data'),
            ),
        ];
    }

    protected function getRoleFormComponent(): Component
    {
        return ToggleButtons::make('role')
            ->options([
                UserRoles::STUDENT->value => UserRoles::STUDENT->getLabel(),
                UserRoles::TEACHER->value => UserRoles::TEACHER->getLabel(),
            ])
            ->default(UserRoles::STUDENT->value)
            ->required()
            ->inline();
    }
}
