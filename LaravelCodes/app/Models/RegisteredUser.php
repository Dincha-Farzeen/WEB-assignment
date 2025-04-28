<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class RegisteredUser extends Authenticatable
{
    use Notifiable;

    // Define the table name if it's not the default 'registered_users'
    protected $table = 'registered_user';

    // Define the primary key if it's not 'id'
    protected $primaryKey = 'u_id';

    // Specify the attributes that are mass assignable
    protected $fillable = ['u_email', 'pass_word', 'u_name', 'u_phoneNum', 'user_name'];

    // Disable timestamps if the table doesn't have created_at and updated_at columns
    public $timestamps = false;

    // Specify the column used for authentication (email)
    public function getAuthIdentifierName()
    {
        return 'u_email';
    }
}