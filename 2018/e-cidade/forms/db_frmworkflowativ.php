<?
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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

//MODULO: Habitacao
require_once(modification("dbforms/db_classesgenericas.php"));
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;

$clrotulo = new rotulocampo;
$clrotulo->label("descrdepto");
$clrotulo->label("p51_codigo");
$clrotulo->label("p51_descr");
$clrotulo->label("db117_db_cadattdinamico");

$clworkflow->rotulo->label();
$clworkflowativ->rotulo->label();
$clworkflowativandpadrao->rotulo->label();
$clandpadrao->rotulo->label();

if (isset($oPost->db_opcaoal)) {

  $db_opcao = 33;
  $db_botao = false;
} else if (isset($oPost->opcao) && $oPost->opcao == "alterar") {

  $db_botao = true;
  $db_opcao = 2;
} else if (isset($oPost->opcao) && $oPost->opcao == "excluir") {

  $db_opcao = 3;
  $db_botao = true;
} else {

  $db_opcao = 1;
  $db_botao = true;
  if (isset($oPost->novo) || isset($oPost->excluir) && $lSqlErro == false) {

  	$db117_db_cadattdinamico = '';
    $db114_descricao         = '';
    $p53_coddepto            = '';
    $descrdepto              = '';
  }

  if (isset($oPost->incluir) && $lSqlErro == false || isset($oPost->alterar) && $lSqlErro == false) {
  	$db_opcao = 2;
  }
}
?>
<form name="form1" method="post" action="">
<?php
db_input('db114_sequencial',10,$Idb114_sequencial,true,'hidden',3);
db_input('db114_ordem',10,$Idb114_ordem,true,'hidden',3);
db_input('db117_db_cadattdinamico',10,$Idb117_db_cadattdinamico,true,'hidden',3);
?>
<fieldset>
<legend><b>Cadastro de Atividades</b></legend>
<table border="0" align="left" width="100%">
  <tr>
    <td nowrap title="<?=@$Tdb112_sequencial?>">
      <?=@$Ldb112_sequencial?>
    </td>
    <td width="10">
			<?
			  db_input('db112_sequencial',10,$Idb112_sequencial,true,'text',3);
      ?>
    </td>
    <td>
      <?
        db_input('db112_descricao',60,$Idb112_descricao,true,'text',3);
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tp51_codigo?>">
      <?=@$Lp51_codigo?>
    </td>
    <td width="10">
      <?
        db_input('p51_codigo',10,$Ip51_codigo,true,'text',3);
      ?>
    </td>
    <td>
      <?
        db_input('p51_descr',60,$Ip51_descr,true,'text',3);
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdb114_descricao?>">
      <?=@$Ldb114_descricao?>
    </td>
    <td width="10" colspan="2">
      <?
        db_input('db114_descricao',80,$Idb114_descricao,true,'text',$db_opcao);
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tp53_coddepto?>">
      <?
        db_ancora('<b>Código do Departamento:</b>',"js_pesquisap53_coddepto(true);",$db_opcao);
      ?>
    </td>
    <td width="10">
      <?
        db_input('p53_coddepto',10,$Ip53_coddepto,true,'text',$db_opcao," onchange='js_pesquisap53_coddepto(false);'");
      ?>
    </td>
    <td>
      <?
        db_input('descrdepto',50,$Idescrdepto,true,'text',3,'');
      ?>
    </td>
  </tr>
</table>
</fieldset>
<table>
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td>
      <?php
        echo "<input name='".($db_opcao==1?'incluir':($db_opcao==2||$db_opcao==22?'alterar':'excluir'))."'\n";
        echo "       type='submit' id='db_opcao' onclick='return js_validar();' \n";
        echo "       value='".($db_opcao==1?'Incluir':($db_opcao==2||$db_opcao==22?'Alterar':'Excluir'))."' \n";
        echo "       ".($db_botao==false?'disabled':'').">\n";

        if ($db_opcao != 1) {
          echo "<input name='novo' type='button' id='cancelar' value='Novo' onclick='js_cancelar();'>\n";
        }

        if ($db_opcao == 2) {
          echo "<input type='button' id='lancaratributos' name='lancaratributos' value='Lançar Atributos'\n";
          echo "       onclick='return js_lancarAtributos();'>\n";
        }

        if ($iNumRowsWorkflowAtiv >= 2 && $db_opcao != 3) {

          echo "<input type='button' id='ordenar' name='ordenar' value='Ordenar' \n";
          echo "       onclick='return js_pesquisaAtividadesLancadas();'> \n";
        }
      ?>
    </td>
  </tr>
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
</table>
<table>
  <tr>
    <td valign="top"  align="center">
	    <?
			  $sWhere                     = "workflowativ.db114_workflow = {$db112_sequencial}";
			  $sOrderBy                   = "workflowativ.db114_ordem";
			  $sSqlWorkflowAtivAndPadrao  = $clworkflowativandpadrao->sql_query(null, "*", $sOrderBy, $sWhere);

        $cliframe_alterar_excluir->sql           = $sSqlWorkflowAtivAndPadrao;
        $cliframe_alterar_excluir->chavepri      = array("db114_sequencial" => @$db114_sequencial);
        $cliframe_alterar_excluir->campos        = "db114_sequencial, db114_descricao, db114_workflow, ";
        $cliframe_alterar_excluir->campos       .= "coddepto, descrdepto, db114_ordem                  ";
        $cliframe_alterar_excluir->legenda       = "ATIVIDADES LANÇADAS";
        $cliframe_alterar_excluir->iframe_height = "160";
        $cliframe_alterar_excluir->iframe_width  = "800";
        $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
	    ?>
    </td>
  </tr>
</table>
</form>
<script>
var sUrlRPC = "hab1_workflowativ.RPC.php";

/**
 * Valida campos antes do cadastro
 */
function js_validar() {

  var iCodDepto  = $('p53_coddepto').value;
  var sDescricao = $('db114_descricao').value;

  if (sDescricao == '') {

    alert('Descrição não informada!');
    return false;
  }

  if (iCodDepto == '') {

    alert('Código do departamento não informado!');
    return false;
  }
}

/**
 * Cancela processo de alteração e exclusão
 */
function js_cancelar(){
  var opcao = document.createElement("input");
  opcao.setAttribute("type","hidden");
  opcao.setAttribute("name","novo");
  opcao.setAttribute("value","true");
  document.form1.appendChild(opcao);
  document.form1.submit();
}

/**
 * Pesquisa departamento para inclusão
 */
function js_pesquisap53_coddepto(mostra) {

  var p53_coddepto = $('p53_coddepto').value;
  if (mostra==true) {

    var sUrl = 'func_db_depart.php?funcao_js=parent.js_mostradepart1|coddepto|descrdepto';
    js_OpenJanelaIframe('', 'db_iframe_depart', sUrl, 'Pesquisa', true, '0');
  } else {

    if (p53_coddepto != '') {

      var sUrl = 'func_db_depart.php?pesquisa_chave='+p53_coddepto+'&funcao_js=parent.js_mostradepart';
      js_OpenJanelaIframe('', 'db_iframe_depart', sUrl, 'Pesquisa', false, '0');
    } else {
      $('descrdepto').value = '';
    }
  }
}

function js_mostradepart(chave,erro) {

  $('descrdepto').value = chave;
  if (erro == true) {

    $('p53_coddepto').focus();
    $('p53_coddepto').value = '';
  }
}

function js_mostradepart1(chave1,chave2) {

  $('p53_coddepto').value = chave1;
  $('descrdepto').value   = chave2;
  db_iframe_depart.hide();
}

/**
 * Monta a window para a ordenação das atividades
 */
function js_mostrarWindow() {

  if ($('windowOrdenacao')) {
    windowOrdenacao.destroy();
  }

  var sConteudo  = "<table border='0' width='50%'>";
      sConteudo += "  <tr>";
      sConteudo += "    <td>";
      sConteudo += "      <fieldset>";
      sConteudo += "      <legend>";
      sConteudo += "        <b>Atividades Lançadas</b>";
      sConteudo += "      </legend>";
      sConteudo += "      <table cellspacing='0' style='border:2px inset white; width:450px'>";
      sConteudo += "        <thead>";
      sConteudo += "        <tr>";
      sConteudo += "          <th class='table_header'><b>Código</b></th>";
      sConteudo += "          <th class='table_header'><b>Descrição</b></th>";
      sConteudo += "          <th class='table_header' style='display: none;'><b>Código And Padrão</b></th>";
      sConteudo += "          <th class='table_header' style='display: none;'><b>Código Departamento</b></th>";
      sConteudo += "          <th class='table_header' width='12px'>&nbsp;</th>";
      sConteudo += "        </tr>";
      sConteudo += "        </thead>";
      sConteudo += "        <tbody id='atividadesLancadas'></tbody>";
      sConteudo += "        <tr>";
      sConteudo += "          <td class='table_footer' colspan='2' class='gridtotalizador'>";
      sConteudo += "            <div align='left'>";
      sConteudo += "              <table border='0' width='10%'>";
      sConteudo += "                <tr>";
      sConteudo += "                  <td><b>Total:</b></td>";
      sConteudo += "                  <td id='totalLinhas' style='color: #2111f9'></td>";
      sConteudo += "                </tr>";
      sConteudo += "              </table>";
      sConteudo += "            </div>";
      sConteudo += "          </td>";
      sConteudo += "        </tr>";
      sConteudo += "      </table>";
      sConteudo += "    </fieldset>";
      sConteudo += "    </td>";
      sConteudo += "    <td>";
      sConteudo += "      <table>";
      sConteudo += "        <tr>";
      sConteudo += "          <td>";
      sConteudo += "            <input name='movercima' type='button' value='^' ";
      sConteudo += "                   title='Ordenar para Cima' style='width:30px;' onClick='js_moveUp();'>";
      sConteudo += "          </td>";
      sConteudo += "        </tr>";
      sConteudo += "        <tr>";
      sConteudo += "          <td>";
      sConteudo += "            <input name='moverbaixo' type='button' value='v' ";
      sConteudo += "                   title='Ordenar para Baixo' style='width:30px;' onClick='js_moveDown();'>";
      sConteudo += "          </td>";
      sConteudo += "        </tr>";
      sConteudo += "      </table>";
      sConteudo += "    </td>";
      sConteudo += "  </tr>";
      sConteudo += "  <tr>";
      sConteudo += "    <td colspan='4'>&nbsp;</td>";
      sConteudo += "  </tr>";
      sConteudo += "  <tr>";
      sConteudo += "    <td colspan='4' align='center'>";
      sConteudo += "      <input type='button' id='btnAtualizar' value='Atualizar'";
      sConteudo += "             onclick='return js_AtualizarOrdemAtividades();'>";
      sConteudo += "    </td>";
      sConteudo += "  </tr>";
      sConteudo += "</table>";

  windowOrdenacao = new windowAux('windowOrdenacao', 'Ordenar Atividades Lançadas', 530, 530);
  windowOrdenacao.setContent(sConteudo);

  var oMessageBoardOrdenacao = new DBMessageBoard("msgBoxOrdemAtividades",
                                                "Ordena as atividades cadastradas para "+
                                                $('db112_sequencial').value+' - '+
                                                $('db112_descricao').value,
                                                'Informe a ordem das atividades do WORKFLOW.',
                                                $("windowwindowOrdenacao_content")
                                               );
  oMessageBoardOrdenacao.show();

  $('windowwindowOrdenacao_btnclose').onclick= function () {

    windowOrdenacao.destroy();
    location.href = location.href;
  }

  windowOrdenacao.show();
}

/**
 * Busca atividades já lançadas pelo usuário
 */
function js_pesquisaAtividadesLancadas() {

  var iCodWorkFlow = $('db112_sequencial').value;

  js_divCarregando('Aguarde, pesquisando atividades...','msgBoxListaAtividades');

  var oParam             = new Object();
      oParam.exec        = "verificaAtividadesLancadas";
      oParam.codworkflow = iCodWorkFlow;
  var oAjax              = new Ajax.Request(
                         sUrlRPC,
                           {
                             method    : 'post',
                             parameters: 'json='+Object.toJSON(oParam),
                             onComplete: function (oAjax) {

                               js_removeObj('msgBoxListaAtividades');
                               js_mostrarWindow();

                               var oRetorno = eval("("+oAjax.responseText+")");

                               $('atividadesLancadas').innerHTML = js_carregaGridAtividadesLancadas(oRetorno.aAtividadesLancadas);
                             }
                           }
                         );
}

/**
 * Preenche a grid com as atividades lançadas
 */
function js_carregaGridAtividadesLancadas(aAtividadesLancadas){

  var sLinha   = "";
  var iNumRows = aAtividadesLancadas.length;

  if (iNumRows > 0) {

    aAtividadesLancadas.each(
      function (oAtividadesLancadas) {

        var iCodAtividade = oAtividadesLancadas.db114_sequencial;
        var sDescricao    = oAtividadesLancadas.db114_descricao;
        var iCodAndPadrao = oAtividadesLancadas.p53_codigo;
        var iCodDepto     = oAtividadesLancadas.p53_coddepto;

        var sAtributos    = " class='linhagrid'";
            sAtributos   += " id='"+iCodAtividade+"'";
            sAtributos   += " style='text-align:left; -moz-user-select:none;'";
            sAtributos   += " onclick='js_marcaLinha(\"linhaCampo"+iCodAtividade+"\", \"marcaRetira\", false);'";
            sAtributos   += " ondblclick='js_retiraCampoSel();'";

            sLinha += " <tr id='linhaCampo"+iCodAtividade+"'>";
            sLinha += "   <td "+sAtributos+" >"+iCodAtividade+"</td> ";
            sLinha += "   <td "+sAtributos+" >"+sDescricao.urlDecode()+"</td> ";
            sLinha += "   <td style='display: none;'>"+iCodAndPadrao+"</td> ";
            sLinha += "   <td style='display: none;'>"+iCodDepto+"</td> ";
            sLinha += " </tr> ";

      }
    );

    sLinha += "<tr id='ultimaLinha' ><td colspan='2' style='height:100%;'>&nbsp;</td></tr>";
  }

  $('totalLinhas').innerHTML = "<b>"+iNumRows+"</b>";

  return sLinha;
}

/**
 * Move registro para cima
 */
function js_moveUp(){

  var objMarcados = js_getElementbyClass($('atividadesLancadas').rows,'marcaRetira');

  if (objMarcados.length > 1 ) {

    alert("Favor escolha apenas uma linha");
    return false;
  } else if (objMarcados.length == 0) {
    return false;
  }


  var iRow    = objMarcados[0];
  var tbody  = $('atividadesLancadas');
  var iRowId  = iRow.rowIndex;
  var hTable = tbody.parentNode;
  var nextId = iRowId-1;

  if (nextId == 0)  {
    return false;
  }

  var next = hTable.rows[nextId];
  tbody.removeChild(iRow);
  tbody.insertBefore(iRow, next);

}

/**
 * Move registro para baixo
 */
function js_moveDown(){

  var objMarcados = js_getElementbyClass($('atividadesLancadas').rows,'marcaRetira');

  if (objMarcados.length > 1 ) {

    alert("Favor escolha apenas uma linha");
    return false;
  } else if (objMarcados.length == 0) {
    return false;
  }

  var iRow   = objMarcados[0];
  var tbody  = $('atividadesLancadas');
  var iRowId = iRow.rowIndex;
  var hTable = tbody.parentNode;
  var nextId = parseInt(iRowId)+2;

  if (nextId > hTable.rows.length-2 ) {
     return false;
  }

  var next = hTable.rows[nextId];
  tbody.removeChild(iRow);
  tbody.insertBefore(iRow, next);

}

/**
 * Marca linha selecionado
 */
function js_marcaLinha(iCod,sTipoMarca,lDesmarca) {

  var aListaRows = $$('#atividadesLancadas tr');
  aListaRows.each(
    function (oRow) {
      oRow.className = '';
    }
  );

  if ($(iCod).className != sTipoMarca) {
    $(iCod).className = sTipoMarca;
  } else {

    if (lDesmarca) {
      $(iCod).className = 'linhagrid';
    } else {
      $(iCod).className = 'marcaSel';
    }
  }
}

/**
 * Desmarca campos selecionados
 */
function js_retiraCampoSel() {

  var objMarcados      = js_getElementbyClass($('atividadesLancadas').rows,'marcaRetira');
  var iLinhasMarcados  = objMarcados.length;

  if(iLinhasMarcados > 0) {

    objMarcados.each(
      function (oAtividadesLancadas, iInd) {

        var sIdCampo = oAtividadesLancadas.id;
        js_marcaLinha(sIdCampo, "marcaRetira", true);
      }
    );
  }
}

/**
 * Atualiza ordem das atividades
 */
function js_AtualizarOrdemAtividades() {

  var iCodWorkFlow               = $('db112_sequencial').value;
  var iOrdem                     = 1;

  var oParam                     = new Object();
      oParam.exec                = "atualizarOrdemAtividades";
      oParam.codworkflow         = iCodWorkFlow;
      oParam.aAtividadesLancadas = new Array();

  var aAtividadesLancadas = $('atividadesLancadas').rows;

  for (var i = 0; i < ( aAtividadesLancadas.length -1 ); i++) {

    var oAtividadesLancadas               = new Object();
        oParam.iCodAndPadrao              = aAtividadesLancadas[i].cells[2].innerHTML;
        oAtividadesLancadas.iCodAtividade = aAtividadesLancadas[i].cells[0].innerHTML;
        oAtividadesLancadas.iOrdemNova    = iOrdem++;
        oAtividadesLancadas.iCodDepto     = aAtividadesLancadas[i].cells[3].innerHTML;
        oParam.aAtividadesLancadas.push(oAtividadesLancadas);
  }

  js_divCarregando('Aguarde, atualizando...','msgBoxListaAtividades');

  var oAjax        = new Ajax.Request(
                       sUrlRPC,
                       {
                         method    : 'post',
                         parameters: 'json='+Object.toJSON(oParam),
                         onComplete: function (oAjax) {

                           js_removeObj('msgBoxListaAtividades');

                           var oRetorno = eval("("+oAjax.responseText+")");
                           if (oRetorno.status == 2) {

                             alert(oRetorno.message.urlDecode());
                             return false;
                           } else {
                             js_pesquisaAtividadesLancadas();
                           }
                         }
                       }
                     );
}

/**
 * Metodo para lançar atributos para a atividade
 */
function js_lancarAtributos() {

  var iCodigoAttDinamico = $('db117_db_cadattdinamico').value;
  var oCadastroAtributoDinamico = new DBViewCadastroAtributoDinamico();
  if (iCodigoAttDinamico == '') {
    oCadastroAtributoDinamico.newAttribute();
  } else {
    oCadastroAtributoDinamico.loadAttribute(iCodigoAttDinamico);
  }

  oCadastroAtributoDinamico.setSaveCallBackFunction(
    function (iRetornoCodigoAttDinamico) {

      $('db117_db_cadattdinamico').value = iRetornoCodigoAttDinamico;
      js_salvarRelacaoLancaAtributos(iRetornoCodigoAttDinamico);
    }
  );
}

/**
 *
 */
function js_salvarRelacaoLancaAtributos(CodigoAttDinamico) {

  var iCodWorkFlowAtiv = $('db114_sequencial').value;

  if (CodigoAttDinamico == '') {

    alert('Código do atributo dinâmico não informado!');
    return false;
  }

  var oParam                 = new Object();
      oParam.exec            = "salvarRelacaoLancaAtributos";
      oParam.codworkflowativ = iCodWorkFlowAtiv;
      oParam.codattdinamico  = CodigoAttDinamico;

  js_divCarregando('Aguarde, salvando...','msgBoxListaRelacaoLancaAtributos');

  var oAjax        = new Ajax.Request(
                       sUrlRPC,
                       {
                         method    : 'post',
                         parameters: 'json='+Object.toJSON(oParam),
                         onComplete: function (oAjax) {

                           js_removeObj('msgBoxListaRelacaoLancaAtributos');

                           var oRetorno = eval("("+oAjax.responseText+")");
                           if (oRetorno.status == 2) {

                             alert(oRetorno.message.urlDecode());
                             return false;
                           }
                         }
                       }
                     );
}
</script>
