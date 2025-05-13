<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;

class TelegramBotTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_save_user_on_start_command()
    {
        // Имитируем запрос на команду /start
        $response = $this->post('/telegram/start', [
            'telegram_id' => '12345',
            'name' => 'John Doe'
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('users', [
            'telegram_id' => '12345',
            'name' => 'John Doe',
            'subscribed' => true
        ]);
    }

    /** @test */
    public function it_sends_task_notifications_to_subscribed_users()
    {
        // Создаем активного пользователя
        $user = User::factory()->create([
            'telegram_id' => '12345',
            'subscribed' => true
        ]);

        // Проверяем выполнение консольной команды
        $this->artisan('notify:tasks')
             ->assertExitCode(0);

        // Проверяем, что уведомление было отправлено
        // Пример для очереди задач, можно проверять или через логи, или через другую логику
        $this->assertDatabaseHas('job_queues', [
            'user_id' => $user->id,
            'status' => 'queued'
        ]);
    }
}
