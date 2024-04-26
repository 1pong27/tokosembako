<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;


class AuthController extends Controller
{
    public function index() {
        return view ('auth.login');
    }
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        $credentials = request(['email', 'password']);

    
        if (auth()->attempt($credentials)) {
            $request->session()->regenerate(); 
            $token = Auth::guard('api')->attempt($credentials);
            // return response()->json([
            //     'success' => true,
            //     'message' => 'login berhasil', 
            //     'token' => $token
            // ]);
            return redirect()->route('dashboard');
        }
        

        return response()->json([
            'success' => false,
            'message' => 'email atau password salah'
        ]);
       
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }

    function register(Request $request)  {
        $validator = Validator::make($request->all(),[
            'nama_member' => 'required',
            'kabupaten' => 'required',
            'kecamatan' => 'required',
            'detail_alamat' => 'required',
            'no_hp' => 'required',
            'email' => 'required|email',
            'password' => 'required|same:konfirmasi_password',
            'konfirmasi_password' => 'required|same:password',    
        ]);

        if($validator->fails()){
            return response()->json(
                $validator->errors(),
                422
            );
        }

        $input = $request->all();
        $input['password'] = bcrypt($request->password);
        unset($input['konfirmasi_password']);
        $Member = Member::create($input);

        return response()->json([
            'data' => $Member
        ]);
    }
    public function login_member(Request $request)
    {   
        $validator =Validator::make($request->all(),[
            'email' => 'required|email',
            'password' => 'required',
        ]);
        
    if ($validator->fails()){
        return response()->json(
            $validator->errors(),
            422
        );
    }
        
    $credentials = $request->only('email', 'password');

    $member = Member::where('email', $request->email)->first();

if ($member) {
    if (Hash::check($request->password, $member->password)) {
        return response()->json([
            'message' => 'success',
            'data' => $member
        ]);
    } else {
        return response()->json([
            'message' => 'failed',
            'data' => 'Password is wrong'
        ]);
    }
} else {
    return response()->json([
        'message' => 'failed',
        'data' => 'Email is wrong'
    ]);
}
    }
    public function logout(){
        Session::flush();
        return redirect('login');
    }
    

    public function logout_member(){
        Session::flush();
        return redirect('login_member');
      
    }
    
}