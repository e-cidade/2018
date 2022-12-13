<?php
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
require_once("libs/db_utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("classes/db_orcprojativ_classe.php");
require_once("classes/db_orcprojativunidaderesp_classe.php");
require_once("dbforms/db_funcoes.php");

$oGet = db_utils::postMemory($_GET);

$oRotuloOrcObjetivo = new rotulo('orcobjetivo');
$oRotuloOrcObjetivo->label('o143_sequencial');

$oRotuloOrcMeta = new rotulo('orcmeta');
$oRotuloOrcMeta->label('o145_sequencial');

$oRotuloOrcIniciativa = new rotulo('orciniciativa');
$oRotuloOrcIniciativa->label('o147_sequencial');


?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="#CCCCCC" style="margin-top:30px;" >
  <center>
    <fieldset style="width: 550px">
      <legend><b>Vínculo de Iniciativas</b></legend>
        <table>
          <tr>
            <td>
              <?php
                db_ancora("{$Lo147_sequencial}", "js_pesquisaIniciativa(true)", 1);
              ?>
            </td>
            <td>
              <?php
                db_input("o147_sequencial", 8, $Io147_sequencial, true, 'text', 1, "onchange='js_pesquisaIniciativa(false)'");
                db_input("o147_descricao",  45, false,  true, 'text', 3);
              ?>
            </td>
          </tr>
        </table>
    </fieldset>
    <p align="center">
      <input type="button" id="btnVincularIniciativa" value="Vincular Iniciativa"/>
    </p>
    <fieldset style="width: 550px">
      <legend><b>Iniciativas Vinculadas</b></legend>
      <div id="ctnIniciativasVinculadas">
      </div>
    </fieldset>
    <p align="center">
      <input type="button" id="btnExcluirIniciativaVinculada" value="Excluir Selecionado(s)"/>
    </p>

  </center>
</body>
</html>
<script>
//codprojativ=1000&anousu=2013
  var oGet    = js_urlToObject(null);
  var sUrlRPC = "orc1_programa.RPC.php";


  var oGridIniciativa          = new DBGrid('ctnIniciativasVinculadas');
  oGridIniciativa.nameInstance = 'oGridIniciativa';
  oGridIniciativa.setCheckbox(0);
  oGridIniciativa.setHeader(new Array("Código", "Descrição"));
  oGridIniciativa.setCellWidth(new Array("15%", "85%"));
  oGridIniciativa.setCellAlign(new Array("right", "left"))
  oGridIniciativa.setHeight(200);
  oGridIniciativa.show($('ctnIniciativasVinculadas'));

  function js_pesquisaIniciativaVinculada() {

    js_divCarregando("Buscando iniciativas vinculadas, aguarde...", "msgBox");

    var oParam             = new Object();
    oParam.exec            = "getIniciativaVinculadaProjetoAtividade";
    oParam.iAnoProjeto     = oGet.anousu;
    oParam.iCodigoProjeto  = oGet.codprojativ;

    var oAjax       = new Ajax.Request(sUrlRPC,
                                      {method: 'post',
                                       parameters: 'json='+Object.toJSON(oParam),
                                       onComplete: js_preencheGridIniciativa
                                      });
  }

  function js_preencheGridIniciativa(oAjax) {

    js_removeObj("msgBox");
    var oRetorno = eval("("+oAjax.responseText+")");

    if (oRetorno.status == 2) {

      alert(oRetorno.message.urlDecode());
      return false;
    }

    oGridIniciativa.clearAll(true);
    oRetorno.aIniciativasProjetoAtividade.each(function (oIniciativa, iIndice) {

      var aLinha = new Array();
      aLinha[0]  = oIniciativa.o147_sequencial;
      aLinha[1]  = oIniciativa.o147_descricao.urlDecode();
      oGridIniciativa.addRow(aLinha);
    });

    oGridIniciativa.renderRows();

  }

  /**
   * Vincula iniciativas aum projeto/atividade
   */
  $('btnVincularIniciativa').observe('click', function() {

    if ($F("o147_sequencial") == "") {

      alert("Selecione uma iniciativa.");
      return false;
    }

    js_divCarregando("Vinculando iniciativa selecionada, aguarde...", "msgBox");

    var oParam               = new Object();
    oParam.exec              = "vincularIniciativaProjeto";
    oParam.iAnoProjeto       = oGet.anousu;
    oParam.iCodigoProjeto    = oGet.codprojativ;
    oParam.iCodigoIniciativa = $F("o147_sequencial");

    var oAjax       = new Ajax.Request(sUrlRPC,
                                      {method: 'post',
                                       parameters: 'json='+Object.toJSON(oParam),
                                       onComplete: function (oAjax) {

                                         var oRetorno = eval("("+oAjax.responseText+")");
                                         js_removeObj("msgBox");
                                         js_limpaIniciativa();
                                         alert(oRetorno.sMessage.urlDecode());
                                         js_pesquisaIniciativaVinculada();
                                       }
                                      });


  });


  /**
   * Função que desvincula iniciativas de um projeto/atividade
   */
  $('btnExcluirIniciativaVinculada').observe('click', function() {

    var aIniciativaExcluir = oGridIniciativa.getSelection();
    if (aIniciativaExcluir.length == 0) {

      alert("Selecione alguma iniciativa para excluir.");
      return false;
    }

    if (!confirm("Deseja desvincular as iniciativas selecionadas?")) {
      return false;
    }

    js_divCarregando("Excluindo iniciativas selecionadas, aguarde...", "msgBox");

    var oParam                 = new Object();
    oParam.exec                = "excluirVinculoIniciativaProjeto";
    oParam.iAnoProjeto         = oGet.anousu;
    oParam.iCodigoProjeto      = oGet.codprojativ;
    oParam.aIniciativasExcluir = aIniciativaExcluir;

    var oAjax       = new Ajax.Request(sUrlRPC,
                                      {method: 'post',
                                       parameters: 'json='+Object.toJSON(oParam),
                                       onComplete: function () {

                                         js_removeObj("msgBox");
                                         js_limpaIniciativa();
                                         alert("Iniciativas desvinculadas com sucesso.");
                                         js_pesquisaIniciativaVinculada();
                                       }
                                      });
  });

  js_pesquisaIniciativaVinculada();


  /**
   * Verifica se a Iniciativa já está vinculada.
   */
  function js_iniciativaEstaVinculada() {

    var iCodigoIniciativa = $F("o147_sequencial");
    var lEstaVinculada    = false;
    oGridIniciativa.aRows.each(function (oRow, iIndice) {

      if (oRow.aCells[1].getValue() == iCodigoIniciativa) {
        lEstaVinculada = true;
      }
    });
    return lEstaVinculada;
  }


  /**
   * Funções relacionadas à iniciativa
   */
  function js_pesquisaIniciativa(lMostra) {

    var sUrlIniciativa = "func_orciniciativa.php?funcao_js=parent.js_preencheIniciativa|o147_sequencial|o147_descricao";
    if (!lMostra) {
      sUrlIniciativa = "func_orciniciativa.php?funcao_js=parent.js_completaIniciativa&pesquisa_chave="+$F('o147_sequencial');
    }
    js_OpenJanelaIframe('','db_iframe_iniciativa', sUrlIniciativa, 'Pesquisa Iniciativa', lMostra);
  }

  function js_preencheIniciativa(iCodigoIniciativa, sDescricaoIniciativa) {

    $('o147_sequencial').value = iCodigoIniciativa;
    $('o147_descricao').value  = sDescricaoIniciativa;
    if (js_iniciativaEstaVinculada()) {

      alert("Iniciativa já vinculada.");
      js_limpaIniciativa();
    }
    db_iframe_iniciativa.hide();
  }

  function js_completaIniciativa(sDescricaoIniciativa, lErro) {

    $('o147_descricao').value  = sDescricaoIniciativa;
    if (lErro) {
      $('o147_sequencial').value = "";
    }
    if (js_iniciativaEstaVinculada()) {

      alert("Iniciativa já vinculada.");
      js_limpaIniciativa();
    }
  }

  /**
   * Função que limpa os campos Iniciativa
   */
  function js_limpaIniciativa() {

    $('o147_sequencial').value = "";
    $('o147_descricao').value  = "";
  }

</script>