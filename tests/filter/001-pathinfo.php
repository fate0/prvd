<?php

use PHPUnit\Framework\TestCase;


final class pathinfo_test extends TestCase
{
    public function test()
    {
        $str = '/www/htdocs/inc/lib.inc.php';
        prvd_xmark($str);
        $path_parts = pathinfo($str);

        $result = $path_parts['dirname'];
        $is_mark = prvd_xcheck($result);
        $this->assertEquals(PRVD_TAINT_ENABLE, $is_mark);

        $result = $path_parts['basename'];
        $is_mark = prvd_xcheck($result);
        $this->assertEquals(PRVD_TAINT_ENABLE, $is_mark);

        $result = $path_parts['extension'];
        $is_mark = prvd_xcheck($result);
        $this->assertEquals(PRVD_TAINT_ENABLE, $is_mark);

        $result = $path_parts['filename'];
        $is_mark = prvd_xcheck($result);
        $this->assertEquals(PRVD_TAINT_ENABLE, $is_mark);
    }
}
