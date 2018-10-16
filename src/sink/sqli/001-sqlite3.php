<?php

/**
 * TODO: class 没有其他方法动态 extends, 只能暂时硬编码 class 名字
 */


class SQLite3 extends prvd_SQLite3 {
    public function exec($query) {
        prvd_check_sqli($query, prvd_translate("Sql Injection"));
        return parent::exec($query);
    }

    public function query($query) {
        prvd_check_sqli($query, prvd_translate("Sql Injection"));
        return parent::query($query);
    }

    public function prepare($query) {
        prvd_check_sqli($query, prvd_translate("Sql Injection"));
        return parent::prepare($query);
    }

    public function querySingle($query, $entire_row=false) {
        prvd_check_sqli($query, prvd_translate("Sql Injection"));
        return parent::querySingle($query, $entire_row);
    }
}