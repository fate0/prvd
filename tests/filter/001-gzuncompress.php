<?php

use PHPUnit\Framework\TestCase;


final class gzuncompress_test extends TestCase
{
    public function test()
    {
        $compressed   = gzcompress('Compress me', 9);
        prvd_xmark($compressed);

        $uncompressed = gzuncompress($compressed);

        $is_mark = prvd_xcheck($uncompressed);
        $this->assertEquals(PRVD_TAINT_ENABLE, $is_mark);
    }
}
