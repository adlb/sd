<html>
<head>
<title>Speed-Dating SP</title>
<script src="ressources/ajax.js" type="text/javascript"></script>
<meta http-equiv="Content-type" content="text/html; charset=UTF-8"/>
<style type="text/css">

th {
	font-weight: bold;
}
td {
    padding: 2;
}
body {
	align-text: center;
	font-size: 12px;
    font-family: sans-serif;
	color: black;
}
textarea {
	font-family: "Comic Sans MS","Trebuchet MS",Times,"Times New Roman",serif;
	font-size: 10px;
}
table {
	font-size: 12px;
    margin: 0 auto;
}

input {
	border: 1px solid #CCCCCC;
    font-size: 11px;
    padding: 2px 0;
	color: black;
}


body {
	border: 0 none;
    margin: 0;
    padding: 15 0 0 0;
    text-align: left;
    vertical-align: baseline;
}

.radio {
	width: 30px;
	border: 0px;
}

H1, H2 {
	font-size: 20px;
    font-weight: normal;
    height: 25px;
    line-height: 25px;
    margin: 0 0;
    text-align: center;
}

H2 {
	font-size: 15px;
}
P{
	text-align: center;
}

.validez {
	width: 80px;
    background: url("ressources/bt_validez.png") no-repeat scroll left top transparent;
    border: 0 none;
    cursor: pointer;
    display: block;
    font-size: 0;
    height: 30px;
    line-height: 0;
    text-indent: -9999em;
}

#login {
	background-color: #8cc600;
	border: 1px solid #e81cc0;
    color: black;
	position: relative;
	width: 350px;
	top: 33%;
	margin: 100px auto;
	padding: 15px;
	text-align: center;
}

#message {
	background-color: #DEFFCA;
	font-size: 15px;
	color: black;
	position: fixed;
	width: 250px;
	left: 55px;
	top: 55px;
	border: 1px solid #e81cc0;
	padding: 5px;
}

a.bouton {
  color: white;
  background-color: #000080;
  text-decoration: none;
  font-weight: bold;
  text-align: center;
  line-height: 30px;
  padding: 5px;
}

a.bouton:hover {
  background-color: #6495ED;
  //background-image: url(aqua.jpg);
}

table.tableau /* Le tableau en lui-même */
{
   margin: auto; /* Centre le tableau */
   border: 4px outset green; /* Bordure du tableau avec effet 3D (outset) */
   border-collapse: collapse; /* Colle les bordures entre elles */
}

table.tableau th /* Les cellules d'en-tête */
{
   background-color: #006600;
   color: white;
   font-size: 1.1em;
   font-family: Arial, "Arial Black", Times, "Times New Roman", serif;
}

table.tableau td /* Les cellules normales */
{
   border: 1px solid black;
   font-family: "Comic Sans MS", "Trebuchet MS", Times, "Times New Roman", serif;
   text-align: center; /* Tous les textes des cellules seront centrés*/
   padding: 5px; /* Petite marge intérieure aux cellules pour éviter que le texte touche les bordures */
}

#floatimage {
	background-color: #8cc600;
	color: black;
	position: absolute;
	right: 5px;
	top: 5px;
	border: 1px solid #e81cc0;
}

.floatright{
float: right;
padding: 2%;
cursor: pointer; 
} 
div#header{ 
  position:absolute;
  top:0;
  left:0;
  width:100%;
  height:20;
  text-align: center;
  background-color: orange;
  z-index:1000;
 }
@media screen{
  body>div#header{
   position: fixed;
  }
 }
 * html body{
  overflow:hidden;
 } 
 * html div#content_wrap{
  height:100%;
  overflow:auto;
 }
</style>
</head>
<body>
<?=displayMessage()?>
<? if ($modegeneral=='sandbox') {echo '<div id="header"><b>mode sand-box</b>
<img class="floatright" src="ressources/delete.png" onclick="document.getElementById(\'header\').style.visibility=\'hidden\';">
				</div>';} ?>