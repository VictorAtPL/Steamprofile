<?php
/*
Plugin Steamprofile
(c) 2011 by Victor
Website: http://www.victor.org.pl/
Version: v1.2
License:
[EN]
License:
1. It's a plugin which I have written.
2. You can use this as you want and wherever you want. However, please note that you can not usurp authorship and delete any copy (if exists).
3. It can be only hosted at http://www.victor.org.pl/ and on MyBB.com sites!
4. Using the work is tantamount to accepting the license. Any misconduct may be investigated, under the Act of February 4, 1994 on Copyright and Related Rights (Journal of Laws of 1994 No. 24, item. 83).

[PL]
Licencja:
1. To jest dzie³o, które napisa³em ja.
2. Mo¿esz korzystac z tego jak chcesz i gdzie chcesz. Pamiêtaj jednak, ¿e nie mo¿esz przyw³aszczyæ sobie aurostwa ani usun¹æ informacji o autorze (je¿eli takowa istnieje).
3. Zastrzegam sobie wy³¹czne hostowanie tego pluginu na stronie http://www.victor.org.pl/ oraz na stronach MyBB.com!
4. Korzystanie z dzie³a równoznaczny jest z akceptacj¹ licencji. Wszelkie uchybienia mog¹ byæ dociekane z tytu³u Ustawy z dnia 4 lutego 1994 r. o prawie autorskim i prawach pokrewnych (Dz. U. z 1994 r. Nr 24, poz. 83).

Update:
1. Deactivate
2. Delete inc/plugins/steamprofile.php
3. Delete inc/plugins/steamprofile
4. Upload new files (overwrite support_class folder and include.php file)
5. Change your steam's Custom Profile Field's name to "Steamprofile"
6. Activate

Changelog:
v0.8 Alpha release (@18.09.2010)
+ pierwsza wersja, stabilna
/.../
v1.2 Update release (@04.12.2011 14:08)
+ zaktualizowana biblioteka steamprofile (do 2.1.0)
+ zaktualizowana biblioteka jquery (do 1.6.2)
+ obs³uga jquery (+ noconflict) poprzez dodanie do szablonu
+ update support_class (v1.4 @ 08.08.2011)
+ wielojêzycznoœæ (obejmuje tylko panel admina)
+ link do profilu steam w profilu
! do wyboru sygnatura albo link w informacjach o u¿ytkowniku
! poprawki w za³¹czaniu kodu
*/

if(!defined("IN_MYBB"))
{
	die("Direct initialization of this file is not allowed.<br /><br />Please make sure IN_MYBB is defined.");
}

/* W ACP */
function steamprofile_info()
{
	global $lang;
	$lang->load("admin/steamprofile");
	
	return array(
		"name"			=> "Steamprofile",
		"description"	=> $lang->{'steamprofile_description'},
		"version"		=> "1.2",
		"compatibility" => "160*",
		"guid"			=> "bdf2ce8abf1f0ca6f89d1af222e50bfa",
		
		"author"		=> "Victor",
		"authorsite"	=> "http://www.victor.org.pl/"
	);
}

if (defined("IN_ADMINCP"))
{
	require_once MYBB_ROOT."inc/plugins/support_class/include.php";

	global $sc_steamprofile;
	$sc_steamprofile = new plugin_support("steamprofile", $mybb, $db, $lang);
	
	$sc_steamprofile->setSettingsGDesc($lang->{'steamprofile_settingsgdesc'});
	$sc_steamprofile->addSetting("fid", NULL, "0", NULL, "text");
	$sc_steamprofile->addSetting("mode", NULL, "1", NULL, "onoff");
	
	$sc_steamprofile->addTemplateChange("postbit", "{\$post['subject']} {\$post['subject_extra']}</strong>", "{\$post['steamprofile']}", "after");
	$sc_steamprofile->addTemplateChange("postbit_classic", " class=\"post_body\">", "{\$post['steamprofile']}", "after");
	$sc_steamprofile->addTemplateChange("headerinclude", "{\$stylesheets}", "<script type=\"text/javascript\" src=\"http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js\"></script>\n<script type=\"text/javascript\">\njQuery.noConflict();var steamprofile = new SteamProfile;</script>\n<script type=\"text/javascript\" src=\"inc/plugins/steamprofile/steamprofile.js\"></script>", "after");

	$template = <<<EOT
<div id="pid_steamprofile_{\$post['pid']}" class="steamprofile" style="float: right;" title="{\$steamid64}"></div>
EOT;

	$sc_steamprofile->addNewTemplate("signature", $template);

	$template = <<<EOT
<div style="margin-left: 5px;"><a href="http://www.steamcommunity.com/profiles/{\$steamid64}" title="{\$steamid}" target="_blank"><img src="images/steam.png" alt="" /></a></div>
EOT;

	$sc_steamprofile->addNewTemplate("link", $template);
}

function steamprofile_activate()
{
	global $sc_steamprofile, $db;
	
	$sc_steamprofile->install();
	$sc_steamprofile->activate();
	
	$query = $db->simple_select("profilefields", "fid", "name = 'Steamprofile'");
	
	if (!$db->num_rows($query))
	{
		if ($db->table_exists("profilefields"))
		{
			$db->query("INSERT INTO `" . $db->table_prefix . "profilefields` (`name`, `description`, `disporder`, `type`, `length`, `maxlength`, `required`, `editable`, `hidden`) VALUES ('Steamprofile', 'Profile field of Steamprofile plugin. Auto-creation.', 4, 'text', 0, 30, 0, 1, 0)");
			
			$fid = $db->insert_id();
			
			$db->query("ALTER TABLE `" . $db->table_prefix . "userfields` ADD `fid" . $fid . "` TEXT NOT NULL");
		}
	}
	elseif ($db->num_rows($query) == 1)
	{
		while ($row = $db->fetch_array($query))
		{
			$fid = $row['fid'];
		}
	}
	
	if ($fid > 0)
	{
		$db->update_query("settings", array('value' => $fid), "name='steamprofile_fid'");
		rebuild_settings();
	}
}

function steamprofile_deactivate()
{
	global $sc_steamprofile;
	$sc_steamprofile->uninstall();
	$sc_steamprofile->deactivate();
}

/* POZA ACP */
$plugins->add_hook("postbit", "steamprofile_process"); 
$plugins->add_hook("member_profile_end", "steamprofile_profile");

function steamprofile_process(&$post)
{
	global $mybb, $templates;
	
	$sid_in_profile = trim($post["fid" . $mybb->settings['steamprofile_fid']]);

	if ( ! $xml = steamprofile_getSteamXML($sid_in_profile))
	{
		return false;
	}	
	
	$steamid64 = $xml->steamID64;
	$steamid = $xml->{"steamID"};
		
	if ($mybb->settings['steamprofile_mode'])
	{
		eval("\$post['steamprofile'] = \"".$templates->get("steamprofile_signature")."\";");
	}
	else
	{
		eval("\$post['user_details'] .= \"".$templates->get("steamprofile_link")."\";");
	}
}

function steamprofile_profile()
{
	global $mybb, $profilefields, $memprofile, $templates;
	
	$sid_in_profile = $memprofile["fid" . $mybb->settings['steamprofile_fid']];

	if ( ! $xml = steamprofile_getSteamXML($sid_in_profile))
	{
		return false;
	}
	
	$steamid64 = $xml->steamID64;
	$steamid = $xml->{"steamID"};
	
	eval("\$steamprofile_link .= \"".$templates->get("steamprofile_link")."\";");
	
	$profilefields = preg_replace("/{$sid_in_profile}/", $steamprofile_link, $profilefields);
}

function steamprofile_getSteamXML($sid_in_profile)
{
	if ( ! $sid_in_profile)
	{
		return false;
	}

	if (preg_match('#[0-9]{1}:([0-9]{1}):([0-9]+)#', $sid_in_profile, $results))
	{
		$steamcommunityid = bcadd((($results[2] * 2) + $results[1]), '76561197960265728');

		$xml = @simplexml_load_file("http://steamcommunity.com/profiles/".$steamcommunityid."/?xml=1");
	}
	elseif (preg_match('#7656119#', $sid_in_profile, $results))
	{
		$xml = @simplexml_load_file("http://steamcommunity.com/profiles/".$sid_in_profile."/?xml=1");
		
	}
	else
	{
		$xml = @simplexml_load_file("http://steamcommunity.com/id/".$sid_in_profile."/?xml=1");
	}
		
	if ( ! $xml->steamID64)
	{
		return false;
	}
	
	return $xml;
}
?>