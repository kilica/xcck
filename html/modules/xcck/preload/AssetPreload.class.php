<?php
/**
 * @file
 * @package xcck
 * @version $Id$
**/

if(!defined('XOOPS_ROOT_PATH'))
{
    exit;
}

require_once XOOPS_TRUST_PATH . '/modules/xcck/preload/AssetPreload.class.php';
Xcck_AssetPreloadBase::prepare(basename(dirname(dirname(__FILE__))));

?>
