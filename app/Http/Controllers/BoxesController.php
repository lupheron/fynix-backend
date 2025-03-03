<?php

namespace App\Http\Controllers;

use App\Models\Boxes;
use App\Http\Requests\StoreBoxesRequest;
use App\Http\Requests\UpdateBoxesRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BoxesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $boxes = DB::table('section')
            ->join('boxes', 'section.id', '=', 'boxes.sec_id')
            ->select(
                'boxes.*',
                'section.*'
            )
            ->get();
        return $boxes;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        // Check if the section already exists in the selected warehouse
        $existingBox = DB::table('boxes')
            ->where('box_name', $request['box_name'])
            ->where('sec_id', $request['sec_id']) // Check warehouse ID
            ->first();

        if ($existingBox) {
            return response()->json([
                'status' => 201,
                'message' => "Bu Quti allaqachon kiritilgan"
            ]);
        }

        $box = DB::table('boxes')->insertGetId([
            'box_name' => $request['box_name'],
            'sec_id' => $request['sec_id'], // Store warehouse ID
        ]);

        if ($box) {
            return ['status' => 200, 'message' => "Quti qo'shildi"];
        } else {
            return ['message' => "Qutini qo'shishda hatolik", "status" => 201];
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBoxesRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Boxes $boxes)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Boxes $boxes)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $box = DB::table('boxes')->where('id', $id)->update([
            'box_name' => $request['box_name']
        ]);

        if ($box) {
            return ['status' => 200, 'message' => "Quti yangilandi"];
        } else {
            return ['status' => 201, 'message' => "Qutini yangilashda hatolik yuzaga keldi"];
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $box = DB::table('boxes')->where('id', $id)->delete();

        if ($box) {
            return ['status' => 200, 'message' => "Quti o'chirildi"];
        } else {
            return ['status' => 201, 'message' => "Qutini o'chirishda hatolik"];
        }
    }
}
