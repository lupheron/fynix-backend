<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use function Laravel\Prompts\select;

class OutController extends Controller
{
    public function index()
    {
        $tq =  DB::table('out')->get();
        foreach ($tq as $t) {
            $t->items = DB::table('out_item')->where('out_id', $t->id)
                ->join('products', 'products.id', 'out_item.pr_id')
                ->select('out_item.*', 'products.name as product')
                ->get();
        }

        return $tq;
    }

    public function getMonthlySales()
    {
        $currentYear = date('Y'); // Get the current year

        $sales = DB::table('out')
            ->select(DB::raw('month, SUM(summa) as total_sales'))
            ->where('year', $currentYear) // Filter only the current year
            ->groupBy('month')
            ->orderBy('month', 'ASC')
            ->get();

        return response()->json($sales);
    }

    public function getYearSumms()
    {
        $currentYear = date('Y'); // Get the current year

        $totalSales = DB::table('out')
            ->where('year', $currentYear) // Filter by current year
            ->sum('summa'); // Get the total sum of the "summa" column

        return response()->json(['total_sales' => $totalSales]);
    }

    public function create(Request $request)
    {
        $request['created_at'] = Carbon::now();

        // Calculate total summa from items
        $items = $request->input('items', []);
        $totalSumma = array_sum(array_column($items, 'summa'));

        // Create order with calculated summa
        $orderId = DB::table('out')->insertGetId([
            'day' => $request['day'],
            'month' => $request['month'],
            'year' => $request['year'],
            'summa' => $totalSumma, // Now calculated instead of taken from request
            'created_at' => Carbon::now(),
            'status' => 1
        ]);

        if ($orderId) {
            $insertedItems = [];

            foreach ($items as $item) {
                // Check if product exists before updating
                $product = DB::table('products')->where('id', $item['pr_id'])->first();
                if (!$product) {
                    return response()->json(["message" => "Product not found"], 404);
                }

                $itemId = DB::table('out_item')->insertGetId([
                    'out_id' => $orderId,
                    'pr_id' => $item['pr_id'],
                    'count' => $item['count'],
                    'price' => $item['price'],
                    'summa' => $item['summa'],
                ]);

                if ($itemId) {
                    // Corrected subtraction logic for summa and count
                    DB::table('products')->where('id', $item['pr_id'])->update([
                        'count' => DB::raw('count - ' . $item['count']),
                        'summa' => DB::raw('summa - ' . $item['summa']) // Ensure this does not overwrite values incorrectly
                    ]);

                    // Add inserted item to response
                    $insertedItems[] = [
                        'id' => $itemId,
                        'pr_id' => $item['pr_id'],
                        'count' => $item['count'],
                        'price' => $item['price'],
                        'summa' => $item['summa'],
                    ];
                }
            }

            return response()->json([
                'id' => $orderId,
                'day' => $request['day'],
                'month' => $request['month'],
                'year' => $request['year'],
                'summa' => $totalSumma,
                'items' => $insertedItems
            ], 201);
        }

        return response()->json(["message" => "Order creation failed"], 400);
    }



    public function update(Request $request, $id)
    {
        $status = $request['status'] == 1 ? 1 : 0; // Ensure status is either 1 or 0

        // Get the old values before update
        $oldItem = DB::table('out_item')->where('id', $id)->first();

        if (!$oldItem) {
            return response()->json(["message" => "Item not found"], 404);
        }

        // Get the related product
        $product = DB::table('products')->where('id', $oldItem->pr_id)->first();

        if (!$product) {
            return response()->json(["message" => "Product not found"], 404);
        }

        // Calculate the differences in count and summa
        $countDiff = $request['count'] - $oldItem->count;
        $summaDiff = $request['summa'] - $oldItem->summa;

        // Update the out_item table
        $it = DB::table('out_item')->where('id', $id)->update([
            'count' => $request['count'],
            'summa' => $request['summa'],
            'status' => $status,
            'updated_at' => Carbon::now(),
        ]);

        // Update the products table
        DB::table('products')->where('id', $oldItem->pr_id)->update([
            'count' => DB::raw('count - ' . $countDiff), // Subtract the difference
            'summa' => DB::raw('summa - ' . $summaDiff),
        ]);

        // Recalculate the total summa in the 'out' table
        $tqs = DB::table('out_item')->where('out_id', $request['out_id'])->sum('summa');
        $tq = DB::table('out')->where('id', $request['out_id'])->update([
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
        $deleted = DB::table('out_item')->where('id', $id)->delete();

        if ($deleted) {
            return response()->json(["message" => "Deleted successfully", "status" => 0]);
        }

        return response()->json(["message" => "Deletion failed"], 400);
    }
}
