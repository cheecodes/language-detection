<?php

namespace CheeCodes\LanguageDetection\Service;

use CheeCodes\LanguageDetection\Loader\LoaderContract;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use InvalidArgumentException;

class LoadLanguage
{

    public function __invoke(Request $request, string $group = 'default') {
        $config = config('language-detection', []);

        /** @var array $loaders */
        $loaders = Arr::get($config, 'loaders.' . $group, []);

        // Ensuring the default is within the allowed languages
        $allowed = array_unique($config['allowed'] + [$config['default']]);

        foreach ($loaders as $loaderClass) {
            $loaderInstance = app()->get($loaderClass);

            if ( !$loaderInstance instanceof LoaderContract) {
                throw new InvalidArgumentException(
                    sprintf('"%s" is not an instance of "%s"', $loaderClass, LoaderContract::class),
                    1580318546
                );
            }

            $language = $loaderInstance->load($request, $config);

            if (in_array($language, $allowed, true)) {
                return $language;
            }
        }

        return $config['default'];
    }
}
