<?php

namespace App\Http\Controllers\Auth;

use JWTAuth;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $body = $request->only(
            'username',
            'password',
            'customer_name'
        );

        $response = array();
        $validator = Validator::make($body, [
            'username' => 'required|string|min:6|max:18',
            'password' => 'required|string|min:6|max:50',
            'customer_name' => 'required|string'
        ]);

        if ($validator->fails()) {
            $response['status'] = 0;
            $response['message'] = 'Please fill the form!';
            $code = 400;
        } else {
            DB::beginTransaction();
            $paramsC['id'] = Uuid::uuid4()->toString();
            $paramsC['customer_name'] = $body['customer_name'];

            $params['id'] = Uuid::uuid4()->toString();
            $params['username'] = $body['username'];
            $params['password'] = bcrypt($body['password']);
            $params['customer_id'] = $paramsC['id'];
            $params['remember_token'] = null;
            $params['is_login'] = false;

            $qSelectU = User::select('username')->where('username', $params['username'])->get();

            if ($qSelectU->count() > 0) {
                $response['status'] = 0;
                $response['message'] = 'User is already exists!';
                $code = 400;
            } else {
                try {
                    User::create($params);
                    Customer::create($paramsC);
                    $response['status'] = 1;
                    $response['message'] = 'User created!';
                    $code = 200;
                } catch (Exception $e) {
                    $response['status'] = 0;
                    $response['message'] = 'Server Error!';
                    $code = 500;
                }
            }
        }

        if($response['status'] == 1){
            DB::commit();
        }else{
            DB::rollBack();
        }

        return response()->json($response, $code);

    }

    public function authenticate(Request $request)
    {
        $credentials = $request->only('username', 'password');

        $validator = Validator::make($credentials, [
            'username' => 'required|string',
            'password' => 'required|string'
        ]);

        $params['username'] = $credentials['username'];
        $params['password'] = $credentials['password'];
        if ($validator->fails()) {
            $response['status'] = 0;
            $response['message'] = $validator->messages();
            $code = 400;
        } else {
        }
        $token = JWTAuth::attempt($credentials);
        $response = array();
        try {
            if ($token == false) {

                $response['status'] = 0;
                $response['message'] = 'User invalid!';
                $code = 406;
            } else {
                User::where('username', $params['username'])
                    ->update(['remember_token' => $token]);
                $userInfo = User::select('id as user_id', 'username', 'customer_id', 'remember_token as token')->where('username', $request->username)->first();
                session_start();
                $_SESSION['token_app'] = $token;

                $response['status'] = 1;
                $response['message'] = 'Logged in!';
                $response['data'] = $userInfo;
                $code = 200;
            }
        } catch (JWTException $e) {
            $response['status'] = 0;
            $response['message'] = 'Server error!';
            $code = 500;
        }

        return response()->json($response, $code);
    }

    public static function getUserInfo($token){
        $token = substr($token, 7);
        $q = User::select('id', 'username', 'customer_id', 'remember_token')
                    ->where('remember_token', $token)
                    ->get()
                    ->toArray();
        $xuser = $q;
        return $xuser;
    }


    public function logout(Request $request)
    {
        $validator = Validator::make($request->only('remember_token'), [
            'remember_token' => 'required'
        ]);

        if ($validator->fails()) {
            $response['status'] = 0;
            $response['message'] = $validator->messages();
            $code = 400;
        }

        try {
            JWTAuth::invalidate($request->token);

            $response['status'] = 1;
            $response['message'] = 'Logged out!';
            $code = 200;
        } catch (JWTException $exception) {
            $response['status'] = 0;
            $response['message'] = 'Server error!';
            $code = 500;
        }

        return response()->json($response, $code);
    }
}
