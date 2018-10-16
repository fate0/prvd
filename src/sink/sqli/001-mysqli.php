<?php

/**
 * TODO: 没有其他方法动态 extends, 只能暂时硬编码
 */
class mysqli extends prvd_mysqli {
    public function prepare($query) {
        prvd_check_sqli($query, prvd_translate("Sql Injection"));
        return parent::prepare($query);
    }

    public function query($query, $resultmode=MYSQLI_STORE_RESULT) {
        prvd_check_sqli($query, prvd_translate("Sql Injection"));
        return parent::query($query, $resultmode);
    }

    public function real_query($query) {
        prvd_check_sqli($query, prvd_translate("Sql Injection"));
        return parent::real_query($query);
    }

    public function multi_query($query) {
        prvd_check_sqli($query, prvd_translate("Sql Injection"));
        return parent::multi_query($query);
    }
}
