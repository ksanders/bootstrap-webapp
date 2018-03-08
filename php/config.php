<?php
define('DEBUG',true);

define('IN_APP',true);
define('ROOT',dirname(__FILE__).'/');
define('TITLE_DEFAULT','New App');
require_once(ROOT.'utilities.php');
if (defined('JSON')) {
	require_once(ROOT.'json.renderer.php');
} else {
	require_once(ROOT.'classes/page.php');
	$page = new Page;
	error_log($page->title);
	require_once(ROOT.'renderer.php');
}
function testfunc() {
	return;
}
