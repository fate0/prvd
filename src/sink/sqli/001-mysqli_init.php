<?php

// hook 之后, mysqli_init 返回的 obj 和 mysqli 不是同一个
function mysqli_init() {
    return new mysqli();
}
