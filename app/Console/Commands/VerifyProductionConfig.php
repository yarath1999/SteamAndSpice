<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class VerifyProductionConfig extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:verify-production-config';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Validate production-critical environment configuration values';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $errors = [];
        $warnings = [];

        if (config('app.env') !== 'production') {
            $warnings[] = 'APP_ENV is not set to production.';
        }

        if ((bool) config('app.debug')) {
            $errors[] = 'APP_DEBUG must be false in production.';
        }

        $appUrl = (string) config('app.url');
        if ($appUrl === '' || str_starts_with($appUrl, 'http://localhost')) {
            $errors[] = 'APP_URL must be set to your public HTTPS URL.';
        }

        $dbConnection = (string) config('database.default');
        if ($dbConnection !== 'mysql') {
            $warnings[] = 'DB_CONNECTION is not mysql. This is fine for local development but usually not for EC2 production.';
        }

        $stripeKey = (string) config('services.stripe.key');
        $stripeSecret = (string) config('services.stripe.secret');

        if ($stripeKey === '' || $stripeSecret === '') {
            $errors[] = 'STRIPE_KEY and STRIPE_SECRET must both be set.';
        }

        if (str_contains($stripeKey, 'your_test_publishable_key') || str_contains($stripeSecret, 'your_test_secret_key')) {
            $errors[] = 'Stripe keys are still placeholders. Replace them with real Stripe test/live keys.';
        }

        if (!app()->environment('local')) {
            $sessionDriver = (string) config('session.driver');
            if ($sessionDriver === 'file') {
                $warnings[] = 'SESSION_DRIVER=file is acceptable for a single server but database/redis is recommended for resilience.';
            }
        }

        if (empty($errors) && empty($warnings)) {
            $this->info('Production configuration looks good.');

            return self::SUCCESS;
        }

        foreach ($warnings as $warning) {
            $this->warn('WARNING: ' . $warning);
        }

        foreach ($errors as $error) {
            $this->error('ERROR: ' . $error);
        }

        return empty($errors) ? self::SUCCESS : self::FAILURE;
    }
}
