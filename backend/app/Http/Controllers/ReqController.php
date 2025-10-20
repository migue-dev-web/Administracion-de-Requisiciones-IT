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
            'descripcion'=>'required',
            'ubicacion' => 'required',
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
    $req = Req_Table::findOrFail($id);
    $req->prioridad = $request->prioridad;
    $req->save();

    return response()->json(['message' => 'Prioridad actualizada']);
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
