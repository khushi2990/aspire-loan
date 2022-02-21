<?php
namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Kyc;
use App\Models\LoanInstallments;
use App\Models\LoanRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class LoanController extends Controller
{
    /**
    * Function for add loan request
    */
    public function submitLoanRequest(Request $request){
        $validator = Validator::make($request->all(), [
            'funding_amount' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'interest_rate' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'tenure' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors()->all());
        } else {
            $user = auth()->user();
            $kyc_status = Kyc::where('user_id', $user->id)->where('verification_status', 1)->first();
            if(!$kyc_status){
                return $this->sendError('Error.', ['error' => 'Please complete your KYC for loan request']);
            }
            $params = $request->all();
            $params['user_id'] = $user->id;
            $params['status'] = 0;
            $request = LoanRequest::create($params);
            $requestId = $request->id;
            if($requestId) {
                return $this->sendResponse($requestId, 'Loan request has been sent successfully . Request Id: '.$requestId);
            } else {
                return $this->sendError('Error.', ['error' => 'Some Thing Wrong. Please Try Again']);
            }
            
        }
    }
    /**
    * Function to approve loan request
    */
    public function approveLoanRequest(Request $request){
        $validator = Validator::make($request->all(), [
            'request_id' => 'required|numeric',
            'approved_amount' => 'required|regex:/^\d+(\.\d{1,2})?$/',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors()->all());
        }
        $params = $request->all();
        $loanRequest = LoanRequest::find($params['request_id']);
        if(!$loanRequest){
            return $this->sendError('Validation Error.', 'Loan request not exist' );
        }
        if($loanRequest->funding_amount < $params['approved_amount']){
            return $this->sendError('Validation Error.', 'Approved amount should not be more that funding amount' );
        }
        $loanRequest['status'] = 1;
        $loanRequest['disbursed_amount'] = $params['approved_amount'];
        $loanRequest['disbursed_date'] = date("Y-m-d");
        $loanRequest->save();
        LoanRequest::addLoanInstallments($loanRequest);
        return $this->sendResponse($loanRequest->toArray(), 'Amount: '.$params['approved_amount'].' Successfully Approved');
    }

     /**
    * Function to pay installment
    */
    public function payLoan(Request $request){
        $validator = Validator::make($request->all(), [
            'request_id' => 'required|numeric',
            'pay_amount' => 'required|regex:/^\d+(\.\d{1,2})?$/',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors()->all());
        }
        $loanRequest = LoanRequest::where('id',$request->request_id)->where('status',1)->first();
        if(!$loanRequest){
            return $this->sendError('Validation Error.', 'Loan request is not approved' );
        }
        $installment = LoanInstallments::where('request_id',$request->request_id)->where('is_paid',0)->first();
        if(!$installment){
            return $this->sendError('Validation Error.', 'All installmensts are already paid');
        }
        if($request->pay_amount < $installment->payment_amount ){
            return $this->sendError('Validation Error.', 'Imstallment amount must be : '.$installment->payment_amount );
        }
        $installment['is_paid'] = 1;
        $installment['installment_paid_date'] = date("Y-m-d");
        $installment->save();
        return $this->sendResponse('', 'Amount: '.$request->pay_amount.' Successfully paid');
    }
    /**
    * Function to get installment detail of approved loan
    */
    public function getInstallmentDetails(Request $request,$requestId){
       
        if (!$requestId) {
            return $this->sendError('Validation Error.','Request id is required');
        }
        $installments = LoanInstallments::where('request_id',$requestId)->get();
        $unpaidInstallmentCount = LoanInstallments::where('request_id',$requestId)->where('is_paid',0)->count();
        return $this->sendResponse($installments , 'Pending Installmensts are : '.$unpaidInstallmentCount);

    }
}   