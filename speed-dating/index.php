<?

	if (!isset($_GET['obj'])) {$obj = 'sd';} else {$obj = $_GET['obj'];}
	if (!isset($_GET['view'])) {$view = 'view';} else {$view = $_GET['view'];}
	if (!isset($_GET['action'])) {$action = '';} else {$action = $_GET['action'];}
	if (!isset($_GET['id'])) {$id = '';} else {$id = $_GET['id'];}
	$a = $_SERVER['REQUEST_URI'];
	$uri = "http://".$_SERVER['HTTP_HOST'].substr($a,0,strrpos($a,'/'));
	if (strpos($uri,'sandbox')===false) {
		$modegeneral = "normal";
	} else {
		$modegeneral = "sandbox";
	}

	include('config.php');
	include('helpers/helper_general.php');
	
	if ($obj != '') {
		if (file_exists('helpers/helper_'.$obj.'.php')) {
			include('helpers/helper_'.$obj.'.php');
		}
		
		if (file_exists('controllers/controller_'.$obj.'.php')) {
			include('controllers/controller_'.$obj.'.php');
		}
		
        if (file_exists('models/model_'.$obj.'.php')) {
			include('models/model_'.$obj.'.php');
            $$obj = new $obj($id);
		}
		
		$function_name = $obj.'_controller_before_action_or_view';
		if (function_exists($function_name)) {
			$function_name($action, $view);
		}

		if ($action == '') {
			$function_name = $obj.'_controller_before_view';
			if (function_exists($function_name)) {
				$function_name($view);
			}
			if (function_exists($obj.'_prepare_'.$view)) {
				$function_name = $obj.'_prepare_'.$view;
				$function_name();
			}
			if (file_exists('views/'.$obj.'_'.$view.'.php')) {
				include 'views/'.$obj.'_'.$view.'.php';
				exit();
			}
		} else {
			$function_name = $obj.'_controller_before_action';
			if (function_exists($function_name)) {
				$function_name($action);
			}
			$function_name = $obj.'_action_'.$action;
			if (function_exists($function_name)) {
				$function_name();
				exit();
			}
		}
	}
header('Location: ?');
?>