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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_habitinscricao_classe.php");
require_once("classes/db_habitinscricaocancelamento_classe.php");

require_once("model/habitacao/CandidatoHabitacao.model.php");
require_once("model/habitacao/InscricaoHabitacao.model.php");
require_once("model/habitacao/InteresseHabitacao.model.php");
require_once("model/habitacao/InteresseProgramaHabitacao.model.php");
require_once("model/processoProtocolo.model.php");
require_once("model/CgmBase.model.php");
require_once("model/CgmFisico.model.php");

$oPost    = db_utils::postMemory($_POST);
$oGet     = db_utils::postMemory($_GET);

$clhabitinscricao             = new cl_habitinscricao();
$clhabitinscricaocancelamento = new cl_habitinscricaocancelamento;

$db_opcao            = 33;
$ht15_sequencial     = '';
$ht15_candidato      = '';
$z01_nome            = '';
$ht15_habitprograma  = '';
$ht01_descricao      = '';
$ht15_datalancamento = '';
$ht13_descricao      = '';
$ht22_motivo         = '';

if (isset($oPost->incluir)) {
	
	try {
		
	  db_inicio_transacao();
	  
	  $oInscricaoHabitacao = new InscricaoHabitacao($oPost->ht15_sequencial);
	  $oInscricaoHabitacao->desistir($oPost->ht22_motivo);
	  $sMensagem = "Inclusão efetuada com sucesso.";
	  
	  db_fim_transacao(false);
	} catch (Exception $eErro) {
		
		db_fim_transacao(true);
		$sMensagem = $eErro->getMessage();
	}
	
} else if (isset($oGet->chavepesquisa)) {
	
	$db_opcao = 1;
	
  $sCampos  = " habitinscricao.ht15_sequencial,                     ";
  $sCampos .= " habitinscricao.ht15_datalancamento,                 ";
  $sCampos .= " habitcandidatointeresse.ht20_habitcandidato,        "; 
  $sCampos .= " cgm.z01_nome,                                       ";
  $sCampos .= " habitcandidatointeresseprograma.ht13_habitprograma, "; 
  $sCampos .= " habitprograma.ht01_descricao                        ";	

  
  $sWhere        = "habitinscricao.ht15_sequencial = {$oGet->chavepesquisa}";
	$sSqlPesquisa  = $clhabitinscricao->sql_query(null, $sCampos, "ht15_sequencial", $sWhere);
	$rsSqlPesquisa = $clhabitinscricao->sql_record($sSqlPesquisa);
	
	if ($clhabitinscricao->numrows > 0) {
		
		$oPesquisa           = db_utils::fieldsMemory($rsSqlPesquisa, 0);
		
		$ht15_sequencial     = $oPesquisa->ht15_sequencial;
		$ht20_habitcandidato = $oPesquisa->ht20_habitcandidato;
		$z01_nome            = $oPesquisa->z01_nome;
		$ht13_habitprograma  = $oPesquisa->ht13_habitprograma;
		$ht01_descricao      = $oPesquisa->ht01_descricao;
		$ht15_datalancamento = db_formatar($oPesquisa->ht15_datalancamento, 'd');
	}
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<style>
td {
  white-space: nowrap;
}

fieldset table td:first-child {
  width: 90px;
  white-space: nowrap;
}

#ht13_descricao, #ht22_motivo {
  width: 100%;
}
</style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="">
<table align="center" style="padding-top:25px;">
  <tr>
    <td height="30px">&nbsp;</td>
  </tr>
  <tr> 
    <td valign="top"> 
      <?
        include("forms/db_frmhabitinscricaodesistencia.php");
      ?>
    </td>
  </tr>
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
<?php
if (isset($oPost->incluir)) {
	
	if (isset($sMensagem) && !empty($sMensagem)) {
		db_msgbox($sMensagem);
	}
}

if ($db_opcao==33) {
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>
</html>