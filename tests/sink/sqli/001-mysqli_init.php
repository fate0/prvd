<?php

use PHPUnit\Framework\TestCase;


final class mysqli_init_test extends TestCase
{
    public function test()
    {
        $conn = mysqli_init();
        $this->assertEquals(true, $conn instanceof mysqli);
    }
}
