<?php

require_once 'EnumInterface.class.php';
/**
 * Created by PhpStorm.
 * User: ichikawa
 * Date: 2017/06/29
 * Time: 15:52
 */
abstract class Xcck_AbstractEnum implements Xcck_EnumInterface
{
    public function getList()
    {
        $ret = array();
        $reflect = new ReflectionClass($this);
        $list = $reflect->getConstants();
        $list = array_flip($list);
        foreach ($list as $key => $value) {
            if ($value!='PREFIX'){
                $ret[$key] = constant(static::PREFIX . $value);
            }
        }
        return $ret;
    }

    public function getLabel($value)
    {
        $reflect = new ReflectionClass($this);
        $list = $reflect->getConstants();
        $list = array_flip($list);
        if (in_array($value, array_keys($list))) {
            return constant(static::PREFIX . $list[$value]);
        }

        return "???";
    }

    public function validateValue($value)
    {
        $keys = array_keys($this->getList());
        return in_array($value, $keys);
    }
}
