<?php

use PHPUnit\Framework\TestCase;


final class printf_test extends TestCase
{
    public function test()
    {
        global $prvd_sentry_client;
        $prvd_sentry_client = new Dummy_Sentry_Client();

        $url = "http://www.fatezero.org";
        prvd_xmark($url);

        $this->expectOutputString("hello ".$url);
        printf("hello %s", $url);

        $this->assertEquals(PRVD_TAINT_ENABLE, $prvd_sentry_client->captured);
    }
}
