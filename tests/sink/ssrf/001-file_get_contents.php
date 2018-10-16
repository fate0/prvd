<?php

use PHPUnit\Framework\TestCase;


final class file_get_contents_ssrf_test extends TestCase
{
    public function testWithTaint()
    {
        global $prvd_sentry_client;
        $prvd_sentry_client = new Dummy_Sentry_Client();

        $url = 'http://www.fatezero.org';
        prvd_xmark($url);

        @file_get_contents($url);
        $this->assertEquals(PRVD_TAINT_ENABLE, $prvd_sentry_client->captured);
    }


    public function testWithPayload()
    {
        global $prvd_sentry_client;
        $prvd_sentry_client = new Dummy_Sentry_Client();

        $url = TEST_PAYLOAD;

        @file_get_contents($url);
        $this->assertEquals(true, $prvd_sentry_client->captured);
    }
}



