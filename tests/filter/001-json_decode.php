<?php

use PHPUnit\Framework\TestCase;


final class json_decode_test extends TestCase
{
    public function test()
    {
        $str = 'test';
        prvd_xmark($str);

        $json = '{"a":1,"b":2,"c":3,"d":4,"e":"'.$str.'"}';

        # 因为这里返回的是 object, 还可以继续改进
        $result = json_decode($json);

        $is_mark = prvd_xcheck($result);
        $this->assertEquals(false, $is_mark);

        $result = json_decode($json, true);
        $is_mark = prvd_xcheck($result);
        $this->assertEquals(PRVD_TAINT_ENABLE, $is_mark);
    }
}


