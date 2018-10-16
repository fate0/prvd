<?php

use PHPUnit\Framework\TestCase;


final class basename_test extends TestCase
{
    public function test()
    {
        $str = "test";
        prvd_xmark($str);

        $result = "1) ".basename($str, ".d").PHP_EOL;
        $is_mark = prvd_xcheck($result);
        $this->assertEquals(PRVD_TAINT_ENABLE, $is_mark);

        $result = "2) ".basename($str).PHP_EOL;
        $is_mark = prvd_xcheck($result);
        $this->assertEquals(PRVD_TAINT_ENABLE, $is_mark);

        $result = "3) ".basename($str).PHP_EOL;
        $is_mark = prvd_xcheck($result);
        $this->assertEquals(PRVD_TAINT_ENABLE, $is_mark);

        $result = "4) ".basename("/etc/").PHP_EOL;
        $is_mark = prvd_xcheck($result);
        $this->assertEquals(false, $is_mark);

        $result = "5) ".basename(".").PHP_EOL;
        $is_mark = prvd_xcheck($result);
        $this->assertEquals(false, $is_mark);

        $result = "6) ".basename("/");
        $is_mark = prvd_xcheck($result);
        $this->assertEquals(false, $is_mark);
    }
}

