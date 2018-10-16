<?php

use PHPUnit\Framework\TestCase;


final class strtoupper_test extends TestCase
{
    public function test()
    {
        $str = "Mary Had A Little Lamb and She LOVED It So";
        prvd_xmark($str);
        $result = strtoupper($str);

        $is_mark = prvd_xcheck($result);
        $this->assertEquals(PRVD_TAINT_ENABLE, $is_mark);
    }
}

