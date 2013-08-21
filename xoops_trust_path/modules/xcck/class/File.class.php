<?php
/**
 * Created by JetBrains PhpStorm.
 * User: kilica
 * Date: 12/11/16
 * Time: 15:13
 * To change this template use File | Settings | File Templates.
 */

class Xcck_File
{
    public $mRootPath = '';

    public function __construct(Xcck_PageObject $obj)
    {
        $this->mPage = $obj;

        $this->mRootPath = XOOPS_TRUST_PATH.'/uploads';
        if(! is_dir($this->mRootPath) || ! is_writable($this->mRootPath)){
            echo 'XOOPS_TRUST_PATH/uploads directory does not exist or is not writable.';die;
        }
    }

    public function getPath($fieldName){
        return $this->getBasePath().'/'.str_pad($this->mPage->get('page_id'), 10, 0, STR_PAD_LEFT).$fieldName;

    }

    /**
     * getBasePath
     *
     * @param   int     $tsize
     *
     * @return  string
     */
    public function getBasePath()
    {
        $root = XCube_Root::getSingleton();
        $umask = $root->getSiteConfig('umask');
        $umask = isset($umask) ? $umask : 0;
        umask($umask);

        //module upload path
        $path = sprintf('%s/%s', $this->mRootPath, $this->mPage->getDirname());
        if (! is_dir($path)) {
            @mkdir($path, 0777);
        }

        //module upload path
        $path = sprintf('%s/%04d', $path, intval($this->mPage->getShow('page_id') / 1000));
        if (! is_dir($path)) {
            @mkdir($path, 0777);
        }

        return $path;
    }

    public function getFileSize($fieldName, $unit='kb')
    {
        switch(strtolower($unit)){
            case 'byte':
                $number = 1;
                break;
            case 'mb':
                $number = 1024*1024;
                break;
            case 'gb':
                $number = 1024*1024*1024;
                break;
            case 'kb':
            default:
                $number = 1024;
                break;
        }
        return round(filesize($this->getPath($fieldName)) / $number, 2);
    }
}

