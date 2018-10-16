<?php

use PHPUnit\Framework\TestCase;


final class hex2bin_test extends TestCase
{
    public function test()
    {
        $str = "6578616d706c6520686578206461746111";
        prvd_xmark($str);

        $hex = hex2bin($str);

        $is_mark = prvd_xcheck($hex);
        $this->assertEquals(PRVD_TAINT_ENABLE, $is_mark);
    }
}
