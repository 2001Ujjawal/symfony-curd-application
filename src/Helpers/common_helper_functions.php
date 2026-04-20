<?php


if (!function_exists('prt')) {
    /**
     * Debug print helper
     *
     * @param mixed $data
     * @param bool $dieFlag (true = stop execution, false = continue)
     */
    function prt($data, bool $dieFlag = true)
    {
        if ($dieFlag == true) {
            print "<pre>";
            print_r($data);
            print "</pre>";
            die;
        }
        print "<pre>";
        print_r($data);
        print "</pre>";
        return;
    }
};

if (!function_exists('vrd')) {
    function vrd($data, bool $dieFlag = true)
    {
        if ($dieFlag == true) {
            print "<pre>";
            var_dump($data);
            print "</pre>";
            die;
        }
        print "<pre>";
        print_r($data);
        print "</pre>";
        return;
    }
};


if (!function_exists('required_filed_message')) {
    function required_filed_message(string $filedName)
    {
        return $filedName . "is required";
    }
}
