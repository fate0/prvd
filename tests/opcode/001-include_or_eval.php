<?php

use PHPUnit\Framework\TestCase;


final class include_or_eval_test extends TestCase
{
    public function testIncludeWithTaint()
    {
        global $prvd_sentry_client;
        $prvd_sentry_client = new Dummy_Sentry_Client();

        $path = "test";
        prvd_xmark($path);

        @include $path;
        $this->assertEquals(PRVD_TAINT_ENABLE, $prvd_sentry_client->captured);
    }

    public function testEvalWithTaint()
    {
        global $prvd_sentry_client;
        $prvd_sentry_client = new Dummy_Sentry_Client();

        $code = "\$d = 'test';";
        prvd_xmark($code);

        eval($code);
        $this->assertEquals(PRVD_TAINT_ENABLE, $prvd_sentry_client->captured);
    }

    public function testIncludeWithPayload()
    {
        global $prvd_sentry_client;
        $prvd_sentry_client = new Dummy_Sentry_Client();

        $path = TEST_PAYLOAD;

        @include $path;
        $this->assertEquals(true, $prvd_sentry_client->captured);
    }

    public function testEvalWithPayload()
    {
        global $prvd_sentry_client;
        $prvd_sentry_client = new Dummy_Sentry_Client();

        eval("\$xtanzi=1;");
        $this->assertEquals(true, $prvd_sentry_client->captured);
    }
}

