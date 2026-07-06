<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanySetting extends Model
{
    protected $fillable = [
        'company_name',
        'owner_name',
        'company_logo',
        'phone',
        'email',
        'address',
        'footer_text',
    ];
}
