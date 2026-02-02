<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OwnerNotification extends Model
{
    protected $table = 'owner_notifications';

    protected $fillable = [
        'type',
        'data_id',
        'message',
        'is_read'
    ];
}
