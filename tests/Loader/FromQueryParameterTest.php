<?php

namespace CheeCodes\LanguageDetection\Tests\Loader;

use CheeCodes\LanguageDetection\Loader\FromQueryParameter;
use Illuminate\Http\Request;
use Orchestra\Testbench\TestCase;

class FromQueryParameterTest extends TestCase
{
    /** @test */
    public function loads_the_language_from_a_query_parameter() {
        /** @var FromQueryParameter $fromQueryParameter */
        $fromQueryParameter = $this->app->make(FromQueryParameter::class);

        $request = Request::create('/', 'GET', ['lang' => 'de']);

        $this->assertSame('de', $fromQueryParameter->load($request, []));
    }

    /** @test */
    public function name_can_be_changed_by_config() {
        /** @var FromQueryParameter $fromQueryParameter */
        $fromQueryParameter = $this->app->make(FromQueryParameter::class);

        $request = Request::create('/', 'GET', ['language' => 'de']);

        $this->assertSame('de', $fromQueryParameter->load($request, ['query' => ['name' => 'language']]));
    }

    /** @test */
    public function returns_null_if_no_corresponding_query_parameter_is_found() {
        /** @var FromQueryParameter $fromQueryParameter */
        $fromQueryParameter = $this->app->make(FromQueryParameter::class);

        $request = Request::create('/');

        self::assertNull($fromQueryParameter->load($request, []));
    }

}
