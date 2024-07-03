<?php

namespace App\Console\Traits;

trait WebhookConfigurationTrait
{
    protected function setWebhookSecretInEnvironmentFile(string $webhookSecret, string $service): bool
    {
        $currentKey = $this->laravel['config']["services.$service.webhook"];

        if (strlen($currentKey) !== 0 && (!$this->confirmToProceed())) {
            return false;
        }

        $this->writeNewEnvironmentFileWith($webhookSecret, $service);

        return true;
    }

    protected function writeNewEnvironmentFileWith(string $webhookSecret, string $service): void
    {
        file_put_contents($this->laravel->environmentFilePath(), preg_replace(
            $this->secretReplacementPattern($service),
            strtoupper($service) . '_WEBHOOK=' . $webhookSecret,
            file_get_contents($this->laravel->environmentFilePath())
        ));
    }

    protected function secretReplacementPattern(string $service): string
    {
        $escaped = preg_quote('=' . $this->laravel['config']["services.$service.webhook"], '/');

        return "/^" . strtoupper($service) . "_WEBHOOK{$escaped}/m";
    }

    protected function makeWebhookUrl(string $service): string
    {
        $app_url = config('app.url');

        $this->comment('Your current app base url is: ' . $app_url);

        if (!$this->confirm("Do you want to use this url for your $service webhook?", true)) {
            $app_url = $this->ask('Please provide the base URL to use instead:');
        }

        return $app_url . '/' . strtolower($service) . '/webhook/' . config("services.$service.webhook");
    }
}
