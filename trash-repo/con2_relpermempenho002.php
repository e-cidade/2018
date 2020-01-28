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

require_once("fpdf151/pdf.php");
require_once("libs/db_stdlib.php");
require_once("std/db_stdClass.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_utils.php");
require_once("libs/db_liborcamento.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");

$oPost = db_utils::postMemory($_POST);

$iIdUsuario     = db_getsession('DB_id_usuario');
$iAnoUsu        = db_getsession('DB_anousu');
$iDptoUsu       = db_getsession('DB_coddepto');

$sWhereUsuarios = "";
$sAnd           = "";
if (isset($oPost->listausuarios) && !empty($oPost->listausuarios)) {
  
  $sWhereUsuarios .= "{$sAnd} db_usupermemp.db21_id_usuario in ({$oPost->listausuarios})";
  $sAnd            = " and ";
}

if (isset($oPost->anousu) && !empty($oPost->anousu)) {
  
  $iAnoUsu         = $oPost->anousu;
  $sWhereUsuarios .= "{$sAnd} db_permemp.db20_anousu = {$iAnoUsu}";
  $sAnd            = " and ";
}

if (isset($sWhereUsuarios) && !empty($sWhereUsuarios)) {
  $sWhereUsuarios  = " where {$sWhereUsuarios}                                                                        ";
} else {
  $sWhereUsuarios  = " where db_usupermemp.db21_id_usuario = {$iIdUsuario}                                            ";
  $sWhereUsuarios .= "   and db_permemp.db20_anousu        = {$iAnoUsu}                                               ";
}

$sWhereDeptos   = "";
$sAnd           = "";
if (isset($oPost->listadptos) && !empty($oPost->listadptos)) {
  
  $sWhereDeptos .= "{$sAnd} db_depusuemp.db22_coddepto in ({$oPost->listadptos})";
  $sAnd          = " and ";
}

if (isset($oPost->listainstit) && !empty($oPost->listainstit)) {
  
  $sInstit       = str_replace('-',',',$oPost->listainstit);
  $sWhereDeptos .= "{$sAnd} db_depart.instit in ({$sInstit})";
  $sAnd          = " and ";
}

if (isset($oPost->anousu) && !empty($oPost->anousu)) {
  
  $sWhereDeptos .= "{$sAnd} db_permemp.db20_anousu = {$oPost->anousu}";
  $sAnd          = " and ";
}

if (isset($oPost->listafiltros) && !empty($oPost->listafiltros)) {
  
  if ($oPost->listafiltros != 'geral') {
    
    $clselorcdotacao = new cl_selorcdotacao();
    $clselorcdotacao->setDados($oPost->listafiltros);  
    
    $sSelOrcDotacao  = $clselorcdotacao->getDados();
    $sSelOrcDotacao  = substr($sSelOrcDotacao,4);
    $sSelOrcDotacao  = str_replace('e.o56_elemento','db_permemp.db20_codele',$sSelOrcDotacao);
    $sSelOrcDotacao  = str_replace('w.','db_permemp.',$sSelOrcDotacao);
    $sSelOrcDotacao  = str_replace('o58_','db20_',$sSelOrcDotacao);

    $sWhereDeptos   .= " {$sSelOrcDotacao} "; 
  }
}

if (isset($sWhereDeptos) && !empty($sWhereDeptos)) {
  $sWhereDeptos  = " where {$sWhereDeptos}                                                                            ";
} else {
  
  $sWhereDeptos  = " where db_depusuemp.db22_coddepto = {$iDptoUsu}                                                   ";
  $sWhereDeptos .= "   and db_permemp.db20_anousu     = {$iAnoUsu}                                                    ";
}

$sCampos          = " db_permemp.db20_orgao,db_permemp.db20_unidade,db_permemp.db20_funcao,                           ";
$sCampos         .= " db_permemp.db20_subfuncao,db_permemp.db20_programa,db_permemp.db20_projativ,                    ";
$sCampos         .= " db_permemp.db20_codele,db_permemp.db20_codigo,db_permemp.db20_tipoperm                          ";

$sSqlPermEmpenho  = "  select 1 as tipo,                                                                              ";
$sSqlPermEmpenho .= "         db_usuarios.id_usuario as cod,                                                          ";
$sSqlPermEmpenho .= "         db_usuarios.nome       as descr,                                                        ";
$sSqlPermEmpenho .= "         {$sCampos}                                                                              ";
$sSqlPermEmpenho .= "    from db_usupermemp                                                                           ";
$sSqlPermEmpenho .= "         inner join db_permemp on db_permemp.db20_codperm = db_usupermemp.db21_codperm           ";
$sSqlPermEmpenho .= "         inner join db_usuarios on db_usuarios.id_usuario = db_usupermemp.db21_id_usuario        ";
$sSqlPermEmpenho .= "         {$sWhereUsuarios}                                                                       ";

$sSqlPermEmpenho .= "  union                                                                                          ";

$sSqlPermEmpenho .= "  select 2 as tipo,                                                                              ";
$sSqlPermEmpenho .= "         db_depart.coddepto   as cod,                                                            ";
$sSqlPermEmpenho .= "         db_depart.descrdepto as descr,                                                          ";
$sSqlPermEmpenho .= "         {$sCampos}                                                                              ";
$sSqlPermEmpenho .= "    from db_depusuemp                                                                            ";
$sSqlPermEmpenho .= "         inner join db_permemp on db_permemp.db20_codperm = db_depusuemp.db22_codperm            "; 
$sSqlPermEmpenho .= "         inner join db_depart  on db_depart.coddepto      = db_depusuemp.db22_coddepto           ";
$sSqlPermEmpenho .= "         {$sWhereDeptos}                                                                         ";

$rsSqlPermEmpenho = db_query($sSqlPermEmpenho);
$iNumRows         = pg_num_rows($rsSqlPermEmpenho);

$head2 = "RELATÓRIO DE PERMISSÕES DE EMPENHO";
$head4 = "EXERCICÍO: ".$iAnoUsu;

if ($iNumRows == 0){
  db_redireciona('db_erros.php?fechar=true&db_erro=Não existem registros cadastrados.');
  exit;
}

$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages();
$pdf->SetFont('arial','b',8);
$pdf->SetFillColor(235);
$pdf->addpage("P");

$iAlt          = 4;
$sDescricao    = "";
$iPreencher    = true;
$aDadosPermEmp = array();
$iTotalGeral   = 0;

for ( $i = 0; $i < $iNumRows; $i++ ) {

  $oRetorno = db_utils::fieldsMemory($rsSqlPermEmpenho,$i);

  $oDadosPerm                 = new stdClass(); 
  if ($oRetorno->tipo == 1) { 
  	
  	$oDadosPerm->iIdUsuario   = $oRetorno->cod; 
  	$oDadosPerm->sNomeUsuario = $oRetorno->descr;
  } else if ($oRetorno->tipo == 2) {
  	
    $oDadosPerm->iCodDpto     = $oRetorno->cod;
    $oDadosPerm->sDescrDpto   = $oRetorno->descr;
  }
  
  $oDadosPerm->iCodOrgao     = $oRetorno->db20_orgao;
  $oDadosPerm->iCodUnid      = $oRetorno->db20_unidade;
  $oDadosPerm->iCodFuncao    = $oRetorno->db20_funcao;
  $oDadosPerm->iCodSubFuncao = $oRetorno->db20_subfuncao;
  $oDadosPerm->iCodPrograma  = $oRetorno->db20_programa;
  $oDadosPerm->iProjAtiv     = $oRetorno->db20_projativ;
  $oDadosPerm->iElemento     = $oRetorno->db20_codele;
  $oDadosPerm->iRecurso      = $oRetorno->db20_codigo;
  $oDadosPerm->sTipoPerm     = $oRetorno->db20_tipoperm;
  
  if ( isset($aDadosPermEmp[$oRetorno->tipo][$oRetorno->cod]) ) {
    $aDadosPermEmp[$oRetorno->tipo][$oRetorno->cod]['aDadosPermEmp'][]  = $oDadosPerm;     
  } else {
  	
  	$aDadosPermEmp[$oRetorno->tipo][$oRetorno->cod]['iTipo']            = $oRetorno->tipo;
    $aDadosPermEmp[$oRetorno->tipo][$oRetorno->cod]['iCodId']           = $oRetorno->cod;
    $aDadosPermEmp[$oRetorno->tipo][$oRetorno->cod]['sDescr']           = $oRetorno->descr;
    $aDadosPermEmp[$oRetorno->tipo][$oRetorno->cod]['aDadosPermEmp'][]  = $oDadosPerm;
  } 
}

foreach ( $aDadosPermEmp as $iInd => $aDados ) {
	
	$iTotalGeral = 0;
	
	foreach ( $aDados as $iInd => $aDadosPerm ) {
		
    if (isset($aDadosPerm['iTipo']) && $aDadosPerm['iTipo'] == 1) {
    	
    	$sDescrTipo = "Usuário: ";
    	$sDescricao = "Usuário: ".$aDadosPerm['iCodId']." - ".$aDadosPerm['sDescr'];
      imprimeCabecalho($pdf,$iAlt,true,$sDescricao);
    } else if (isset($aDadosPerm['iTipo']) && $aDadosPerm['iTipo'] == 2) {
    	
    	$sDescrTipo = "Departamento: ";
      $sDescricao = "Departamento: ".$aDadosPerm['iCodId']." - ".$aDadosPerm['sDescr'];
      imprimeCabecalho($pdf,$iAlt,true,$sDescricao);
    }
    
	  if (isset($aDadosPerm['aDadosPermEmp'])) {     
    
	  	$iTotal = 0;
	  	
	    foreach ( $aDadosPerm['aDadosPermEmp'] as $iInd => $oDadosPermissaoEmpenho ) {
	      
	      imprimeCabecalho($pdf,$iAlt,false,$sDescricao);
	      
	      $pdf->SetFont('arial','',8);
	      $pdf->cell(21,$iAlt,$oDadosPermissaoEmpenho->iCodOrgao                                          ,"TBR",0,"C",0);
	      $pdf->cell(21,$iAlt,$oDadosPermissaoEmpenho->iCodUnid                                               ,1,0,"C",0);
	      $pdf->cell(21,$iAlt,$oDadosPermissaoEmpenho->iCodFuncao                                             ,1,0,"C",0);
	      $pdf->cell(21,$iAlt,$oDadosPermissaoEmpenho->iCodSubFuncao                                          ,1,0,"C",0);
	      $pdf->cell(21,$iAlt,$oDadosPermissaoEmpenho->iCodPrograma                                           ,1,0,"C",0);
	      $pdf->cell(21,$iAlt,$oDadosPermissaoEmpenho->iProjAtiv                                              ,1,0,"C",0);
	      $pdf->cell(21,$iAlt,$oDadosPermissaoEmpenho->iElemento                                              ,1,0,"C",0);
	      $pdf->cell(21,$iAlt,$oDadosPermissaoEmpenho->iRecurso                                               ,1,0,"C",0);
	      $pdf->cell(25,$iAlt,$oDadosPermissaoEmpenho->sTipoPerm                                          ,"TBL",1,"C",0);

	      $iTotal++;
	      $iTotalGeral++;
	    }
	    
	    $pdf->Ln(1);	    
	    $pdf->cell(193,1,""                                                                                 ,"B",1,"L",0);
      $pdf->SetFont('arial','b',8);
      $pdf->cell(20,$iAlt,"Total {$sDescrTipo}".$iTotal                                                     ,0,1,"L",0);
    }    
	}
	
  $pdf->cell(193,1,""                                                                                     ,"B",1,"L",0);
  $pdf->SetFont('arial','b',8);
  $pdf->cell(20,$iAlt,"Total Geral {$sDescrTipo}".$iTotalGeral                                              ,0,1,"L",0);
  $pdf->cell(192,1,""                                                                                       ,0,1,"L",0);	
}

$pdf->Output();

function imprimeCabecalho($pdf,$iAlt,$lImprime=false,$sDescricao="") {
  
  if (($pdf->gety() > $pdf->h - 30)  || $lImprime  ) {
    
    if (($pdf->gety() > $pdf->h - 30)) {
      $pdf->addpage("P");
    }
    
    $pdf->Ln(1);
    
    $pdf->SetFont('arial','b',8);
    $pdf->cell(192,$iAlt,$sDescricao                                                                        ,0,1,"L",0);
    $pdf->cell(192,1,""                                                                                     ,0,1,"L",0);
    $pdf->cell(21,$iAlt,"Orgão"                                                                         ,"TBR",0,"C",1);
    $pdf->cell(21,$iAlt,"Unidade"                                                                           ,1,0,"C",1);
    $pdf->cell(21,$iAlt,"Função"                                                                            ,1,0,"C",1);
    $pdf->cell(21,$iAlt,"SubFunção"                                                                         ,1,0,"C",1);
    $pdf->cell(21,$iAlt,"Programa"                                                                          ,1,0,"C",1);
    $pdf->cell(21,$iAlt,"Proj/Ativ"                                                                         ,1,0,"C",1);
    $pdf->cell(21,$iAlt,"Elemento"                                                                          ,1,0,"C",1);
    $pdf->cell(21,$iAlt,"Recurso"                                                                           ,1,0,"C",1);
    $pdf->cell(25,$iAlt,"Manut/Consulta"                                                                ,"TBL",1,"C",1);
  }
}
?>