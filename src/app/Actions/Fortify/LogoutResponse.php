<?php

namespace App\Actions\Fortify;

use Illuminate\Http\Request;

class LogoutResponse implements \Laravel\Fortify\Contracts\LogoutResponse
{
    /**
     * Create an HTTP respose that represents the object.
     * 
     * @param \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function toResponse($request)
    {
        return redirect('/shop');
    }
}
