<?php

namespace App\Http\Responses;

use Filament\Http\Responses\Auth\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    /**
     * Create an HTTP response that represents the object.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function toResponse($request)
    {
        // return whatever you want as url
        $url = auth()->user()->hasRole(['admin', 'admanager', 'editor', 'reporter'])
             ? '/control-panel/news' 
             : '/';

        return redirect()->intended($url);
    }
}
