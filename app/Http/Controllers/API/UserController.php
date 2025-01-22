<?php

namespace App\Http\Controllers\API;

use App\Actions\Fortify\PasswordValidationRules;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use function Livewire\of;

class UserController extends Controller
{

    use PasswordValidationRules;

    public function login(Request $request) {
        try{
            //validasi input
            $request->validate([
                'email' =>'email|required',
                'password' => 'required'
            ]);

            $credentials = request(['email', 'password']);
            if(!Auth::attempt($credentials)){
                return ResponseFormatter::error([
                    'message' => 'Unauthorized'
                ], 'Autentification Failed', 500);
            }
           
            //jika hash tidak sesuai = error
            $user = User::where('email' , $request->email)->first();
            if(!Hash::check($request->password, $user->password, [])) {
                throw new \Exception('invalid Credentials');

            }

             //jike berhasil login
             $tokenResult = $user->createToken('authToken')->plainTextToken;
             return ResponseFormatter::success([
                'access_token' => $tokenResult,
                'token_type' => 'Bearer',
                'user' => $user
             ]
             , 'Authenticated');
        } catch (Exception $e) {
            return ResponseFormatter::error([
                'message' => 'shomething went wrong',
                'error' => $e
            ], 'Authentication Failed',500);
        }
    }

    public function register(Request $request){
       try {
        $request->validate([
            'name' =>['required' ,  'string' , 'max:255'],
            'email' =>['required' , 'string' ,'email', 'unique:users' ,'max:255'],
            'nomor_telpon' =>['required' ,  'string' , 'max:14'],
            'password'=> $this->passwordRules()

        ]);
        User::create([
            'name' => $request->name,
            'email' => $request -> email,
            'nomor_telpon' =>$request -> nomor_telpon,
            'password' => Hash::make($request -> password)
        ]);

        $user = User::where('email' , $request->email)->first();

        $tokenResult = $user->createToken('authToken')->plainTextToken;

        return ResponseFormatter::success(
          [  'access_token' => $tokenResult,
            'token_type' => 'Bearer',
            'user'=> $user
        ]);
       } catch (\Exception $th) {
        return ResponseFormatter :: error([
            'message' => 'Something went wrong',
            'error' => $th
        ], 'Authenticatin Failed', 500);
       }
    }

    public function logout ( Request $request){
        $token = $request ->user()->currentAccessToken()->delete();

        return ResponseFormatter::success($token, 'token revoked');
    }

    public function fetch(Request $request)
    {
        return ResponseFormatter::success($request->user(), 'data profile user berhasil di ambil');
    }


}
