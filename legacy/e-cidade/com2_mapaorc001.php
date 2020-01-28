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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);

$clrotulo = new rotulocampo();
$clrotulo->label("pc20_codorc");
?>

<html>
  <head>
    <title>DBSeller Informática Ltda - Página Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" content="0">
    <?php
      db_app::load('scripts.js');
      db_app::load('prototype.js');
      db_app::load('estilos.css');
    ?> 
    <style>
      .fieldsetinterno {
        margin-top: 10px;
        border: 0px;
        border-top: 2px groove white;
      };
      fieldset.fieldsetinterno table {
        width: 100%;
        table-layout: auto;
      };
      fieldset.fieldsetinterno table tr td {
        white-space: nowrap;
      };
      select {
        width: 100%;
      };
    </style>
  </head>

  <body>
    <div class="Container">
      <form name="form1" method="post" action="">
        <fieldset>
          <legend>Mapa das Propostas do Orçamento por Item</legend>

          <fieldset class="fieldsetinterno">
            <legend>Orçamento do Processo de Compras</legend>
            <table>  
              <tr> 
                <td title="<?= $Tpc20_codorc ?>">
                  <?php
                    db_ancora('Orçamento', "js_pesquisar_orcamento_processo(true);", 1);
                  ?>:
                </td>              
                <td>
                  <?php
                    db_input("pc20_codorc", 6, $Ipc20_codorc, true, "text", 4, "onchange='js_pesquisar_orcamento_processo(false);'", "cod_orcamento_processo");
                  ?>
                </td>
              </tr>
            </table>
          </fieldset>  

          <fieldset class="fieldsetinterno">
            <legend>Orçamento da Solicitação de Compras</legend>
            <table>  
              <tr> 
                <td title="<?= $Tpc20_codorc ?>">
                  <?php
                    db_ancora('Orçamento', "js_pesquisar_orcamento_solicitacao(true);", 1);
                  ?>:
                </td>
                <td>
                  <?php
                    db_input("pc20_codorc", 6, $Ipc20_codorc, true, "text", 4, "onchange='js_pesquisar_orcamento_solicitacao(false);'", "cod_orcamento_solicitacao");
                  ?>
                </td>
              </tr>
            </table>
          </fieldset> 

          <fieldset class="fieldsetinterno">
            <legend>Visualização</legend>
            <table>  
              <tr>
                <td><b>Modelo:</b></td>
                <td>
                  <?php
                    $aModelos = array("1" => "Modelo 1", "2" => "Modelo 2");
                    db_select("modelo", $aModelos, true, 4, "style='width:83px;'");
                  ?>
                </td>
              </tr>
              <tr>
                <td><b>Imprimir justificativa de troca de fornecedores:</b></td>
                <td>
                  <?php
                    $aJustificativas = array("S" => "Sim", "N" => "Não");
                    db_select("imp_troca", $aJustificativas, true, 4, "style='width:83px;'");
                  ?>
                </td>
              </tr>
            </table>
          </fieldset> 
        </fieldset>

        <input id="btnProcessar" name="btnProcessar" type="button" value="Processar" onclick="js_validar_formulario();" >

      </form>
    </div>

    <?php
      db_menu(db_getsession("DB_id_usuario"), 
              db_getsession("DB_modulo"), 
              db_getsession("DB_anousu"), 
              db_getsession("DB_instit"));
    ?>

  </body>
  
  <script language="JavaScript" type="text/javascript">

    const MENSAGENS = "patrimonial.compras.com2_mapaorcamentoitem.";

    function js_pesquisar_orcamento_processo(lMostra) {
      
      if (js_verificar_campo(2)) {
        return false;
      }
      
      var sUrl = "func_pcorcamlancval.php?lProcessos=true&sol=false";
      
      if (lMostra) {
        
        sUrl += "&funcao_js=parent.js_mostrar_orcamento_processo|pc20_codorc";
        js_OpenJanelaIframe('top.corpo', 'db_iframe_pcorcam', sUrl, 'Pesquisar Orçamento do Processo de Compras', true);
      } else {

        $('btnProcessar').disabled = true;
        var sValorCampo = $F("cod_orcamento_processo");
        sUrl += "&pesquisa_chave=" + sValorCampo + "&chave_pc20_codorc=" + sValorCampo +"&funcao_js=parent.js_mostrar_orcamento_processo_background";
        js_OpenJanelaIframe('top.corpo', 'db_iframe_pcorcam', sUrl, 'Pesquisar Orçamento do Processo de Compras', false);
      }
    }
    
    function js_mostrar_orcamento_processo(sCodigoOrcamento) {
      
      $('btnProcessar').disabled = false;
      $("cod_orcamento_processo").value = sCodigoOrcamento;
      db_iframe_pcorcam.hide();
    }
    
    function js_mostrar_orcamento_processo_background(sCodigoOrcamento, lErro) {
      
      $('btnProcessar').disabled = false;
      if (lErro) {
        
        var iCodigoOrcamento = $F("cod_orcamento_processo");
        alert( _M(MENSAGENS + "orcamento_nao_encontrado", { sCodigo : iCodigoOrcamento}));
      
        $("cod_orcamento_processo").value = "";
        $("cod_orcamento_processo").focus();
      }
    }
    
    function js_pesquisar_orcamento_solicitacao(lMostra) {
      
      if (js_verificar_campo(1)) {
        return false;
      }
      

      var sUrl = "func_orcsolicita.php?sol=true";

      if (lMostra) {

        sUrl += "&funcao_js=parent.js_mostrar_orcamento_solicitacao|pc20_codorc";
        js_OpenJanelaIframe('top.corpo', 'db_iframe_pcorcam', sUrl, 'Pesquisar Orçamento da Solicitação de Compras', true);
      } else {

        $('btnProcessar').disabled = true;
        var sValorCampo = $F("cod_orcamento_solicitacao");
        sUrl += "&pesquisa_chave=" + sValorCampo + "&funcao_js=parent.js_mostrar_orcamento_solicitacao_background";
        js_OpenJanelaIframe('top.corpo', 'db_iframe_pcorcam', sUrl, 'Pesquisar Orçamento da Solicitação de Compras', false);
      }
    }    
    
    function js_mostrar_orcamento_solicitacao(sCodigoOrcamento) {
     
      $('btnProcessar').disabled = false;
      $("cod_orcamento_solicitacao").value = sCodigoOrcamento;
      db_iframe_pcorcam.hide(); 
    }
  
    function js_mostrar_orcamento_solicitacao_background(sCodigoOrcamento, lErro) {
      
      $('btnProcessar').disabled = false;
      if (lErro) {
        
        var iCodigoOrcamento = $F("cod_orcamento_solicitacao");
        alert( _M(MENSAGENS + "orcamento_nao_encontrado", { sCodigo : iCodigoOrcamento}));
      
        $("cod_orcamento_solicitacao").value = "";
        $("cod_orcamento_solicitacao").focus();
      }
    }

    function js_verificar_campo(iTipoCampo) {

      var sCodigoOrcamentoProcesso    = $F('cod_orcamento_processo');
      var sCodigoOrcamentoSolicitacao = $F('cod_orcamento_solicitacao');
      var lErro                       = false;
      
      if (iTipoCampo == 1) {
        
        if (!empty(sCodigoOrcamentoProcesso)) {

          $('cod_orcamento_solicitacao').value = "";
          lErro                                = true;
        }
      } else if (iTipoCampo == 2) {

        if (!empty(sCodigoOrcamentoSolicitacao)) {

          $('cod_orcamento_processo').value = "";
          lErro                             = true;
        }
      }
      
      if (lErro) {
        alert( _M(MENSAGENS + "selecione_apenas_um"));
      }
      
      return lErro;
    }
    
    function js_validar_formulario(){
      
      var sCodigoOrcamentoProcesso    = $F('cod_orcamento_processo');
      var sCodigoOrcamentoSolicitacao = $F('cod_orcamento_solicitacao');
      
      if(empty(sCodigoOrcamentoProcesso) && empty(sCodigoOrcamentoSolicitacao)) {
        
        alert( _M(MENSAGENS + "selecione_um_orcamento"));
        return false;
      }
      
      if (!empty(sCodigoOrcamentoProcesso)) {
        js_submeter_formulario(sCodigoOrcamentoProcesso, "processo"); 
      } 
      
      if (!empty(sCodigoOrcamentoSolicitacao)) {
        js_submeter_formulario(sCodigoOrcamentoSolicitacao, "solicitacao");
      }
      
    }
    
    function js_submeter_formulario(iCodigoOrcamento, sTipoOrcamento) {
      
      var sQueryString  = 'pc20_codorc=' + iCodigoOrcamento;
      sQueryString     += '&tipoOrcamento=' + sTipoOrcamento;
      sQueryString     += '&modelo=' + $F('modelo');
      sQueryString     += '&imp_troca=' + $F('imp_troca');

      var jan = window.open('com2_mapaorc002.php?' + sQueryString, 
                            '', 
                            'width=' + (screen.availWidth - 5) + ',height=' + (screen.availHeight - 40) + ',scrollbars=1,location=0 ');
      jan.moveTo(0, 0);
    }
  </script>
</html>