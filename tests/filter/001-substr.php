<?php

use PHPUnit\Framework\TestCase;


final class substr_test extends TestCase
{
    public function test()
    {
        $str = "abcdef";
        prvd_xmark($str);

        $result = substr($str, -1);    // returns "f"
        $is_mark = prvd_xcheck($result);
        $this->assertEquals(PRVD_TAINT_ENABLE, $is_mark);

        $result = substr($str, -2);    // returns "ef"
        $is_mark = prvd_xcheck($result);
        $this->assertEquals(PRVD_TAINT_ENABLE, $is_mark);

        $result = substr($str, -3, 1); // returns "d"
        $is_mark = prvd_xcheck($result);
        $this->assertEquals(PRVD_TAINT_ENABLE, $is_mark);
    }
}
