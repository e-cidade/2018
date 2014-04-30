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
require_once("classes/db_habitformaavaliacao_classe.php");
require_once("classes/db_habittipogrupoprograma_classe.php");
require_once("classes/db_habittipogrupoprogramaformaavaliacao_classe.php");
require_once("dbforms/db_funcoes.php");

$oPost = db_utils::postMemory($_POST);
$oGet  = db_utils::postMemory($_GET);

$clhabitformaavaliacao                  = new cl_habitformaavaliacao;
$clhabittipogrupoprograma               = new cl_habittipogrupoprograma;
$clhabittipogrupoprogramaformaavaliacao = new cl_habittipogrupoprogramaformaavaliacao;

$db_opcao = 2;
$db_botao = false;
$sqlerro  = false;

if (isset($oPost->incluir)) {
  
  if ($sqlerro == false) {
    
    db_inicio_transacao();
    
    $clhabittipogrupoprogramaformaavaliacao->ht06_habittipogrupoprograma = $oPost->ht06_habittipogrupoprograma;
    $clhabittipogrupoprogramaformaavaliacao->ht06_habitformaavaliacao    = $oPost->ht07_sequencial;
    $clhabittipogrupoprogramaformaavaliacao->incluir(null);
    $erro_msg = $clhabittipogrupoprogramaformaavaliacao->erro_msg;
    if ($clhabittipogrupoprogramaformaavaliacao->erro_status == 0) {
      $sqlerro = true;
    }
    
    db_fim_transacao($sqlerro);
  }
} else if(isset($oPost->alterar)) {
  
  if ($sqlerro == false) {
    
    db_inicio_transacao();
    
    $clhabittipogrupoprogramaformaavaliacao->ht06_sequencial             = $oPost->ht06_sequencial;
    $clhabittipogrupoprogramaformaavaliacao->ht06_habittipogrupoprograma = $oPost->ht06_habittipogrupoprograma;
    $clhabittipogrupoprogramaformaavaliacao->ht06_habitformaavaliacao    = $oPost->ht07_sequencial;
    $clhabittipogrupoprogramaformaavaliacao->alterar($clhabittipogrupoprogramaformaavaliacao->ht06_sequencial);
    $erro_msg = $clhabittipogrupoprogramaformaavaliacao->erro_msg;
    if ($clhabittipogrupoprogramaformaavaliacao->erro_status == 0) {
      $sqlerro = true;
    }
    
    db_fim_transacao($sqlerro);
  }
} else if (isset($oPost->excluir)) {
  
  if ($sqlerro == false) {
    
    db_inicio_transacao();
    
    $clhabittipogrupoprogramaformaavaliacao->ht06_sequencial = $oPost->ht06_sequencial;
    $clhabittipogrupoprogramaformaavaliacao->excluir($clhabittipogrupoprogramaformaavaliacao->ht06_sequencial);
    $erro_msg = $clhabittipogrupoprogramaformaavaliacao->erro_msg;
    if ($clhabittipogrupoprogramaformaavaliacao->erro_status == 0) {
      $sqlerro = true;
    }
    
    db_fim_transacao($sqlerro);
  }
} else if(isset($oPost->opcao)) {
  
  $sSqlTipoGrupoProgramaFormaAvaliacao = $clhabittipogrupoprogramaformaavaliacao->sql_query($oPost->ht06_sequencial);
  $rsTipoGrupoProgramaFormaAvaliacao   = $clhabittipogrupoprogramaformaavaliacao->sql_record($sSqlTipoGrupoProgramaFormaAvaliacao);
  if ($rsTipoGrupoProgramaFormaAvaliacao != false && $clhabittipogrupoprogramaformaavaliacao->numrows > 0) {
    db_fieldsmemory($rsTipoGrupoProgramaFormaAvaliacao,0);
  }
}

if (isset($oGet->ht02_sequencial)) {
  $ht06_habittipogrupoprograma = $oGet->ht02_sequencial;
} else {
  $ht06_habittipogrupoprograma = $oPost->ht06_habittipogrupoprograma;
}

$sWhere                = "ht02_sequencial = {$ht06_habittipogrupoprograma}";
$sSqlTipoGrupoPrograma = $clhabittipogrupoprograma->sql_query(null,"*",null,$sWhere);
$rsTipoGrupoPrograma   = $clhabittipogrupoprograma->sql_record($sSqlTipoGrupoPrograma);
if ($clhabittipogrupoprograma->numrows > 0) {
  
  db_fieldsmemory($rsTipoGrupoPrograma,0);
  $ht06_habittipogrupoprograma = $ht02_sequencial;
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<style>
td {
  white-space: nowrap
}

fieldset table td:first-child {
  width: 100px;
  white-space: nowrap
}

#ht02_descricao {
  width: 81%;
}

#ht07_descricao {
  width: 81%;
}
</style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table border="0" align="center" cellspacing="0" cellpadding="0" >
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr> 
    <td valign="top" bgcolor="#CCCCCC"> 
    <center>
      <?
        include("forms/db_frmhabtipogrupoprogramaformaavaliacao.php");
      ?>
    </center>
  </td>
  </tr>
</table>
</body>
</html>
<?
if (isset($oPost->alterar) || isset($oPost->excluir) || isset($oPost->incluir)) {
  
  db_msgbox($erro_msg);
  if ($clhabittipogrupoprogramaformaavaliacao->erro_campo != "") {
    
    echo "<script> document.form1.".$clhabittipogrupoprogramaformaavaliacao->erro_campo.".style.backgroundColor='#99A9AE';</script>";
    echo "<script> document.form1.".$clhabittipogrupoprogramaformaavaliacao->erro_campo.".focus();</script>";
  }
}
?>