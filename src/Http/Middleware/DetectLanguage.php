<?php

namespace CheeCodes\LanguageDetection\Http\Middleware;

use CheeCodes\LanguageDetection\Service\LoadLanguage;
use Closure;
use Illuminate\Support\Facades\Config;

class DetectLanguage
{

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @param string                   $group
     *
     * @return mixed
     */
    public function handle($request, Closure $next, ?string $group = 'default') {
        /** @var LoadLanguage $loadLanguage */
        $loadLanguage = app()->get(LoadLanguage::class);

        Config::set('app.locale', $loadLanguage($request, $group ?? 'default'));

        return $next($request);
    }

}
