<?php

namespace App\Filament\Auth;

use Filament\Auth\Pages\Login as BaseLogin;
use Filament\Actions\Action;
use Illuminate\Contracts\Support\Htmlable;

class Login extends BaseLogin
{
    public function getTitle(): string|Htmlable
    {
        return __('Log In');
    }

    public function getHeading(): string|Htmlable|null
    {
        return __('Log In');
    }

    protected function getAuthenticateFormAction(): Action
    {
        return parent::getAuthenticateFormAction()
            ->label(__('Log In'));
    }
}
