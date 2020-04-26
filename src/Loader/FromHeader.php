<?php

namespace CheeCodes\LanguageDetection\Loader;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class FromHeader implements LoaderContract
{

    public function load(Request $request, array $config): ?string {
        return $request->header($this->headerName($config), null);
    }

    private function headerName(array $config): string {
        return Arr::get($config, 'header.name', 'x-lang');
    }
}
