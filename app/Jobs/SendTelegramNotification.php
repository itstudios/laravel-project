<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class SendTelegramNotification implements ShouldQueue
{
    use Dispatchable, Queueable, InteractsWithQueue, SerializesModels;

    protected $user;
    protected $tasks;

    public function __construct(User $user, $tasks)
    {
        $this->user = $user;
        $this->tasks = $tasks;
    }

    public function handle()
    {
        $message = "Привет, {$this->user->name}!\nВот список текущих задач:\n";

        foreach ($this->tasks->take(5) as $task) {
            $message .= "- [ID {$task['id']}] {$task['title']}\n";
        }

        $this->sendTelegramMessage($this->user->telegram_id, $message);
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
            \Log::error("Ошибка при отправке уведомления в Telegram: " . $response->body());
        }
    }
}

