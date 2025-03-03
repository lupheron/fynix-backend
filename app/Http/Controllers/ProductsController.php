<?php

namespace App\Http\Controllers;

use App\Models\Products;
use App\Http\Requests\StoreProductsRequest;
use App\Http\Requests\UpdateProductsRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = DB::table('products')
            ->leftJoin('country', 'products.country_id', '=', 'country.id')
            ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
            ->leftJoin('material', 'products.material_id', '=', 'material.id')
            ->leftJoin('warehouse', 'products.warehouse_id', '=', 'warehouse.id')
            ->leftJoin('section', 'products.section_id', '=', 'section.id')
            ->leftJoin('boxes', 'products.box_id', '=', 'boxes.id')
            ->select(
                'products.id',
                'products.name',      // ✅ Include name
                'products.price',     // ✅ Include price
                'products.count',     // ✅ Include count
                'products.summa',
                'country.name as country',
                'categories.name as category',
                'material.name as material',
                'warehouse.name as warehouse',
                'section.sec_name as section',
                'boxes.box_name as box'
            )
            ->get();

        return response()->json($products);
    }


    /**
     * Search products by name.
     */
    public function search($param)
    {
        $product = DB::table('products')
            ->where('products.name', 'LIKE', "%$param%")
            ->get();

        return response()->json($product);
    }

    /**
     * Create a new product.
     */
    public function create(Request $request)
    {
        $product = DB::table('products')->insertOrIgnore([
            'name' => $request['name'],
            'price' => $request['price'],
            'count' => $request['count'],
            'summa' => $request['summa'],
            'country_id' => $request['country'],
            'category_id' => $request['category'],
            'material_id' => $request['material'],
            'warehouse_id' => $request['warehouse'],
            'section_id' => $request['section'],
            'box_id' => $request['box'],
        ]);

        return response()->json(['status' => $product ? 200 : 400, 'message' => $product ? "Product added" : "Error adding product"]);
    }

    /**
     * Store a newly created product.
     */
    public function store(StoreProductsRequest $request)
    {
        //
    }

    /**
     * Display the specified product.
     */
    public function show(Products $products)
    {
        //
    }

    /**
     * Edit a product.
     */
    public function edit(Products $products)
    {
        //
    }

    /**
     * Update an existing product.
     */
    public function update(Request $request, $id)
    {
        $product = DB::table('products')->where('id', $id)->update([
            'name' => $request['name'],
            'price' => $request['price'],
            'count' => $request['count'],
            'summa' => $request['summa'],
            'country_id' => $request['country'],
            'category_id' => $request['category'],
            'material_id' => $request['material'],
            'warehouse_id' => $request['warehouse'],
            'section_id' => $request['section'],
            'box_id' => $request['box'],
        ]);

        return response()->json(['status' => $product ? 200 : 400, 'message' => $product ? "Product updated" : "Error updating product"]);
    }

    /**
     * Delete a product.
     */
    public function destroy($id)
    {
        $products = DB::table('products')->where('id', $id)->delete();

        return response()->json(['status' => $products ? 200 : 400, 'message' => $products ? "Product deleted" : "Error deleting product"]);
    }
}
