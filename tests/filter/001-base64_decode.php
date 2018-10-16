<?php

use PHPUnit\Framework\TestCase;


final class base64_decode_test extends TestCase
{
    public function test()
    {
        $str = "test";
        prvd_xmark($str);

        $is_mark = prvd_xcheck($str);
        $this->assertEquals(true, $is_mark);

        $result = base64_decode($str);

        $is_mark = prvd_xcheck($result);
        $this->assertEquals(PRVD_TAINT_ENABLE, $is_mark);
    }
}