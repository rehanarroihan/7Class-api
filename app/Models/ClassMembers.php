<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassMembers extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'class_members';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
}
