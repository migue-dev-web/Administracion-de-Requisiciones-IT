<?php

namespace App\Http\Controllers;

use App\Models\Req_Table;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
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
        ]);

        return response()->json($requisicion, Response::HTTP_CREATED);
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

        return response()->json($requisicion, Response::HTTP_OK);
    }

    public function getRequisicionesActivas()
    {
        $requisicion = Req_Table::where('status', true)->get();

        return response()->json($requisicion, Response::HTTP_OK);
    }

    public function getRequisicionesCerradas()
    {
        $requisicion = Req_Table::where('status', false)->get();

        return response()->json($requisicion, Response::HTTP_OK);
    }
}
