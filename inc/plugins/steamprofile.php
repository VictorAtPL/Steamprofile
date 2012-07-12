<?php
/*
Plugin Steamprofile
(c) 2012 by Victor
Website: http://www.victor.org.pl/
Version: v1.4
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
		"version"		=> "1.4",
		"compatibility"	=> "160*",
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
	$sc_steamprofile->addSetting("unique", NULL, "0", NULL, "onoff");
	
	$sc_steamprofile->addTemplateChange("postbit", "{\$post['subject']} {\$post['subject_extra']}</strong>", "{\$post['steamprofile']}", "after");
	$sc_steamprofile->addTemplateChange("postbit_classic", " class=\"post_body\">", "{\$post['steamprofile']}", "after");
	$sc_steamprofile->addTemplateChange("headerinclude", "{\$stylesheets}", "<script type=\"text/javascript\" src=\"inc/plugins/steamprofile/steamprofile.js\"></script>", "after");

	$template = <<<EOT
<div id="pid_steamprofile_{\$post['pid']}" class="steamprofile" style="float: right;" title="{\$sid_in_profile}"></div>
EOT;

	$sc_steamprofile->addNewTemplate("signature", $template);

	$template = <<<EOT
<div style="margin-left: 5px;"><a href="http://www.steamcommunity.com/profiles/{\$sid_in_profile}" title="{\$sid_in_profile}" target="_blank"><img src="images/steam.png" alt="" /></a></div>
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
$plugins->add_hook("pre_output_page", "steamprofile_addjquery");

function steamprofile_addjquery(&$output)
{
	// Add jquery if needed
	if (!preg_match("/noconflict/i", $output))
	{
		$jscode = '<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
<script type="text/javascript">jQuery.noConflict();</script>';

		$output = preg_replace("/<\/title>/", "$0\n{$jscode}", $output);
	}
	
	return $output;
}

function steamprofile_process(&$post)
{
	global $mybb, $templates, $alreadydone;
	
	if (!$alreadydone)
	{
		$alreadydone = array();
	}
	
	$sid_in_profile = trim($post["fid" . $mybb->settings['steamprofile_fid']]);
	
	if (!check_sid_in_profile($post['uid'], $sid_in_profile) || (in_array($post['uid'], $alreadydone) && $mybb->settings['steamprofile_unique']))
	{
		return false;
	}
	
	if ($mybb->settings['steamprofile_mode'])
	{
		eval("\$post['steamprofile'] = \"".$templates->get("steamprofile_signature")."\";");
	}
	else
	{
		eval("\$post['user_details'] .= \"".$templates->get("steamprofile_link")."\";");
	}
	
	$alreadydone[] = $post['uid'];
	return $post;
}

function steamprofile_profile()
{

	global $mybb, $profilefields, $memprofile, $templates, $userfields;
	
	$sid_in_profile = $userfields["fid" . $mybb->settings['steamprofile_fid']];

	if (!check_sid_in_profile($memprofile['uid'], $sid_in_profile))
	{
		return false;
	}
	
	eval("\$steamprofile_link = \"".$templates->get("steamprofile_link")."\";");
	
	$profilefields = preg_replace("/". $sid_in_profile . "/", $steamprofile_link, $profilefields);
}

function check_sid_in_profile($uid, $sid_in_profile)
{
	global $mybb, $db;
	
	if (!$sid_in_profile)
	{
		return false;
	}
	elseif (preg_match('#7656119#', $sid_in_profile))
	{
		return $sid_in_profile;
	}
	elseif (preg_match('#[0-9]{1}:([0-9]{1}):([0-9]+)#', $sid_in_profile, $results))
	{
		$steamcommunityid = bcadd((($results[2] * 2) + $results[1]), '76561197960265728');

		$xml = @simplexml_load_file("http://steamcommunity.com/profiles/".$steamcommunityid."/?xml=1");
	}
	else
	{
		$xml = @simplexml_load_file("http://steamcommunity.com/id/".$sid_in_profile."/?xml=1");
	}
		
	if ($xml->{"steamID64"})
	{
		$update_array = array(
			"fid" . $mybb->settings['steamprofile_fid'] => $xml->{"steamID64"}
			);
				
		$db->update_query("userfields", $update_array, "ufid=" . $uid);			
		$sid_in_profile = $xml->{"steamID64"};
		return $sid_in_profile;
	}
	/*else
	{
		$update_array = array(
			"fid" . $mybb->settings['steamprofile_fid'] => ""
			);
			
		$db->update_query("userfields", $update_array, "ufid=" . $uid);
		
	}*/
	
	return false;
}
?>