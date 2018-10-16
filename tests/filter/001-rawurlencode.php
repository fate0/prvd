<?php

use PHPUnit\Framework\TestCase;


final class rawurlencode_test extends TestCase
{
    public function test()
    {
        $str = 'foo @+%/@ftp.example.com/x.txt">';
        prvd_xmark($str);
        $result = '<a href="ftp://user:'.rawurlencode($str);

        $is_mark = prvd_xcheck($result);
        $this->assertEquals(PRVD_TAINT_ENABLE, $is_mark);
    }
}
