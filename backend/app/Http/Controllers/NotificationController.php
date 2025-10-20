<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::where('leida', false)->get();
        return response()->json($notifications, Response::HTTP_OK);
    }

    public function marcarLeida($id)
    {
        $noti = Notification::findOrFail($id);
        $noti->leida = true;
        $noti->save();

        return response()->json(['message' => 'Notificación marcada como leída']);
    }
}
