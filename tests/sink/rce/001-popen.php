<?php

use PHPUnit\Framework\TestCase;


final class popen_test extends TestCase
{
    public function testWithTaint()
    {
        global $prvd_sentry_client;
        $prvd_sentry_client = new Dummy_Sentry_Client();

        $str = 'fate0';
        prvd_xmark($str);

        @popen("id {$str}", "r");
        $this->assertEquals(PRVD_TAINT_ENABLE, $prvd_sentry_client->captured);
    }


    public function testWithPayload()
    {
        global $prvd_sentry_client;
        $prvd_sentry_client = new Dummy_Sentry_Client();

        $str = TEST_PAYLOAD;

        @popen("id {$str}", "r");
        $this->assertEquals(true, $prvd_sentry_client->captured);
    }
}
