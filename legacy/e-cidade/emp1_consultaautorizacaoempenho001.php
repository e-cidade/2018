<?php
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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_liborcamento.php");
require_once("classes/db_empempenho_classe.php");
require_once("classes/db_orcdotacao_classe.php");
require_once("classes/db_pcmater_classe.php");
require_once("classes/db_cgm_classe.php");

$oDaoEmpEmpenho  = db_utils::getDao('empempenho');
$oDaoOrcDotacao  = db_utils::getDao('orcdotacao');
$oDaoPcMater     = db_utils::getDao('pcmater');
$oDaoCgm         = db_utils::getDao('cgm');
$oDaoEmpAutoriza = db_utils::getDao('empautoriza');

$oRotuloCampo = new rotulocampo;
$oRotuloCampo->label("o40_descr");
$oRotuloCampo->label("e60_emiss");
$oDaoPcMater->rotulo->label();
$oDaoCgm->rotulo->label();
$oDaoEmpEmpenho->rotulo->label();
$oDaoOrcDotacao->rotulo->label();
$oDaoEmpAutoriza->rotulo->label();


?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
  
  <style>
  .titleTd {
    font-weight: bold;
    width: 140px;
  }
  </style>
</head>
<body style="background-color: #CCCCCC; margin-top: 30px;">
  <center>
    <form id="form1" name="form1">
      <fieldset style="width: 550px;">
        <legend><b>Consulta Autorização de Empenho</b></legend>
        <table width="100%" border="0">
          <tr>
            <td class="titleTd">
              <?php 
                db_ancora($Le54_autori, "js_pesquisaAutorizacao(true);", 1);
              ?>
            </td>
            <td>
              <?php 
                db_input("e54_autori", 10, $Ie54_autori, true, "text", 1, "onchange='js_pesquisaAutorizacao(false);'");
              ?>
            </td>
          </tr>
          <tr>
            <td class="titleTd">
              <?php 
                db_ancora($Lo58_coddot, "js_pesquisaReduzido(true);", 1);
              ?>
            </td>
            <td>
              <?php 
                db_input("o58_coddot", 10, null, true, "text", 1, "onchange='js_pesquisaReduzido(false);'");
                db_input("o56_descr",40,"",true,"text",3);
              ?>
            </td>
          </tr>
          <tr>
            <td class="titleTd">
              <?php 
                db_ancora($Lpc01_codmater, "js_pesquisaMaterial(true);", 1);
              ?>
            </td>
            <td>
              <?php 
                db_input("pc01_codmater", 10, $Ipc01_codmater, true, "text", 1, "onchange='js_pesquisaMaterial(false);'");
                db_input("pc01_descrmater",40,"",true,"text",3);
              ?>
            </td>
          </tr>
          <tr>
            <td class="titleTd">
              <?php 
                db_ancora("Fornecedor (CGM):", "js_pesquisaFornecedor(true);", 1);
              ?>
            </td>
            <td>
              <?php 
                db_input("z01_numcgm", 10, $Iz01_numcgm, true, "text", 1, "onchange='js_pesquisaFornecedor(false);'");
                db_input("z01_nome", 40, null, true, "text", 3);
              ?>
            </td>
          </tr>
          <tr>
            <td class="titleTd">Data de Emissão:</td>
            <td>
              <?php 
                db_inputdata('e60_emiss1',@$e60_emiss_dia,@$e60_emiss_mes,@$e60_emiss_ano,true,'text',1,"");   		          
                echo " <b>a</b> ";
                db_inputdata('e60_emiss2',@$e60_emiss_dia,@$e60_emiss_mes,@$e60_emiss_ano,true,'text',1,"");
              ?>
            </td>
          </tr>
        </table>
      </fieldset>
      <br>
      <input type="button" name="btnPesquisaEmpenho" id="btnPesquisaEmpenho" value="Pesquisar">
      <input type="reset" name="btnResetEmpenho" id="btnResetEmpenho" value="Limpar Formulário">
    </form>
  </center>
<?php 
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>


<script>

$("btnPesquisaEmpenho").observe("click", function () {

  var sUrlRelatorio  = "emp1_consultaautorizacaoempenho003.php?";
  sUrlRelatorio     += "&iCodigoDotacao="+$F("o58_coddot")+"&iCodigoMaterial="+$F("pc01_codmater");
  sUrlRelatorio     += "&iCodigoFornecedor="+$F("z01_numcgm")+"&iCodigoAutorizacao="+$F("e54_autori");
  sUrlRelatorio     += "&dtDataInicial="+$F("e60_emiss1")+"&dtDataFinal="+$F("e60_emiss2");
  js_OpenJanelaIframe('top.corpo','db_iframe_consultaautorizacaoempenho003', sUrlRelatorio, 'Consulta Empenho',true);
  
});

/**
 * Funções de pesquisa da Autorização de Empenho
 */
function js_pesquisaAutorizacao (lMostra) {
  var sUrlOpen = "func_empautoriza.php?pesquisa_chave="+$F("e54_autori")+"&funcao_js=parent.js_completaAutorizacao";
  if (lMostra) {
    sUrlOpen = "func_empautoriza.php?funcao_js=parent.js_preencheAutorizacao|e54_autori";
  }
  js_OpenJanelaIframe('top.corpo', 'db_iframe_empautoriza', sUrlOpen, 'Pesquisa Autorização', lMostra);
}
function js_preencheAutorizacao (iCodigo, sDescricao) {
  
  $("e54_autori").setValue(iCodigo);
  db_iframe_empautoriza.hide();
}
function js_completaAutorizacao (sDescricao, lErro) {
  if (lErro) {
    $("e54_autori").setValue("");
  }
}

/**
 * Funções de Pesquisa CGM
 */
function js_pesquisaFornecedor (lMostra) {
  var sUrlOpen = "func_cgm_empenho.php?pesquisa_chave="+$F("z01_numcgm")+"&funcao_js=parent.js_completaFornecedor";
  if (lMostra) {
    sUrlOpen = "func_cgm_empenho.php?funcao_js=parent.js_preencheFornecedor|e60_numcgm|z01_nome";
  }
  js_OpenJanelaIframe('top.corpo', 'db_iframe_cgm', sUrlOpen, 'Pesquisa Material', lMostra);
}
function js_preencheFornecedor (iCodigo, sDescricao) {
  
  $("z01_numcgm").setValue(iCodigo);
  $("z01_nome").setValue(sDescricao);
  db_iframe_cgm.hide();
}
function js_completaFornecedor (sDescricao, lErro) {
  $("z01_nome").setValue(sDescricao);
  if (lErro) {
    $("z01_numcgm").setValue("");
  }
}

/**
 * Funções de Pesquisa do Material
 */
function js_pesquisaMaterial (lMostra) {
  var sUrlOpen = "func_pcmater.php?pesquisa_chave="+$F("pc01_codmater")+"&funcao_js=parent.js_completaMaterial";
  if (lMostra) {
    sUrlOpen = "func_pcmater.php?funcao_js=parent.js_preencheMaterial|pc01_codmater|pc01_descrmater";
  }
  js_OpenJanelaIframe('top.corpo', 'db_iframe_pcmater', sUrlOpen, 'Pesquisa Material', lMostra);
}
function js_preencheMaterial (iCodigo, sDescricao) {
  
  $("pc01_codmater").setValue(iCodigo);
  $("pc01_descrmater").setValue(sDescricao);
  db_iframe_pcmater.hide();
}
function js_completaMaterial (sDescricao, lErro) {
  $("pc01_descrmater").setValue(sDescricao);
  if (lErro) {
    $("pc01_codmater").setValue("");
  }
}

/**
 * Funções de Pesquisa do Reduzido (Dotação)
 */
function js_pesquisaReduzido (lMostra) {
  var sUrlOpen = "func_orcdotacao.php?pesquisa_chave="+$F("o58_coddot")+"&funcao_js=parent.js_completaReduzido";
  if (lMostra) {
    sUrlOpen = "func_orcdotacao.php?funcao_js=parent.js_preencheReduzido|o58_coddot|o56_descr";
  }
  js_OpenJanelaIframe('top.corpo', 'db_iframe_orcdotacao', sUrlOpen, 'Pesquisa Reduzido Dotação', lMostra);
}
function js_preencheReduzido (iCodigoReduzido, sDescricao) {
  
  $("o58_coddot").setValue(iCodigoReduzido);
  $("o56_descr").setValue(sDescricao);
  db_iframe_orcdotacao.hide();
}
function js_completaReduzido (sDescricao, lErro) {
  $("o56_descr").setValue(sDescricao);
  if (lErro) {
    $("o58_coddot").setValue("");
  }
}

</script>