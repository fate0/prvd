<?php

use PHPUnit\Framework\TestCase;


final class sprintf_test extends TestCase
{
    public function test()
    {
        $num = "test";
        prvd_xmark($num);

        $location = 'tree';

        $format = 'There are %d monkeys in the %s';
        $result = sprintf($format, $num, $location);

        $is_mark = prvd_xcheck($result);
        $this->assertEquals(PRVD_TAINT_ENABLE, $is_mark);
    }
}

