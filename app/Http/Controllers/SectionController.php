<?php

namespace App\Http\Controllers;

use App\Models\Section;
use App\Http\Requests\StoreSectionRequest;
use App\Http\Requests\UpdateSectionRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $section = DB::table('section')
            ->leftJoin('boxes', 'section.id', '=', 'boxes.sec_id')
            ->select(
                'section.*',
                DB::raw('COUNT(boxes.id) as box_count')
            )
            ->groupBy('section.id')
            ->get();

        return $section;
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        // Check if the section already exists in the selected warehouse
        $existingSection = DB::table('section')
            ->where('sec_name', $request['sec_name'])
            ->where('w_id', $request['w_id']) // Check warehouse ID
            ->first();

        if ($existingSection) {
            return response()->json([
                'status' => 201,
                'message' => "Bu Bo'lim allaqachon kiritilgan"
            ]);
        }

        $sec = DB::table('section')->insertGetId([
            'sec_name' => $request['sec_name'],
            'w_id' => $request['w_id'], // Store warehouse ID
        ]);

        if ($sec) {
            return ['status' => 200, 'message' => "Bo'lim qo'shildi"];
        } else {
            return ['message' => "Bo'limni qo'shishda hatolik", "status" => 201];
        }
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSectionRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Section $section)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Section $section)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $section = DB::table('section')->where('id', $id)->update([
            'sec_name' => $request['sec_name']
        ]);

        if ($section) {
            return ['status' => 200, 'message' => "Bo'limni yangilandi"];
        } else {
            return ['status' => 201, 'message' => "Bo'limni yangilashda hatolik yuzaga keldi"];
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $section = DB::table('section')->where('id', $id)->delete();

        if ($section) {
            return ['status' => 200, 'message' => "Bo'lim o'chirildi"];
        } else {
            return ['status' => 201, 'message' => "Bo'limni o'chirishda hatolik"];
        }
    }
}
