<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Message;
use App\Models\User;

class Conversation extends Model
{
    use HasFactory;

    protected $fillable = [
        'receiver_id',
        'sender_id'
    ];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

public function getReceiver()
{
    if ($this->sender_id == auth()->id()) {
        return $this->receiver;
    }
    return $this->sender;
}

public function unreadMessagesCount(){
    return $unreadCount=Message::where('conversation_id',$this->id)
    ->where('receiver_id',auth()->id())
    ->whereNull('read_at')
    ->count();
}

public function scopeWhereNotDeleted($query) 
     {
        $userId=auth()->id();

        return $query->where(function ($query) use ($userId){

            #where message is not deleted
            $query->whereHas('messages',function($query) use($userId){

                $query->where(function ($query) use($userId){
                    $query->where('sender_id',$userId)
                        ->whereNull('sender_deleted_at');
                })->orWhere(function ($query) use ($userId){

                    $query->where('receiver_id',$userId)
                    ->whereNull('receiver_deleted_at');
                });


            })
             #include conversations without messages
              ->orWhereDoesntHave('messages');


        });

}
}