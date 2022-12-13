<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa e software livre; voce pode redistribui-lo e/ou     
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versao 2 da      
 *  Licenca como (a seu criterio) qualquer versao mais nova.          
 *                                                                    
 *  Este programa e distribuido na expectativa de ser util, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de              
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM           
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU     
 *  junto com este programa; se nao, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Copia da licenca no diretorio licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

include("fpdf151/scpdf.php");
include("fpdf151/assinatura.php");
include("classes/db_rhvisavale_classe.php");
include("classes/db_rhvisavalecad_classe.php");
include("classes/db_db_config_classe.php");
include("classes/db_db_docparag_classe.php");
$classinatura = new cl_assinatura;
$clrhvisavale = new cl_rhvisavale;
$clrhvisavalecad = new cl_rhvisavalecad;
$cldb_config = new cl_db_config;
$cldb_docparag = new cl_db_docparag;
$clrotulo = new rotulocampo;

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

$sql_rhvisavale = $clrhvisavale->sql_query_file(db_getsession("DB_instit")," * ");
$result_rhvisavale = $clrhvisavale->sql_record($sql_rhvisavale);
$numrows_rhvisavale = $clrhvisavale->numrows;
if($numrows_rhvisavale == 0){
  db_redireciona('db_erros.php?fechar=true&db_erro=Não existem Vales cadastrados no período de '.$mes.' / '.$ano);
}
db_fieldsmemory($result_rhvisavale, 0);

$dbwhere = "rh49_instit = ".db_getsession("DB_instit")." and rh49_anousu = ".$ano." and rh49_mesusu = ".$mes;
if(isset($campo_auxilio_regi) && trim($campo_auxilio_regi) != ""){
	$dbwhere .= " and rh49_regist in (".$campo_auxilio_regi.")";
}

$sql_rhvisavalecad = $clrhvisavalecad->sql_query_documentos(null," * ","z01_nome", $dbwhere);
$result_rhvisavalecad = $clrhvisavalecad->sql_record($sql_rhvisavalecad);
$numrows_rhvisavalecad = $clrhvisavalecad->numrows;
if($numrows_rhvisavalecad == 0){
  db_redireciona('db_erros.php?fechar=true&db_erro=Não existem Vales cadastrados no período de '.$mes.' / '.$ano);
}

$result_db_config = $cldb_config->sql_record($cldb_config->sql_query_file(db_getsession("DB_instit"),"*"));
if($cldb_config->numrows > 0){
	db_fieldsmemory($result_db_config, 0);
}

$dia = date("d",db_getsession("DB_datausu"));
$mes = date("m",db_getsession("DB_datausu"));
$ano = date("Y",db_getsession("DB_datausu"));

// die($cldb_docparag->sql_query(113,"","db_docparag.*,db02_texto,db02_espaca,db02_alinha,db02_inicia","db04_ordem"));
$result_paragrafo = $cldb_docparag->sql_record($cldb_docparag->sql_query(113,"","db_docparag.*,db02_texto,db02_espaca,db02_alinha,db02_inicia","db04_ordem"));
$numrows_paragrafo = $cldb_docparag->numrows;

$pdf = new scpdf(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$pdf->setfillcolor(235);
$pdf->Addpage();

$pdf->SetTextColor(0,0,0);
$pdf->SetFillColor(220);

$alturainicial = 0;

for($ix=0; $ix<$numrows_rhvisavalecad; $ix++){
	db_fieldsmemory($result_rhvisavalecad, $ix);

  if(($pdf->gety() > $pdf->h - 30) || ($ix != 0 && $ix % 4 == 0)){
  	$pdf->Addpage();
  	$alturainicial = 0;
  }

	$pdf->SetY($alturainicial+10);
	
	for($i=0; $i<$numrows_paragrafo; $i++){
	  db_fieldsmemory($result_paragrafo,$i);
	
	  $texto=db_geratexto($db02_texto);
	
	  $pdf->SetFont('Arial','',12);
	  $pdf->SetX($db02_alinha);
	  $pdf->MultiCell("0",4+$db02_espaca,$texto,"0","J",0,$db02_inicia+0);
	  $pdf->cell(0,6,"",0,1,"R",0);
	
	}
	$alturainicial += 60;
}

$pdf->Output();
?>