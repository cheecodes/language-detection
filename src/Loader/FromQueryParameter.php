<?php

namespace CheeCodes\LanguageDetection\Loader;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class FromQueryParameter implements LoaderContract
{

    public function load(Request $request, array $config): ?string {
        return $request->query($this->parameterName($config), null);
    }

    /**
     * @param array $config
     *
     * @return mixed|string
     */
    public function parameterName(array $config) {
        return Arr::get($config, 'query.name', 'lang');
    }
}
