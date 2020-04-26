<?php

namespace CheeCodes\LanguageDetection\Loader;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class FromSession implements LoaderContract
{

    public function load(Request $request, array $config): ?string {
        return $request->session()->get($this->getKeyName($config), null);
    }

    private function getKeyName(array $config): string {
        return Arr::get($config, 'session.name', 'lang');
    }
}
