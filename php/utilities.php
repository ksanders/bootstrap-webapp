<?php
function slugtext($value) {
	// replace non letter or digits by -
	$text = preg_replace('~[^\pL\d]+~u', '-', $value);
	// transliterate
	$text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
	// remove unwanted characters
	$text = preg_replace('~[^-\w]+~', '', $text);
	// trim
	$text = trim($text, '-');
	// remove duplicate -
	$text = preg_replace('~-+~', '-', $text);
	// lowercase
	$text = strtolower($text);
	if (empty($text)) {
	  return 'n-a';
	}
	return $text;
}

function lipsum($length = 100) {
	$lipsum = explode(' ','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec sagittis elementum rhoncus. Nullam rhoncus, nisl nec tempor blandit, risus orci hendrerit risus, et mattis eros quam a lorem. Proin vehicula, erat vitae lobortis varius, nunc sem interdum dui, in ultricies eros neque a metus. Ut dui metus, porta in sapien sed, tristique auctor odio. Morbi at dolor id diam malesuada maximus. Aliquam cursus id libero ut ultricies. Suspendisse vel fringilla ante. Ut sit amet facilisis sem, dapibus egestas tellus. Phasellus porttitor dui vel maximus laoreet. Donec in rutrum nisl. Donec sollicitudin suscipit sapien. Morbi quis est mollis, egestas felis a, tristique arcu. Proin eleifend lorem ac dignissim rhoncus. Fusce sagittis ut massa id dignissim. Proin convallis tempus varius. In ex quam, egestas et enim non, vehicula imperdiet tellus. Vivamus tortor dui, aliquet sed convallis et, posuere non lorem. Aenean pulvinar turpis enim, sed rutrum eros suscipit at. Curabitur pretium eros in tristique tempus. Aliquam erat tellus, finibus et tristique id, pharetra sit amet neque. Suspendisse aliquam mattis fringilla. Vivamus a lorem quis turpis pulvinar lobortis. Nullam vestibulum nulla eget venenatis finibus. Donec accumsan at purus eu dignissim. Suspendisse aliquet volutpat erat, at condimentum augue interdum ac. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque molestie nulla a magna pretium tristique. Integer ac magna at nisl ullamcorper accumsan at eu dolor. Cras tristique vel tellus sit amet tincidunt. Sed et elit eget nisl pharetra rutrum in sed nisl. Phasellus luctus libero in elit sodales euismod. Etiam risus erat, fringilla nec sem et, venenatis finibus felis. Aliquam aliquet velit ante, non semper odio egestas ut. Quisque non leo pellentesque, eleifend mi a, dapibus ligula. Donec scelerisque enim et dui sodales, quis vulputate orci posuere. Donec fermentum nibh ligula, in dictum quam cursus eu. Mauris finibus accumsan tellus at vehicula. Curabitur maximus, metus at pretium tincidunt, sapien magna congue lacus, eu fermentum elit tortor vitae massa. Fusce facilisis auctor nulla, et semper erat varius non. Nullam egestas est non felis laoreet, at dictum ipsum posuere. Vivamus tempor vulputate nisi a hendrerit. Nulla a est nisi. Praesent vitae condimentum elit. Donec commodo congue lectus ac mattis. Aenean id blandit leo. In non vulputate arcu. Cras in dolor finibus, congue mauris eget, vulputate dolor. Donec vulputate, metus quis rutrum malesuada, nisl diam blandit nisl, sit amet condimentum velit turpis et risus. Pellentesque dictum dignissim interdum. Etiam et lobortis neque, eu volutpat sapien. Etiam vitae dui ut sem scelerisque euismod. Ut quis purus metus. Mauris feugiat tempor justo, placerat luctus tellus finibus ac. Pellentesque scelerisque sapien eu bibendum consequat. Vestibulum suscipit risus sed tortor elementum imperdiet. Nulla facilisis dui id pharetra sodales. Fusce mollis, nisl vel malesuada suscipit, neque nulla accumsan nulla, sit amet congue eros lorem nec purus. Maecenas in tempor mauris. Donec posuere nec sapien a mattis. Aliquam posuere elementum nisi, a sodales lacus. Vivamus commodo turpis magna, sed lacinia nulla auctor at. Nulla metus sem, euismod id est sit amet, aliquam tempor odio. Cras ultricies orci id dui condimentum, ut placerat diam tincidunt. Fusce ac tincidunt lectus. Aliquam pretium hendrerit mi non fringilla. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Cras non feugiat neque.');
	$text = '';
	for ($i = 1; $i <= $length; $i++) {
		$text .= $lipsum[array_rand($lipsum)].' ';
	}
	return trim($text);
}
