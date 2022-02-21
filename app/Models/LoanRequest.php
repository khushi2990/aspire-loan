<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LoanRequest extends Model
{
    protected $table  = 'loan_request';
    /**
     * The attribute's' that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'funding_amount',
        'interest_rate',
        'frequency',
        'tenure',
        'status',
        'disbursed_amount'
    ];

    /**
    * Function to add total installements when admin approve customer loan
    */
    public static function addLoanInstallments($request)
    {
        $approved_amount = $request->disbursed_amount;
        $interest_rate = $request->interest_rate;
        $tenure = $request->tenure;
        $rePaymentDate = $request->disbursed_date;
        $weekly_payment = (($interest_rate /(100 * 12)) * $approved_amount) / (1 - pow(1 + $interest_rate / 1200,  (-$tenure)));
        for($i = 1; $i<=$tenure; $i++) {
            $rePaymentDate =  date("Y-m-d", strtotime(date("Y-m-d", strtotime($rePaymentDate)) . " +1 week"));
            LoanInstallments::create([
                'request_id' => $request->id, 
                'payment_amount' => $weekly_payment,
                'repayment_date' => $rePaymentDate, 
                'is_paid' => 0
            ]);
        }
    }
}
