<?php

use PHPUnit\Framework\TestCase;

require_once("config.php");


final class pg_query_params_test extends TestCase
{
    private $link;

    public function setUp()
    {
        $this->link = pg_connect("host=".PG_DB_HOST." port=".PG_DB_PORT." dbname=".PG_DB_NAME." user=".PG_DB_USERNAME." password=".PG_DB_PASSWORD);
    }

    public function tearDown()
    {
        pg_close($this->link);
    }

    public function testWithTaint()
    {
        global $prvd_sentry_client;
        $prvd_sentry_client = new Dummy_Sentry_Client();

        $id = "1";
        prvd_xmark($id);

        @pg_query_params($this->link, "SELECT user FROM pg_user LIMIT ".$id, array());
        $this->assertEquals(PRVD_TAINT_ENABLE, $prvd_sentry_client->captured);
    }

    public function testWithPayload()
    {
        global $prvd_sentry_client;
        $prvd_sentry_client = new Dummy_Sentry_Client();

        $id = TEST_PAYLOAD;

        @pg_query_params($this->link, "SELECT user FROM pg_user LIMIT ".$id, array());
        $this->assertEquals(true, $prvd_sentry_client->captured);
    }
}
