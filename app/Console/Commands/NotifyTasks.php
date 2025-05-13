<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\User;

class NotifyTasks extends Command
{
    protected $signature = 'notify:tasks';
    protected $description = 'Отправка невыполненных задач всем активным пользователям в Telegram';

    public function handle()
{
    $this->info('Получение задач из внешнего API...');

    $response = Http::get('https://jsonplaceholder.typicode.com/todos');

    if ($response->failed()) {
        $this->error('Ошибка при получении задач.');
        return 1;
    }

    $tasks = collect($response->json())
        ->where('completed', false)
        ->where('userId', '<=', 5);

    if ($tasks->isEmpty()) {
        $this->info('Нет подходящих невыполненных задач.');
        return 0;
    }

    $this->info('Невыполненных задач: ' . $tasks->count());

    $users = User::where('subscribed', true)->get();

    if ($users->isEmpty()) {
        $this->warn('Нет активных пользователей.');
        return 0;
    }

    foreach ($users as $user) {
        if (!$user->telegram_id) {
            $this->warn("❌ У пользователя {$user->name} нет telegram_id. Пропускаем.");
            continue;
        }

        // Отправляем в очередь
        dispatch(new \App\Jobs\SendTelegramNotification($user, $tasks));
        $this->info("📤 Уведомление отправлено в очередь: {$user->name}");
    }

    return 0;
}


protected function sendTelegramMessage($chatId, $message)
{
    $token = env('TELEGRAM_BOT_TOKEN');

    $response = Http::post("https://api.telegram.org/bot{$token}/sendMessage", [
        'chat_id' => $chatId,
        'text' => $message,
        'parse_mode' => 'Markdown'
    ]);

    if ($response->failed()) {
       // $this->error("❌ Ошибка при отправке: " . $response->body());
    } else {
       // $this->info("📨 Ответ Telegram: " . $response->body());
    }
}

}
