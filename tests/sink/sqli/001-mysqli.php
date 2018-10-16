<?php

use PHPUnit\Framework\TestCase;

require_once("config.php");


final class mysqli_test extends TestCase
{
    private $mysqli;

    public function setUp()
    {
        $this->mysqli = new mysqli(MYSQL_DB_HOST, MYSQL_DB_USERNAME, MYSQL_DB_PASSWORD, MYSQL_DB_NAME);
    }

    public function tearDown()
    {
        $this->mysqli->close();
    }

    public function testQueryWithTaint()
    {
        global $prvd_sentry_client;
        $prvd_sentry_client = new Dummy_Sentry_Client();

        $id = "1";
        prvd_xmark($id);

        @$this->mysqli->query("SELECT user FROM user LIMIT ".$id);
        $this->assertEquals($prvd_sentry_client->captured, PRVD_TAINT_ENABLE);
    }

    public function testPrepareWithTaint()
    {
        global $prvd_sentry_client;
        $prvd_sentry_client = new Dummy_Sentry_Client();

        $id = "1";
        prvd_xmark($id);

        @$this->mysqli->prepare("SELECT user FROM user LIMIT ".$id);
        $this->assertEquals($prvd_sentry_client->captured, PRVD_TAINT_ENABLE);
    }

    public function testRealQueryWithTaint()
    {
        global $prvd_sentry_client;
        $prvd_sentry_client = new Dummy_Sentry_Client();

        $id = "1";
        prvd_xmark($id);

        @$this->mysqli->real_query("SELECT user FROM user LIMIT ".$id);
        $this->assertEquals(PRVD_TAINT_ENABLE, $prvd_sentry_client->captured);
    }

    public function testMultiQueryWithTaint()
    {
        global $prvd_sentry_client;
        $prvd_sentry_client = new Dummy_Sentry_Client();

        $id = "1";
        prvd_xmark($id);

        @$this->mysqli->multi_query("SELECT user FROM user LIMIT ".$id);
        $this->assertEquals(PRVD_TAINT_ENABLE, $prvd_sentry_client->captured);
    }


    public function testQueryWithPayload()
    {
        global $prvd_sentry_client;
        $prvd_sentry_client = new Dummy_Sentry_Client();

        $id = TEST_PAYLOAD;

        @$this->mysqli->query("SELECT user FROM user LIMIT ".$id);
        $this->assertEquals(true, $prvd_sentry_client->captured);
    }

    public function testPrepareWithPayload()
    {
        global $prvd_sentry_client;
        $prvd_sentry_client = new Dummy_Sentry_Client();

        $id = TEST_PAYLOAD;

        @$this->mysqli->prepare("SELECT user FROM user LIMIT ".$id);
        $this->assertEquals(true, $prvd_sentry_client->captured);
    }

    public function testRealQueryWithPayload()
    {
        global $prvd_sentry_client;
        $prvd_sentry_client = new Dummy_Sentry_Client();

        $id = TEST_PAYLOAD;

        @$this->mysqli->real_query("SELECT user FROM user LIMIT ".$id);
        $this->assertEquals(true, $prvd_sentry_client->captured);
    }

    public function testMultiQueryWithPayload()
    {
        global $prvd_sentry_client;
        $prvd_sentry_client = new Dummy_Sentry_Client();

        $id = TEST_PAYLOAD;

        @$this->mysqli->multi_query("SELECT user FROM user LIMIT ".$id);
        $this->assertEquals(true, $prvd_sentry_client->captured);
    }
}
