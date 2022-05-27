<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use HasFactory, Notifiable;
    protected $fillable = [
        'username',
        'name',
        'password',
        'profile_image',
        'phone',
    ];

    public function notifications(){
        return $this->morphMany(AdminNotification::class, 'notifiable' )->orderBy('created_at', 'desc');
    }
}