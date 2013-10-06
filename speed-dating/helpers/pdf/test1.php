<?
include("phpToPDF.php");

$PDF = new phpToPDF();
$PDF->AddPage();
$PDF->SetFont("Arial","B",16);
$PDF->Text(40,10,"Uniquement un texte");
$PDF->Output();
?>