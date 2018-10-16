<?php

use PHPUnit\Framework\TestCase;


final class dir_test extends TestCase
{
    public function testWithTaint()
    {
        global $prvd_sentry_client;
        $prvd_sentry_client = new Dummy_Sentry_Client();

        $dir = 'example.txt';
        prvd_xmark($dir);

        @dir($dir);
        $this->assertEquals(PRVD_TAINT_ENABLE, $prvd_sentry_client->captured);
    }


    public function testWithPayload()
    {
        global $prvd_sentry_client;
        $prvd_sentry_client = new Dummy_Sentry_Client();

        $dir = TEST_PAYLOAD;

        @dir($dir);
        $this->assertEquals(true, $prvd_sentry_client->captured);
    }
}

