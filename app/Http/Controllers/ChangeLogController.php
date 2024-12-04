<?php

namespace App\Http\Controllers;

use App\Http\Resources\ChangeLogCollectionDTO;
use App\Models\ChangeLog;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ChangeLogController extends Controller
{
    // Получение истории изменений
    public function getEntityChangeLog($entityType, $entityId) 
    {
        if (Gate::denies('permission-check', "get-story-$entityType")) {
            return response()->json([
                "error" => "Access Denied",
                "message" => "User does not have the required permission: get-story-$entityType"
            ], 403);
        }

        $logs = ChangeLog::where('entity_type', ucfirst($entityType))
            ->where('entity_id', $entityId)
            ->orderBy('created_at', 'desc')
            ->get();

        return new ChangeLogCollectionDTO($logs);
    }

    public function restoreEntityState($entityType, $entityId, $logId)
    {
        // Определяем модель в зависимости от типа сущности
        $model = $this->getModelByEntityType(ucfirst($entityType));

        // Если модель не найдена, возвращаем ошибку
        if (!$model) {
            return response()->json(['message' => 'Invalid entity type.'], 400);
        }

        // Находим лог изменений для сущности
        $changeLog = ChangeLog::where('entity_type', $entityType)
            ->where('entity_id', $entityId)
            ->where('id', $logId)
            ->first();

        // Если лог не найден, выбрасываем исключение
        if (!$changeLog) {
            throw new ModelNotFoundException('Change log not found.');
        }

        // Получаем старое состояние (before_change) и преобразуем его из JSON в массив
        $beforeChange = json_decode($changeLog->before_change, true);

        // Проверяем, что старое состояние существует и является массивом
        if (!$beforeChange || !is_array($beforeChange)) {
            return response()->json(['message' => 'No previous state to restore.'], 400);
        }

        // Находим сущность по ID и восстанавливаем её состояние
        $entity = $model::find($entityId);

        // Если сущность не найдена, выбрасываем исключение
        if (!$entity) {
            return response()->json(['message' => 'Entity not found.'], 404);
        }

        // Восстанавливаем сущность в состояние до изменений
        $entity->fill($beforeChange);
        $entity->save();

        return response()->json(['message' => "{$entityType} restored successfully.", 'entity' => $entity]);
    }

    /**
     * Возвращает модель в зависимости от типа сущности.
     *
     * @param  string  $entityType
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    private function getModelByEntityType($entityType)
    {
        switch ($entityType) {
            case 'User':
                return User::class;
            case 'Role':
                return Role::class;
            case 'Permission':
                return Permission::class;
            default:
                return null;
        }
    }
}
