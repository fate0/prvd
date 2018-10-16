<?php

use PHPUnit\Framework\TestCase;


final class highlight_file_test extends TestCase
{
    public function testWithTaint()
    {
        global $prvd_sentry_client;
        $prvd_sentry_client = new Dummy_Sentry_Client();

        $file = 'example.txt';
        prvd_xmark($file);

        @highlight_file($file, true);
        $this->assertEquals(PRVD_TAINT_ENABLE, $prvd_sentry_client->captured);
    }


    public function testWithPayload()
    {
        global $prvd_sentry_client;
        $prvd_sentry_client = new Dummy_Sentry_Client();

        $file = TEST_PAYLOAD;

        @highlight_file($file, true);
        $this->assertEquals(true, $prvd_sentry_client->captured);
    }
}
