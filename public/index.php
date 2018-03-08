<?php
require(dirname($_SERVER['DOCUMENT_ROOT']).'/php/config.php');


$page->addMenuItem('test','Test 1','test.php',false,array('attrs'=>'data-test="BUM"'));
$page->addMenuItem('test2','Test 2');
$page->addMenuItem('test3','Test 3','test.php','test2');
$page->addMenuItemBefore('test3','test4','Test 4','test.php');
$page->addMenuItemAfter('test3','test5','Test 5','test.php');
$page->activeMenuItem('test');

$page->addBreadcrumb(lipsum(rand(1,3)),'1.php');
$page->addBreadcrumb(lipsum(rand(1,3)));
$page->addBreadcrumb(lipsum(rand(1,3)),'1.php');
$page->addBreadcrumb(lipsum(rand(1,3)),false,true);

$page->addContent(render('menu',$page,true));
$content = array(lipsum(50),lipsum(50),lipsum(50));

$page->addContent(alert(lipsum(20)));
$page->addContent(alert(lipsum(20),'warning'));
$page->addContent(alert(lipsum(20),'danger',true));
$page->addContent(alert(lipsum(20),'primary',true,array('class'=>'sashascjhb')));
$page->addContent(row($content,'md'));
$page->addContent(row($content,array('sm-6','sm-3','sm-3')));
$page->addContent(row($content,array('sm-6','sm-3','sm-3'),array(),'Bunghole'));
$page->addContent(row($content,array('sm-6','sm-4'),array('test'),'Bunghole'));

render_page();
