<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use OpenApi\Annotations as OA;

class TelegramBotController extends Controller
{
    /**
     * @OA\PathItem(
     *     path="/telegram/webhook"
     * )
     */
    public function handle(Request $request)
    {
        Log::info('Telegram webhook:', $request->all());

        $data = $request->all();

        if (!isset($data['message']['text']) || !isset($data['message']['from'])) {
            return response('OK');
        }

        $text = $data['message']['text'];
        $user = $data['message']['from'];

        $telegramId = $user['id'];
        $name = $user['first_name'] ?? 'Unknown';

        if ($text === '/start') {
            User::updateOrCreate(
                ['telegram_id' => $telegramId],
                ['name' => $name, 'telegram_id' => $telegramId, 'subscribed' => true]
            );
            $this->sendMessage($telegramId, '✅ Вы подписаны!');
        } elseif ($text === '/stop') {
            User::where('telegram_id', $telegramId)->update(['subscribed' => false]);
            $this->sendMessage($telegramId, '❌ Вы отписаны.');
        }

        return response('OK');
    }

    private function sendMessage($chatId, $text)
    {
        $token = env('TELEGRAM_BOT_TOKEN');
        $url = "https://api.telegram.org/bot{$token}/sendMessage";

        $post = [
            'chat_id' => $chatId,
            'text' => $text
        ];

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $post,
        ]);
        curl_exec($ch);
        curl_close($ch);
    }
}
