<?php

function xcck_search($dirname, $keywords, $andor, $limit, $offset, $uid=null)
{
    $search = new Xcck_Search($dirname);
    return $search->searchKeyword($keywords, $andor, $limit, $offset, $uid)


}
?>
