<?php

namespace InsertOnConflict\Tests\Support\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    public $timestamps = false;

    protected $guarded = [];
}