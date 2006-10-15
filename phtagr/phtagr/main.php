<?php

session_start();

include "$phtagr_lib/User.php";
include "$phtagr_lib/Sql.php";
include "$phtagr_lib/Search.php";
include "$phtagr_lib/Edit.php";

include "$phtagr_lib/PageBase.php";
include "$phtagr_lib/SectionHeaderLeft.php";
include "$phtagr_lib/SectionHeaderRight.php";
include "$phtagr_lib/SectionMenu.php";
include "$phtagr_lib/SectionHome.php";
include "$phtagr_lib/SectionFooter.php";
include "$phtagr_lib/SectionHelp.php";

include "$phtagr_lib/SectionAccount.php";

include "$phtagr_lib/SectionExplorer.php";
include "$phtagr_lib/SectionImage.php";
include "$phtagr_lib/SectionBrowser.php";
include "$phtagr_lib/SectionSearch.php";
include "$phtagr_lib/SectionUpload.php";
include "$phtagr_lib/SectionInstall.php";
include "$phtagr_lib/SectionAdmin.php";

$page = new PageBase("phTagr");

$hdr = new SectionBase('header');
$headerleft = new SectionHeaderLeft();
$hdr->add_section($headerleft);
$headerright = new SectionHeaderRight();
$hdr->add_section(&$headerright);

$page->add_section(&$hdr);

$body = new SectionBase("body");
$page->add_section(&$body);

$cnt = new SectionBase("content");
$body->add_section(&$cnt);

$menu = new SectionMenu();
$body->add_section(&$menu);

$footer = new SectionBase("footer");
$fcnt = new SectionFooter("content");
$footer->add_section(&$fcnt);
$page->add_section(&$footer);

$db = new Sql();
$user = new User();

$section="";
$action="";

if (isset($_REQUEST['section']))
  $section=$_REQUEST['section'];
if (isset($_REQUEST['action']))
  $action=$_REQUEST['action'];

if ($section=="install")
{
  $install = new SectionInstall();
  $cnt->add_section(&$install);
  $page->layout();
  return;
}

if (!$db->connect() && $section!="install")
{
  $msg = new SectionBase();
  $cnt->add_section(&$msg);
    
  $msg->h(_("No Installation found"));
  $link=sprintf("<a href=\"./index.php?section=install\">%s</a>",
    _("this link"));
  $text=sprintf(_("It looks as if phtagr is not completely configured. ".
    "Please follow %s to install phtagr."), $link);
  $msg->p($text);
  
  $page->layout();
  return;
}

$user->check_session();
$search= new Search();
$search->from_URL();


$pref=$db->read_pref($user->get_userid());
$pref['theme']='default';
$pref['path.theme']="./themes/".$pref['theme'];

$menu=new SectionMenu('menu', _("Menu"));
$menu->set_item_param('section');

$menu->add_item('home', _("Home"));
$menu->add_item('explorer', _("Explorer"));
$menu->add_item('search', _("Search"));

if ($user->can_browse())
{
  $menu->add_item('browser', _("Browser"));
}
if ($user->can_upload())
{
  $menu->add_item('upload', _("Upload"));
}
if ($user->is_admin())
{
  $menu->add_item('admin', _("Administration"));
}

if (isset($_REQUEST['section']))
{
  $section=$_REQUEST['section'];
    
  if ($user->is_member() && 
      $_REQUEST['section']=='account' && isset($_REQUEST['goto']))
  {
    $section=$_REQUEST['goto'];
  } 

  if ($_REQUEST['section']=='account' && $_REQUEST['action']=='logout')
  {
    $section='home';
  }
  
  if($section=='account')
  {
    $account=new SectionAccount();
    $cnt->add_section(&$account);
  }
  else if($section=='explorer')
  {
    if($_REQUEST['action']=='edit')
    {
      $edit=new Edit();
      $edit->execute();
      unset($edit);
    }
    $explorer= new SectionExplorer();
    $cnt->add_section(&$explorer);
  } 
  else if($section=='image' && isset($_REQUEST['id']))
  {
    if($_REQUEST['action']=='edit')
    {
      $edit=new Edit();
      $edit->execute();
      unset($edit);
    }
    $image= new SectionImage(intval($_REQUEST['id']));
    $cnt->add_section(&$image);
  } 
  else if($section=='search')
  {
    $seg_search= new SectionSearch();
    $cnt->add_section(&$seg_search);
  } 
  else if($section=='browser')
  {
    if ($user->can_browse()) {
      $browser = new SectionBrowser();
      // @todo set roots from preferences
      // $browser->reset_roots();
      // $browser->add_root(root, alias);
      $cnt->add_section(&$browser);
    } else {
      $login = new SectionLogin();
      $login->section=$section;
      $login->message=_("You are not loged in!");
      $cnt->add_section(&$login);
    }
  } 
  else if($section=='admin')
  {
    if (!$db->link || $user->is_admin()) 
    {
      $admin=new SectionAdmin();
      $cnt->add_section(&$admin);
    } else {
      $login=new SectionAccount();
      $login->message=_('You are not loged in as an admin');
      $login->section='admin';
      $cnt->add_section(&$login);
    }
  }
   else if($section=='upload')
  {
    if ($user->can_upload())
    {
      $upload = new SectionUpload();
      $cnt->add_section(&$upload);
    }
  }
  else if($section=='help')
  {
    $help = new SectionHelp();
    $cnt->add_section(&$help);
  }
  else if($section=='install')
  {
    $install = new SectionInstall();
    $cnt->add_section(&$install);
  }
  else {
    $home = new SectionHome();
    $cnt->add_section(&$home);
  }
} else {
  $home = new SectionHome();
  $cnt->add_section(&$home);
}

$page->layout();

?>
