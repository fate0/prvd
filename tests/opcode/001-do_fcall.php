<?php

use PHPUnit\Framework\TestCase;


final class do_fcall_test extends TestCase
{
    public function test()
    {
        global $prvd_sentry_client;
        $prvd_sentry_client = new Dummy_Sentry_Client();

        $stripos = "stripos";
        prvd_xmark($stripos);
        $stripos("test", "t");

        $this->assertEquals(PRVD_TAINT_ENABLE, $prvd_sentry_client->captured);
    }
}
