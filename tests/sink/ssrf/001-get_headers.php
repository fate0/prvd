<?php

use PHPUnit\Framework\TestCase;


final class get_headers_test extends TestCase
{
    public function testWithTaint()
    {
        global $prvd_sentry_client;
        $prvd_sentry_client = new Dummy_Sentry_Client();

        $url = "http://www.fatezero.org";
        prvd_xmark($url);

        @get_headers($url);
        $this->assertEquals(PRVD_TAINT_ENABLE, $prvd_sentry_client->captured);
    }


    public function testWithPayload()
    {
        global $prvd_sentry_client;
        $prvd_sentry_client = new Dummy_Sentry_Client();

        $url = TEST_PAYLOAD;

        @get_headers($url);
        $this->assertEquals(true, $prvd_sentry_client->captured);
    }
}
