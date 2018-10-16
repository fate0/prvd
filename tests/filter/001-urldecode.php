<?php

use PHPUnit\Framework\TestCase;


final class urldecode_test extends TestCase
{
    public function test()
    {
        $query = "my=apples&are=green+and+red";
        prvd_xmark($query);

        foreach (explode('&', $query) as $chunk) {
            $param = explode("=", $chunk);

            if ($param) {
                $result = urldecode($param[0]);
                $is_mark = prvd_xcheck($result);
                $this->assertEquals(PRVD_TAINT_ENABLE, $is_mark);

                $result = urldecode($param[1]);
                $is_mark = prvd_xcheck($result);
                $this->assertEquals(PRVD_TAINT_ENABLE, $is_mark);
            }
        }
    }
}


