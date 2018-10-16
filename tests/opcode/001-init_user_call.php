<?php

use PHPUnit\Framework\TestCase;


final class init_user_call_test extends TestCase
{
    public function testWithTaint()
    {
        global $prvd_sentry_client;
        $prvd_sentry_client = new Dummy_Sentry_Client();

        $method = "hello";
        prvd_xmark($method);

        @call_user_func(array("my_class", $method), "test");
        $this->assertEquals(PRVD_TAINT_ENABLE, $prvd_sentry_client->captured);
    }

    public function testWithPayload()
    {
        global $prvd_sentry_client;
        $prvd_sentry_client = new Dummy_Sentry_Client();

        $method = TEST_PAYLOAD;

        @call_user_func(array("my_class", $method), "test");
        $this->assertEquals(true, $prvd_sentry_client->captured);
    }
}



