<?php

use PHPUnit\Framework\TestCase;


final class htmlentities_test extends TestCase
{
    public function test()
    {
        $orig = "I'll \"walk\" the <b>dog</b> now";
        $id = "test";
        prvd_xmark($id);

        $a = htmlentities($orig);
        $b = $a.$id;
        $c = html_entity_decode($b);

        $is_mark = prvd_xcheck($a);
        $this->assertEquals(false, $is_mark);

        $is_mark = prvd_xcheck($b);
        $this->assertEquals(PRVD_TAINT_ENABLE, $is_mark);

        $is_mark = prvd_xcheck($c);
        $this->assertEquals(PRVD_TAINT_ENABLE, $is_mark);

    }
}

