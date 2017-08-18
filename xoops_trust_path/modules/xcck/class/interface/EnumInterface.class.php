<?php

/**
 * Created by PhpStorm.
 * User: ichikawa
 * Date: 2017/06/29
 * Time: 15:45
 */
interface Xcck_EnumInterface
{
    public function getList();

    public function getLabel($key);

    public function validateValue($value);
}
