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
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_utils.php");

$oRotuloInventario = new rotulo('inventario');
$oRotuloInventario->label();
/*
 * Validamos o parametro para saber se devemos permitir ordenar o relatorio por orgao
 */
$oDaoParametro      = db_utils::getDao("cfpatri");
$sSqlBuscaParametro = $oDaoParametro->sql_query_file(null, "t06_pesqorgao");
$rsBuscaParametro   = $oDaoParametro->sql_record($sSqlBuscaParametro);
$lPesquisaOrgao     = false;
if ($oDaoParametro->numrows > 0) {
  
  $sParametroOrgao = db_utils::fieldsMemory($rsBuscaParametro, 0)->t06_pesqorgao;
  $sParametroOrgao == "t" ? $lPesquisaOrgao = true : $lPesquisaOrgao = false;
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
    
    <style>
      .fieldsetOrdenacao {
        border-bottom: none;
        border-right: none;
        border-left: none;
      }
      select {
        width: 120px;
      }
    </style>
  </head>
  <body  bgcolor=#CCCCCC >
    <form class="container" name="form1" id="form1">
        <fieldset>
          <legend>Manutenção de Inventário</legend>
          <table class="form-container">
            <tr>
              <td>
                <?php 
                  db_ancora("<b>{$Lt75_sequencial}</b>", "js_pesquisaInventario(true);", 1);
                ?>
              </td>
              <td>
                <?php 
                  db_input("t75_sequencial", 10, $It75_sequencial, true, 'text', 3, 'js_pesquisaInventario(false);');
                ?>
              </td>
            </tr>
            <tr>
              <td>
                Formato do Documento:
              </td>
              <td>
                <?php 
                  $aModelos = array("pdf" => "PDF", "csv" => "CSV");
                  db_select("sModelo", $aModelos, true, 1, "onchange='js_alterarModelo();'");
                ?>
              </td>
            </tr>
            
            <tr>
              <td>
                Modelo:
              </td>
              <td>
                <select id='movFinanceira' name='movFinanceira'>
                  <option value = "0" >MODELO 1</option>
                  <option value = "1" >MODELO 2</option>
                </select>
              </td>
            </tr>            
          </table>
          
          <fieldset class="fieldsetOrdenacao" id="fieldsetOrdenacao">
            <legend>Ordenação</legend>
            <table  class="form-container">
              <tr>
                <td>
                  <select multiple="multiple" style="width: 150px" id="selectOrigem">
                    <?php 
                      if ($lPesquisaOrgao) {
                        echo "<option id='o40_orgao' value='o40_orgao'>Órgão</option>";
                        echo "<option id='o41_unidade' value='o41_unidade'>Unidade</option>";
                      }
                    ?>
                    <option id='coddepto' value='coddepto'>Departamento</option>
                    <option id='t30_codigo' value='t30_codigo'>Divisão</option>
                  </select>
                </td>
                <td>
                  <input type="button" name="btnAdicionarCampo" id="btnAdicionarCampo" value=">" />
                  <br /><br />
                  <input type="button" name="btnRemoverCampo" id="btnRemoverCampo" value='&lt;' />
                </td>
                <td>
                  <select multiple="multiple" style="width: 150px" id="selectDestino">
                  </select>
                </td>
              </tr>
            </table>
            <table class="form-container">
              <tr>
                <td>
                  Ordem:
                </td>
                <td>
                  <?php 
                    $aOrdem = array("asc" => "Crescente", "desc" => "Decrescente");
                    db_select("sOrdem", $aOrdem, true, 1);
                  ?>
                </td>
              </tr>
              <tr>
                <td>
                  Quebra de Página:
                </td>
                <td>
                  <?php 
                    $aQuebraPagina = array("t" => "Sim", "f" => "Não");
                    db_select("sQuebraPagina", $aQuebraPagina, true, 1);
                  ?>
                </td>
              </tr>
            </table>
          </fieldset>
        </fieldset>
          <input type="button" name="btnImprimir" id="btnImprimir" value="Imprimir" />
    </form>
    <? 
    db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
    ?> 
  </body>
</html>

<script>

function js_alterarModelo() {

  if ($F('sModelo') == "csv") {
    $('fieldsetOrdenacao').style.display = "none";
  } else {
    $('fieldsetOrdenacao').style.display = "";
  }
}
    
$('btnImprimir').observe('click', function() {

  if ($F('t75_sequencial') == "") {
    alert(_M("patrimonial.patrimonio.pat3_relatoriomanutencaoinventario001.selecione_inventario"));
    return false
  }

  var aOpcoesOrdem = $('selectDestino').options;
  var iTotalOpcao  = $('selectDestino').length;
  var aOpcoesSelecionadas = new Array();
  if (iTotalOpcao > 0) {
    for (var i=0; i<iTotalOpcao; i++) {
      aOpcoesSelecionadas.push(aOpcoesOrdem[i].value);
    }
  }

  if ($F('sModelo') == "pdf") {
  
    var sQuery  = "pat3_relatoriomanutencaoinventario002.php?";
        sQuery += "&iCodigoInventario="       + $F('t75_sequencial');
        sQuery += "&lQuebraPagina="           + $F('sQuebraPagina');
        sQuery += "&sModelo="                 + $F('sModelo');
        sQuery += "&sTipoOrdem="              + $F('sOrdem');
        sQuery += "&iMovimentacaoFinanceira=" + $F('movFinanceira');
        sQuery += "&sOrdem="                  + aOpcoesSelecionadas.toString();
        sQuery += "&lParametro=<?php echo $sParametroOrgao;?>";

    var jan = window.open(sQuery,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0');
    jan.moveTo(0,0);
    
  } else {
    /* Imprime CSV */
    var oParam               = new Object();
    oParam.exec              = "emiteCSV";
    oParam.iCodigoInventario = $F('t75_sequencial');
    oParam.lQuebraPagina     = $F('sQuebraPagina');
    oParam.sModelo           = $F('sModelo');
    oParam.sTipoOrdem        = $F('sOrdem');
    oParam.sOrdem            = aOpcoesSelecionadas.toString();
    oParam.lParametro        = '<?php echo $sParametroOrgao;?>';
    
    var oAjax = new Ajax.Request('pat3_relatoriomanutencaoinventario003.php',
                                {method:'post',
                                 parameters:'json='+Object.toJSON(oParam),
                                 onComplete: js_retornoRelatorioCSV});
    
  }
});

function js_retornoRelatorioCSV(oAjax) {

  var oRetorno = eval("("+oAjax.responseText+")");

  var sArquivo = oRetorno.sCaminhoArquivo+'#Download do Arquivo';
  
  alert(_M("patrimonial.patrimonio.pat3_relatoriomanutencaoinventario001.arquivo_gerado"));
  js_montarlista(sArquivo,'form1');	    
}

function moverItens(oOrigem, oDestino) {

  var aOptions      = oOrigem.options;
  var iTotalOptions = aOptions.length;
  var aOptionsMover = new Array();
  for (var iRow = 0; iRow < iTotalOptions; iRow++) {

    var oOptionOrigem = aOptions[iRow];
    if (oOptionOrigem.selected) {

      var oOptionDestino       = new Option();
      oOptionDestino.value     = oOptionOrigem.value;
      oOptionDestino.innerHTML = oOptionOrigem.innerHTML;

      oDestino.appendChild(oOptionDestino, null);
      aOptionsMover.push(oOptionOrigem);
    }
  }
  aOptionsMover.each(function(oOptionOrigem, iSeq) {
      oOptionOrigem.remove();
  });
  delete aOptionsMover;
}

$('btnAdicionarCampo').observe('click', function() {
  moverItens($('selectOrigem'), $('selectDestino'));
});

$('btnRemoverCampo').observe('click', function() {
  moverItens($('selectDestino'), $('selectOrigem'));
});

/*
 * Funcao que envia os dados de origem
 */
$('selectOrigem').observe('dblclick', function() {
  moverItens($('selectOrigem'), $('selectDestino'));
});

/*
 * Funcao que envia os dados de destino 
 */
$('selectDestino').observe('dblclick', function() {
  moverItens($('selectDestino'), $('selectOrigem'));
});
    
function js_pesquisaInventario(lMostra) {

  var sUrlPesquisa = "func_inventario.php?funcao_js=parent.js_preencheInventario|t75_sequencial";
  js_OpenJanelaIframe("", "db_iframe_inventario", sUrlPesquisa, 'Pesquisa Inventário', lMostra);
}
function js_preencheInventario(iCodigoInventario) {

  $('t75_sequencial').value = iCodigoInventario;
  db_iframe_inventario.hide();
}
js_pesquisaInventario(true);
</script>
<script>

$("t75_sequencial").addClassName("field-size2");
$("t75_sequencial").addClassName("field-size2");

</script>