<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    protected $fillable = [
        'user_type',
        'name',
        'lastname',
        'document_type',
        'document_number',
        'phone',
        'email',
        'photo_url',
        'certificate_number',
        'certificate_url',
        'certificate_date',
        'certificate_expiration_date',
        'certificate_status',
    ];
}
