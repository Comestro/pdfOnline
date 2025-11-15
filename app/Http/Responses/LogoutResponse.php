<?php

namespace App\Http\Responses;

use Filament\Http\Responses\Auth\LogoutResponse as LogoutResponseContract;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class LogoutResponse implements LogoutResponseContract
{
    public function toResponse(Request $request): RedirectResponse
    {
        return redirect('/');
    }
}

