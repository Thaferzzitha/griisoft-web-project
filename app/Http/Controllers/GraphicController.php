<?php

namespace App\Http\Controllers;

use App\Models\Graphic;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class GraphicController extends Controller
{

    /**
     * Display stadistics about graphics
     */
    public function stadistics()
    {      
        $userId = request('user_id');
        $startDate = request('start_date', Carbon::now()->startOfMonth());
        $endDate = request('end_date', Carbon::now()->endOfMonth());
        $query = Graphic::query();

        if (auth()->user()->is_super_admin) {
            if ($userId && !empty($userId)) {
                $query->where('user_id', $userId);
            }
        } else {
            $query->where('user_id', auth()->user()->id);
        }

        $graphics = $query->whereBetween('updated_at', [$startDate, $endDate])->orderBy('updated_at', 'asc')->get();

        $graphicsStats = [
            'sprott' => [
                'total' => 0,
                'dates' => []
            ],
            'lorenz' => [
                'total' => 0,
                'dates' => []
            ],
            'chen' => [
                'total' => 0,
                'dates' => []
            ],
            'rossler' => [
                'total' => 0,
                'dates' => []
            ],
            'start_date' => $startDate,
            'end_date' => $endDate
        ];

        foreach ($graphics as $graphic) {
            $graphicsStats[$graphic->type]['total'] = $graphicsStats[$graphic->type]['total'] + 1;
            $date = Carbon::parse($graphic->updated_at)->format('Y-m-d');
            if (!isset($graphicsStats[$graphic->type]['dates'][$date])) {
                $graphicsStats[$graphic->type]['dates'][$date] = 0;
            }
    
            $graphicsStats[$graphic->type]['dates'][$date] += 1;
        }
        
        return response($graphicsStats, 200);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Graphic/Index', [
            'status' => session('status'),
            'isSuperAdmin' => auth()->user()->is_super_admin,
        ]);
    }

    /**
     * Listar gráficos
     *
     * @OA\Get(
     *     path="/api/graphic/list",
     *     tags={"Gráfico"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="type",
     *         in="query",
     *         description="Tipo de gráfico (rossler, sprott, chen o lorenz)",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="title",
     *         in="query",
     *         description="Título del gráfico (búsqueda parcial)",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="user_id",
     *         in="query",
     *         description="ID del usuario para filtrar gráficos (solo para super administradores)",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="start_date",
     *         in="query",
     *         description="Fecha de inicio para filtrar gráficos (YYYY-MM-DD)",
     *         required=false,
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Parameter(
     *         name="end_date",
     *         in="query",
     *         description="Fecha de fin para filtrar gráficos (YYYY-MM-DD)",
     *         required=false,
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Gráficos listados exitosamente",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=16),
     *                 @OA\Property(property="user_id", type="integer", example=2),
     *                 @OA\Property(property="title", type="string", example="Atractor de Sprott"),
     *                 @OA\Property(property="parameters", type="object",
     *                     @OA\Property(property="point_number", type="integer", example=10000),
     *                     @OA\Property(property="step_size", type="float", example=0.01),
     *                     @OA\Property(property="a", type="float", example=5),
     *                     @OA\Property(property="b", type="float", example=2),
     *                     @OA\Property(property="c", type="float", example=1.6),
     *                     @OA\Property(property="x", type="float", example=1),
     *                     @OA\Property(property="y", type="float", example=1),
     *                     @OA\Property(property="z", type="float", example=1),
     *                 ),
     *                 @OA\Property(property="type", type="string", example="sprott"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2023-07-25T02:47:17.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2023-07-25T02:47:17.000000Z"),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Tipo de gráfico no válido",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Tipo de gráfico no válido, los tipos válidos son: rossler, sprott, chen y lorenz")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autorizado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthorized")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno del servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Internal Server Error")
     *         )
     *     )
     * )
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
        $userId = request('user_id');
        $startDate = request('start_date');
        $endDate = request('end_date');

        if (auth()->user()->is_super_admin) {
            if ($userId && !empty($userId)) {
                $query = Graphic::where('user_id', $userId);
            } else {
                $query = Graphic::query();
            }
        } else {
            $query = Graphic::where('user_id', auth()->user()->id);
        }

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

        if ($startDate && !empty($startDate)) {
            $query->where('updated_at', '>=', $startDate);
        }

        if ($endDate && !empty($endDate)) {
            $query->where('updated_at', '<=', $endDate);
        }

        if (auth()->user()->is_super_admin) {
            $query->with(['user']);
        }
        
        $graphics = $query->orderBy('updated_at', 'desc')->get();

        return response($graphics, 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Graphic/Create', [
            'status' => session('status'),
            'canLogin' => !Auth::check(),
        ]);
    }

    /**
     * Crear un nuevo gráfico
     *
     * @OA\Post(
     *     path="/api/graphic/store",
     *     tags={"Gráfico"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={"title", "parameters", "type"},
     *                 @OA\Property(property="title", type="string", example="Atractor de Rossler"),
     *                 @OA\Property(property="parameters", type="object",
     *                     @OA\Property(property="point_number", type="integer", example=10000),
     *                     @OA\Property(property="step_size", type="float", example=0.01),
     *                     @OA\Property(property="a", type="float", example=0.2),
     *                     @OA\Property(property="b", type="float", example=0.2),
     *                     @OA\Property(property="c", type="float", example=5.7),
     *                     @OA\Property(property="x", type="float", example=1),
     *                     @OA\Property(property="y", type="float", example=1),
     *                     @OA\Property(property="z", type="float", example=1),
     *                 ),
     *                 @OA\Property(property="type", type="string", example="rossler",
     *                     enum={"rossler", "sprott", "chen", "lorenz"}
     *                 ),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Gráfico creado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="user_id", type="integer", example=2),
     *                 @OA\Property(property="title", type="string", example="Atractor de Rossler"),
     *                 @OA\Property(property="parameters", type="object",
     *                     @OA\Property(property="point_number", type="integer", example=10000),
     *                     @OA\Property(property="step_size", type="float", example=0.01),
     *                     @OA\Property(property="a", type="float", example=0.2),
     *                     @OA\Property(property="b", type="float", example=0.2),
     *                     @OA\Property(property="c", type="float", example=5.7),
     *                     @OA\Property(property="x", type="float", example=1),
     *                     @OA\Property(property="y", type="float", example=1),
     *                     @OA\Property(property="z", type="float", example=1),
     *                 ),
     *                 @OA\Property(property="type", type="string", example="rossler"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2023-07-25T02:54:39.000000Z"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2023-07-25T02:54:39.000000Z"),
     *                 @OA\Property(property="id", type="integer", example=17)
     *             ),
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Graphic created successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error de validación",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="validation error"),
     *             @OA\Property(property="errors", type="object", example={"title": {"The title field is required."}})
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autorizado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthorized")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno del servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Internal Server Error")
     *         )
     *     )
     * )
     */
    public function store(Request $request)
    {
        try {
            $validateGraphic = Validator::make(
                $request->all(),
                [
                    'parameters' => 'required|array',
                    'parameters.point_number' => 'required|numeric',
                    'parameters.step_size' => 'required|numeric',
                    'parameters.a' => 'required|numeric',
                    'parameters.b' => 'required|numeric',
                    'parameters.c' => 'required|numeric',
                    'parameters.x' => 'required|numeric',
                    'parameters.y' => 'required|numeric',
                    'parameters.z' => 'required|numeric',
                    'title' => 'required|string',
                    'type' => 'required|string',
                ]
            );

            if ($validateGraphic->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateGraphic->errors()
                ], 400);
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
     * Mostrar detalles de un gráfico por su ID
     *
     * @OA\Get(
     *     path="/api/graphic/{id}",
     *     tags={"Gráfico"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del gráfico a mostrar",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Detalles del gráfico",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=17),
     *             @OA\Property(property="user_id", type="integer", example=2),
     *             @OA\Property(property="title", type="string", example="Atractor de Rossler"),
     *             @OA\Property(property="parameters", type="object",
     *                 @OA\Property(property="point_number", type="integer", example=10000),
     *                 @OA\Property(property="step_size", type="float", example=0.01),
     *                 @OA\Property(property="a", type="float", example=0.2),
     *                 @OA\Property(property="b", type="float", example=0.2),
     *                 @OA\Property(property="c", type="float", example=5.7),
     *                 @OA\Property(property="x", type="float", example=1),
     *                 @OA\Property(property="y", type="float", example=1),
     *                 @OA\Property(property="z", type="float", example=1),
     *             ),
     *             @OA\Property(property="type", type="string", example="rossler"),
     *             @OA\Property(property="updated_at", type="string", format="date-time", example="2023-07-25T02:54:39.000000Z"),
     *             @OA\Property(property="created_at", type="string", format="date-time", example="2023-07-25T02:54:39.000000Z")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Gráfico no encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Graphic not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autorizado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthorized")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno del servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Internal Server Error")
     *         )
     *     )
     * )
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
     * Actualizar un gráfico existente por su ID
     *
     * @OA\Put(
     *     path="/api/graphic/{graphic}",
     *     tags={"Gráfico"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="graphic",
     *         in="path",
     *         description="ID del gráfico a actualizar",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={"title", "parameters", "type"},
     *                 @OA\Property(property="title", type="string", example="Atractor de Rossler"),
     *                 @OA\Property(property="parameters", type="object",
     *                     @OA\Property(property="point_number", type="integer", example=10000),
     *                     @OA\Property(property="step_size", type="float", example=0.01),
     *                     @OA\Property(property="a", type="float", example=0.2),
     *                     @OA\Property(property="b", type="float", example=0.2),
     *                     @OA\Property(property="c", type="float", example=5.7),
     *                     @OA\Property(property="x", type="float", example=1),
     *                     @OA\Property(property="y", type="float", example=1),
     *                     @OA\Property(property="z", type="float", example=1),
     *                 ),
     *                 @OA\Property(property="type", type="string", example="rossler",
     *                     enum={"rossler", "sprott", "chen", "lorenz"}
     *                 ),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Gráfico actualizado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=17),
     *                 @OA\Property(property="user_id", type="integer", example=2),
     *                 @OA\Property(property="title", type="string", example="Atractor de Rossler"),
     *                 @OA\Property(property="parameters", type="object",
     *                     @OA\Property(property="point_number", type="integer", example=10000),
     *                     @OA\Property(property="step_size", type="float", example=0.01),
     *                     @OA\Property(property="a", type="float", example=0.2),
     *                     @OA\Property(property="b", type="float", example=0.2),
     *                     @OA\Property(property="c", type="float", example=5.7),
     *                     @OA\Property(property="x", type="float", example=1),
     *                     @OA\Property(property="y", type="float", example=1),
     *                     @OA\Property(property="z", type="float", example=1),
     *                 ),
     *                 @OA\Property(property="type", type="string", example="rossler"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2023-07-25T02:54:39.000000Z"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2023-07-25T02:54:39.000000Z")
     *             ),
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Graphic updated successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error de validación",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="validation error"),
     *             @OA\Property(property="errors", type="object", example={"title": {"The title field is required."}})
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autorizado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthorized")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Gráfico no encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Graphic not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno del servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Internal Server Error")
     *         )
     *     )
     * )
     */
    public function update(Request $request, Graphic $graphic)
    {
        try {
            $validateGraphic = Validator::make(
                $request->all(),
                [
                    'parameters' => 'required|array',
                    'parameters.point_number' => 'required|numeric',
                    'parameters.step_size' => 'required|numeric',
                    'parameters.a' => 'required|numeric',
                    'parameters.b' => 'required|numeric',
                    'parameters.c' => 'required|numeric',
                    'parameters.x' => 'required|numeric',
                    'parameters.y' => 'required|numeric',
                    'parameters.z' => 'required|numeric',
                    'title' => 'required|string',
                    'type' => 'required|string',
                ]
            );

            if ($validateGraphic->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateGraphic->errors()
                ], 400);
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
     * Eliminar un gráfico por su ID
     *
     * @OA\Delete(
     *     path="/api/graphic/{graphic}",
     *     tags={"Gráfico"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="graphic",
     *         in="path",
     *         description="ID del gráfico a eliminar",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Gráfico eliminado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Graphic deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autorizado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthorized")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Gráfico no encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Graphic not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno del servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Internal Server Error")
     *         )
     *     )
     * )
     */
    public function destroy(Graphic $graphic)
    {
        $graphic->delete();
        return response('', 200);
    }
}
