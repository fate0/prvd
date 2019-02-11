# prvd 

[![Build Status](https://travis-ci.org/fate0/prvd.svg?branch=master)](https://travis-ci.org/fate0/prvd)
![GitHub](https://img.shields.io/github/license/fate0/prvd.svg)

[中文文档](https://github.com/fate0/prvd/blob/master/README.zh-CN.md)


### Table of Contents

* [Introduction](#introduction)
* [Installation](#installation)
* [Configuration](#configuration)
* [DVWA](#dvwa)
* [How it work](#how-it-work)
* [Ref](#ref)


### Introduction

PHP Runtime Vulnerability Detection

![sentry_detail](https://raw.githubusercontent.com/fate0/prvd/master/artwork/sentry_detail.png)


### Installation

1. git clone to a non-web directory, assuming the directory is `/data/prvd`

``` sh
git clone https://github.com/fate0/prvd.git /data/prvd
```

2. composer

``` sh
composer install
```

3. install xmark extension

* [install xmark](https://github.com/fate0/xmark)

4. edit php.ini 

* set `auto_prepend_file` to `/data/prvd/src/Entry.php`
* set `extension` to `xmark.so`
* for the rest of the configuration, please copy the contents of prvd.ini in this project


### Configuration

edit `/data/prvd/src/Config.php`

``` php
define("PRVD_FUZZER_DSN", "");
define("PRVD_SENTRY_DSN", "");        
define("PRVD_TAINT_ENABLE", true);
define("PRVD_TANZI", "xtanzi");
define("PRVD_LOG_FILE", "/data/prvd/prvd.log");
```

Sign up for an account at [https://sentry.io](https://sentry.io) or install sentry server by yourself


### DVWA

You can use the docker to experience prvd

```sh
docker pull fate0/prvd-dvwa
docker run -d -e "PRVD_SENTRY_DSN={YOUR_SENTRY_DSN}" -p 80:80 fate0/prvd-dvwa
```

The environment variables that can be set are

* `PRVD_FUZZER_DSN`
* `PRVD_SENTRY_DSN`
* `PRVD_TAINT_ENABLE`
* `PRVD_TANZI`


More about `prvd-dvwa` can be seen [here](https://github.com/fate0/prvd/blob/master/dvwa/README.md)


### How it work

* [PHP Runtime Vulnerability Detection](http://blog.fatezero.org/2018/11/11/prvd/)


### Ref

* [xmark](https://github.com/fate0/xmark)
