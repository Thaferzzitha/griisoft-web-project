<?php

namespace App\Http\Controllers;

use App\Models\Graphic;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
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
        $allowedGraphicTypes = [
            Graphic::ROSSLER,
            Graphic::SPROTT,
            Graphic::CHEN,
            Graphic::LORENZ,
        ];

        $type = request('type');
        $title = request('title');
        
        $query = Graphic::where('user_id', auth()->user()->id);

        if ($type && !empty($type)) {
            $isAllowedType = in_array($type, $allowedGraphicTypes);
            if ($isAllowedType) {
                $query->where('type', $type);
            } else {
                return response()->json(['error' => 'Tipo de gráfico no válido, los tipos válidos son: rossler, sprott, chen y lorenz'], Response::HTTP_BAD_REQUEST);
            }
        }

        if ($title && !empty($title)) {
            $query->where('title', 'like', '%'.$title.'%');
        }

        $graphics = $query->get();

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
            $validateGraphic = Validator::make(
                $request->all(),
                [
                    'parameters' => 'required|array',
                    'title' => 'required|string',
                ]
            );

            if ($validateGraphic->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateGraphic->errors()
                ], 401);
            }

            $graphic = Graphic::create([
                'user_id' => auth()->user()->id,
                'title' => $request->title,
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
        return Inertia::render('Graphic/Edit', [
            'status' => session('status'),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Graphic $graphic)
    {
        try {
            $validateGraphic = Validator::make(
                $request->all(),
                [
                    'parameters' => 'required|array',
                    'title' => 'required|string',
                ]
            );

            if ($validateGraphic->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateGraphic->errors()
                ], 401);
            }

            $graphic->update([
                'title' => $request->title,
                'parameters' => $request->parameters,
                'results' => [],
                'type' => $request->type,
            ]);

            return response()->json([
                'data' => $graphic,
                'status' => true,
                'message' => 'Graphic updated successfully'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Graphic $graphic)
    {
        $graphic->delete();
        return response('', 200);
    }
}
