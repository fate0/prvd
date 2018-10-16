<?php

use PHPUnit\Framework\TestCase;


final class proc_open_test extends TestCase
{
    public function testWithTaint()
    {
        global $prvd_sentry_client;
        $prvd_sentry_client = new Dummy_Sentry_Client();

        $str = 'fate0';
        prvd_xmark($str);

        $proc = proc_open("id {$str}",
            array(
                array("pipe","r"),
                array("pipe","w"),
                array("pipe","w")
            ),
            $pipes);

        $this->assertEquals(true, isset($pipes[1]));
        $this->assertEquals(PRVD_TAINT_ENABLE, $prvd_sentry_client->captured);
    }


    public function testWithPayload()
    {
        global $prvd_sentry_client;
        $prvd_sentry_client = new Dummy_Sentry_Client();

        $str = TEST_PAYLOAD;

        $proc = proc_open("id {$str}",
            array(
                array("pipe","r"),
                array("pipe","w"),
                array("pipe","w")
            ),
            $pipes);

        $this->assertEquals(true, isset($pipes[1]));
        $this->assertEquals(true, $prvd_sentry_client->captured);
    }
}

