<?php

namespace App\Console\Commands;

use App\Console\Traits\WebhookConfigurationTrait;
use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class ConfigureTrelloWebhook extends Command
{
    use ConfirmableTrait;
    use WebhookConfigurationTrait;

    protected $signature = 'trello:configure-webhook';
    protected $description = 'Configures the Trello Webhook.';

    public function handle(): int
    {
        $webhookSecret = Str::random(45);

        if (!$this->setWebhookSecretInEnvironmentFile($webhookSecret, 'trello')) {
            $this->error('An Error occurred');
            return Command::FAILURE;
        }

        $this->laravel['config']['services.trello.webhook'] = $webhookSecret;

        $webhookUrl = $this->makeWebhookUrl('trello');

        $this->configureTrelloBotWith($webhookUrl);

        return Command::SUCCESS;
    }

    protected function configureTrelloBotWith(string $webhookUrl): void
    {
        $trelloBaseApiUrl = config('services.trello.base_uri');
        $trelloApiToken = config('services.trello.token');
        $apiKey = config('services.trello.key');
        $boardId = config('services.trello.board_id');
        $url = "{$trelloBaseApiUrl}/tokens/{$trelloApiToken}/webhooks?key={$apiKey}";

        $response = Http::post($url, [
            'callbackURL' => $webhookUrl,
            'idModel' => $boardId,
            'description' => 'Trello Webhook',
        ]);

        if ($response->failed()) {
            $this->error($response->body());
            return;
        }

        $this->info("The Trello Bot Webhook was created successfully:\n" . $response->json()['callbackURL']);
    }
}
