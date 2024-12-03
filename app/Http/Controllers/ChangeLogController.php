<?php

namespace App\Http\Controllers;

use App\Http\Resources\ChangeLogCollectionDTO;
use App\Models\ChangeLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChangeLogController extends Controller
{
    // Получение истории изменений для пользователя
    public function getUserHistory($id)
    {
        $logs = ChangeLog::where('entity_type', 'User')->where('entity_id', $id)->get();
        return response()->json(new ChangeLogCollectionDTO($logs));
    }

    // Получение истории изменений для роли
    public function getRoleHistory($id)
    {
        $logs = ChangeLog::where('entity_type', 'Role')->where('entity_id', $id)->get();
        return response()->json(new ChangeLogCollectionDTO($logs));
    }

    // Получение истории изменений для разрешения
    public function getPermissionHistory($id)
    {
        $logs = ChangeLog::where('entity_type', 'Permission')->where('entity_id', $id)->get();
        return response()->json(new ChangeLogCollectionDTO($logs));
    }
}
