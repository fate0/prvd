<?php

use PHPUnit\Framework\TestCase;


final class rawurldecode_test extends TestCase
{
    public function test()
    {
        $str = 'foo%20bar%40baz';
        prvd_xmark($str);
        $result = rawurldecode($str); // foo bar@baz

        $is_mark = prvd_xcheck($result);
        $this->assertEquals(PRVD_TAINT_ENABLE, $is_mark);
    }
}


