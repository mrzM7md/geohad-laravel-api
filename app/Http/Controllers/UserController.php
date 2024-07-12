<?php

namespace App\Http\Controllers;

use App\Exceptions\CustomeException;
use App\Exceptions\ServerException;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();
        return response()->json(['success' => true, 'data' => $users], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return response()->json(['success' => true, 'data' => $user], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $user = User::whereId($request->id)->first();
            if($user){
                $this->authorize('update', $user);

                $user->update([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                ]);
        
                return response()->json(['success' => true, 'message' => 'تم تحديث المستخدم بنجاح', 'data' => $user], 200);
            }else{
                throw new CustomeException('المستخدم غير موجود', 404);
            }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        try{
            $user->delete();
            return response()->json(['success' => true, 'message' => 'تم حذف المستخدم بنجاح'], 200);
        }catch(\Exception){
            throw new ServerException();
        }
    }
}
