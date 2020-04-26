<?php

namespace CheeCodes\LanguageDetection\Loader;

use Illuminate\Http\Request;

/**
 * Contract for language loaders
 *
 * @package CheeCodes\LanguageDetection\Loader
 */
interface LoaderContract
{
    public function load(Request $request, array $config): ?string;
}
