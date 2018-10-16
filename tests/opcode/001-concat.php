<?php

use PHPUnit\Framework\TestCase;


final class concat_test extends TestCase
{
    public function test()
    {
        $result = "test";
        $is_mark = prvd_xcheck($result);
        $this->assertEquals(false, $is_mark);

        $append = "hello";
        prvd_xmark($append);

        $result = "xxx".$append;
        $is_mark = prvd_xcheck($result);
        $this->assertEquals(PRVD_TAINT_ENABLE, $is_mark);
    }
}

