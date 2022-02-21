<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kyc extends Model
{
    protected $table  = 'kyc_verification';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'kyc_type',
        'number',
        'file',
        'verification_status'
    ];
    }
