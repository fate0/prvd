<?php

use PHPUnit\Framework\TestCase;


final class explode_test extends TestCase
{
    public function test()
    {
        $pizza  = "test hello just test";
        prvd_xmark($pizza);

        $pieces = explode(" ", $pizza);
        $is_mark = prvd_xcheck($pieces[0]);
        $this->assertEquals(PRVD_TAINT_ENABLE, $is_mark);

        $is_mark = prvd_xcheck($pieces[1]);
        $this->assertEquals(PRVD_TAINT_ENABLE, $is_mark);

        $data = "foo:*:1023:1000::/home/foo:/bin/sh";
        prvd_xmark($data);
        list($user, $pass, $uid, $gid, $gecos, $home, $shell) = explode(":", $data);

        $is_mark = prvd_xcheck($user);
        $this->assertEquals(PRVD_TAINT_ENABLE, $is_mark);

        $is_mark = prvd_xcheck($pass);
        $this->assertEquals(PRVD_TAINT_ENABLE, $is_mark);
    }
}


