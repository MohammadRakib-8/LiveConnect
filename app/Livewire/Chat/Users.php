<?php

namespace App\Livewire\Chat;

use Livewire\Component;
use App\Models\User;


class Users extends Component{

    public function render()
    {
        $users = User::all();

        return view('livewire.chat.users', [
            'users' => $users
        ]);

    }
      public function message($id)
    {
        info("Message clicked for user: $id");
    }
}


// }
