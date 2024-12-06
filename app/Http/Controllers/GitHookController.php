<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;

class GitHookController extends Controller
{
    public function handle(Request $request)
    {
        // Получаем секретный ключ из переменных окружения
        $envSecretKey = env('GIT_HOOK_SECRET_KEY');

        // Проверяем, что параметр secret_key передан в запросе
        $inputSecretKey = $request->input('secret_key');

        // Сравниваем ключи
        if ($inputSecretKey !== $envSecretKey) {
            return response()->json(['message' => 'Invalid secret key'], 403);
        }

        // Проверяем, не выполняется ли уже обновление
        if (Cache::has('git_update_lock')) {
            return response()->json(['message' => 'Update already in progress'], 409);
        }

        // Устанавливаем блокировку на выполнение обновления
        Cache::put('git_update_lock', true, 300); // 5 минут блокировки

        try {
            // Логируем дату и IP-адрес
            Log::info('Git Hook triggered', [
                'date' => now(),
                'ip' => $request->ip(),
            ]);

            // Выполняем git-команды
            $this->executeGitCommands();

            return response()->json(['message' => 'Update completed successfully']);
        } catch (\Exception $e) {
            Log::error('Git Hook error: ' . $e->getMessage());
            return response()->json(['message' => 'Update failed'], 500);
        } finally {
            // Снимаем блокировку после выполнения
            Cache::forget('git_update_lock');
        }
    }

    protected function executeGitCommands()
    {
        $this->runCommand(['git', 'checkout', 'master'], 'Switch to main branch');

        $this->runCommand(['git', 'reset', '--hard'], 'Discard local changes');

        $this->runCommand(['K:\Server\source\server\.git', 'pull', 'origin', 'master'], 'Pull latest changes');

        $this->runCommand(['git', 'checkout', 'origin', 'lab6'], 'Pull latest changes');
    }

    protected function runCommand(array $command, string $logMessage)
    {
        $process = new Process($command);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new \RuntimeException($process->getErrorOutput());
        }

        Log::info($logMessage, ['output' => $process->getOutput()]);
    }
}
