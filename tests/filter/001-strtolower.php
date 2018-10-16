<?php

use PHPUnit\Framework\TestCase;


final class strtolower_test extends TestCase
{
    public function test()
    {
        $str = "Mary Had A Little Lamb and She LOVED It So";
        prvd_xmark($str);
        $result = strtolower($str);

        $is_mark = prvd_xcheck($result);
        $this->assertEquals($is_mark, PRVD_TAINT_ENABLE);
    }
}

