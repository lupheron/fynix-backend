<?php

namespace App\Http\Controllers;

use App\Models\UsersModel;
use App\Http\Requests\UpdateUsersModelRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersModelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = User::get();
        return $user;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function register(Request $request)
    {
        $user = DB::table('users')->insertGetId([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
            'phone' => $request['phone'],
            'role' => $request['role'],
        ]);

        if ($user > 0) {
            return $user;
        } else {
            return ['message' => "Ro'yxatga olishda hatolik", "status" => 201];
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function login(Request $request)
    {
        $user = DB::table('users')->where('email', $request['email'])->first();
        if (Hash::check($request['password'], $user->password)) {
            $token = Hash::make($request['email'] . $request['password']);
            $t = DB::table('users')->where('id', $user->id)->update(['remember_token' => $token]);

            if ($t) {
                $user->token =  $token;
            } else {
                return ['message' => "Tokenni yaratishda hatolik"];
            }
            return $user;
        } else {
            return ["message" => "Bunday foydalanuvchi topilmadi", 'status' => 401];
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(UsersModel $usersModel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function create(Request $request)
    {
        $userId = DB::table('users')->insertGetId([
            'name' => $request['name'],
            'email' => $request['email'],
            'role' => $request['role'],   
            'phone' => $request['phone'],
            'password' => bcrypt($request['password']),
        ]);

        if ($userId > 0) {
            return response()->json(['status' => 200, 'message' => "Foydalanuvchi qo'shildi"]);
        } else {
            return response()->json(['status' => 500, 'message' => "Foydalanuvchini qo'shishda hatolik"]);
        }
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $user = DB::table('users')->where('id', $id)->update([
            'name' => $request['name'],
            'email' => $request['email'],
            'role' => $request['role'],
            'phone' => $request['phone'],
            'password' => bcrypt($request['password'])
        ]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $user = DB::table('users')->where('id', $id)->delete();

        if ($user) {
            return ['status' => 200, 'message' => "Foydalanuvchi o'chirildi"];
        } else {
            return ['status' => 201, 'message' => "Foydalanuvchi o'chirishda hatolik"];
        }
    }
}
