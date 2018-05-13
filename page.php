<?php
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
require_once('functions.php');

if (isset($_GET["tid"])) {
    $pageCont = curl('https://steamcn.com/forum.php?mod=viewthread&tid='.$_GET["tid"]);
    preg_match('/authorposton.+?span title=\"(.+?)\"/', $pageCont, $postTime);
    $postTime = date(DATE_RFC822, strtotime($postTime[1].' +0800'));
    preg_match('/t_f.+?>([\s\S]*?)<\/table/', $pageCont, $postCont);
    $postCont = preg_replace('/<ignore_js_op>[\s\S]*?<\/ignore_js_op>/', '', $postCont[1]);
    $postCont = str_replace('<div class="showhide">', '<br /><div class="showhide">', $postCont);
    $postCont = preg_replace('/<script.+?>[\s\S]*?<\/script>/', '', $postCont);
    $postCont = str_replace('<br />', '|', $postCont);
    $postCont = preg_replace('/<i class=\"pstatus\">.*?<\/i>/', '', $postCont);
    $postCont = preg_replace('/style>.+?<\/style>/', '', $postCont);
    $postCont = str_replace('&nbsp;', '', $postCont);
    $postCont = preg_replace('/<font.+?>/', '', $postCont);
    $postCont = preg_replace('/<\/font>/', '', $postCont);
    $postCont = str_replace('<strong>', '', $postCont);
    $postCont = str_replace('</strong>', '', $postCont);
    $postCont = str_replace('<i>', '', $postCont);
    $postCont = str_replace('</i>', '', $postCont);
    $postCont = preg_replace('/<a href=\"member\.php.+?>.+?<\/a>/', '|', $postCont);
    $postCont = preg_replace('/<\/.+?>/', '|', $postCont);
    $postCont = preg_replace('/<.+?>/', '', $postCont);
    $postCont = str_replace('|||||', '||||', $postCont);
    $postCont = str_replace('||||', '|||', $postCont);
    $postCont = str_replace('|||', '||', $postCont);
    $postCont = str_replace('||', '|', $postCont);
    $postCont = str_replace('|', '<br />', $postCont);
    $postCont = preg_replace('/\n/', '', $postCont);
    if (isset($_GET["pure"])) {
        echo $postCont;
    }
    else {
        echo '<html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head><body><a href="https://steamcn.com/t'.$_GET["tid"].'-1-1">https://steamcn.com/t'.$_GET["tid"].'-1-1</a><br>'.$postCont.'</body></html>';
    }
}
?>