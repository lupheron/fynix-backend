<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use App\Http\Requests\StoreWarehouseRequest;
use App\Http\Requests\UpdateWarehouseRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WarehouseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $wh = DB::table('warehouse')
            ->leftJoin('section', 'warehouse.id', '=', 'section.w_id')
            ->leftJoin('boxes', 'boxes.sec_id', '=', 'section.id')
            ->select(
                'warehouse.*',
                DB::raw('COUNT(DISTINCT section.id) as section_count'),
                DB::raw('COUNT(boxes.id) as box_count')
            )
            ->groupBy('warehouse.id')
            ->get();
    
        return $wh;
    }
    

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $wh = DB::table('warehouse')->insertGetId([
            'name' => $request['name']
        ]);

        if ($wh) {
            return ['status' => 200, 'message' => "Sklad qo'shildi"];
        } else {
            return ['message' => "Sklad qo'shishda hatolik", "status" => 201];
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreWarehouseRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Warehouse $warehouse)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Warehouse $warehouse)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $warehouse = DB::table('warehouse')->where('id', $id)->update([
            'name' => $request['name']
        ]);

        if ($warehouse) {
            return ['status' => 200, 'message' => "Sklad yangilandi"];
        } else {
            return ['status' => 201, 'message' => "Skladni yangilashda hatolik yuzaga keldi"];
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $warehouse = DB::table('warehouse')->where('id', $id)->delete();

        if ($warehouse) {
            return ['status' => 200, 'message' => "Sklad o'chirildi"];
        } else {
            return ['status' => 201, 'message' => "Skladni o'chirishda hatolik"];
        }
    }  
}
