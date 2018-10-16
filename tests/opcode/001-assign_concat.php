<?php

use PHPUnit\Framework\TestCase;


final class assign_concat_test extends TestCase
{
    public function test()
    {
        $result = "test";
        $is_mark = prvd_xcheck($result);
        $this->assertEquals(false, $is_mark);

        $append = "hello";
        prvd_xmark($append);

        $result .= $append;
        $is_mark = prvd_xcheck($result);
        $this->assertEquals(PRVD_TAINT_ENABLE, $is_mark);
    }
}


