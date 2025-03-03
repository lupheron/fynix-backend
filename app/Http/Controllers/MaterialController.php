<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MaterialController extends Controller
{
    public function index()
    {
        $material = DB::table('material')
            ->join('categories', 'categories.id', '=', 'material.cat_id')
            ->select(
                'material.*',
                'categories.name as category'
            )
            ->get();
        return $material;
    }

    public function create(Request $request)
    {
        $material = DB::table('material')->insertOrIgnore([
            'name' => $request['name'],
            'cat_id' => $request['cat_id']
        ]);

        if ($material) {
            return ['status' => 200, 'message' => "Material qo'shildi"];
        } else {
            return ['status' => 201, 'message' => "Materialni qo'shilda xatolik!!!"];
        }
    }

    public function update(Request $request, $id)
    {
        $material = DB::table('material')->where('id', $id)->update([
            'name' => $request['name']
        ]);

        if ($material) {
            return ['status' => 200, 'message' => "Material yangilandi"];
        } else {
            return ['status' => 201, 'message' => "Materialni yangilashdada xatolik!!!"];
        }
    }

    public function delete($id)
    {
        $material = DB::table('material')->where('id', $id)->delete();

        if ($material) {
            return ['status' => 200, 'message' => "Material o'chirildi"];
        } else {
            return ['status' => 201, 'message' => "Materialni o'chirishda xatolik!!!"];
        }
    }
}
