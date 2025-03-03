<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CountryController extends Controller
{
    public function index()
    {
        $country = DB::table('country')->get();
        return $country;
    }

    public function create(Request $request)
    {
        $country = DB::table('country')->insertOrIgnore([
            'name' => $request['name']
        ]);

        if ($country) {
            return ['status' => 200, 'message' => "Davlat qo'shildi"];
        } else {
            return ['status' => 201, 'message' => "Davlatni qo'shilda xatolik!!!"];
        }
    }

    public function update(Request $request, $id)
    {
        $country = DB::table('country')->where('id', $id)->update([
            'name' => $request['name']
        ]);

        if ($country) {
            return ['status' => 200, 'message' => "Davlat yangilandi"];
        } else {
            return ['status' => 201, 'message' => "Davlatni yangilashdada xatolik!!!"];
        }
    }

    public function delete($id)
    {
        $country = DB::table('country')->where('id', $id)->delete();

        if ($country) {
            return ['status' => 200, 'message' => "Davlat o'chirildi"];
        } else {
            return ['status' => 201, 'message' => "Davlatni o'chirishda xatolik!!!"];
        }
    }
}
