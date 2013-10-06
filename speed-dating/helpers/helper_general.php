<?
@session_start();

	function headerLocation($obj, $param=array()) {
		//echo '<a href=?'.get_url_link($obj, $param).'>'.get_url_link($obj, $param).'</a>';
		header('Location: ?'.get_url_link($obj, $param));
		exit();
	}
	
	function headerView($obj, $param=array()) {
		if (gettype($obj)!='string') {
			$objectName = get_class($obj);
			$$objectName = $obj;
		} else {
			$objectName = $obj;
		}

		if (!isset($param['view'])) {$param['view']='view';}
		return 'views/'.$objectName.'_'.$param['view'].'.php';
	}
	
	function t($string, $size=0){
		if ($size !=0 && strlen($string)>$size) {
			return htmlentities(substr($string,0,$size-1)."...", ENT_COMPAT, "UTF-8");
		}
		return htmlentities($string, ENT_COMPAT, "UTF-8");
	}
	
	function toLowerWithFirstUpper($st) {
		$st = strtolower($st);
		$starray = explode(' ', $st);
		for($i=0;$i<count($starray);$i++) {
			$starray[$i]=ucfirst($starray[$i]);
		}
		$st = implode(' ', $starray);
		
		$starray = explode('\'', $st);
		for($i=0;$i<count($starray);$i++) {
			$starray[$i]=ucfirst($starray[$i]);
		}
		$st = implode('\'', $starray);
		
		$starray = explode('-', $st);
		for($i=0;$i<count($starray);$i++) {
			$starray[$i]=ucfirst($starray[$i]);
		}
		$st = implode('-', $starray);
		return $st;
	}
	
	function addMessage($st) {
		if (!isset($_SESSION['message'])) {
			$_SESSION['message']=array($st);
		} else {
			$_SESSION['message'][]=$st;
		}
	}
	
	function displayMessage(){
		if (isset($_SESSION['message'])) {
			if (gettype($_SESSION['message'])!='array') {
				$_SESSION['message'] = array($_SESSION['message']);
			}
			$st = array();
			foreach($_SESSION['message'] as $item){
				if ($item != '') {
					$st[] = '<li>'.$item.'</li>';
				}
			}
			
			unset($_SESSION['message']);
			
			if (count($st)>0) {
				return '<div id=message>
				<img class="floatright" src="ressources/delete.png" onclick="
				document.getElementById(\'message\').parentNode.removeChild(document.getElementById(\'message\'));">
				<b>Message</b><br/><ul>'.implode('',$st).'</ul></div>';
			}
		}
		return '';
	}
	
	function get_url_link($obj, $param=array()){
		$st = "";
		if ($obj != null && gettype($obj) != 'string') {
			$st .= '&obj='.urlencode(get_class($obj)).'&id='.urlencode($obj->id);
		} elseif ($obj != null && gettype($obj) == 'string') {
			$st .= '&obj='.urlencode($obj);
		}
		foreach($param as $key=>$value) {
			$st .= '&'.$key.'='.urlencode($value);
		}
		return $st;
	}
	
	function displayLink($display, $obj, $param=array()){
		$st = "<a href=?";
		$st .= get_url_link($obj, $param);
		$st .= ' class=\'bouton\'>'.t($display).'</a>';
		return $st;
	}
    
    function displayLinkAjax($display, $obj, $param=array(), $cible){
		
        $st = "<a href=\"javascript:;\" ";
		$st .= "onclick=\"submitForm('?".get_url_link($obj, $param)."','".$cible."');\">";
		$st .= t($display).'</a>';
		return $st;
	}
	
	function displayLinkImage($display, $obj, $param=array()){
		$st = "<a href=?";
		$st .= get_url_link($obj, $param);
		$st .= '><img src="ressources/'.$display.'"></a>';
		return $st;
	}
    
	function displayLinkImageAjax($display, $obj, $param=array(), $cible){
		$st = "<a href=\"javascript:;\" ";
        $st .= "onclick=\"submitForm('?".get_url_link($obj, $param)."','".$cible."');\">";
		$st .= '<img src="ressources/'.$display.'"></a>';
		return $st;
	}
	function displayForm($obj, $param=array()){
		$st = "<FORM ACTION=?";
		$st .= get_url_link($obj, $param);
		$unique = substr(md5(microtime()),0,4);
		$st .= '  METHOD="POST" ENCTYPE="application/x-www-form-urlencoded" name="form'.$unique.'">';
		return $st;
	}
?>