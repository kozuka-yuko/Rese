<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Registered;

class RedirectAfterRegister
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(Registered $event)
    {
        session(['url.intended' => '/thanks']);
    }
}
