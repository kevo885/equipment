<?php
// Snippet from PHP Share: http://www.phpshare.org

function byteConverter($bytes)
{
    if ($bytes >= 1073741824) {
        $bytes = number_format($bytes / 1073741824, 2) . ' GB';
    } else if ($bytes >= 1048576) {
        $bytes = number_format($bytes / 1048576, 2) . ' MB';
    } else if ($bytes >= 1024) {
        $bytes = number_format($bytes / 1024, 2) . ' KB';
    } else if ($bytes > 1) {
        $bytes = $bytes . ' bytes';
    } else if ($bytes == 1) {
        $bytes = $bytes . ' byte';
    } else {
        $bytes = '0 bytes';
    }

    return $bytes;
}
