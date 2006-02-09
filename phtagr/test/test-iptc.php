#!/usr/bin/php
<?php
/*
 Thanks to Christian Tratz, who has written a nice IPTC howto on
 http://www.codeproject.com/bitmap/iptc.asp
*/
include_once('../phtagr/Iptc.php');

$filename=$argv[1];

$img=new Iptc();
if ($img->load_from_file($filename)==false)
{
  echo $img->error."\n";
} else {
  $img->add_iptc('2:025', "Keyword");
  $content=$img->_iptc2bytes();
  $img->_replace_iptc();
}
print_r($img);

exit;

