<?php 
date_default_timezone_set('Asia/Shanghai');
require_once('config.php');
function curl($url, $referer="https://steamcn.com/", $useragent="Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/46.0.2490.71 Safari/537.36", $header=array(), $post=0, $post_data="") {
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 3);
    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 15);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
    curl_setopt ($curl, CURLOPT_REFERER, $referer);
    curl_setopt($curl, CURLOPT_USERAGENT, $useragent);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    if ($post==1) {
      curl_setopt($curl, CURLOPT_POST, 1);
      curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
    }
    $src = curl_exec($curl);
    curl_close($curl);
    return $src;
}
function handlePage($pageHtml) {
  preg_match_all('/normalthread_(\d*)/', $pageHtml, $id);
  preg_match_all('/class="xst.+?>(.+?)</', $pageHtml, $title);
  preg_match_all('/target="_blank">(.+?)<\/a><\/td>/', $pageHtml, $category);
  preg_match_all('/\"suid-\d*\" c=\"1\">(.+?)<\/a><\/cite>/', $pageHtml, $auther);
  $id = $id[1];
  $title = $title[1];
  $category = $category[1];
  $auther = $auther[1];
  $content = array();
  $timeText = date('D, d M y H:i:s +0800', time());
  for ($i=0;$i<10;$i++) {
    $content[] = array($id[$i], urlencode($title[$i]), urlencode($category[$i]), urlencode($auther[$i]), '', urlencode($timeText));
  }
  return $content;
}
function showPage($pageCont,$installpath) {
    $len = count($pageCont);
    $top = '<?xml version="1.0" encoding="utf-8"?>
<rss version="2.0">
  <channel>
    <title>SteamCN New</title>
    <link>https://steamcn.com/forum.php?mod=guide&amp;view=newthread</link>
    <description>Recent</description>
    <generator>sffxzzp</generator>
    <lastBuildDate>'.date(DATE_RFC822).'</lastBuildDate>
    <ttl>1</ttl>
    <image>
      <url>https://steamcn.com/static/image/common/logo_88_31.gif</url>
      <title>SteamCN</title>
      <link>https://steamcn.com/</link>
    </image>';
    $mid = '';
    for ($i=$len-1;$i>=0;$i--) {
        $mid = $mid.'
    <item>
      <title>'.urldecode($pageCont[$i][1]).'</title>
      <link>https://'.$_SERVER['HTTP_HOST'].$installpath.'/page.php?tid='.$pageCont[$i][0].'</link>
      <description><![CDATA['.curl('https://'.$_SERVER['SERVER_NAME'].$installpath.'/page.php?pure=1&tid='.$pageCont[$i][0]).']]></description>
      <category>'.urldecode($pageCont[$i][2]).'</category>
      <author>'.urldecode($pageCont[$i][3]).'</author>
      <pubDate>'.urldecode($pageCont[$i][5]).'</pubDate>
    </item>';
    }
    $bot = '
  </channel>
</rss>';
    echo $top.$mid.$bot;
}
?>