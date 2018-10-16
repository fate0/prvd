<?php

use PHPUnit\Framework\TestCase;


final class str_replace_test extends TestCase
{
    public function test()
    {
        // Provides: <body text='black'>
        $str = "<body text='%body%'>";
        prvd_xmark($str);
        $result = str_replace("%body%", "black", $str);
        $is_mark = prvd_xcheck($result);
        $this->assertEquals(PRVD_TAINT_ENABLE, $is_mark);

        // Provides: Hll Wrld f PHP
        $str = "Hello World of PHP";
        prvd_xmark($str);
        $vowels = array("a", "e", "i", "o", "u", "A", "E", "I", "O", "U");
        $result = str_replace($vowels, "", $str);
        $is_mark = prvd_xcheck($result);
        $this->assertEquals(PRVD_TAINT_ENABLE, $is_mark);

        // Provides: You should eat pizza, beer, and ice cream every day
        $phrase  = "You should eat fruits, vegetables, and fiber every day.";
        prvd_xmark($phrase);
        $healthy = array("fruits", "vegetables", "fiber");
        $yummy   = array("pizza", "beer", "ice cream");

        $result = str_replace($healthy, $yummy, $phrase);
        $is_mark = prvd_xcheck($result);
        $this->assertEquals(PRVD_TAINT_ENABLE, $is_mark);

        // Provides: 2
        $str = "good golly miss molly!";
        prvd_xmark($str);
        $result = str_replace("ll", "", $str, $count);
        $is_mark = prvd_xcheck($result);
        $this->assertEquals(PRVD_TAINT_ENABLE, $is_mark);
        $this->assertEquals(2, $count);
    }
}



