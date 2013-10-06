<?

require_once('model.php');

class log extends recordset {
	var $tableName = 'sd_log';
	var $fields	   = array('texte'=>'TEXT');
	
	var $id, $texte;
	
}

function log_logInBase($string){
	$a = new log;
	$a->texte = $string;
	$a->save();
}
		
?>