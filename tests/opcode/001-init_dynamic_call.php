<?php

use PHPUnit\Framework\TestCase;


function init_dynamic_call_hello() {}
function init_dynamic_call_xtanzi() {}


final class init_dynamic_call_test extends TestCase
{
    public function testWithTaint()
    {
        global $prvd_sentry_client;
        $prvd_sentry_client = new Dummy_Sentry_Client();

        $function = "init_dynamic_call_hello";
        prvd_xmark($function);

        $function();
        $this->assertEquals(PRVD_TAINT_ENABLE, $prvd_sentry_client->captured);
    }

    public function testWithPayload()
    {
        global $prvd_sentry_client;
        $prvd_sentry_client = new Dummy_Sentry_Client();

        $function = "init_dynamic_call_".PRVD_TANZI;

        $function();
        $this->assertEquals(true, $prvd_sentry_client->captured);
    }
}



