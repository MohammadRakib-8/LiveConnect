<?php

namespace App\Livewire\Chat;

use Illuminate\Support\Facades\Auth;    
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Contracts\Events\Dispatcher;



class UpdateUserLastSeen 
{

public  function handleLogin(Login $event){

if($event->user){
    $event->user->last_seen_at=now();
    $event->user->save();
}

}

public function handleLogout(Logout $event){

if($event->user){
    $event->user->last_seen_at=now();
    $event->user->save();
}
  
}

public function subscriber (Dispatcher $events):arra{

return[
Login::Class=>'handleLogin',
Logout::Class=>'handleLogout',

];

}
}