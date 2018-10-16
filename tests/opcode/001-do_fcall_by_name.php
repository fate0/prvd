<?php

use PHPUnit\Framework\TestCase;


if (1 + 2) {
    function do_fcall_by_name_hello() {}
    function do_fcall_by_name_xtanzi() {}
}


final class do_fcall_by_name_test extends TestCase
{
    public function testWithTaint()
    {
        global $prvd_sentry_client;
        $prvd_sentry_client = new Dummy_Sentry_Client();

        $function = "do_fcall_by_name_hello";
        prvd_xmark($function);

        $function();
        $this->assertEquals(PRVD_TAINT_ENABLE, $prvd_sentry_client->captured);
    }

    public function testWithPayload()
    {
        global $prvd_sentry_client;
        $prvd_sentry_client = new Dummy_Sentry_Client();

        $function = "do_fcall_by_name_".PRVD_TANZI;

        $function();
        $this->assertEquals(true, $prvd_sentry_client->captured);
    }
}
