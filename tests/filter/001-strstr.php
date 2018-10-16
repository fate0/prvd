<?php

use PHPUnit\Framework\TestCase;


final class strstr_test extends TestCase
{
    public function test()
    {
        $email  = 'name@example.com';
        prvd_xmark($email);
        $result = strstr($email, '@');
        $is_mark = prvd_xcheck($result);
        $this->assertEquals(PRVD_TAINT_ENABLE, $is_mark);

        $result = strstr($email, '@', true); // As of PHP 5.3.0
        $is_mark = prvd_xcheck($result);
        $this->assertEquals(PRVD_TAINT_ENABLE, $is_mark);
    }
}

