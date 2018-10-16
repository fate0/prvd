<?php

use PHPUnit\Framework\TestCase;


final class dirname_test extends TestCase
{
    public function test()
    {
        $str = "test";
        prvd_xmark($str);

        $result = dirname($str) . PHP_EOL;
        $is_mark = prvd_xcheck($result);
        $this->assertEquals(PRVD_TAINT_ENABLE, $is_mark);

        $result = dirname("/etc/") . PHP_EOL;
        $is_mark = prvd_xcheck($result);
        $this->assertEquals(false, $is_mark);

        $result = dirname(".") . PHP_EOL;
        $is_mark = prvd_xcheck($result);
        $this->assertEquals(false, $is_mark);

        $result = dirname("C:\\") . PHP_EOL;
        $is_mark = prvd_xcheck($result);
        $this->assertEquals(false, $is_mark);

        $result = dirname($str, 2);
        $is_mark = prvd_xcheck($result);
        $this->assertEquals(PRVD_TAINT_ENABLE, $is_mark);
    }
}

