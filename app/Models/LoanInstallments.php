<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LoanInstallments extends Model
{
    protected $table  = 'loan_installments';
    /**
     * The attribute's' that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'request_id',
        'repayment_date',
        'payment_amount',
        'is_paid',
        'installment_paid_date'
    ];
    
}
