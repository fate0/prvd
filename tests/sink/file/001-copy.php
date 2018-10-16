<?php

use PHPUnit\Framework\TestCase;


final class copy_test extends TestCase
{
    public function testWithTaint()
    {
        global $prvd_sentry_client;
        $prvd_sentry_client = new Dummy_Sentry_Client();

        $file = 'example.txt';
        $new_file = 'example.txt.bak';
        prvd_xmark($file);

        @copy($file, $new_file);
        $this->assertEquals(PRVD_TAINT_ENABLE, $prvd_sentry_client->captured);
    }


    public function testWithPayload()
    {
        global $prvd_sentry_client;
        $prvd_sentry_client = new Dummy_Sentry_Client();

        $file = TEST_PAYLOAD;
        $new_file = 'example.txt.bak';

        @copy($file, $new_file);
        $this->assertEquals(true, $prvd_sentry_client->captured);
    }
}

