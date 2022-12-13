<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

include("libs/db_sql.php");
include("fpdf151/pdf1.php");
include("classes/db_inicial_classe.php");
include("classes/db_processoforoinicial_classe.php");
include("classes/db_db_docparag_classe.php");
include("classes/db_db_config_classe.php");
include("classes/db_issbase_classe.php");
include("classes/db_iptubase_classe.php");

$cldb_docparag         = new cl_db_docparag;
$clinicial             = new cl_inicial;
$clprocessoforoinicial = new cl_processoforoinicial;
$cldb_config           = new cl_db_config;
$clissbase             = new cl_issbase;
$cliptubase            = new cl_iptubase;
$clrotulo              = new rotulocampo;

$clrotulo->label('');
$clrotulo->label('');
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);exit;
if (isset($dadosini)&&$dadosini!=""){
  $matriz = split("xx",$dadosini);
}
$result = $cldb_docparag->sql_record($cldb_docparag->sql_query(null,null,"db_docparag.*,db02_alinhamento,db02_texto,db02_espaca,db02_alinha,db02_inicia","db04_ordem","db03_tipodoc=4"));
$numrows = $cldb_docparag->numrows;
if ($numrows == 0 ) {
 db_redireciona('db_erros.php?fechar=true&db_erro=Sem documento configurado');	
}
$pdf = new PDF1();
$pdf->Open();
$pdf->AliasNbPages();
for($q=0;$q<sizeof($matriz);$q++){
  if ($matriz[$q]!=""){
	$dadosi = split("ww",$matriz[$q]);
	$inicial = $dadosi[0];
	$chave = $dadosi[1];
	$modo = $dadosi[2];
	
    if ($modo=='inscricao'){
	  $result_issbase = $clissbase->sql_record($clissbase->sql_query($chave,"distinct z01_nome as nome"));
	  db_fieldsmemory($result_issbase,0);
	}else if($modo=='matricula'){
	  $result_iptubase = $cliptubase->sql_record($cliptubase->sql_query($chave,"distinct z01_nome as nome"));
	  db_fieldsmemory($result_iptubase,0);
	}
  $dia = date ('d',db_getsession("DB_datausu"));
  $mes = date ('m',db_getsession("DB_datausu"));
  $ano = date ('Y',db_getsession("DB_datausu"));
  $mes = db_mes($mes);
	$result_adv = $clinicial->sql_record($clinicial->sql_query($inicial,"a.z01_nome as advogado,v57_oab"));
	if ($clinicial->numrows>0){
	  db_fieldsmemory($result_adv,0); //pega advogado
	}
	$result_local = $clinicial->sql_record($clinicial->sql_query($inicial,"v54_descr as localiza"));
	if ($clinicial->numrows>0){
	  db_fieldsmemory($result_local,0); //pega localiza
	}
	
	$sWhere = "processoforoinicial.v71_inicial = {$inicial} and processoforoinicial.v71_anulado is false";
	$sSqlProcessoForoInicial = $clprocessoforoinicial->sql_query(null,"processoforo.v70_codforo,vara.v53_descr",null,$sWhere);
	$result_foro = $clprocessoforoinicial->sql_record($sSqlProcessoForoInicial);
	if ($clprocessoforoinicial->numrows>0){
	  db_fieldsmemory($result_foro,0); //pega codigo do processo
	}
	$result_pref = $cldb_config->sql_record($cldb_config->sql_query(db_getsession("DB_instit"),"nomeinst as prefeitura,ender as enderpref,munic"));
	if ($cldb_config->numrows>0){
	  db_fieldsmemory($result_pref,0); //pega o dados da prefa
	}
	$pdf->Addpage();
	$pdf->setfont('arial','b',8);
	$pdf->SetTextColor(0,0,0);
	$pdf->SetFillColor(220);
	for ($i=0;$i<$numrows;$i++){
	  db_fieldsmemory($result,$i);
	  $pdf->SetFont('Arial','',12);
	  $pdf->SetX($db02_alinha);
	  $texto = db_geratexto($db02_texto);
	  $pdf->SetFont('Arial','',12);
	  $pdf->MultiCell("0",4+$db02_espaca,$texto,"0",$db02_alinhamento,0,$db02_inicia+0);
	  $pdf->cell(0,6,"",0,1,"R",0);
	}
  $pdf->SetTextColor(0,0,0);
  $pdf->SetFillColor(220);
  $yy = $pdf->h - 11;
  $pdf->SetFont('Arial','',5);
  $pdf->Text(10,$yy,'Controle Administrativo nº '.$inicial);
  }  
}
$pdf->Output();

?>