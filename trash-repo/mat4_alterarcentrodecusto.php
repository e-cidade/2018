<?php
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

require("libs/db_stdlib.php");
require("libs/db_utils.php");
require("std/db_stdClass.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_custoapropria_classe.php");

$oGet  = db_utils::postMemory($_GET);
$oPost = db_utils::postMemory($_POST);
$lErro = false;
if (isset($oPost->btnalterar)) {
  
  if ($oPost->cc08_sequencial != "") {

    db_inicio_transacao();
    $oDaoCustoApropria = db_utils::getDao("custoapropria");         
    $oDaoCustoApropria->cc12_sequencial          = $oPost->cc12_sequencial;
    $oDaoCustoApropria->cc12_custocriteriorateio = $oPost->cc08_sequencial;
    $oDaoCustoApropria->alterar($oPost->cc12_sequencial);
    if ($oDaoCustoApropria->erro_status == 0) {
      
      $lErro    = true; 
      $sErroMsg = $oDaoCustoApropria->erro_msg;
      db_fim_transacao(true);
      
    } else {
      
      db_fim_transacao(false);
      
    }
    
  } else {
    
    $sErroMsg = "Centro de Custo deve ser informado.";
    $lErro    = true;
    
  }
  
}
if (isset($oGet->chavepesquisa) && $chavepesquisa != "") {
  
  $oDaoCustoApropria = db_utils::getDao("custoapropria");
  $sCampos            = "cc12_sequencial,";
  $sCampos           .= "m70_codmatmater,";
  $sCampos           .= "m60_descr,";
  $sCampos           .= "m80_codtipo,";
  $sCampos           .= "m81_descr,";
  $sCampos           .= "case when drequi.coddepto is null then depto1.coddepto";
  $sCampos           .= "    else  drequi.coddepto end as m80_coddepto,";
  $sCampos           .= "case when drequi.coddepto is null then depto1.descrdepto";
  $sCampos           .= "    else  drequi.descrdepto end as descrdepto,";
  $sCampos           .= "m80_data,";
  $sCampos           .= "cc08_sequencial,";
  $sCampos           .= "cc08_descricao,";
  $sCampos           .= "cc12_qtd,";
  $sCampos           .= "cc12_valor";
  $sSqlCusto          = $oDaoCustoApropria->sql_query_custoapropria($oGet->chavepesquisa, $sCampos);
  $rsCusto            = $oDaoCustoApropria->sql_record($sSqlCusto);
  if ($oDaoCustoApropria->numrows > 0) {
    
    db_fieldsmemory($rsCusto, 0);
  }
}
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
  <body bgcolor="#CCCCCC">
    <table width="790" border="0" cellpadding="0" cellspacing="0">
      <tr> 
        <td width="360" height="18">&nbsp;</td>
        <td width="263">&nbsp;</td>
        <td width="25">&nbsp;</td>
        <td width="140">&nbsp;</td>
      </tr>
    </table>
    <center>
      <?
       require("forms/db_frmalterarcentrocusto.php");
      ?>
    </center>
  </body>
</html>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));

if ($lErro) {
  db_msgbox($sErroMsg);
} else if (isset($oPost->btnalterar) || !isset($oGet->chavepesquisa)) {
  
  echo "<script>js_pesquisa()</script>";
}
?>