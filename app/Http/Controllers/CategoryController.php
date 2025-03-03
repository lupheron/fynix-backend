<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    public function index()
    {
        $country = DB::table('categories')->get();
        return $country;
    }

    public function create(Request $request)
    {
        $country = DB::table('categories')->insertOrIgnore([
            'name' => $request['name']
        ]);

        if ($country) {
            return ['status' => 200, 'message' => "Kategoriya qo'shildi"];
        } else {
            return ['status' => 201, 'message' => "Kategoriyani qo'shilda xatolik!!!"];
        }
    }

    public function update(Request $request, $id)
    {
        $country = DB::table('categories')->where('id', $id)->update([
            'name' => $request['name']
        ]);

        if ($country) {
            return ['status' => 200, 'message' => "Kategoriya yangilandi"];
        } else {
            return ['status' => 201, 'message' => "Kategoriyani yangilashdada xatolik!!!"];
        }
    }

    public function delete($id)
    {
        $country = DB::table('categories')->where('id', $id)->delete();

        if ($country) {
            return ['status' => 200, 'message' => "Kategoriya o'chirildi"];
        } else {
            return ['status' => 201, 'message' => "Kategoriyani o'chirishda xatolik!!!"];
        }
    }
}
