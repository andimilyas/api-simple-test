<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ItemRequest;
use App\Models\Item;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Redirect;
use Validator;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Item::all();

        if($data->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'Data not found', 
                'data' => $data
            ], 404);
        } else {
            return response()->json([
                'status' => true,
                'message' => 'Data found',
                'data' => $data
            ], 200);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'description' => 'required',
            'stock' => 'required|numeric',
            'price' => 'required|numeric',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validated->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validated->errors()
            ], 422);
        }

        $item = Item::create($request->all()); 

        return response()->json([
            'status' => true,
            'message' => 'Data created',
            'data' => $item
        ], 201);  
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
