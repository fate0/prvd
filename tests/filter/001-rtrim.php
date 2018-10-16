<?php

use PHPUnit\Framework\TestCase;


final class rtrim_test extends TestCase
{
    public function test()
    {
        $text = "\t\tThese are a few words :) ...  ";
        prvd_xmark($text);

        $binary = "\x09Example string\x0A";
        prvd_xmark($binary);

        $hello  = "Hello World";
        prvd_xmark($hello);

        $result = rtrim($text);
        $is_mark = prvd_xcheck($result);
        $this->assertEquals(PRVD_TAINT_ENABLE, $is_mark);

        $result = rtrim($hello, "Hdle");
        $is_mark = prvd_xcheck($result);
        $this->assertEquals(PRVD_TAINT_ENABLE, $is_mark);

        $result = rtrim($binary, "\x00..\x1F");
        $is_mark = prvd_xcheck($result);
        $this->assertEquals(PRVD_TAINT_ENABLE, $is_mark);
    }
}

