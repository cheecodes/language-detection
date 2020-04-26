<?php

namespace CheeCodes\LanguageDetection\Tests\Loader;

use CheeCodes\LanguageDetection\Loader\FromSession;
use Illuminate\Http\Request;
use Orchestra\Testbench\TestCase;

class FromSessionTest extends TestCase
{
    /** @test */
    public function loads_the_language_from_the_session() {
        /** @var $fromSession FromSession */
        $fromSession = $this->app->make(FromSession::class);

        $request = $this->prepareRequest(['lang' => 'de']);

        $this->assertSame('de', $fromSession->load($request, []));
    }

    /**
     * @return \Illuminate\Http\Request
     */
    protected function prepareRequest($data = null): Request {
        $session = session();

        if (is_array($data)) {
            $session->put($data);
        }

        $request = Request::create('/');
        $request->setLaravelSession($session);

        return $request;
    }

    /** @test */
    public function the_name_can_be_changed_by_config() {
        /** @var $fromSession FromSession */
        $fromSession = $this->app->make(FromSession::class);

        $request = $this->prepareRequest(['language' => 'de']);

        $this->assertSame('de', $fromSession->load($request, ['session' => ['name' => 'language']]));
    }

    /** @test */
    public function returns_null_if_no_matching_entry_is_found() {
        /** @var $fromSession FromSession */
        $fromSession = $this->app->make(FromSession::class);

        $request = $this->prepareRequest();

        $this->assertNull($fromSession->load($request, []));
    }
}
