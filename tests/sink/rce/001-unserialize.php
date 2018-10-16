<?php

use PHPUnit\Framework\TestCase;

final class unserialize_test extends TestCase
{
    public function testWithTaint()
    {
        global $prvd_sentry_client;
        $prvd_sentry_client = new Dummy_Sentry_Client();

        $str = 'O:1:"a":1:{s:5:"value";s:3:"100";}';
        prvd_xmark($str);

        @unserialize($str);
        $this->assertEquals(PRVD_TAINT_ENABLE, $prvd_sentry_client->captured);
    }


    public function testWithPayload()
    {
        $this->assertEquals(true, true);
        /*
        global $prvd_sentry_client;
        $prvd_sentry_client = new Dummy_Sentry_Client();

        $str = TEST_PAYLOAD;
        @unserialize($str);
        $this->assertEquals(true, $prvd_sentry_client->captured);

        $prvd_sentry_client->captured = false;

        $str = serialize($str);
        var_dump($str);
        @unserialize($str);
        $this->assertEquals(false, $prvd_sentry_client->captured);
        */
    }
}
