<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
require_once("std/DBDate.php");

require_once("classes/db_extratolinha_classe.php");
require_once("classes/db_conciliapendextrato_classe.php");

db_postmemory($HTTP_POST_VARS);
$iInstituicao = db_getsession("DB_instit");

$oExtratoLinha        = new cl_extratolinha();
$oConciliaPendExtrato = new cl_conciliapendextrato();
$oConciliaExtrato     = new cl_conciliaextrato();
$oDaoConDataConf      = new cl_condataconf();

$sMensagem = "Erro excluindo pendencias do extrato";

/**
 * Busca a data de encerramento da contabilidade da instituição
 */
$sSqlConDataConf = $oDaoConDataConf->sql_query(null, $iInstituicao, "c99_data");
$rsConDataConf   = $oDaoConDataConf->sql_record($sSqlConDataConf);

if ($oDaoConDataConf->numrows > 0) {

  $oData      = new DBDate(db_utils::fieldsMemory($rsConDataConf, 0)->c99_data);
  $sMensagem  = "Operação não permitida. Data da conciliação, menor que a data do encerramento da contabilidade\n";
  $sMensagem .= "para a instituição! ({$oData->getDate(DBDate::DATA_PTBR)})";
}

if (isset($excluir)) {
	
	try {
    
	   db_inicio_transacao();
	   
	   $sSqlValidaExtrato = $oExtratoLinha->sql_query_file($codigoextrato);
	   $rsExtratoLinha    = $oExtratoLinha->sql_record($sSqlValidaExtrato);
	   if ($oExtratoLinha->numrows == 0) {
	   	 throw new Exception("Codigo não Cadastrado");
	   }
	   
	   $sSqlValidaConciliacao = $oConciliaExtrato->sql_query_file(null, "1", null, "k87_extratolinha = {$codigoextrato}");
     $rsValidaConciliacao   = $oConciliaExtrato->sql_record($sSqlValidaConciliacao);
     if ($oConciliaExtrato->numrows > 0){
     	 throw new Exception("Existe conciliação para a linha do extrato");
     }
    
	   $oConciliaPendExtrato->excluir("", "k88_extratolinha = {$codigoextrato}");
	   if ($oConciliaPendExtrato->erro_status == "0") {
	     throw new Exception($sMensagem);
	   }	  
	   
	   $oExtratoLinha->excluir($codigoextrato);
     if ($oExtratoLinha->erro_status == "0") {
     	 throw new Exception("Erro excluindo linha do extrato");
     }
     
     db_fim_transacao(false);
     db_msgbox("Exclusão da linha do extrato executada com sucesso!");
     
	} catch (Exception $oErro) {
		
		db_fim_transacao(true);
		db_msgbox($oErro->getMessage());
		
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
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<form name="form1" method="POST" >
<center>
<table width="390" border="0" cellpadding="0" cellspacing="0">
    <tr>
     <td></td>
     <td></td>
    </tr>
    <tr>
      <td> Codigo do Extrato: </td>
      <td >
      <input name="codigoextrato" >
      </td>
    </tr>
    <tr>
      <td colspan="2" >
      <input type="submit" name="excluir" value="Confirma Exclusao">  
      </td>
    </tr>
</table>
</center>
	</form>
	</td>
  </tr>
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>