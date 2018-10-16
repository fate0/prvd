<?php

use PHPUnit\Framework\TestCase;


final class htmlspecialchars_decode_test extends TestCase
{
    public function test()
    {
        $str = "<p>this -&gt; &quot;</p>\n";
        prvd_xmark($str);

        $result = htmlspecialchars_decode($str);
        $is_mark = prvd_xcheck($result);
        $this->assertEquals(PRVD_TAINT_ENABLE, $is_mark);

        $result = htmlspecialchars_decode($str, ENT_NOQUOTES);
        $is_mark = prvd_xcheck($result);
        $this->assertEquals(PRVD_TAINT_ENABLE, $is_mark);
    }
}

