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

require_once XCCK_TRUST_PATH . '/class/AbstractViewAction.class.php';
require_once XCCK_TRUST_PATH . '/class/File.class.php';

class Xcck_DownloadAction extends Xcck_AbstractViewAction
{
    const DATANAME = 'page';

    protected function _getName()
    {
        return $this->mRoot->mContext->mRequest->getRequest('field_name');
    }

    /**
     * &_getHandler
     *
     * @param	void
     *
     * @return	&XoopsObjectGenericHandler
     **/
    protected function &_getHandler()
    {
        return $this->mAsset->getObject('handler', 'Page');

    }

    public function executeViewSuccess($render) {
        $fileManager = new Xcck_File($this->mObject);
        $path_file = $fileManager->getPath($this->_getName());

        if (!file_exists($path_file)) {
            die("Error: File(".$path_file.") does not exist");
        }

        if (!($fp = fopen($path_file, "r"))) {
            die("Error: Cannot open the file(".$path_file.")");
        }
        fclose($fp);

        if (($content_length = filesize($path_file)) == 0) {
            die("Error: File size is 0.(".$path_file.")");
        }

        header("Content-Disposition: inline; filename=\"".$this->mObject->getShow($this->_getName())."\"");
        header("Content-Length: ".$content_length);
        header("Content-Type: application/octet-stream");

        if (!readfile($path_file)) {
            die("Cannot read the file(".$path_file.")");
        }
        die();
        //parent::executeViewSuccess($render);
    }
}

?>
