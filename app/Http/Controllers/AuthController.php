<?php

namespace App\Http\Controllers;

use App\Exceptions\CustomeException;
use App\Exceptions\ServerException;
use App\Models\User as ModelsUser;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function __construct(){
        // $this->middleware('auth:api')->except(['register']) ;
    }

    public function login()
    {
        try{
            $tokenResult = Auth::user()->createToken('Access Token');
            $accessToken = $tokenResult->accessToken; // الحصول على الرمز الشخصي
            $result=  ((object) Auth::user());
            $result ->token  = $accessToken;
            return response()->json(['success' => true, 'message'=>'تم تسجيل الدخول بنجاح', 'data' => $result ], 200);
        }catch(Exception $e){
            return new CustomeException($e->getMessage(), 500);
        }
    }
    
        /**
     * Store a newly created resource in storage.
     */
    public function register(Request $request)
    {
        $u = ModelsUser::whereEmail($request->email)->first();
            if($u){
                throw new CustomeException("هذا المستخدم مسجل لدينا بالفعل", 400);
            }

            $user = ModelsUser::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
    
            return response()->json(['success' => true, 'message' => 'تم إنشاء مستخدم جديد بنجاح', 'data' => $user], 201);
    }
    

}
