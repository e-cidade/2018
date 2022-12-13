<?php
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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("dbforms/db_funcoes.php");
$oLabel = new rotulocampo();
$oLabel->label("ed60_i_aluno");
$oLabel->label("ed47_v_nome");
$oLabel->label("ed305_sequencial");
$oLabel->label("ed57_i_codigo");
$oLabel->label("ed57_c_descr");
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?
     db_app::load("scripts.js, prototype.js, strings.js, datagrid.widget.js, windowAux.widget.js, arrays.js");
     db_app::load("dbmessageBoard.widget.js, dbtextFieldData.widget.js, dbcomboBox.widget.js");
     db_app::load("estilos.css, grid.style.css");
    ?>
    <style type="">
    fieldset.fieldsetinterno {
      border:0px;
      border-top: 2px groove white;
    }
    </style>
  </head>
  <body bgcolor="#CCCCCC" style='margin-top: 25px'>
    <center>
      <form name='form1' method="post">
      <div style="display: table;">
        <fieldset>
          <legend><b>Filtros</b></legend>
          <table>
            <tr>
              <td>
                <?
                 db_ancora("<b>Lote:</b>", "js_pesquisaLote(true)", 1);
                ?>
              </td>
              <td>
                <?
                 db_input("ed305_sequencial", 10, $Ied305_sequencial, true, "text", 3);
                ?>
              </td>
            </tr>
            <tr>
              <td>
                <?
                 db_ancora("<b>Turma:</b>", "js_pesquisaTurma(true)", 1);
                ?>
              </td>
              <td>
                <?
                 db_input("ed57_i_codigo", 10, $Ied57_i_codigo, true, "text", 1, "onchange='js_pesquisaTurma(false)'");
                 db_input("ed57_c_descr", 30, $Ied57_c_descr, true, "text", 3);
                ?>
              </td>
            </tr>
            <tr>
              <td>
                <?
                 db_ancora("<b>Aluno:</b>", "js_pesquisaAluno(true)", 1);
                ?>
              </td>
              <td>
                <?
                 db_input("ed60_i_aluno", 10, $Ied60_i_aluno, "text", true, 1, "onchange='js_pesquisaAluno(false)'");
                 db_input("ed47_v_nome", 30, $Ied47_v_nome, "text", true, 3);
                ?>
              </td>
            </tr>
            <tr>
              <td><b>Situação:</b></td>
              <td>
                <?
                 $oDaoSituacaoCarteira   = db_utils::getDao("cartaoidentificacaosituacao");
                 $sSqlSituacoesCarteirao = $oDaoSituacaoCarteira->sql_query(null, "*", "ed307_sequencial");
                 $rsSituacaoCartao       = $oDaoSituacaoCarteira->sql_record($sSqlSituacoesCarteirao);
                 $aSituacoes             = db_utils::getCollectionByRecord($rsSituacaoCartao);
                 $aSituacoesFiltro       = array();
                 $aSituacoesFiltro[0]    = "Selecione";
                 foreach ($aSituacoes as $oSituacao) {
                   $aSituacoesFiltro[$oSituacao->ed307_sequencial] = $oSituacao->ed307_descricao;
                 }
                 unset($aSituacoes);
                 db_select("situacoes", $aSituacoesFiltro, true, 1);
                ?>
              </td>
            </tr>
          </table>
        </fieldset>
        <center>
          <input type="button" value='Pesquisar' id='btnPesquisar'>
          <input type="reset" value='Limpar Filtros' id='btnLimpar'>
        </center>
      </div>
      </form>
    </center>      
  </body>
</html>  
<div style="position: absolute;padding: 3px; background-color:#FFFFCC; 
            border: 1px solid #999999; display:none;z-index:10000000"
     id='ctnDisplayFoto'>
   <img  width="95" height="120"  style='border:1px inset white' id='previewfotogrid'>
</div>
<?
db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), 
        db_getsession("DB_anousu"), db_getsession("DB_instit"));
?>
<script>
$('situacoes').style.width = '100%';
var sUrlRPC                = 'edu04_gerararquivocartaoaluno.RPC.php';
function js_pesquisaAluno(mostra) {

  if (mostra) {
  
    js_OpenJanelaIframe('top.corpo', 'db_iframe_aluno', 
                       'func_aluno.php?funcao_js=parent.js_mostraaluno1|ed47_i_codigo|ed47_v_nome', 
                       'Pesquisar Alunos',
                        true
                       );
  } else {
  
    if ($F('ed60_i_aluno') != "") {
    
      js_OpenJanelaIframe('top.corpo', 'db_iframe_aluno', 
                       'func_aluno.php?funcao_js=parent.js_mostraaluno2&pesquisa_chave2='+$F('ed60_i_aluno'), 
                       'Pesquisar Alunos',
                        false
                       );
      
    } else {
     $('ed47_v_nome') = "";
    }
  }
}

function js_mostraaluno1(chave1,chave2) {

  $('ed60_i_aluno').value = chave1;
  $('ed47_v_nome').value   = chave2;
  db_iframe_aluno.hide();
}

function js_mostraaluno2(chave1, lErro) {
  
  $('ed47_v_nome').value   = chave1;
  if (lErro) {
  
    $('ed47_v_nome').value  = "Chave ("+$('ed60_i_aluno').value+") Não encontrada";  
    $('ed60_i_aluno').value = '';
    $('ed60_i_aluno').focus();
  }
}

function js_pesquisaTurma(mostra) {

  if (mostra) {
  
    js_OpenJanelaIframe('top.corpo', 
                        'db_iframe_turma', 
                        'func_turma.php?funcao_js=parent.js_mostraTurma|ed57_i_codigo|ed57_c_descr', 
                        'Pesquisar Turmas',
                         true
                        );
  } else {
  
    if ($F('ed57_i_codigo') != "") {
    
      js_OpenJanelaIframe('top.corpo', 
                          'db_iframe_turma', 
                          'func_turma.php?funcao_js=parent.js_mostraTurma2&pesquisa_chave='+$F('ed57_i_codigo'), 
                          'Pesquisar Turmas',
                           false
                          );
                        
    } else {
      $('ed57_c_descr').value  = '';
    }
  }
}

function js_mostraTurma(chave1,chave2) {

  $('ed57_i_codigo').value = chave1;
  $('ed57_c_descr').value  = chave2;
  db_iframe_turma.hide();
}

function js_mostraTurma2(chave1, chave2, chave3, chave4, chave5, lErro) {
  
  $('ed57_c_descr').value   = chave1;
  if (lErro) {
  
    $('ed57_c_descr').value  = "Chave ("+$('ed57_i_codigo').value+") Não encontrada";  
    $('ed57_i_codigo').value = '';
    $('ed57_i_codigo').focus();
  }
}
function js_pesquisaLote(mostra) {

  if (mostra) {
  
    js_OpenJanelaIframe('top.corpo', 'db_iframe_lote', 
                       'func_loteimpressaocartaoidentificacaoescola.php?funcao_js=parent.js_mostrarLote1|ed305_sequencial', 
                       'Pesquisar Lotes',
                        true
                       );
  }
}

function js_mostrarLote1(chave1) {

  $('ed305_sequencial').value = chave1;
  db_iframe_lote.hide();
}
$('btnPesquisar').observe("click", function () {
  
  var oBtnPesquisar       = $('btnPesquisar');
  oBtnPesquisar.disabled  = true;
  var iWidth              = document.body.getWidth()-10;
  oWindowManutencaoCartao = new windowAux("wndManutencaoCartao", 
                                          "Manutenção de Cartão de Identificação", 
                                          iWidth
                                         );
  oWindowManutencaoCartao.setShutDownFunction(function() {
  
     oWindowManutencaoCartao.destroy();
     oBtnPesquisar.disabled  = false;
  });
  var sConteudo = '<div>';
      sConteudo += '  <fieldset><legend><b>Alunos</b></legend>';
      sConteudo += '    <div id="ctnGridAlunos" style="width:100%">';
      sConteudo += '    </div>';
      sConteudo += '  </fieldset>';
      sConteudo += '  <fieldset>';
      sConteudo += '    <legend><b>Marcar Selecionados como:</b></legend>';
      sConteudo += '    <span id="ctnCboSituacoes"></span>';
      sConteudo += '  </fieldset>';
      sConteudo += '  <fieldset>'
      sConteudo += '  <legend><b>Visualizar Somente:</b></legend>';
      sConteudo += '    <div id="ctnCheckboxGroup"></div>';
      sConteudo += '  </fieldset>';
      sConteudo += '    <center>';
      sConteudo += '      <input type="button" value="Salvar" id="btnSalvar" onclick="js_salvar()">';
      sConteudo += '      <input type="button" value="Fechar" id="btnFechar">';
      sConteudo += '    </center>';
      sConteudo += '</div>';
  oWindowManutencaoCartao.setContent(sConteudo);    
  var sMessagemUsuario = 'Informe a situaçao de cada cartão.';
  var oMessageBoard    = new DBMessageBoard('msgBoardAlunos',
                                            'Manuntenção de Cartões de Identificação',
                                             sMessagemUsuario,
                                             oWindowManutencaoCartao.getContentContainer()
                                             );
  oMessageBoard.show();
  oWindowManutencaoCartao.show();
  oDataGridAlunos              = new DBGrid('dbGridAlunos');
  oDataGridAlunos.nameInstance = 'oDataGridAlunos';
  oDataGridAlunos.setCheckbox(0);
  var aWidths                  = new Array("5%", "30%", "5%", "15%", "15%", "15%", "4%", "10%");
  var aHeaders                 = new Array("cod.Aluno", 
                                           "Nome",
                                           "Nasc.",  
                                           "Pai", 
                                           "Mãe",
                                           "Resp. Legal",
                                           "Foto",
                                           "Situação"
                             );
  oDataGridAlunos.setHeight(oWindowManutencaoCartao.getHeight() / 2.3); 
  oDataGridAlunos.setCellWidth(aWidths);                             
  oDataGridAlunos.setHeader(aHeaders);
  oDataGridAlunos.show($('ctnGridAlunos'));   
  oDataGridAlunos.selectSingle = function (oCheckbox, sRow, oRow) {
   
    if (oCheckbox.checked) {
    
      $(sRow).addClassName('marcado');
      oRow.isSelected   = true;
      
    } else {
  
      $(sRow).removeClassName('marcado');
      oRow.isSelected   = false;
     
    }
    return true;
  }
  
  $('btnFechar').observe('click', function() {
  
     oWindowManutencaoCartao.destroy();
     oBtnPesquisar.disabled  = false;
  });
  
  oCboSituacoes = new DBComboBox('oCboSituacoes', 'oCboSituacoes');
  oCboSituacoes.show($('ctnCboSituacoes'));
  var oSituacao = $('situacoes');
  for (var iSituacoes = 0; iSituacoes < oSituacao.options.length; iSituacoes++) {
    
    var iValorSituacao     = oSituacao.options[iSituacoes].value;
    var sDescricaoSituacao = oSituacao.options[iSituacoes].innerHTML
    oCboSituacoes.addItem(iValorSituacao, sDescricaoSituacao);
    /**
     * Criamos a lista dos Checkboxes para mostrar as situações das carteiras;
     */
    if (iValorSituacao != 0) {
       
       var oCheckbox   = document.createElement('input');
       oCheckbox.type  = 'checkbox';
       /**
        * Como valor do checkbox, coloco o label, sem espacos em branco
        */
       oCheckbox.value   = sDescricaoSituacao.replace(/ /g, '');
       oCheckbox.id      = 'chksituacao'+iValorSituacao;
       oCheckbox.checked = true;
       oCheckbox.observe("click", function () {
         js_filtrarSituacao(this.checked, this.value); 
       });
       $('ctnCheckboxGroup').appendChild(oCheckbox);
        
       var oLabelSituacao       = document.createElement('label');
       oLabelSituacao.htmlFor   = 'chksituacao'+iValorSituacao;
       oLabelSituacao.innerHTML = '<b>'+sDescricaoSituacao+'</b>';
       $('ctnCheckboxGroup').appendChild(oLabelSituacao); 
    }
  }
  $('oCboSituacoes').observe("change", function() {
 
    if (oCboSituacoes.getValue() != 0) {
     
      var aLinhas = oDataGridAlunos.getSelection("object");
      aLinhas.each(function(oLinha, iSeq) {
        $(oLinha.aCells[8].sId).childNodes[0].value = oCboSituacoes.getValue();
      });
    }
  });
  js_carregarAlunos(); 
});

function js_carregarAlunos() {

   js_divCarregando('Aguarde, carregando Cartões', 'msgBox');
   var oParametros    = new Object();
   oParametros.exec   = 'getCartoesAlunos';
   oParametros.iLote  = $F('ed305_sequencial');
   oParametros.iTurma = $F('ed57_i_codigo');
   oParametros.iAluno = $F('ed60_i_aluno');
   var oAjax          = new Ajax.Request(sUrlRPC, 
                                         {method:'post',
                                          parameters:'json='+Object.toJSON(oParametros),
                                          onComplete: js_preencherCartoes
                                         }); 

}


function js_preencherCartoes(oResponse) {

  js_removeObj('msgBox');
  var oRetorno = eval("("+oResponse.responseText+")");
  oDataGridAlunos.clearAll(true);
  oRetorno.cartoes.each(function (oCartao, iSeq) {
  
     var aLinha = new Array();
     aLinha[0]  = oCartao.codigo;
     aLinha[1]  = oCartao.nome.urlDecode();
     aLinha[2]  = js_formatar(oCartao.datanascimento, 'd');
     aLinha[3]  = oCartao.nomepai.urlDecode().substr(0, 30);
     aLinha[4]  = oCartao.nomemae.urlDecode().substr(0, 30);
     aLinha[5]  = oCartao.nomeresponsavellegal.urlDecode().substr(0, 30);
     var  sImagem   =  oCartao.foto.urlDecode();
     aLinha[6]  = "<input type='button' value='...' onclick='js_previewImagem(\""+sImagem+"\", this)' onblur='js_closePreview()'>";
     aLinha[7]  = js_createComboBox(oCartao.codigo, oCartao.situacaocarteira);
     oDataGridAlunos.addRow(aLinha);
     var sClassName = oCartao.descricaosituacaocarteira.urlDecode().replace(/ /g, '');
     oDataGridAlunos.aRows[iSeq].setClassName(sClassName);
     
  });
  oDataGridAlunos.renderRows();
}

function js_createComboBox(iAluno, iValorPadrao) {
    
  var sComboBox = "<select id='situacaoAluno' style='width:155px'>"; 
  var oSituacao = $('situacoes');
  for (var iSituacoes = 0; iSituacoes < oSituacao.options.length; iSituacoes++) {
  
    var sSelecionado       = " ";
    var iValorSituacao     = oSituacao.options[iSituacoes].value;
    var sDescricaoSituacao = oSituacao.options[iSituacoes].innerHTML
    if (iValorPadrao == iValorSituacao) {
      sSelecionado = ' selected '
    }
    sComboBox += "<option value='"+iValorSituacao+"' "+sSelecionado+">"+sDescricaoSituacao+"</option>";
  }
  sComboBox +="</select>";
  return sComboBox;
}

function js_previewImagem(sImagem, oDiv) {
    
  el =  oDiv; 
  var x = 0;
  var y = el.offsetHeight;
  
  /*
   * calculamos a distancia do dropdown em relação a página, 
   * para podemos renderiza-lo na posição correta.
   */
  while (el.offsetParent && el.id.toUpperCase() != 'wndAuxiliar') {
    
    if (el.className != "windowAux12") { 
    
      x += new Number(el.offsetLeft);
      y += new Number(el.offsetTop);
      
    }
    el = el.offsetParent;
    
  }
  x += new Number(el.offsetLeft);
  y += new Number(el.offsetTop)+4;
  /*
   * Pegamos a largura do dropdown, e diminuimos da posiçao do cursors
   */
  $('ctnDisplayFoto').style.left = x+40;
  $('ctnDisplayFoto').style.top  = y-($('dbGridAlunosbody').scrollTop)-4;
  $('previewfotogrid').src       = sImagem;
  $('ctnDisplayFoto').style.display = '';
}  
 
function js_closePreview() {
  
  $('previewfotogrid').src='';
  $('ctnDisplayFoto').style.display = 'none';
} 

function js_filtrarSituacao(lMostrar, sClassName) {

  var sDisplay = 'table-row';
  if (!lMostrar) {
    sDisplay = 'none';
  }
  var aLinhas = oDataGridAlunos.getElementsByClass(sClassName, document, "tr");
  for (var iLinha = 0; iLinha < aLinhas.length; iLinha++) {
    aLinhas[iLinha].style.display = sDisplay;
  }
}
function js_salvar () {
  
  var aCartoesSelecionados = oDataGridAlunos.getSelection('object');
  if (aCartoesSelecionados.length == 0) {
   
    alert('Nenhum cartão foi selecionado para manutenção.');
    return false;
  }
  
  var aCartoesAlterar   = new Array();
  var lLinhasEscondidas = false;
  aCartoesSelecionados.each(function(oCartao, iSeq) {
    
    if ($(oCartao.sId).style.display == 'none') {
      lLinhasEscondidas = true;
    }
    var oCartaoAlterar       = new Object();
    oCartaoAlterar.iAluno    = oCartao.aCells[0].getValue();
    oCartaoAlterar.iSituacao = $(oCartao.aCells[8].sId).childNodes[0].value;
    aCartoesAlterar.push(oCartaoAlterar);      
  });
  
  delete aCartoesSelecionados;
  var sMsgUsuario = 'Confirma a alteração da situação dos cartoes selecionados?';
  if (lLinhasEscondidas) {
    sMsgUsuario +='\nExistem cartões selecionados que nao estão sendo mostrados. Confirmar a Alteração?'; 
  }
  if (!confirm(sMsgUsuario)) {
    return false;
  }
  
  js_divCarregando('Aguarde, salvando alterações', 'msgBox');
  var oParam      = new Object();
  oParam.exec     = 'salvarCartaoIdentificacao';
  oParam.aCartoes = aCartoesAlterar;
  var oAjax       = new Ajax.Request(sUrlRPC, 
                                    {
                                     method:'post',
                                     parameters:'json='+Object.toJSON(oParam),
                                     onComplete: function (oResponse) {
                                        
                                        js_removeObj('msgBox');
                                        var oRetorno = eval("("+oResponse.responseText+")");
                                        if (oRetorno.status == 2) {
                                          alert(oRetorno.message.urlDecode());
                                        } else {
                                          
                                          alert('Cartoes Alterados com sucesso.');
                                          js_carregarAlunos();
                                        }
                                      } 
                                    }
                                    );
}
</script>