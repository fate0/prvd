# prvd-dvwa

## 介绍

prvd-dvwa 是一个安装了 prvd 的 dvwa 环境，用于简单地体验 prvd 的能力。

![dvwa_sentry_index](https://raw.githubusercontent.com/fate0/prvd/master/artwork/dvwa_sentry_index.png)


## 安装

```sh
docker pull fate0/prvd-dvwa
docker run -d -e "PRVD_SENTRY_DSN={YOUR_SENTRY_DSN}" -p 80:80 fate0/prvd-dvwa
```

可设置的环境变量有

* `PRVD_FUZZER_DSN`
* `PRVD_SENTRY_DSN`
* `PRVD_TAINT_ENABLE`
* `PRVD_TANZI`

## 漏洞

prvd-dvwa 环境中，prvd 能够检测下面几种类型漏洞：

* Command Injection
* File Inclusion
* File Upload
* SQL Injection
* SQL Injection (Blind)
* 部分 XSS

其中 taint 模式下也会有误报的情况，例如在 File Inclusion 关卡 impossible 难度的情况下：
```php

<?php 

// file: vulnerabilities/fi/source/impossible.php

// The page we wish to display 
$file = $_GET[ 'page' ]; 

// Only allow include.php or file{1..3}.php 
if( $file != "include.php" && $file != "file1.php" && $file != "file2.php" && $file != "file3.php" ) {
    // This isn't the page we want! 
    echo "ERROR: File not found!"; 
    exit; 
} 

?> 
```

虽然已经指定了具体文件名，但是并没有什么 filter 函数将 `$file` 变量上的 flag 给清除了，
所以就产生了一个误报。

taint 模式也会有漏报的情况，例如在 SQL Injection 关卡 medium 难度的情况下：

```php
<?php

// file: vulnerabilities/sqli/source/medium.php

if( isset( $_POST[ 'Submit' ] ) ) {
    // Get input
    $id = $_POST[ 'id' ];

    $id = mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $id);

    $query  = "SELECT first_name, last_name FROM users WHERE user_id = $id;";
    $result = mysqli_query($GLOBALS["___mysqli_ston"], $query) or die( '<pre>' . mysqli_error($GLOBALS["___mysqli_ston"]) . '</pre>' );

    // ...
}
```

`mysqli_real_escape_string` 会将变量的 flag 给去除掉，这是很正常的情况，
但是 SQL 语句中并没有使用引号对 `$id` 包围，所以就产生了一个漏报。

同样 payload 模式也会有漏报的情况，具体不再描述。

其他类型的漏洞，例如在客户端上的漏洞、服务器端上逻辑类型的漏洞，prvd 均没办法检测。
