<?php

use PHPUnit\Framework\TestCase;


final class system_test extends TestCase
{
    public function testWithTaint()
    {
        global $prvd_sentry_client;
        $prvd_sentry_client = new Dummy_Sentry_Client();

        $str = 'hello';
        prvd_xmark($str);

        @system("id {$str}");
        $this->assertEquals(PRVD_TAINT_ENABLE, $prvd_sentry_client->captured);
    }


    public function testWithPayload()
    {
        global $prvd_sentry_client;
        $prvd_sentry_client = new Dummy_Sentry_Client();

        $str = TEST_PAYLOAD;

        @system("id {$str}");
        $this->assertEquals(true, $prvd_sentry_client->captured);
    }
}
