<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Console\Input\Input;

class ComingController extends Controller
{
    public function index()
    {
        $tq =  DB::table('tovar_qabuli')->get();
        foreach ($tq as $t) {
            $t->items = DB::table('in_item')->where('in_id', $t->id)
                ->join('products', 'products.id', 'in_item.pr_id')
                ->select('in_item.*', 'products.name as product')
                ->get();
        }

        return $tq;
    }

    public function create(Request $request)
    {
        $request['created_at'] = Carbon::now();

        // Calculate total summa from items
        $items = $request->input('items', []);
        $totalSumma = array_sum(array_column($items, 'summa'));

        // Create order with calculated summa
        $orderId = DB::table('tovar_qabuli')->insertGetId([
            'date' => $request->input('date'),
            'supplier' => $request->input('supplier'),
            'summa' => $totalSumma, // Now calculated instead of taken from request
            'created_at' => Carbon::now(),
            'status' => 1
        ]);

        if ($orderId) {
            $insertedItems = [];

            foreach ($items as $item) {
                $itemId = DB::table('in_item')->insertGetId([
                    'in_id' => $orderId,
                    'pr_id' => $item['pr_id'],
                    'count' => $item['count'],
                    'price' => $item['price'],
                    'in_summa' => $item['in_summa'],
                ]);

                if ($itemId) {
                    DB::table('products')->where('id', $item['pr_id'])->update([
                        'count' => DB::raw('count + ' . $item['count']),
                        'summa' => DB::raw('summa + ' . $item['in_summa']) // Ensure this does not overwrite values incorrectly
                    ]);

                    // Add inserted item to response
                    $insertedItems[] = [
                        'id' => $itemId,
                        'pr_id' => $item['pr_id'],
                        'count' => $item['count'],
                        'price' => $item['price'],
                        'in_summa' => $item['in_summa'],
                    ];
                }
            }

            return response()->json([
                'id' => $orderId,
                'date' => $request->input('date'),
                'supplier' => $request->input('supplier'),
                'summa' => $totalSumma,
                'items' => $insertedItems
            ], 201);
        }

        return response()->json(["message" => "Order creation failed"], 400);
    }


    public function update(Request $request, $id)
    {
        $status = $request['status'] == 1 ? 1 : 0; // Ensure status is either 1 or 0

        $it = DB::table('in_item')->where('id', $id)->update([
            'count' => $request['count'],
            'summa' => $request['in_summa'],
            'status' => $status,
            'updated_at' => Carbon::now(),
        ]);

        $tqs = DB::table('in_item')->where('in_id', $request['in_id'])->sum('summa');

        $tq = DB::table('tovar_qabuli')->where('id', $request['in_id'])->update([
            'summa' => $tqs,
        ]);

        if ($it && $tq) {
            return response()->json(["message" => "Updated successfully"]);
        } else {
            return response()->json(["message" => "Error while updating"]);
        }
    }


    public function destroy($id)
    {
        $deleted = DB::table('in_item')->where('id', $id)->delete();

        if ($deleted) {
            return response()->json(["message" => "Deleted successfully", "status" => 0]);
        }

        return response()->json(["message" => "Deletion failed"], 400);
    }
}
