<?php
require_once('lib/include.php');
import('phpdocwriter.pdw_document');

$sxw = new pdw_document;
$sxw->SetLanguage('en','US');
//$sxw->AddPageDef(array('name'=>'Standard','w'=>$w, 'h'=>$h, 'margins'=>$margins));
//$sxw->SetLanguage('es','ES');

$sxw->SetStdFont ("Times New Roman",10);
$sxw->SetFont (array('family'=>'Univers','style'=>'BI','size'=>27));
$sxw->Write ('Podemos ');
$sxw->SetFont (array('family'=>'Arial','style'=>'U','size'=>22));
$sxw->Write ('aplicar ');
$sxw->Ln();
$sxw->SetFont (array('family'=>'Comic Sans MS','size'=>32));
$sxw->Write ('DIFERENTES ');
$sxw->Ln();
$sxw->SetFont (array('family'=>'Verdana','style'=>'B','size'=>22));
$sxw->Write ('tipos de letra ');
$sxw->SetFont (array('family'=>'Univers','style'=>'UI','size'=>24));
$sxw->Write ('al texto');

#if (isset ($_REQUEST["format"]) && $_REQUEST["format"]!='') $sxw->SetExportFilter ($_REQUEST["format"]);
#if (isset ($_REQUEST["zip"])) $sxw->CompressOutput();
$sxw->CompressOutput();
$sxw->Output('teste2.sxw');
?>
