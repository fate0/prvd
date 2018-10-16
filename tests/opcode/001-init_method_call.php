<?php

use PHPUnit\Framework\TestCase;


class init_method_call_example {
    public function __call($name, $arguments) {}
    public function hello() {}
    public function xtanzi() {}
}


final class init_method_call_test extends TestCase
{
    public function testWithTaint()
    {
        global $prvd_sentry_client;
        $prvd_sentry_client = new Dummy_Sentry_Client();

        $c = new init_method_call_example();
        $method = "hello";
        prvd_xmark($method);

        $c->$method();
        $this->assertEquals(PRVD_TAINT_ENABLE, $prvd_sentry_client->captured);
    }

    public function testWithPayload()
    {
        global $prvd_sentry_client;
        $prvd_sentry_client = new Dummy_Sentry_Client();

        $c = new init_method_call_example();
        $method = TEST_PAYLOAD;

        $c->$method();
        $this->assertEquals(true, $prvd_sentry_client->captured);
    }
}



