<?php

namespace App\Providers;

use App\View\Html\Sanitizer;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\HtmlSanitizer\HtmlSanitizer;
use Symfony\Component\HtmlSanitizer\HtmlSanitizerConfig;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->app->scoped(Sanitizer::class, function () {
            return new Sanitizer(new HtmlSanitizer(
                (new HtmlSanitizerConfig)
                    ->allowSafeElements()
                    ->allowAttribute('class', '*')
            ));
        });
        Event::listen(function (\SocialiteProviders\Manager\SocialiteWasCalled $event) {
            $event->extendSocialite('saml2', \SocialiteProviders\Saml2\Provider::class);
        });
    }
}
