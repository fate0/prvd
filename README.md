# prvd 

[![Build Status](https://travis-ci.org/fate0/prvd.svg?branch=master)](https://travis-ci.org/fate0/prvd)
![GitHub](https://img.shields.io/github/license/fate0/prvd.svg)


### 介绍

PHP 动态漏洞检测

![sentry_detail](https://raw.githubusercontent.com/fate0/prvd/master/artwork/sentry_detail.png)


### 安装

1. git clone 到一个非 web 目录下，假设目录为 `/data/prvd`

``` sh
git clone git@github.com:fate0/prvd.git /data/prvd
```

2. 安装依赖

``` sh
composer install
```

3. 下载编译 xmark

* [install xmark](https://github.com/fate0/xmark)

4. 更改 php.ini 配置文件

* `auto_prepend_file` 配置成 `/data/prvd/src/Entry.php`
* `extension` 配置成 `xmark.so` 路径
* 其余的配置请直接 copy 本项目中 php.ini 的内容

### 配置

使用编辑器打开 `/data/prvd/src/Config.php`

``` php
define("PRVD_FUZZ_DSN", "");                    // fuzz dsn 地址
define("PRVD_SENTRY_DSN", "");                  // sentry dsn 地址
define("PRVD_TAINT_ENABLE", true);              // 是否启用 taint 模式
define("PRVD_TANZI", "xtanzi");                 // payload 关键字
define("PRVD_LOG_FILE", "/data/prvd/prvd.log"); // log 文件
```

### 原理

// TODO

### 引用

* [xmark](https://github.com/fate0/xmark)