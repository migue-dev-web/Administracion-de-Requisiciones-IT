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
            'requisitor'=> $request->requisitor,
            'tecnico'=> $request->tecnico,
            'media'=>$request->media,
            'descripcion'=> $request->descripcion,
            'ubicacion'=> $request->ubicacion,
            'fecha_creacion' => now(), 
        ]);

        return response()->json($requisicion, Response::HTTP_CREATED);
    }

    public function finalizar(Request $request,$id)
    {
        $requisicion = Req_Table::findOrFail( $id);
        $requisicion->update([
            'fecha_finalizacion' => now(),
            'tecnico'=>$request->tecnico,
        ]);

        return response()->json($requisicion, Response::HTTP_OK);
    }
}
