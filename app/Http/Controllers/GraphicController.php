<?php

namespace App\Http\Controllers;

use App\Models\Graphic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class GraphicController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Graphic/Index', [
            'status' => session('status'),
        ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function list()
    {
        $graphics = Graphic::where('user_id', auth()->user()->id)->get();
        return response($graphics, 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Graphic/Create', [
            'status' => session('status'),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validateGraphic = Validator::make($request->all(), 
            [
                'parameters' => 'required|array',
            ]);

            if($validateGraphic->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateGraphic->errors()
                ], 401);
            }

            $graphic = Graphic::create([
                'user_id' => auth()->user()->id,
                'parameters' => $request->parameters,
                'results' => [],
                'type' => $request->type,
            ]);

            return response()->json([
                'data' => $graphic,
                'status' => true,
                'message' => 'Graphic created successfully'
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $graphic = Graphic::find($id);

        return response($graphic, 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
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
