<?php
// src/Models/Profile.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $table = 'profiles';
    protected $fillable = ['company_label', 'company_name', 'company_address', 'company_phone', 'company_email'];
}
