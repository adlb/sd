<?
//include 'generalParameters.php';

function login_action_logout() {
	$_SESSION['adminAccredit']='no';
	unset($_SESSION['adminAccredit']);
	headerLocation('login', array());
}

function login_action_login() {
	global $adminSitePassword;
	if (isset($_POST['pwd']) && $_POST['pwd']==$adminSitePassword) {
		$_SESSION['adminAccredit']='yes';
		headerLocation('parties', array());
	} else {
		headerLocation('login', array());
	}
}

function login_isAdmin(){
	return (isset($_SESSION['adminAccredit']) && $_SESSION['adminAccredit']=='yes');
}

?>