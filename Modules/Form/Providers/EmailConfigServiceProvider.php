<?php

namespace Modules\Form\Providers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Modules\Form\Entities\EmailConfig;

class EmailConfigServiceProvider extends ServiceProvider
{
    protected $mailerConfigs = [
        'smtp' => [
            'transport' => 'smtp',
            'host' => 'MAIL_HOST',
            'port' => 'MAIL_PORT',
            'encryption' => 'MAIL_ENCRYPTION',
            'username' => 'MAIL_USERNAME',
            'password' => 'MAIL_PASSWORD',
        ],
        'mailgun' => [
            'transport' => 'mailgun',
            'domain' => 'MAILGUN_DOMAIN',
            'secret' => 'MAILGUN_SECRET',
            'endpoint' => 'MAILGUN_ENDPOINT',
        ],
        'ses' => [
            'transport' => 'ses',
            'key' => 'AWS_ACCESS_KEY_ID',
            'secret' => 'AWS_SECRET_ACCESS_KEY',
            'region' => 'AWS_DEFAULT_REGION',
        ],
        'sendgrid' => [
            'transport' => 'sendgrid',
            'api_key' => 'SENDGRID_API_KEY',
        ],
    ];

    public function boot()
    {
        $this->app->booted(function () {
            $this->configureEmail();
        });
    }

    protected function configureEmail()
    {
        try {
            if (DB::connection()->getDatabaseName()) {
                $emailConfig = EmailConfig::where('is_default', true)->first();

                if ($emailConfig) {
                    $mailerType = $emailConfig->mailer_type;
                    $configurations = $emailConfig->configurations;

                    if (!is_array($configurations)) {
                        $configurations = json_decode($configurations, true);
                        if (!is_array($configurations)) {
                            throw new \Exception('Invalid configurations data');
                        }
                    }

                    Config::set('mail.default', $mailerType);
                    $this->configureMailer($mailerType, $configurations);

                    Config::set('mail.from.address', $configurations['MAIL_FROM_ADDRESS'] ?? env('MAIL_FROM_ADDRESS'));
                    Config::set('mail.from.name', $configurations['MAIL_FROM_NAME'] ?? env('MAIL_FROM_NAME'));
                }
            }
        } catch (\Exception $e) {
            \Log::error('Failed to configure email: ' . $e->getMessage());
        }
    }

    protected function configureMailer($mailerType, $configurations)
    {
        if (!isset($this->mailerConfigs[$mailerType])) {
            throw new \Exception("Unsupported mailer type: {$mailerType}");
        }

        $mailerConfig = $this->mailerConfigs[$mailerType];
        $newConfig = [];

        foreach ($mailerConfig as $key => $envKey) {
            if ($key === 'transport') {
                $newConfig[$key] = $mailerType;
            } elseif (isset($configurations[$envKey])) {
                $newConfig[$key] = $configurations[$envKey];
            } elseif (env($envKey) !== null) {
                $newConfig[$key] = env($envKey);
            }
        }

        Config::set("mail.mailers.{$mailerType}", $newConfig);
    }

    public function register()
    {
        //
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
}
