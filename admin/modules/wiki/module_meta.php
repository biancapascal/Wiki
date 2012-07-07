<?php
// Disallow direct access to this file for security reasons
if(!defined("IN_MYBB"))
{
	die("Direct initialization of this file is not allowed.<br /><br />Please make sure IN_MYBB is defined.");
}

function wiki_meta()
{
	global $page, $lang, $db;
	$lang->load("wiki");

	$query = $db->simple_select("settinggroups", "gid", "name='Wiki'");
    $g = $db->fetch_array($query);

	$sub_menu = array();
	$sub_menu['5'] = array("id" => "index", "title" => $lang->wiki_index, "link" => "index.php?module=wiki");
	$sub_menu['10'] = array("id" => "article", "title" => $lang->wiki_article, "link" => "index.php?module=wiki-article");
	$sub_menu['15'] = array("id" => "option", "title" => $lang->wiki_option, "link" => "index.php?module=config-settings&action=change&gid=".$g['gid']);
	$sub_menu['20'] = array("id" => "import", "title" => $lang->wiki_import, "link" => "index.php?module=wiki-import");
	$sub_menu['25'] = array("id" => "update", "title" => $lang->wiki_update, "link" => "index.php?module=wiki-update");
	
	$query = $db->simple_select("settinggroups", "gid", "name='Wiki'");
	if($db->num_rows($query))
		$page->add_menu_item($lang->wiki, "wiki", "index.php?module=wiki", 70, $sub_menu);
	return true;
}

function wiki_action_handler($action)
{
	global $page, $lang;
	
	$page->active_module = "wiki";
	
	$actions = array(
		'index' => array('active' => 'index', 'file' => 'home.php'),
		'article' => array('active' => 'article', 'file' => 'article.php'),
		'import' => array('active' => 'import', 'file' => 'import.php'),
		'update' => array('active' => 'update', 'file' => 'update.php')
	);
		
	if(isset($actions[$action]))
	{
		$page->active_action = $actions[$action]['active'];
		return $actions[$action]['file'];
	}
	else
	{
		$page->active_action = "index";
		return "home.php";
	}
}

function postinpoints_admin_permissions()
{
	global $lang;
	
	$admin_permissions = array(
		"index"	=> $lang->wiki_permissions_index,
		"article"	=> $lang->wiki_permissions_article,
		"import"	=> $lang->wiki_permissions_import,
		"update"	=> $lang->wiki_permissions_update
	);
	
	require_once MYBB_ROOT."inc/plugins/wiki.php";
	if(wiki_is_installed())
		return array("name" => "wiki", "permissions" => $admin_permissions, "disporder" => 70);
}
?>