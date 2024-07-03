<?php

namespace App\Console\Commands;

use App\Console\Traits\WebhookConfigurationTrait;
use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class ConfigureTelegramWebhook extends Command
{
    use ConfirmableTrait;
    use WebhookConfigurationTrait;

    protected $signature = 'telegram:configure-webhook';
    protected $description = 'Configures the Telegram Webhook.';

    public function handle(): int
    {
        $webhookSecret = Str::random(45);

        if (!$this->setWebhookSecretInEnvironmentFile($webhookSecret, 'telegram')) {
            $this->error('An Error occurred');
            return Command::FAILURE;
        }

        $this->laravel['config']['services.telegram.webhook'] = $webhookSecret;

        $webhookUrl = $this->makeWebhookUrl('telegram');

        $this->configureTelegramBotWith($webhookUrl);

        return Command::SUCCESS;
    }

    protected function configureTelegramBotWith(string $webhookUrl): void
    {
        $telegramBaseApiUrl = config('services.telegram.base_uri');
        $telegramApiToken = config('services.telegram.token');
        $allowedUpdates = config('services.telegram.allowed_updates', '["message"]');

        $response = Http::post($telegramBaseApiUrl . $telegramApiToken . '/setWebhook', [
            'url' => $webhookUrl,
            'allowed_updates' => $allowedUpdates,
        ]);

        if ($response->failed()) {
            $this->error($response->json()['description']);
            return;
        }

        $response = Http::get($telegramBaseApiUrl . $telegramApiToken . '/getWebhookInfo');

        if ($response->failed()) {
            $this->error($response->json()['description']);
            return;
        }

        $this->info("The Telegram Bot Webhook was created successfully:\n" . $response->json()['result']['url']);
    }
}
