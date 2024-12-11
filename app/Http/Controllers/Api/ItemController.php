<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller; 
use App\Models\Item; 
use Illuminate\Http\Request; 
use Validator;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Item::all();

        if ($data->isEmpty()) {
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
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
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
        $data = Item::findOrFail($id);

        if ($data) {
            return response()->json([
                'status' => true,
                'message' => 'Data found',
                'data' => $data
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Data not found',
                'data' => $data
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'description' => 'required',
            'stock' => 'required|numeric',
            'price' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validated->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validated->errors()
            ], 422);
        }

        $item = Item::findOrFail($id);

        if (!$item) {
            return response()->json([
                'status' => false,
                'message' => 'Data not found',
                'data' => $item
            ], 404);
        }

        $item->update($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Data updated',
            'data' => $item
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data = Item::findOrFail($id);

        if (!$data) {
            return response()->json([
                'status' => false,
                'message' => 'Data not found', 
            ], 404);
        }

        $data->delete();

        return response()->json([
            'status' => true,
            'message' => 'Data deleted', 
        ], 200);
    }
}
