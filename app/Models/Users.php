<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Users extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'id',
      'auth_type',
      'auth_id',
      'first_name',
      'last_name',
      'email',
      'phone',
      'contact_type',
      'password',
      'need_reset_password',
      'is_verified',
      'picture_url',
      'created_at',
      'created_at'
    ];

    protected $hidden = [
      'password'
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;
}
