<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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

require_once("fpdf151/pdf.php");
require_once("libs/db_sql.php");
require_once("classes/db_protprocesso_classe.php");
require_once("classes/db_procandamint_classe.php");
require_once("libs/db_utils.php");

$clprocandamint = new cl_procandamint;
$clprotprocesso = new cl_protprocesso;
$clrotulo       = new rotulocampo;

$oGet = db_utils::postMemory($_GET);

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

$sTipoDespacho       = "Despacho";
$result_procandamint = $clprocandamint->sql_record($clprocandamint->sql_query_sim($codprocandamint));

if ( $clprocandamint->numrows > 0 ) {

  db_fieldsmemory($result_procandamint, 0);
  $despacho      = $p78_despacho;
  $sTipoDespacho = $p100_descricao;
}

$public        = "Não";
if ( $p78_publico == 't' ) {
  $public = "Sim";
}
$sNumeroProcesso = $codproc;

/**
 * Busca numero e ano do processo pelo codigo processo 
 */
$sSqlNumeroProcesso = $clprotprocesso->sql_query_file($codproc, 'p58_numero, p58_ano');
$rsNumeroProcesso   = $clprotprocesso->sql_record($sSqlNumeroProcesso);

if ( $clprotprocesso->numrows > 0 ) {

  $oNumeroProcesso = db_utils::fieldsMemory($rsNumeroProcesso, 0);
  $sNumeroProcesso = $oNumeroProcesso->p58_numero . '/' . $oNumeroProcesso->p58_ano;
}

$head2 = "PROCESSO N° $sNumeroProcesso";
$head3 = "IMPRESSÃO DE ".mb_strtoupper($sTipoDespacho);
$head4 = "Data: ".db_formatar(@$p78_data,'d');
$head5 = "Hora: ".@$p78_hora;
$head6 = "Usuário: ".@$nome;
$head7 = "Público: ".@$public;

$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$pdf->addpage();
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);
$alt = 4;
  
$result_protprocesso = $clprotprocesso->sql_record($clprotprocesso->sql_query($codproc));

if ($clprotprocesso->numrows!=0){

  db_fieldsmemory($result_protprocesso,0);
  
  $pdf->cell(25,$alt,'Processo :',0,0,"R",0);
  $pdf->setfont('arial','',8);
  $pdf->cell(75,$alt, $sNumeroProcesso,0,0,"L",0);
  $pdf->setfont('arial','b',8);
  $pdf->cell(25,$alt,'Titular do Processo :',0,0,"R",0);
  $pdf->setfont('arial','',8);
  $pdf->cell(75,$alt,$z01_nome,0,1,"L",0);
  
  $pdf->setfont('arial','b',8);
  $pdf->cell(25,$alt,'Data :',0,0,"R",0);
  $pdf->setfont('arial','',8);
  $pdf->cell(75,$alt,db_formatar($p58_dtproc,'d'),0,0,"L",0);
  $pdf->setfont('arial','b',8);
  $pdf->cell(25,$alt,'Hora :',0,0,"R",0);
  $pdf->setfont('arial','',8);
  $pdf->cell(75,$alt,$p58_hora,0,1,"L",0);
  
  $pdf->setfont('arial','b',8);
  $pdf->cell(25,$alt,'Tipo :',0,0,"R",0);
  $pdf->setfont('arial','',8);
  $pdf->cell(75,$alt,$p51_descr,0,0,"L",0);
  $pdf->setfont('arial','b',8);
  $pdf->cell(25,$alt,'Atendente :',0,0,"R",0);
  $pdf->setfont('arial','',8);
  $pdf->cell(75,$alt,$nome,0,1,"L",0);
  
  $pdf->setfont('arial','b',8);
  $pdf->cell(25,$alt,'Requerente :',0,0,"R",0);
  $pdf->setfont('arial','',8);
  $pdf->cell(75,$alt,$p58_requer,0,1,"L",0);
  
  $pdf->setfont('arial','b',8);
  $pdf->cell(25,$alt,'Observação :',0,0,"R",0);
  $pdf->setfont('arial','',8);
  $pdf->multicell(160,$alt,$p58_obs,0,"L",0);
}
$pdf->Ln();
$pdf->cell(190,$alt,'','T',1,"R",0);
$pdf->setfont('arial','b',10);
$pdf->cell(25,$alt, "{$sTipoDespacho} :",0,0,"R",0);
$pdf->multicell(160,$alt,$despacho,0,"L",0);

$pdf->Output();