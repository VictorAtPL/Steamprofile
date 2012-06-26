<?php
/*
Plugin Steamprofile
(c) 2012 by Victor
Website: http://www.victor.org.pl/
Version: v1.3
*/

$donate = <<<EOT
<br /><br />
If you want to give me a beer, click below.<br />
<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=MS7TLFFA36VLA" alt="Donate" target="_blank"><img src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_SM.gif" border="0" name="submit" alt="PayPal — Pay easy and safe" /></a>
EOT;

$l['steamprofile_description'] = "Steam \"signature\" (from <a href=\"http://code.google.com/p/steamprofile/\">google code repository</a>). Also link to steam profile in user's profile. Example:<br /> <img src=\"http://steamprofile.googlecode.com/svn/wiki/images/steamprofile_in-game_menu.png\" width=\"246\" height=\"48\" />" . trim($donate);

$l['steamprofile_fid_title'] = "Custom Profile Field id (fid)";
$l['steamprofile_fid_description'] = "Fid - unique id of custom profile field where users insert steamid, steamnick or steamprofileid.<br />Usually auto-created or auto-detected when installing by plugin (name of field must equal Steamprofile).";

$l['steamprofile_mode_title'] = "If turned on, steam signature will be shown in post, either only steam link in postbit.";

$l['steamprofile_settingsgdesc'] = "Fid field and mode check.";
?>