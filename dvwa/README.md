# prvd-dvwa

## Introduction

prvd-dvwa is a dvwa environment with prvd installed, which is used to simply experience prvd.

![dvwa_sentry_index](https://raw.githubusercontent.com/fate0/prvd/master/artwork/dvwa_sentry_index.png)


## Installation

```sh
docker pull fate0/prvd-dvwa
docker run -d -e "PRVD_SENTRY_DSN={YOUR_SENTRY_DSN}" -p 80:80 fate0/prvd-dvwa
```

The environment variables that can be set are

* `PRVD_FUZZER_DSN`
* `PRVD_SENTRY_DSN`
* `PRVD_TAINT_ENABLE`
* `PRVD_TANZI`

## 漏洞

In the prvd-dvwa environment, prvd is able to detect the following types of vulnerabilities:

* Command Injection
* File Inclusion
* File Upload
* SQL Injection
* SQL Injection (Blind)
* Partial XSS


There are also false positives in taint mode, such as in the case of `File Inclusion` (impossible level):

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

There is no filter function to clear the flag on the `$file` variable.
So there was a false positive.

There is also a false negative in the taint mode, such as in the case of the `SQL Injection` (medium level):

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

`mysqli_real_escape_string` will remove the flag of the variable, which is normal.
However, the SQL statement does not use quotes to surround `$id`, so this is a false negative.

Similarly, the payload mode will also have a false negative, which will not be described.

Other types of vulnerabilities, such as vulnerabilities on the client side and logical types of 
vulnerabilities on the server side, are not detectable by prvd.