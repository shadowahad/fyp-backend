<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\PasswordReset;
use Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\userRegisterMail;

class HelperController extends Controller
{
    //
    public function registerUser(Request $request){
        $exist = User::where('email', $request->email)->first();
        if(isset($exist)){
            return response()->json("User already exist", 400);
        }
        $password = $request->password;
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($password),
            'type' => 'Agent'
        ];
        $user = User::create($data);
        try{
            Mail::to($user->email)->send(new userRegisterMail($user, $password));
        }catch(\Exception $e){
            // dd($e);
        }
        return "User Registered Successfully";
    }

    public function forgetEmail(Request $request)
    {
        $findUser = User::where('email', $request->email)->first();
        if ($findUser) {
            $token = Str::random(64);
            $email = $request->email;
            $data = [
                'email' => $request->email,
                'token' => $token,
            ];
            $store = PasswordReset::create($data);
            if ($store) {
                Mail::send('mails.forgetPassword', ['token' => $token."&&".$email], function ($message) use ($request) {
                    $message->to($request->email);
                    $message->subject('Reset Password');
                });
                return "We have e-mailed your password reset link!";
            } else {
                return 'Something went wrong';
            }
        } else {
            return 'No record found!';
        }
    }

    public function resetPassword(Request $request)
    {
        $findUser = User::where('email', $request->email)->first();
        if ($findUser) {
            $updatePassword = PasswordReset::where(['email' => $request->email,'token' => $request->token])->first();
            if(!$updatePassword) {
                return 'Invalid Token';
            }
            $user = User::where('email', $request->email)->update(['password' => Hash::make($request->password)]);
            PasswordReset::where(['email' => $request->email])->delete();
            return 'Your password has been changed!';
        } else {
            return 'No record found!';
        }
    }

    public function dashaboadDate(){
        //Investment VS Reveneue Monthly
        $netProfit = range(1500, 5000);
        shuffle($netProfit);
        $netProfit = array_slice($netProfit ,0,5);

        $revenue = range(5000, 10000);
        shuffle($revenue);
        $revenue = array_slice($revenue ,0,5);

        $investment = range(500, 3000);
        shuffle($netProfit);
        $netProfit = array_slice($netProfit ,0,5);

        $insvesment = [
            'months' => ["May","Jun","Jul","Aug","Sep","Oct"],
            'series' => [
                [
                    'name' => "Net Profit",
                    'data' => $netProfit
                ],
                [
                    'name' => "Revenue",
                    'data' => $revenue
                ],
                [
                    'name' => "Investment",
                    'data' => $netProfit
                ],
            ],
        ];

        //Top Manufecture
        $netProfit = range(1500, 5000);
        shuffle($netProfit);
        $netProfit = array_slice($netProfit ,0,5);

        $revenue = range(1500, 5000);
        shuffle($revenue);
        $revenue = array_slice($revenue ,0,5);

        $investment = range(1500, 5000);
        shuffle($netProfit);
        $netProfit = array_slice($netProfit ,0,5);

        $name = [
            'Nike',
            'Hornby',
            'Bachmann',
            'Faller',
            'Dapol',
            'Corgi',
        ];
        $key = shuffle($name);
        $topManufecture = [
            'months' => ["May","Jun","Jul","Aug","Sep","Oct"],
            'series' => [
                [
                    'name' => $name[0],
                    'type' => "column",
                    'data' => $netProfit
                ],
                [
                    'name' => $name[1],
                    'type' => "area",
                    'data' => $revenue
                ],
                [
                    'name' => $name[2],
                    'type' => "line",
                    'data' => $netProfit
                ],
            ],
        ];

        $data = [
            'investmentReveneueMonthly' => $insvesment,
            'topManufecture' => $topManufecture
        ];
        return response()->json($data, 200);
    }
}
