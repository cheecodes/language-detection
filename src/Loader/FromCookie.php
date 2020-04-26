<?php

namespace CheeCodes\LanguageDetection\Loader;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class FromCookie implements LoaderContract
{

    public function load(Request $request, array $config): ?string {
        return $request->cookie($this->cookieName($config), null);
    }

    /**
     * @param array $config
     *
     * @return mixed|string
     */
    public function cookieName(array $config) {
        return Arr::get($config, 'cookie.name', 'lang');
    }
}
