<?php

use PHPUnit\Framework\TestCase;

require_once("config.php");


final class mysqli_query_test extends TestCase
{
    private $link;

    public function setUp()
    {
        $this->link = mysqli_connect(MYSQL_DB_HOST, MYSQL_DB_USERNAME, MYSQL_DB_PASSWORD, MYSQL_DB_NAME);
    }

    public function tearDown()
    {
        mysqli_close($this->link);
    }

    public function testWithTaint()
    {
        global $prvd_sentry_client;
        $prvd_sentry_client = new Dummy_Sentry_Client();

        $id = "1";
        prvd_xmark($id);

        @mysqli_query($this->link, "SELECT user FROM user LIMIT ".$id);
        $this->assertEquals(PRVD_TAINT_ENABLE, $prvd_sentry_client->captured);
    }

    public function testWithPayload()
    {
        global $prvd_sentry_client;
        $prvd_sentry_client = new Dummy_Sentry_Client();

        $id = TEST_PAYLOAD;

        @mysqli_query($this->link, "SELECT user FROM user LIMIT ".$id);
        $this->assertEquals(true, $prvd_sentry_client->captured);
    }
}

