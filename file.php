<?php
// show attachment file
// $Id: file.php,v 1.1 2007/02/23 05:27:28 nobu Exp $

include "../../mainfile.php";
include "functions.php";

if (!function_exists('mime_content_type ')) {
    function mime_content_type($f) {
	return trim(exec('file -bi '.escapeshellarg($f)));
    }
}

$msgid = isset($_GET['id'])?intval($_GET['id']):0;
$file = $_GET['file'];

if ($msgid) {
    $res = $xoopsDB->query("SELECT msgid,uid,touid,onepass FROM ".MESSAGE." WHERE msgid=$msgid");
    if (!$res || $xoopsDB->getRowsNum($res)==0) die("No File");
    $data = $xoopsDB->fetchArray($res);
    if (!check_perm($data)) {
	redirect_header(XOOPS_URL.'/user.php', 3, _NOPERM);
	exit;
    }
}

$path = XOOPS_UPLOAD_PATH.attach_path($msgid, $file);
$type = mime_content_type($path);
$stat = stat($file);
//header("Last-Modified: ".formatTimestamp($stat['mtime'], "r"));
header("Content-Type: $type");
//header("Content-Length: ".$stat['size']);

if ($_SERVER["REQUEST_METHOD"]=="GET") {
    header('Content-Disposition: inline;filename="'.$file.'"');
    print file_get_contents($path);
}
?>