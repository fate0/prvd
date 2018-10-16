<?php

/**
 * TODO: class 没有其他方法动态 extends, 只能暂时硬编码 class 名字
 */


class PDO extends prvd_PDO {
    public function query($query, ...$args)
    {
        prvd_check_sqli($query, prvd_translate("Sql Injection"));
        return parent::query($query, ...$args);
    }

    public function prepare($statement, $options=array())
    {
        prvd_check_sqli($statement, prvd_translate("Sql Injection"));
        return parent::prepare($statement, $options);
    }

    public function exec($query='')
    {
        prvd_check_sqli($query, prvd_translate("Sql Injection"));
        return parent::exec($query);
    }
}