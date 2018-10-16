<?php

use PHPUnit\Framework\TestCase;

require_once("config.php");


final class SQLite3_test extends TestCase
{
    private $db;

    public function setUp()
    {
        $this->db = new SQLite3(SQLITE_DB);
        @$this->db->exec('CREATE TABLE IF NOT EXISTS bar (bar STRING)');
    }

    public function tearDown()
    {
        $this->db->close();
    }

    public function testExecWithTaint()
    {
        global $prvd_sentry_client;
        $prvd_sentry_client = new Dummy_Sentry_Client();

        $id = "1";
        prvd_xmark($id);

        @$this->db->exec("SELECT name FROM sqlite_master LIMIT ".$id);
        $this->assertEquals(PRVD_TAINT_ENABLE, $prvd_sentry_client->captured);
    }

    public function testQueryWithTaint()
    {
        global $prvd_sentry_client;
        $prvd_sentry_client = new Dummy_Sentry_Client();

        $id = "1";
        prvd_xmark($id);

        @$this->db->query("SELECT name FROM sqlite_master LIMIT ".$id);
        $this->assertEquals(PRVD_TAINT_ENABLE, $prvd_sentry_client->captured);
    }

    public function testPrepareWithTaint()
    {
        global $prvd_sentry_client;
        $prvd_sentry_client = new Dummy_Sentry_Client();

        $id = "1";
        prvd_xmark($id);

        @$this->db->prepare("SELECT name FROM sqlite_master LIMIT ".$id);
        $this->assertEquals(PRVD_TAINT_ENABLE, $prvd_sentry_client->captured);
    }

    public function testQuerySingleWithTaint()
    {
        global $prvd_sentry_client;
        $prvd_sentry_client = new Dummy_Sentry_Client();

        $id = "1";
        prvd_xmark($id);

        @$this->db->querySingle("SELECT name FROM sqlite_master LIMIT ".$id, true);
        $this->assertEquals(PRVD_TAINT_ENABLE, $prvd_sentry_client->captured);
    }

    public function testExecWithPayload()
    {
        global $prvd_sentry_client;
        $prvd_sentry_client = new Dummy_Sentry_Client();

        $id = TEST_PAYLOAD;

        @$this->db->exec("SELECT name FROM sqlite_master LIMIT ".$id);
        $this->assertEquals(true, $prvd_sentry_client->captured);
    }

    public function testQueryWithPayload()
    {
        global $prvd_sentry_client;
        $prvd_sentry_client = new Dummy_Sentry_Client();

        $id = TEST_PAYLOAD;

        @$this->db->query("SELECT name FROM sqlite_master LIMIT ".$id);
        $this->assertEquals(true, $prvd_sentry_client->captured);
    }

    public function testPrepareWithPayload()
    {
        global $prvd_sentry_client;
        $prvd_sentry_client = new Dummy_Sentry_Client();

        $id = TEST_PAYLOAD;

        @$this->db->prepare("SELECT name FROM sqlite_master LIMIT ".$id);
        $this->assertEquals(true, $prvd_sentry_client->captured);
    }

    public function testQuerySingleWithPayload()
    {
        global $prvd_sentry_client;
        $prvd_sentry_client = new Dummy_Sentry_Client();

        $id = TEST_PAYLOAD;

        @$this->db->querySingle("SELECT name FROM sqlite_master LIMIT ".$id);
        $this->assertEquals(true, $prvd_sentry_client->captured);
    }
}
