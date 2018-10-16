<?php

use PHPUnit\Framework\TestCase;


final class str_pad_test extends TestCase
{
    public function test()
    {
        $input = "Alien";
        prvd_xmark($input);

        $result = str_pad($input, 10); // produces "Alien
        $is_mark = prvd_xcheck($result);
        $this->assertEquals(PRVD_TAINT_ENABLE, $is_mark);

        $result = str_pad($input, 10, "-=", STR_PAD_LEFT);  // produces "-=-=-Alien"
        $is_mark = prvd_xcheck($result);
        $this->assertEquals(PRVD_TAINT_ENABLE, $is_mark);

        $result = str_pad($input, 10, "_", STR_PAD_BOTH);   // produces "__Alien___"
        $is_mark = prvd_xcheck($result);
        $this->assertEquals(PRVD_TAINT_ENABLE, $is_mark);

        $result = str_pad($input,  6, "___");               // produces "Alien_"
        $is_mark = prvd_xcheck($result);
        $this->assertEquals(PRVD_TAINT_ENABLE, $is_mark);

        $result = str_pad($input,  3, "*");
        $is_mark = prvd_xcheck($result);
        $this->assertEquals(PRVD_TAINT_ENABLE, $is_mark);
    }
}

