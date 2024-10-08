<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class RegisterController extends Controller
{
    public function redirectPath()
    {
        return 'views/thanks';
    }
}
