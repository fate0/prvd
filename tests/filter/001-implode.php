<?php

use PHPUnit\Framework\TestCase;


final class implode_test extends TestCase
{
    public function test()
    {
        $str = 'name';
        prvd_xmark($str);
        $array = array($str, 'email', 'phone');
        $comma_separated = implode(",", $array);

        $is_mark = prvd_xcheck($comma_separated);
        $this->assertEquals(PRVD_TAINT_ENABLE, $is_mark);
    }
}





