<?php

namespace CheeCodes\LanguageDetection\Loader;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class FromAcceptLanguage implements LoaderContract
{

    public function load(Request $request, array $config): ?string {
        $allowedLanguages = Arr::get($config, 'allowed', ['en']);
        $strict           = Arr::get($config, 'accept-language.strict', true);

        return $this->getLanguageFromRequest($request, $allowedLanguages, $strict);
    }

    /**
     * Extract preferred language from headers
     *
     * @param \Illuminate\Http\Request $request
     * @param array                    $allowedLanguages
     * @param bool                     $strict
     *
     * @return string result language
     */
    protected function getLanguageFromRequest(Request $request, array $allowedLanguages, $strict = true): ?string {
        $acceptHeader = $request->header('accept-language');

        if (empty($acceptHeader)) {
            return null;
        }

        $acceptedLanguages = preg_split('/,\s*/', $acceptHeader);

        $currentLanguage   = null;
        $currentPreference = 0;

        foreach ($acceptedLanguages as $language) {
            [$matches, $result] = $this->extractLanguage($language);

            if ( !$result) {
                continue;
            }

            $langCodes = explode('-', $matches[1]);

            $quality = $this->getMatchQuality($matches);

            while (count($langCodes)) {
                $currentCode = strtolower(implode('-', $langCodes));
                if ($quality > $currentPreference && in_array($currentCode, $allowedLanguages, true)) {
                    $currentLanguage   = $currentCode;
                    $currentPreference = $quality;

                    break;
                }
                if ($strict) {
                    break;
                }
                array_pop($langCodes);
            }
        }

        return $currentLanguage;
    }

    /**
     * @param string $language
     *
     * @return array
     */
    protected function extractLanguage(string $language): array {
        $result = preg_match(
            '/^([a-z]{1,8}(?:-[a-z]{1,8})*)(?:;\s*q=(0(?:\.[0-9]{1,3})?|1(?:\.0{1,3})?))?$/i',
            $language,
            $matches
        );

        return [$matches, $result];
    }

    /**
     * @param $matches
     *
     * @return float
     */
    protected function getMatchQuality($matches): float {
        return isset($matches[2])
            ? (float)$matches[2]
            : 1.0;
    }
}
