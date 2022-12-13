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
 * 
 * @author I
 * @revision $Author: dbiuri $
 * @version $Revision: 1.1 $
 */
require("libs/db_stdlib.php");
require("std/db_stdClass.php");
require("libs/db_app.utils.php");
require("libs/db_utils.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_custocriteriorateio_classe.php");
include("classes/db_custoplanilha_classe.php");
include("classes/db_custoliberaplanilhamovimentos_classe.php");
require_once("model/custoPlanilha.model.php");
include("dbforms/db_funcoes.php");
$oPost = db_utils::postMemory($_POST);
$oGet  = db_utils::postMemory($_GET);
db_postmemory($_POST);
$aParamKeys = array(
                    db_getsession("DB_anousu")
                   );
$aParametrosCustos   = db_stdClass::getParametro("parcustos",$aParamKeys);
$iTipoControleCustos = 0; 
$db_opcao            = 1;
$lSqlErro            = false; 
$sDisabled           = " disabled ";
if (count($aParametrosCustos) > 0) {
  $iTipoControleCustos = $aParametrosCustos[0]->cc09_tipocontrole;
}

if (isset($oPost->btnencerramentocompetencia)) {

  $sErroMsg = "Fechamento da Competência realizada com sucesso.";
  $oDaoPlanilhaCusto = new cl_custoplanilha;
  $oDaoPlanilhaEncerramento = new cl_custoliberaplanilhamovimentos;
  
  db_inicio_transacao();
  $oDaoPlanilhaCusto->cc15_situacao   = 2;
  $oDaoPlanilhaCusto->cc15_sequencial = $oGet->chavepesquisa;
  $oDaoPlanilhaCusto->alterar($oGet->chavepesquisa);
  if ($oDaoPlanilhaCusto->erro_status == 0) {
    $lSqlErro = true;
  }
  if (!$lSqlErro) {
    
    $oDaoPlanilhaEncerramento->cc16_custoplanilha = $oGet->chavepesquisa;
    $oDaoPlanilhaEncerramento->cc16_hora          = db_hora();
    $oDaoPlanilhaEncerramento->cc16_datamov       = date("Y-m-d", db_getsession("DB_datausu"));
    $oDaoPlanilhaEncerramento->cc16_id_usuario    = db_getsession("DB_id_usuario");
    $oDaoPlanilhaEncerramento->cc16_motivo        = " $oPost->cc16_motivo";
    $oDaoPlanilhaEncerramento->incluir(null);
    if ($oDaoPlanilhaEncerramento->erro_status == 0) {
      
      $lSqlErro = true;
      $sErroMsg = $oDaoPlanilhaEncerramento->erro_msg;
        
    }
  }
  db_fim_transacao($lSqlErro);
  if (!$lSqlErro) {
    unset($oGet);
  }
}
if (isset($oGet->chavepesquisa)) {
  
  $oDaoPlanilhaCusto = new cl_custoplanilha;
  $sSqlPlanilha = $oDaoPlanilhaCusto->sql_query_file($oGet->chavepesquisa);
  $rsPlanilha   = $oDaoPlanilhaCusto->sql_record($sSqlPlanilha);
  if ($oDaoPlanilhaCusto->numrows > 0) {

    $oDadosPlanilha = db_utils::fieldsMemory($rsPlanilha, 0);
    $cc15_anousu    = $oDadosPlanilha->cc15_anousu; 
    $cc15_mesusu    = $oDadosPlanilha->cc15_mesusu;
    $sDisabled      = " ";
     
  }
}

$oRotuloCampo = new rotulocampo();
$oRotuloCampo->label("cc15_anousu");
$oRotuloCampo->label("cc15_mesusu");
$oRotuloCampo->label("cc16_motivo");
?>
<html>
  <head>
  
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?
     db_app::load("scripts.js, prototype.js, strings.js, widgets/windowAux.widget.js, datagrid.widget.js");
     db_app::load("estilos.css, grid.style.css");
    ?>
  </head>
  <body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
    <table border="0" cellpadding="0" cellspacing="0">
      <tr> 
        <td width="360" height="18">&nbsp;</td>
        <td width="263">&nbsp;</td>
        <td width="25">&nbsp;</td>
        <td width="140">&nbsp;</td>
      </tr>
    </table>
    <center>
    <form name='frmEncerramentoCustos' method="post">
      <table>
        <tr>
          <td>
            <fieldset>
              <legend>
                <b>Encerramento Custos Competência:</b>
              </legend>
              <table>
                <tr>
                  <td>
                    <b>Mês:</b>
                  </td>
                  <td>
                    <?
                      db_input("cc15_mesusu", 10, $Icc15_mesusu, true,"text", 3);
                    ?>
                  </td>
                </tr>
                <tr>
                  <td>
                    <b>Ano:</b>
                  </td>
                  <td>
                    <?
                      db_input("cc15_anousu", 10, $Icc15_anousu, true,"text", 3);
                    ?>
                  </td>
                </tr>
                <tr>
                  <td>
                    <b>Motivo:</b>
                  </td>
                  <td>
                    <?
                      db_textarea("cc16_motivo",5, 70, $Icc16_motivo, true,"text", 1);
                    ?>
                  </td>
                </tr>
              </table>
            </fieldset>
          </td>
        </tr>
        <tr>
          <td colspan="2" style="text-align: center">
            <input type="submit" id="btnEncerramentoConpetencia" <?=$sDisabled?> name='btnencerramentocompetencia' value="Encerrar">
            <input type="button" id="btnPesquisa" name='btnpesquisa' value="Pesquisar">
          </td>
        <tr>
      </table>
    </form>
  </center>
 </body>
</html>
<?
 db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit"));
 
 if ($lSqlErro) {
   db_msgbox($sErroMsg);
 } else if (isset($oPost->btnencerramentocompetencia) && !$lSqlErro) {
   db_msgbox($sErroMsg);
 }
?>                  

<script>
function js_pesquisa() {

 var sUrl = 'funcao_js=parent.js_preenchepesquisa|cc15_sequencial&situacao=1'; 
 js_OpenJanelaIframe('', 'db_iframe_planilha','func_custoplanilha.php?'+sUrl,'Planilhas de Custos');
}

function js_preenchepesquisa(valor) {
   location.href = 'cus4_encerramentocustos001.php?chavepesquisa='+valor;
}
$('btnPesquisa').observe("click", js_pesquisa);
</script>