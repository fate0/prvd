<?php

use PHPUnit\Framework\TestCase;


final class vsprintf_test extends TestCase
{
    public function test()
    {
        $str = '1988-8-1';
        prvd_xmark($str);

        $result = vsprintf("%04d-%02d-%02d", explode('-', $str)); // 1988-08-01

        $is_mark = prvd_xcheck($result);
        $this->assertEquals(PRVD_TAINT_ENABLE, $is_mark);
    }
}
