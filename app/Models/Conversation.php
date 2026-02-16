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

    // 1. Define these relationships
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

    // You can keep this helper, or use the relationships directly in the view
public function getReceiver()
{
    // IMPORTANT: Use the relationship, do not run User::firstWhere(...) again
    if ($this->sender_id == auth()->id()) {
        return $this->receiver;
    }
    return $this->sender;
}
}