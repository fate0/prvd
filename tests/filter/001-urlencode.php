<?php

use PHPUnit\Framework\TestCase;


final class urlencode_test extends TestCase
{
    public function test()
    {
        $str = 'test';
        prvd_xmark($str);
        $result = '<a href="mycgi?foo='.urlencode($str).'">';

        $is_mark = prvd_xcheck($result);
        $this->assertEquals(PRVD_TAINT_ENABLE, $is_mark);
    }
}


