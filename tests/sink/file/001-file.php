<?php

use PHPUnit\Framework\TestCase;


final class file_test extends TestCase
{
    public function testWithTaint()
    {
        global $prvd_sentry_client;
        $prvd_sentry_client = new Dummy_Sentry_Client();

        $file = 'example.txt';
        prvd_xmark($file);

        @file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $this->assertEquals(PRVD_TAINT_ENABLE, $prvd_sentry_client->captured);
    }


    public function testWithPayload()
    {
        global $prvd_sentry_client;
        $prvd_sentry_client = new Dummy_Sentry_Client();

        $file = TEST_PAYLOAD;

        @file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $this->assertEquals(true, $prvd_sentry_client->captured);
    }
}
