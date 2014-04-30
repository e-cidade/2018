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

/**
 * Cadastro de licitações desertas
 * @author Iuri Guntchnigg
 * @revision $Author: dbiuri $
 * @version $Revision: 1.3 $
 */
require("libs/db_stdlib.php");
require("libs/db_utils.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
require("model/licitacao.model.php");
$db_botao = false;
$db_opcao = 3;
$oGet     = db_utils::postMemory($_GET);
$oPost    = db_utils::postMemory($_POST);
$lErro    = false;
$sMsg     = "";
$iTipo    = 3;

if (isset($oPost->excluir)) {
  
  $oLicitacao = new licitacao($oPost->l20_codigo);
  try {
    
    db_inicio_transacao();
    $oLicitacao->cancelaDeserta($oPost->l11_obs);
    $sMsg = "Procedimento realizado com sucesso!";
    db_fim_transacao(false);
    
  }
  catch (Exception $eLicitacao) {
    
    db_fim_transacao(true);
    $lErro = true;
    $sMsg  = "Erro[".$eLicitacao->getCode()."] - ".str_replace("\n", "\\n", $eLicitacao->getMessage()); 
    
  }
  
  
}
if (isset($oGet->chavepesquisa) && $oGet->chavepesquisa != "") {
   
  $oDaoLiclicita = db_utils::getDao("liclicita");
  $sSqlLicita    = $oDaoLiclicita->sql_query_file($oGet->chavepesquisa); 
  $rsLicita      = $oDaoLiclicita->sql_record($sSqlLicita);
  if ($oDaoLiclicita->numrows > 0) {
    
    $db_botao = true;
    db_fieldsmemory($rsLicita, 0);
    
    /*
     * Verificamos se a licitação já possui um orçamento realizado para ela
     * caso esse orçamento já exista, não podemos incluir esse solicitação como deserta.
     */
    $sSqlLicitaOrcamento = $oDaoLiclicita->sql_query_pco($oGet->chavepesquisa);
    $rsLicitaOrcamento   = $oDaoLiclicita->sql_record($sSqlLicitaOrcamento);
    if ($oDaoLiclicita->numrows > 0) {

      $lErro    = true;
      $sMsg     = "Licitacao {$l20_numero} já possui valores lançados!\\nNão poderá ser incluida como deserta"; 
      $db_botao = false;
      
    }
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
    include("forms/db_frmlicitacaodeserta.php");
  ?>
  </center>
</body>
</html>
<?
 db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
 
 if ($lErro) {
   db_msgbox($sMsg); 
 } else {
   
   if ($sMsg != "") {
     
    db_msgbox($sMsg);
    echo "<script>js_pesquisa_liclicita(true)</script>";
    
   }
 }
?>