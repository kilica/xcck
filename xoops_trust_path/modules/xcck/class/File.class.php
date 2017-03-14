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

    public function getPath($fieldName)
    {
        return $this->getBasePath().'/'.str_pad($this->mPage->get('page_id'), 10, 0, STR_PAD_LEFT).$fieldName;

    }

    public function savePreviewFile(Xcck_DefinitionObject $definition)
    {
        $uploadedFilePath = @$_FILES[$definition->getShow('field_name').'_file']['tmp_name'] ? $_FILES[$definition->getShow('field_name').'_file']['tmp_name'] : null;
        if(isset($uploadedFilePath) && file_exists($uploadedFilePath)){
            $filename = $this->getPreviewFileName();
            move_uploaded_file(
                $uploadedFilePath,
                sprintf('%s/%s/%s', $this->mRootPath, $this->mPage->getDirname(), $filename)
            );
            return $filename;
        }
        return null;
    }

    public function saveFile($fieldName, $tmpFilename)
    {
        $path = sprintf('%s/%s/%s', $this->mRootPath, $this->mPage->getDirname(), $tmpFilename);
        if (! file_exists($path)) {
           return false;
        }
        return rename($path, $this->getPath($fieldName));
    }

    protected function getPreviewFileName()
    {
        $salt = XCube_Root::getSingleton()->getSiteConfig('Cube', 'Salt');
        srand(microtime() *1000000);
        return md5($salt . rand());
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

    /**
     * @param string $fileName
     * @return bool
     */
    public function existFile($fileName)
    {
        return file_exists($this->getPath($fileName));
    }
}

