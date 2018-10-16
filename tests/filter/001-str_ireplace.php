<?php

use PHPUnit\Framework\TestCase;


final class str_ireplace_test extends TestCase
{
    public function test()
    {
        $str = "test";
        prvd_xmark($str);

        $result = str_ireplace("%body%", $str, "<body text=%BODY%>");

        $is_mark = prvd_xcheck($result);
        $this->assertEquals(PRVD_TAINT_ENABLE, $is_mark);
    }
}


