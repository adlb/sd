<?

class recordset {
	var $errors = array();
	
	function delete(){
		Global $db, $prefixe_database;
		$sql = "DELETE FROM ".$prefixe_database.$this->tableName."";
		$sql .= " WHERE `id` = '".$this->id."' LIMIT 1";
		return mysql_query($sql, $db) or die("Erreur pendant la mise à jour de la table ".get_class($this)."! - $sql");
	}

	function createInDatabaseXX(){
		Global $db, $prefixe_database;
		if (!$this->tableExist()) {
			$this->createTable();
		}
		$sql = "INSERT INTO `".$prefixe_database.$this->tableName."` (";
		foreach ($this->fields as $key => $value) {
			$sql .= "`".$key."`, ";
		}
		$sql = substr($sql, 0, -2).") VALUES (";
		foreach ($this->fields as $key => $value) {
			switch ($value){
				case 'TEXT':
					$sql .= "'".mysql_escape_string($this->$key)."', ";
					break;
				case 'DATE':
					$sql .= "'".mysql_escape_string(date("Y-m-d",$this->$key))."', ";
					break;
				case 'BOOLEAN':
					if ($this->$key) {
						$sql .= "'1', ";
					} else {
						$sql .= "'0', ";
					}
					break;
				case 'PASSWORD':
					$sql .= "'".mysql_escape_string($this->$key)."', ";
					break;
				case 'INT':
					$sql .= "'".mysql_escape_string($this->$key)."', ";
					break;
				default:
					die("type $value non reconnu dans ".get_class($this)."!");
			}
		}
		$sql = substr($sql, 0, -2).")";
		$a = mysql_query($sql, $db) or die("ERREUR !  ".mysql_error());
		$this->id = mysql_insert_id();
		return $this->id;
	}
	
	function updateInDatabaseXX(){
		Global $db, $prefixe_database;
		$sql = "UPDATE `".$prefixe_database.$this->tableName."` SET ";
		foreach ($this->fields as $key => $value) {
			$sql .= "`".$key."` = ";
			switch ($value){
				case 'TEXT':
					$sql .= "'".mysql_escape_string($this->$key)."', ";
					break;
				case 'DATE':
					$sql .= "'".mysql_escape_string(date("Y-m-d",$this->$key))."', ";
					break;
				case 'BOOLEAN':
					if ($this->$key) {
						$sql .= "'1', ";
					} else {
						$sql .= "'0', ";
					}
					break;
				case 'INT':
					$sql .= "'".mysql_escape_string($this->$key)."', ";
					break;
				default:
					die("type $value non reconnu dans ".get_class($this)."!");
			}
		}
		$sql = substr($sql, 0, -2)." WHERE `id` = '".$this->id."'";
		return mysql_query($sql, $db) or die("Erreur pendant la mise à jour de la table ".get_class($this)."! - $sql");
	}
	
	function save(){
		if ($this->id != null) {
			return $this->updateInDatabaseXX();
		} else {
			return $this->createInDatabaseXX();
		}
	}
	
	function updateFromForm($param, $fields=null){
		Global $db;
		$errors = array();
		$myFields = ($fields==null)?$this->fields:$fields;
		
		foreach ($myFields as $key => $value) {
			switch ($value){
				case 'TEXT':
					if (!isset($param[$key])) {$param[$key]=null;}
					$this->$key = stripcslashes($param[$key]);	break;
				case 'DATE':
					if (!isset($param[$key])) {$param[$key]=null;}
					$this->$key = mktime(0,0,0,(int)substr($param[$key],3,2),(int)substr($param[$key],0,2),(int)substr($param[$key],6,4)); break;
				case 'BOOLEAN':
					if (!isset($param[$key])) {$param[$key]=false;}
					$this->$key = ($param[$key]=='true') ? 1 : 0; break;
				case 'INT':
					if (!isset($param[$key])) {$param[$key]=null;}
					$this->$key = $param[$key]; break;
				default:
					die("type $value non reconnu dans ".get_class($this)."!");
			}
		}
		if (method_exists($this, 'afterUpdate')) {
			$this->afterUpdate();
		}
	}
	
	function displayUpdateFormLines($fields=null){
		Global $db;
		$myFields = ($fields==null)?$this->fields:$fields;
		$st="<tr><td>Id</td><td>".$this->id."<INPUT TYPE=HIDDEN NAME=id VALUE=".$this->id."></td></tr>";
		foreach ($myFields as $key => $value) {
			switch ($value){
				case 'TEXT':
					$st .= "<tr><td>$key</td><td><input TYPE=\"TEXT\" NAME=\"".$key."\" SIZE=\"30\" VALUE=\"".$this->$key."\"></td></tr>";
					break;
				case 'DATE':
					$st .= "<tr><td>$key</td><td><input TYPE=\"TEXT\" NAME=\"$key\" SIZE=\"10\" VALUE=\"".date('d/m/Y',$this->$key)."\"></td></tr>";
					break;
				case 'BOOLEAN':
					$st2 = ($this->$key==1) ? "CHECKED" : "";
					$st .= "<tr><td>$key</td><td><INPUT TYPE=\"checkbox\" value=\"true\" name=\"$key\" $st2/></td></tr>";
					break;
				case 'INT':
					$st .= "<tr><td>$key</td><td><input TYPE=\"TEXT\" NAME=\"$key\" SIZE=\"10\" VALUE=\"".$this->$key."\"></td></tr>";
					break;
				default:
					die("type $value non reconnu dans ".get_class($this)."!");
			}
		}
		return $st;
	}
	
	function displayNewFormLines($fields=null){
		Global $db;
		$myFields = ($fields==null)?$this->fields:$fields;
		$st="";
		foreach ($myFields as $key => $value) {
			switch ($value){
				case 'TEXT':
					$st .= "<tr><td>$key</td><td><input TYPE=\"TEXT\" NAME=\"".$key."\" SIZE=\"30\" VALUE=\"".$this->$key."\"></td></tr>";
					break;
				case 'DATE':
					$st .= "<tr><td>$key</td><td><input TYPE=\"TEXT\" NAME=\"$key\" SIZE=\"10\" VALUE=\"".date('d/m/Y',$this->$key)."\"></td></tr>";
					break;
				case 'BOOLEAN':
					$ch = $this->$key==1 ? 'checked' : '';
					$st .= "<tr><td>$key</td><td><INPUT TYPE=\"checkbox\" value=\"true\" name=\"$key\" $ch></td></tr>";
					break;
				case 'INT':
					$st .= "<tr><td>$key</td><td><input TYPE=\"TEXT\" NAME=\"$key\" SIZE=\"10\" VALUE=\"".$this->$key."\"></td></tr>";
					break;
				default:
					die("type $key non reconnu dans ".get_class($this)."!");
			}
		}
		return $st;
	}
	
	function createFromFormXX($param, $fields=null){
		Global $db;
		$this->id = null;
		foreach ($this->fields as $key => $value) {
			switch ($value){
				case 'TEXT':
					if (isset($param[$key])) {
						$this->$key = stripcslashes($param[$key]);
					}
					break;case 'BOOLEAN':
				case 'INT':
					if (isset($param[$key])) {
						$this->$key = $param[$key];
					}
					break;
				case 'PASSWORD':
					if (isset($param[$key.'1']) && isset($param[$key.'2'])) {
						if ($param[$key.'1']==$param[$key.'2']){
							$this->$key = $param[$key.'1'];
						} else {
							$this->$key = null;
						}
					}
					break;
				case 'DATE':
					if (isset($param[$key])) {
						$this->$key = mktime(0,0,0,(int)substr($param[$key],3,2),(int)substr($param[$key],0,2),(int)substr($param[$key],6,4));
					}
					break;
				default:
					die("type $value non reconnu dans ".get_class($this)."!");
			}	
		}
		if (method_exists($this, 'afterCreate')) {
			$this->afterCreate();
		}
	}
	
	function getByIdXX($id) {
		Global $db, $prefixe_database;
		$tablename = $prefixe_database.$this->tableName;
		$sql = "SELECT * FROM $tablename WHERE id = ".$id." LIMIT 1";
		$req = mysql_query($sql, $db);
		if (!$req) {
			return null;
		}
		if (mysql_num_rows($req) == 0) {
			return null;
		}
		$item = mysql_fetch_assoc($req);
		$this->id = $item['id'];
		
		foreach ($this->fields as $key => $value) {
			switch ($value){
				case 'TEXT':
				case 'PASSWORD':
					$this->$key = $item[$key];
					break;	
				case 'DATE':
					$this->$key = mktime(0,0,0,(int)substr($item[$key],5,2),(int)substr($item[$key],-2,2),(int)substr($item[$key],0,4));
					break;
				case 'BOOLEAN':
					$this->$key = $item[$key];
					break;
				case 'INT':
					$this->$key = $item[$key];
					break;
				default:
					die("type $value non reconnu dans ".get_class($this)."!");
			}
		}
	}
	
	//constructeur
	function recordset($param=null, $fields=null){
		if (gettype($param)!='array') {
			$this->getByIdXX($param);
		} else {
			$this->createFromFormXX($param, $fields);
		}
	}
	
	function tableExist(){
		Global $db, $prefixe_database;
		return mysql_query("SELECT 1 FROM ".$prefixe_database.$this->tableName." LIMIT 0", $db);
	}

	function createTable(){
		Global $db, $prefixe_database;
		if (count($this->fields)==0) {
			die("Les champs de ".$prefixe_database.$this->tableName." ne sont pas définis !");
		}
		if ($this->tableExist()){
			die("La table de ".$prefixe_database.$this->tableName." existe déjà !");
		}
		$sql = "CREATE TABLE `".$prefixe_database.$this->tableName."` (";
		$sql = $sql."`id` INT UNSIGNED NOT NULL AUTO_INCREMENT , ";
		
		foreach ($this->fields as $key => $value) {
			$sql = $sql."`".$key."`" ;
			switch ($value){
				case 'TEXT':
					$sql .= " CHAR(50), ";
					break;
				case 'DATE':
					$sql .= " DATE, ";
					break;
				case 'BOOLEAN':
					$sql .= " BOOLEAN, ";
					break;
				case 'PASSWORD':
					$sql .= " CHAR(50), ";
					break;
				case 'INT':
					$sql .= " INT, ";
					break;
				default:
					die("type $value non reconnu dans ".$prefixe_database.$this->tableName."!");
			}
		}
		
		$sql .= " PRIMARY KEY (`id`)) CHARACTER SET utf8 COLLATE utf8_general_ci";
		
		return mysql_query($sql, $db) or die("Erreur pendant la création de la table ".$prefixe_database.$this->tableName."! - $sql");
	}

}

function recordset_get_all($model){
	Global $db, $prefixe_database;
	$obj = new $model;
	$sql = "SELECT id FROM ".$prefixe_database.$obj->tableName." ";
	
	$req = mysql_query($sql, $db);
	if (!$req || mysql_num_rows($req)==0) { return array();}
	$result = array();
	while ($res = mysql_fetch_assoc($req)) {
		$result[] = new $model($res['id']);
	}
	return $result;
}

function recordset_erase_all($model){
	Global $db, $prefixe_database;
	$obj = new $model;
	if ($obj->tableExist()) {
		$sql = "DROP TABLE `".$prefixe_database.$obj->tableName."`";
		return mysql_query($sql, $db) or die("Erreur pendant la suppression de la table ".$prefixe_database.$obj->tableName."! - $sql");
	}
}