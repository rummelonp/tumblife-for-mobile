<?php
// Copyright 2009 Google Inc. All Rights Reserved.
function googleAnalyticsGetImageUrl() {
  $url = "/ga.php?";
  $url .= "utmac=MO-18368276-1";
  $url .= "&utmn=" . rand(0, 0x7fffffff);
  $referer = $_SERVER["HTTP_REFERER"];
  $query = $_SERVER["QUERY_STRING"];
  $path = $_SERVER["REQUEST_URI"];
  if (empty($referer)) {
    $referer = "-";
  }
  $url .= "&utmr=" . urlencode($referer);
  if (!empty($path)) {
    $url .= "&utmp=" . urlencode($path);
  }
  $url .= "&guid=ON";
  return str_replace("&", "&amp;", $url);
}

// convert for mobile
function m($text)
{
  $text = preg_replace('/(\t|\r|\n|\f)/', '', $text);
  $text = preg_replace('/ {2,}/', ' ', $text);
  $text = mb_convert_encoding($text, 'SJIS', 'UTF-8');
  return $text;
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ja" lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=shift_jis" />
<meta name="keywords" content="Tumblr, Tumblife, Mobile" />
<meta name="language" content="ja" />
<meta name="title" content="携帯用TumblrビューアTumblife for Mobile" />
<meta name="description" content="携帯用TumblrビューアTumblife for Mobile" />
<title>Tumblife for Mobile</title>
</head>
<body>
<font size="5">Tumblife for Mobile</font><br>
<br>
<?php echo m($content_for_layout) ?>
<hr>
<font size="2">Powerd by Kazuya Takeshima. <a href="http://mitukiii.jp/">mitukiii.jp</a></font><br>
<br>
<?php echo '<img src="' . googleAnalyticsGetImageUrl() . '" />' ?>
</body>
</html>