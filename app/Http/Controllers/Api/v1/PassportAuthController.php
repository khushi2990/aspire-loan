<?php
 
namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Kyc;
use Illuminate\Http\Request;
 
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class PassportAuthController extends Controller
{
    /**
     * Registration Req
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'c_password' => 'required|same:password',
            'employmnet_type' =>'in:self-employed,salaried',
            'phone'=>'required'
        ]);
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['token'] =  $user->createToken(env('APP_NAME'))->accessToken;
        $success['name'] =  $user->name;
        return $this->sendResponse($success, 'User register successfully.');
    }
  
    /**
     * Login Req
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
        $data = [
            'email' => $request->email,
            'password' => $request->password
        ];
  
        if (auth()->attempt($data)) {
            $token = auth()->user()->createToken(env('APP_NAME'))->accessToken;
            return response()->json(['token' => $token], 200);
        } else {
            return response()->json(['error' => 'Unauthorised'], 401);
        }
    }
    /**
    * Function to user details
    */
    public function userInfo() 
    {
 
     $user = auth()->user();
      
     return response()->json(['user' => $user], 200);
 
    }

    /**
    * Function for kyv verification of customer
    */
    public function kycverification(Request $request){

       
        $validator = Validator::make($request->all(), [
            'file' => 'required',
            'number' => 'required',
            'kyc_type' =>'in:passport,pan',
        ]);
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
        $input = $request->all();
        if($request->file('file')){
            $path = $request->file('file')->store('files');
            $input['file'] = $path;
        }
        $input['verification_status'] = 0;
        $input['user_id'] = auth()->user()->id;
        $kyc = Kyc::create($input);
      
        return response()->json($kyc, 200);
    }
}