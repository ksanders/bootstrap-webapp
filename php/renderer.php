<?php
require(dirname($_SERVER['DOCUMENT_ROOT']).'/php/Handlebars-master/src/Handlebars/Autoloader.php');
Handlebars\Autoloader::register();
use Handlebars\Handlebars;
$m = new Handlebars(array(
    'loader' => new \Handlebars\Loader\FilesystemLoader(__DIR__.'/templates/'),
    'partials_loader' => new \Handlebars\Loader\FilesystemLoader(
        dirname($_SERVER['DOCUMENT_ROOT']).'/php/templates/',
        array(
            'prefix' => '_'
        )
    )
));

function render($template,$data,$return=true) {
	global $m;
	$tpl = $m->loadTemplate($template);
	$output = $tpl->render($data);
	if ($return) {
		return($output);
	} else {
		echo $output;
	}
}

function render_page($return=false) {
	global $page;
	if (defined('DEBUG')) {
		$page->debuginfo = print_r($page,true);
	}
	render('header',$page,$return);
	render('footer',$page,$return);
}


function row($content,$size='sm',$classes=array(),$rowclass=false) {
	$contentarray = array();
	if (is_array($size)) {
		foreach ($content as $k => $row) {
			$contentarray[] = array(
				'text' => $row,
				'size' => (array_key_exists($k,$size)?$size[$k]:false),
				'class' => (array_key_exists($k,$classes)?$classes[$k]:false),
			);
		}
	} else {
		foreach ($content as $k => $row) {
			$contentarray[] = array(
				'text' => $row,
				'size' => $size,
				'class' => (array_key_exists($k,$classes)?$classes[$k]:false),
			);
		}
	}
	return render('row',array('content'=>$contentarray,'rowclass'=>$rowclass),true);
}


function alert($content,$type='info',$dismissable=false,$options=array()) {

    return render('alert',array('content'=>$content,'type'=>$type,'dismissable'=>$dismissable,'options'=>$options),true);
}
