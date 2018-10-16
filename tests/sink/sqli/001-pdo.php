<?php

use PHPUnit\Framework\TestCase;

require_once("config.php");


final class PDO_test extends TestCase
{
    private $pgsql;
    private $sqlite;
    private $mysql;

    public function setUp()
    {
        $this->pgsql = new PDO("pgsql:dbname=".PG_DB_NAME.";host=".PG_DB_HOST, PG_DB_USERNAME, PG_DB_PASSWORD);
        $this->mysql = new PDO("mysql:dbname=".MYSQL_DB_NAME.';host='.MYSQL_DB_HOST, MYSQL_DB_USERNAME, MYSQL_DB_PASSWORD);
        $this->sqlite = new PDO("sqlite:".SQLITE_DB);
    }

    public function testQueryWithTaint()
    {
        global $prvd_sentry_client;
        $prvd_sentry_client = new Dummy_Sentry_Client();

        $id = "1";
        prvd_xmark($id);

        $prvd_sentry_client->captured = false;
        $this->pgsql->query("SELECT user FROM pg_user LIMIT ".$id);
        $this->assertEquals(PRVD_TAINT_ENABLE, $prvd_sentry_client->captured);

        $prvd_sentry_client->captured = false;
        $this->mysql->query("SELECT user from user LIMIT ".$id);
        $this->assertEquals(PRVD_TAINT_ENABLE, $prvd_sentry_client->captured);

        $prvd_sentry_client->captured = false;
        $this->sqlite->query("SELECT name from sqlite_master LIMIT ".$id);
        $this->assertEquals(PRVD_TAINT_ENABLE, $prvd_sentry_client->captured);
    }

    public function testPrepareWithTaint()
    {
        global $prvd_sentry_client;
        $prvd_sentry_client = new Dummy_Sentry_Client();

        $id = "1";
        prvd_xmark($id);

        $prvd_sentry_client->captured = false;
        $this->pgsql->prepare("SELECT user FROM pg_user LIMIT ".$id);
        $this->assertEquals(PRVD_TAINT_ENABLE, $prvd_sentry_client->captured);

        $prvd_sentry_client->captured = false;
        $this->mysql->prepare("SELECT user from user LIMIT ".$id);
        $this->assertEquals(PRVD_TAINT_ENABLE, $prvd_sentry_client->captured);

        $prvd_sentry_client->captured = false;
        $this->sqlite->prepare("SELECT name from sqlite_master LIMIT ".$id);
        $this->assertEquals(PRVD_TAINT_ENABLE, $prvd_sentry_client->captured);
    }

    public function testExecWithTaint()
    {
        global $prvd_sentry_client;
        $prvd_sentry_client = new Dummy_Sentry_Client();

        $id = "1";
        prvd_xmark($id);

        $prvd_sentry_client->captured = false;
        $this->pgsql->exec("SELECT user FROM pg_user LIMIT ".$id);
        $this->assertEquals(PRVD_TAINT_ENABLE, $prvd_sentry_client->captured);

        $prvd_sentry_client->captured = false;
        $this->mysql->exec("SELECT user FROM user LIMIT ".$id);
        $this->assertEquals(PRVD_TAINT_ENABLE, $prvd_sentry_client->captured);

        $prvd_sentry_client->captured = false;
        $this->sqlite->exec("SELECT name FROM sqlite_master LIMIT ".$id);
        $this->assertEquals(PRVD_TAINT_ENABLE, $prvd_sentry_client->captured);
    }

    public function testQueryWithPayload()
    {
        global $prvd_sentry_client;
        $prvd_sentry_client = new Dummy_Sentry_Client();

        $id = TEST_PAYLOAD;

        $prvd_sentry_client->captured = false;
        @$this->pgsql->query("SELECT user FROM pg_user LIMIT ".$id);
        $this->assertEquals(true, $prvd_sentry_client->captured);

        $prvd_sentry_client->captured = false;
        @$this->mysql->query("SELECT user from user LIMIT ".$id);
        $this->assertEquals(true, $prvd_sentry_client->captured);

        $prvd_sentry_client->captured = false;
        @$this->sqlite->query("SELECT name from sqlite_master LIMIT ".$id);
        $this->assertEquals(true, $prvd_sentry_client->captured);
    }

    public function testPrepareWithPayload()
    {
        global $prvd_sentry_client;
        $prvd_sentry_client = new Dummy_Sentry_Client();

        $id = TEST_PAYLOAD;

        $prvd_sentry_client->captured = false;
        @$this->pgsql->prepare("SELECT user FROM pg_user LIMIT ".$id);
        $this->assertEquals(true, $prvd_sentry_client->captured);

        $prvd_sentry_client->captured = false;
        @$this->mysql->prepare("SELECT user from user LIMIT ".$id);
        $this->assertEquals(true, $prvd_sentry_client->captured);

        $prvd_sentry_client->captured = false;
        @$this->sqlite->prepare("SELECT name from sqlite_master LIMIT ".$id);
        $this->assertEquals(true, $prvd_sentry_client->captured);
    }

    public function testExecWithPayload()
    {
        global $prvd_sentry_client;
        $prvd_sentry_client = new Dummy_Sentry_Client();

        $id = TEST_PAYLOAD;

        $prvd_sentry_client->captured = false;
        @$this->pgsql->exec("SELECT user FROM pg_user LIMIT ".$id);
        $this->assertEquals(true, $prvd_sentry_client->captured);

        $prvd_sentry_client->captured = false;
        @$this->mysql->exec("SELECT user FROM user LIMIT ".$id);
        $this->assertEquals(true, $prvd_sentry_client->captured);

        $prvd_sentry_client->captured = false;
        @$this->sqlite->exec("SELECT name FROM sqlite_master LIMIT ".$id);
        $this->assertEquals(true, $prvd_sentry_client->captured);
    }
}
