<?php

namespace App\Http\Controllers;

use App\Models\Req_Table;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Notification;
class ReqController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'problema' => 'required|string|max:255',
    'descripcion' => 'required',
    'ubicacion' => 'required',
    'prioridad' => 'required|integer|min:1|max:3',
    'razon_prioridad' => 'nullable|string|max:255',
        ]);

        $requisicion = Req_Table::create([
            'problema' => $request->problema,
            'requisitor'=> $request->user()->name,
            'media'=>$request->media,
            'descripcion'=> $request->descripcion,
            'ubicacion'=> $request->ubicacion,
            'status'=> true,
            'fecha_creacion' => now(),
            'prioridad' => $request->prioridad,
            'razon_prioridad' => $request->razon_prioridad, 
        ]);

        Notification::create([
            'req_id' => $requisicion->id,
            'mensaje' => "Nueva requisición creada por {$requisicion->requisitor}: {$requisicion->problema}",
        ]);

        return response()->json($requisicion, Response::HTTP_CREATED);
    }

    public function cambiarPrioridad(Request $request, $id)
{
    $request->validate([
        'prioridad' => 'required|integer|min:1|max:3'
    ]);
    $requisicion = Req_Table::find($id);

    if (!$requisicion) {
        return response()->json(['error' => 'No se encontró la requisición'], 404);
    }

    $requisicion->prioridad = $request->input('prioridad'); // lee del JSON
    $requisicion->save();

    return response()->json([
        'message' => 'Prioridad actualizada',
        'requisicion' => $requisicion
    ]);
}

    public function finalizar(Request $request,$id)
    {
        if (!in_array($request->user()->role, ['admin', 'tecnico'])) {
        return response()->json(['message' => 'No autorizado'], Response::HTTP_FORBIDDEN);
    }
        $requisicion = Req_Table::findOrFail( $id);
        $requisicion->update([
            'fecha_finalizacion' => now(),
            'tecnico' => $request->user()->name,
            'status'=> false,
        ]);
        Notification::where('req_id', $id)->delete();
        return response()->json($requisicion, Response::HTTP_OK);
    }

    public function getRequisicionesActivas()
    {
        $requisicion = Req_Table::where('status', true)
            ->orderBy('prioridad', 'asc')
            ->get();

        return response()->json($requisicion, Response::HTTP_OK);
    }

    public function getRequisicionesCerradas()
    {
        $requisicion = Req_Table::where('status', false)
            ->orderBy('prioridad', 'asc')
            ->get();

        return response()->json($requisicion, Response::HTTP_OK);
    }

    
}
