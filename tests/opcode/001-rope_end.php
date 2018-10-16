<?php

use PHPUnit\Framework\TestCase;


final class rope_end_test extends TestCase
{
    public function test()
    {
        $id = "hello";
        prvd_xmark($id);

        $result = "hello from x $id , welcome";;
        $is_mark = prvd_xcheck($result);
        $this->assertEquals(PRVD_TAINT_ENABLE, $is_mark);
    }
}



