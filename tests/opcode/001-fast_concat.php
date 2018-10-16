<?php

use PHPUnit\Framework\TestCase;


final class fast_concat_test extends TestCase
{
    public function test()
    {
        $a = "test";
        prvd_xmark($a);
        $this->assertEquals(true, prvd_xcheck($a));

        $z = "$a hello";
        $this->assertEquals(PRVD_TAINT_ENABLE, prvd_xcheck($z));
    }
}



