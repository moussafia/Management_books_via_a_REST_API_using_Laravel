<?php


namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;


class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register','resetPassword','reset']]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        $credentials = $request->only('email', 'password');

        $token = Auth::attempt($credentials);
        if (!$token) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 401);
        }

        $user = Auth::user();
        return response()->json([
            'status' => 'success',
            'user' => $user,
            'authorisation' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ]);
    }

    public function register(Request $request)
    { 
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = Auth::login($user);
        return response()->json([
            'status' => 'success',
            'message' => 'User created successfully',
            'user' => $user,
            'authorisation' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ]);
    }

    public function logout()
    {
        Auth::logout();
        return response()->json([
            'status' => 'success',
            'message' => 'Successfully logged out',
        ]);
    }

    public function refresh()
    {
        return response()->json([
            'status' => 'success',
            'user' => Auth::user(),
            'authorisation' => [
                'token' => Auth::refresh(),
                'type' => 'bearer',
            ]
        ]);
    }
    public function resetPassword(){
        request()->validate(['email' => 'required|email|exists:users']);
        $token = Str::random(64);

        $email = request('email');

        DB::table('password_reset_tokens')->insert([
            'email' => $email, 
            'token' => $token, 
            'created_at' => Carbon::now()
        ]);
        Mail::send([], [], function($message) use($email,$token){
            $message->to($email);
            $message->subject('Reset Password');
            $message->text(
                "please click on the link below to reset your password. \n
                http://localhost:8000/api/password/reset?email=".$email."&token=".$token
                            );
        });
        return response()->json([
            'message' => 'email sended',
        ]);
    }
    public function reset(){
        request()->validate([
            'password' => 'required|string|confirmed',
            'password_confirmation' => 'required'
        ]);

        $updatePassword = DB::table('password_reset_tokens')
                            ->where([
                              'email' => request('email'), 
                              'token' => request('token')
                            ])
                            ->first();

        if(!$updatePassword){
            return response()->json(['error'=>'Invalid token!']);
        }

        $user = User::where('email', request('email'))
                    ->update(['password' => Hash::make(request('password'))]);

        DB::table('password_reset_tokens')->where(['email'=> request('email')])->delete();

        return response()->json(['message'=>'Your password has been changed!']);
    }
    public function update(Request $request){
        $request->validate([
            'email'=>['email', 'unique:users,email,' . Auth::id()],
            'current_password' => 'required',
           
        ]);
        $user=Auth::user();
        $current_password=$user->password;
        if(Hash::check($request->current_password,$current_password)){
            if($request->has('email')){
                $user->email=$request->input('email');
            }
            if($request->has('nouveau_mot_de_passe')){
                $request->validate([
                    'nouveau_mot_de_passe' => 'required|string|confirmed',
                    'nouveau_mot_de_passe_confirmation' => 'required'
                ]);
                $user->fill([
                    'nouveau_mot_de_passe' => Hash::make($request->password)
                ]);
            }
            $user->save();
            return response()->json([
                'message'=>"your data is succesfuly updated"
            ],200);
        }
        
    }
}

