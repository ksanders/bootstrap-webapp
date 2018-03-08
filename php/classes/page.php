<?php
class Page {
	private $title = TITLE_DEFAULT;
	private $bodyclasses = array();
	private $containerclass = 'container';
	private $stylesheets = array('/style/style.css');
	private $scripts = array('/js/script.js');
	private $inlinescripts = array();
	private $precontent = array();
	private $breadcrumbs = array();
	private $content = array();
	private $postcontent = array();
	private $debug = false;
	private $debuginfo = false;
	private $menu = array();
	private $usedids = array();

	function __construct() {
		if (defined('DEBUG')) {
			$this->debug = true;
			error_log('DEBUG: '.__METHOD__);
		}
	}

	public function __get($property) {
		if (defined('DEBUG')) {
			error_log('DEBUG: '.__METHOD__.'('.$property.')');
		}
		if( method_exists( $this , $method = ( 'get' . ucfirst( $property  ) ) ) ) {
			return $this->$method();
		} else if (property_exists($this, $property)) {
			return $this->$property;
		} else {
			return false;
		}
	}

	public function __set($property, $value) {
		if (defined('DEBUG')) {
			error_log('DEBUG: '.__METHOD__.'('.$property.')');
		}
		if( method_exists( $this , $method = ( 'set' . ucfirst( $property  ) ) ) ) {
			return $this->$method();
		} else if (property_exists($this, $property)) {
			$this->$property = $value;
		}

		return $this;
	}

	public function __isset($property) {
		if (defined('DEBUG')) {
			error_log('DEBUG: '.__METHOD__.'('.$property.')');
		}
		if (property_exists($this, $property)) {
			return true;
		} else {
			return false;
		}
	}

	public function getMenu() {
		if (defined('DEBUG')) {
			error_log('DEBUG: '.__METHOD__);
		}
		foreach ($this->menu as &$item) {
			if (!isset($item['id'])) {
				$item['id'] = $this->getUid($item['text']);
			}
			if (!isset($item['attrs'])) {
				$item['attrs'] = false;
			}
			if (isset($item['children'])) {
				foreach ($item['children'] as &$child) {
					if (!isset($child['id'])) {
						$child['id'] = $this->getUid($child['text']);
					}
					if (!isset($child['attrs'])) {
						$child['attrs'] = false;
					}
					if (isset($child['active']) && $child['active']) {
						$item['activechild'] = true;
					}
				}
			}
		}
		return $this->menu;
	}

	function getUid($text) {
		if (defined('DEBUG')) {
			error_log('DEBUG: '.__METHOD__.'('.$text.')');
		}
		$text = slugtext($text);
		$found = false;
		$i = 0;
		while (!$found) {
			if (!in_array($text.($i == 0?'':'-'.$i),$this->usedids)) {
				$this->usedids[] = $text.($i == 0?'':'-'.$i);
				$found = true;
				return $text.($i == 0?'':'-'.$i);
			}
			$i++;
			error_log($i);
		}
	}

	public function addBreadcrumb($text,$url=false,$active=false) {
		$this->breadcrumbs[] = array(
			'text' => $text,
			'url' => $url,
			'id' => $this->getUid('breadcrumb '.$url),
			'active' => $active,
		);
		return $this->breadcrumbs;
	}

	public function addMenuItem($id,$title,$url=false,$parent=false,$options=array()) {
		if (defined('DEBUG')) {
			error_log('DEBUG: '.__METHOD__.'('.$id.','.$title.','.$url.','.$parent.','.print_r($options,true).')');
		}
		$newitem = array();
		$newitem['text'] = $title;
		if ($url) {
			$newitem['url'] = $url;
		}
		$newitem['id'] = $this->getUid($id);
		if (!empty($options)) {
			foreach ($options as $k => $v) {
				$newitem[$k] = $v;
			}
		}
		if (!$parent) {
			$this->menu[] = $newitem;
		} else {
			$i = false;
			foreach ($this->menu as $k => $v) {
				if ($v['id'] == $parent) {
					$i = $k;
				}
			}
			if ($i === false) {
				error_log('Invalid menu-item ID: '.$parent);
				$this->menu[] = $newitem;
				return $this->menu;
			} else {
				if (!isset($this->menu[$i]['children'])) {
					$this->menu[$i]['children'] = array();
				}
				$this->menu[$i]['children'][] = $newitem;
			}
		}
		return $this->menu;
	}

	public function addMenuItemBefore($target,$id,$title,$url=false,$options=array()) {
		if (defined('DEBUG')) {
			error_log('DEBUG: '.__METHOD__.'('.$target.','.$id.','.$title.','.$url.','.print_r($options,true).')');
		}
		$newitem = array();
		$newitem['text'] = $title;
		if ($url) {
			$newitem['url'] = $url;
		}
		$newitem['id'] = $this->getUid($id);
		if (!empty($options)) {
			foreach ($options as $k => $v) {
				$newitem[$k] = $v;
			}
		}

		$i = false;
		foreach ($this->menu as $k => $v) {
			if ($v['id'] == $target) {
				$i = $k;
			}
		}
		if ($i === false) {
			foreach ($this->menu as $k => $v) {
				if (isset($v['children']) && is_array($v['children']) && !empty($v['children'])) {
					foreach ($v['children'] as $kk => $vv) {
						if ($vv['id'] == $target) {
							array_splice($this->menu[$k]['children'],$kk,0,array($newitem));
							return $this->menu;
						}
					}
				}
			}
			error_log('Invalid menu-item ID: '.$target);
			$this->menu[] = $newitem;
			return $this->menu;
		} else {
			array_splice($this->menu,$i,0,array($newitem));
		}
		return $this->menu;
	}

	public function addMenuItemAfter($target,$id,$title,$url=false,$options=array()) {
		if (defined('DEBUG')) {
			error_log('DEBUG: '.__METHOD__.'('.$target.','.$id.','.$title.','.$url.','.print_r($options,true).')');
		}
		$newitem = array();
		$newitem['text'] = $title;
		if ($url) {
			$newitem['url'] = $url;
		}
		$newitem['id'] = $this->getUid($id);
		if (!empty($options)) {
			foreach ($options as $k => $v) {
				$newitem[$k] = $v;
			}
		}

		$i = false;
		foreach ($this->menu as $k => $v) {
			if ($v['id'] == $target) {
				$i = $k;
			}
		}
		if ($i === false) {
			foreach ($this->menu as $k => $v) {
				if (isset($v['children']) && is_array($v['children']) && !empty($v['children'])) {
					foreach ($v['children'] as $kk => $vv) {
						if ($vv['id'] == $target) {
							array_splice($this->menu[$k]['children'],$kk+1,0,array($newitem));
							return $this->menu;
						}
					}
				}
			}
			error_log('Invalid menu-item ID: '.$target);
			$this->menu[] = $newitem;
			return $this->menu;
		} else {
			array_splice($this->menu,$i+1,0,array($newitem));
		}
		return $this->menu;
	}

	public function removeMenuItem($target,$preservechildren=false) {
		if (defined('DEBUG')) {
			error_log('DEBUG: '.__METHOD__.'('.$target.')');
		}
		$i = false;
		foreach ($this->menu as $k => $v) {
			if ($v['id'] == $target) {
				$i = $k;
			}
		}
		if ($i === false) {
			foreach ($this->menu as $k => $v) {
				if (isset($v['children']) && is_array($v['children']) && !empty($v['children'])) {
					foreach ($v['children'] as $kk => $vv) {
						if ($vv['id'] == $target) {
							array_splice($this->menu[$k]['children'],$kk,1,array());
						}
					}
				}
			}
		} else {
			if ($preservechildren && isset($this->menu[$i]['children']) && is_array($this->menu[$i]['children']) && !empty($this->menu[$i]['children'])) {
				array_splice($this->menu,$i,1,$this->menu[$i]['children']);
			} else {
				array_splice($this->menu,$i,1,array());
			}
		}
		return $this->menu;
	}

	public function modifyMenuItem($target,$options=array()) {
		if (defined('DEBUG')) {
			error_log('DEBUG: '.__METHOD__.'('.$target.','.print_r($options,true).')');
		}
		foreach ($this->menu as &$v) {
			if ($v['id'] == $target) {
				foreach ($options as $attr => $val) {
					$v[$attr] = $val;
				}
				return $this->menu;
			} else if (isset($v['children']) && is_array($v['children']) && !empty($v['children'])) {
				foreach ($v['children'] as $kk => &$vv) {
					if ($vv['id'] == $target) {
						foreach ($options as $attr => $val) {
							$vv[$attr] = $val;
						}
						return $this->menu;
					}
				}
			}
		}
		error_log('Invalid menu-item ID: '.$target);
		return $this->menu;
	}

	public function activeMenuItem($target) {
		if (defined('DEBUG')) {
			error_log('DEBUG: '.__METHOD__.'('.$target.')');
		}
		foreach ($this->menu as &$v) {
			if ($v['id'] == $target) {
				$v['active'] = true;
			} else {
				$v['active'] = false;
			}
			if (isset($v['children']) && is_array($v['children']) && !empty($v['children'])) {
				foreach ($v['children'] as $kk => &$vv) {
					if ($vv['id'] == $target) {
						$vv['active'] = true;
					} else {
						$vv['active'] = false;
					}
				}
			}
		}
		error_log('Invalid menu-item ID: '.$target);
		return $this->menu;
	}

	public function addStyle($newstyle,$external = false) {
		if (defined('DEBUG')) {
			error_log('DEBUG: '.__METHOD__.'('.$newstyle.','.$external.')');
		}
		if (!$external) {
			if (substr($newstyle,0,7) != '/style/') {
				$newstyle = '/style/'.$newstyle;
			}
		}
		if (!in_array($newstyle,$this->stylesheets)) {
			$this->stylesheets[] = $newstyle;
		}
	}
	public function addJs($newscript,$external=false,$inline=false) {
		if (defined('DEBUG')) {
			error_log('DEBUG: '.__METHOD__.'('.$newstyle.','.$external.','.$inline.')');
		}
		if ($inline) {
			$this->inlinescripts[] = $newscript;
		}
		if (!$external) {
			if (substr($newscript,0,4) != '/js/') {
				$newscript = '/js/'.$newscript;
			}
		}
		if (!in_array($newscript,$this->scripts)) {
			$this->scripts[] = $newscript;
		}
	}
	function addPreContent($value) {
		if (defined('DEBUG')) {
			error_log('DEBUG: '.__METHOD__.'('.$value.')');
		}
		$this->precontent[] = $value;
		return $this;
	}

	function addContent($value) {
		if (defined('DEBUG')) {
			error_log('DEBUG: '.__METHOD__.'('.$value.')');
		}
		$this->content[] = $value;
		return $this;
	}

	function addPostContent($value) {
		if (defined('DEBUG')) {
			error_log('DEBUG: '.__METHOD__.'('.$value.')');
		}
		$this->postcontent[] = $value;
		return $this;
	}
}
