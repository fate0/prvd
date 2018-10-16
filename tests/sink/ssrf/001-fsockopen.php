<?php

use PHPUnit\Framework\TestCase;


final class fsockopen_test extends TestCase
{
    public function testWithTaint()
    {
        global $prvd_sentry_client;
        $prvd_sentry_client = new Dummy_Sentry_Client();

        $host = 'www.fatezero.org';
        prvd_xmark($host);

        @fsockopen($host, 80, $errno, $errstr, 30);
        $this->assertEquals(PRVD_TAINT_ENABLE, $prvd_sentry_client->captured);
    }


    public function testWithPayload()
    {
        global $prvd_sentry_client;
        $prvd_sentry_client = new Dummy_Sentry_Client();

        $host = TEST_PAYLOAD;

        @fsockopen($host, 80, $errno, $errstr, 30);
        $this->assertEquals(true, $prvd_sentry_client->captured);
    }
}

