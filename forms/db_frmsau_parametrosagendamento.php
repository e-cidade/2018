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

//MODULO: ambulatorial
$oRotulo = new rotulocampo;
$oRotulo->label('sd63_c_procedimento');
$oRotulo->label('sd63_c_nome');
$oRotulo->label('s157_i_procedimento');
$oRotulo->label('s157_i_unidade');
$oRotulo->label('descrdepto');
?>
<form name="form1" method="post" action="">
<center>
  <table border="0">
    <tr>
      <td nowrap title="<?=@$Ts157_i_unidade?>">
        <?
        db_ancora(@$Ls157_i_unidade, 'js_pesquisas157_i_unidade(true);', $db_opcao);
        ?>
      </td>
      <td nowrap> 
        <?
        db_input('s157_i_unidade', 10, $Is157_i_unidade, true, 'text', $db_opcao, 
                 'onchange="js_pesquisas157_i_unidade(false);"'
                );
        db_input('descrdepto', 50, $Idescrdepto, true, 'text', 3, '');
        ?>
      </td>
    </tr>
    <tr>
      <td nowrap title="<?=@$Ts157_i_procedimento?>">
        <?
        db_ancora(@$Ls157_i_procedimento, 'js_pesquisas157_i_procedimento(true);', $db_opcao);
        ?>
      </td>
      <td nowrap> 
        <?
        db_input('sd63_c_procedimento', 10, $Isd63_c_procedimento, true, 'text', $db_opcao,
                 ' onchange="js_pesquisas157_i_procedimento(false);"'
                );
        db_input('s157_i_procedimento', 10, $Is157_i_procedimento, true, 'hidden', 3, '');
        db_input('sd63_c_nome', 50, $Isd63_c_nome, true, 'text', 3, '');
        ?>
      </td>
    </tr>
  </table>

  <br>
  <input name="db_opcao" id="db_opcao" type="button" onclick="js_incluirProcedimento();" value="Incluir">
  <input name="limpar" type="button" id="limpar" value="limpar" onclick="js_limpar();">

  <br><br>

  <table border="0" width="90%">
    <tr>
      <td>
        <div id='grid_procedimentos' style='width: 100%;'></div>
      </td>
    </tr>
  </table>

</center>
</form>

<script>

oDBGridProcedimentos = js_criaDataGrid();

function js_ajax(oParam, jsRetorno, sUrl, lAsync) {

  var mRetornoAjax;

  if (sUrl == undefined) {
    sUrl = 'sau4_ambulatorial.RPC.php';
  }

  if (lAsync == undefined) {
    lAsync = true;
  }
  
  var oAjax = new Ajax.Request(sUrl, 
                               {
                                 method: 'post', 
                                 asynchronous: lAsync,
                                 parameters: 'json='+Object.toJSON(oParam),
                                 onComplete: function(oAjax) {
                                    
                                               var evlJS    = jsRetorno+'(oAjax);';
                                               return mRetornoAjax = eval(evlJS);
                                               
                                           }
                              }
                             );

  return mRetornoAjax;

}

function js_validaEnvio() {

  if ($F('s157_i_unidade') == '') {

    alert('Informe a unidade.');
    return false;

  }

  if ($F('sd63_c_procedimento') == '' || $F('s157_i_procedimento') == '') {

    alert('Informe um procedimento.');
    return false;

  }
  
  return true;

}

function js_incluirProcedimento() {

  if (!js_validaEnvio()) {
    return false;
  }

  var oParam           = new Object();
  oParam.exec          = 'incluirProcedimentoAgendaUnidade';
  oParam.iUnidade      = $F('s157_i_unidade');
  oParam.iProcedimento = $F('s157_i_procedimento');

  js_ajax(oParam, 'js_retornoIncluirProcedimento');

}
function js_retornoIncluirProcedimento(oRetorno) {
  
  oRetorno = eval("("+oRetorno.responseText+")");
  alert(oRetorno.sMessage.urlDecode().replace(/\\n/g, "\n"));
  if (oRetorno.iStatus != 1) {
    return false;
  } else {

    $('s157_i_procedimento').value = '';
    $('sd63_c_procedimento').value = '';
    $('sd63_c_nome').value         = '';

    js_reloadGrid();

    return true;

  }

}

function js_excluirProcedimento(iCodigo, sProced) {

  if (iCodigo == '') {

    alert('Informe um procedimento a ser excluído.');
    return false;

  }

  if (!confirm('Deseja excluir o procedimento '+sProced+'?')) {
    return false;
  }

  var oParam           = new Object();
  oParam.exec          = 'excluirProcedimentoAgendaUnidade';
  oParam.iCodigo       = iCodigo;

  js_ajax(oParam, 'js_retornoExcluirProcedimento');

}
function js_retornoExcluirProcedimento(oRetorno) {
  
  oRetorno = eval("("+oRetorno.responseText+")");
  alert(oRetorno.sMessage.urlDecode().replace(/\\n/g, "\n"));
  if (oRetorno.iStatus != 1) {
    return false;
  } else {

    js_reloadGrid();
    return true;

  }

}

function js_reloadGrid() {

  oDBGridProcedimentos.clearAll(true);
  js_getProcedimentosAgendaUnidade();

}

/**** Bloco de funções do grid início */
function js_criaDataGrid() {

  oDBGrid                = new DBGrid('grid_procedimentos');
  oDBGrid.nameInstance   = 'oDBGridProcedimentos';
  oDBGrid.hasTotalizador = false;
  oDBGrid.setCellWidth(new Array('5%', '10%', '75%', '10%'));
  oDBGrid.setHeight(200);
  oDBGrid.allowSelectColumns(false);

  var aHeader = new Array();
  aHeader[0]  = 'Código';
  aHeader[1]  = 'Procedimento';
  aHeader[2]  = 'Descrição';
  aHeader[3]  = 'Opções';
  oDBGrid.setHeader(aHeader);

  var aAligns = new Array();
  aAligns[0]  = 'left';
  aAligns[1]  = 'left';
  aAligns[2]  = 'left';
  aAligns[3]  = 'center';
  
  oDBGrid.setCellAlign(aAligns);
  oDBGrid.show($('grid_procedimentos'));
  oDBGrid.clearAll(true);

  return oDBGrid;

}

function js_getProcedimentosAgendaUnidade() {

  var oParam       = new Object();
  oParam.exec      = 'getProcedimentosAgendaUnidade';
  oParam.iUnidade  = $F('s157_i_unidade');

  js_ajax(oParam, 'js_retornoGetProcedimentosAgendaUnidade');

}

function js_retornoGetProcedimentosAgendaUnidade(oRetorno) {
  
  oRetorno = eval("("+oRetorno.responseText+")");

  if (oRetorno.iStatus != 1) {
    return false;
  } else {

    var iTam = oRetorno.aProcedimentos.length;
    for (var iCont = 0; iCont < iTam; iCont++) {

      var aLinha = new Array();

      aLinha[0]  = oRetorno.aProcedimentos[iCont].s157_i_codigo;
      aLinha[1]  = oRetorno.aProcedimentos[iCont].sd63_c_procedimento.urlDecode();
      aLinha[2]  = oRetorno.aProcedimentos[iCont].sd63_c_nome.urlDecode();
      aLinha[3]  = '<input type="button" value="Excluir" onclick="js_excluirProcedimento(';
      aLinha[3] += oRetorno.aProcedimentos[iCont].s157_i_codigo+', '+"'";
      aLinha[3] += oRetorno.aProcedimentos[iCont].sd63_c_nome.urlDecode()+"');\">";

      oDBGridProcedimentos.addRow(aLinha);

    }
    oDBGridProcedimentos.renderRows();

  }

}

/* Bloco de funções do grid fim *****/


function js_limpar() {

  $('s157_i_unidade').value      = '';
  $('descrdepto').value          = '';
  $('s157_i_procedimento').value = '';
  $('sd63_c_procedimento').value = '';
  $('sd63_c_nome').value         = '';
  oDBGridProcedimentos.clearAll(true);

}

function js_pesquisas157_i_unidade(mostra) {

  if (mostra == true) {

    js_OpenJanelaIframe('top.corpo', 'db_iframe_unidades', 'func_unidades.php?'+
                        'funcao_js=parent.js_mostraunidade1|sd02_i_codigo|descrdepto',
                        'Pesquisa', true
                       );

  } else {

    if (document.form1.s157_i_unidade.value != '') { 

      js_OpenJanelaIframe('top.corpo', 'db_iframe_unidades', 'func_unidades.php?pesquisa_chave='+
                          document.form1.s157_i_unidade.value+
                          '&funcao_js=parent.js_mostraunidade', 'Pesquisa', false
                         );

    } else {

      document.form1.descrdepto.value     = '';
      document.form1.s157_i_unidade.value = '';
      oDBGridProcedimentos.clearAll(true);

    }

  }

}
function js_mostraunidade(chave, erro) {

  document.form1.descrdepto.value = chave; 
  if (erro == true) { 

    document.form1.s157_i_unidade.focus();
    document.form1.s157_i_unidade.value = '';

  }
  oDBGridProcedimentos.clearAll(true);
  js_getProcedimentosAgendaUnidade();

}
function js_mostraunidade1(chave1, chave2) {

  document.form1.s157_i_unidade.value = chave1;
  document.form1.descrdepto.value     = chave2;
  db_iframe_unidades.hide();
  oDBGridProcedimentos.clearAll(true);
  js_getProcedimentosAgendaUnidade();

}

function js_pesquisas157_i_procedimento(mostra) {

  if (mostra == true) {

    js_OpenJanelaIframe('top.corpo', 'db_iframe_sau_procedimento', 'func_sau_procedimento.php?'+
                        'funcao_js=parent.js_mostrasau_procedimento1|sd63_i_codigo|sd63_c_nome|sd63_c_procedimento',
                        'Pesquisa', true
                       );

  } else {

    if (document.form1.sd63_c_procedimento.value != '') { 

       js_OpenJanelaIframe('top.corpo', 'db_iframe_sau_procedimento', 'func_sau_procedimento.php?pesquisa_chave='+
                           document.form1.sd63_c_procedimento.value+
                           '&funcao_js=parent.js_mostrasau_procedimento', 'Pesquisa', false
                          );

    } else {

      document.form1.sd63_c_nome.value         = '';
      document.form1.s157_i_procedimento.value = '';

    }

  }

}
function js_mostrasau_procedimento(chave, erro, chave2) {
  
  document.form1.sd63_c_nome.value         = chave; 
  document.form1.s157_i_procedimento.value = chave2; 
  if (erro == true) { 

    document.form1.s157_i_procedimento.focus();
    document.form1.s157_i_procedimento.value = '';
    document.form1.sd63_c_procedimento.value = '';

  }

}
function js_mostrasau_procedimento1(chave1, chave2, chave3) {

  document.form1.s157_i_procedimento.value = chave1;
  document.form1.sd63_c_nome.value         = chave2;
  document.form1.sd63_c_procedimento.value = chave3;
  db_iframe_sau_procedimento.hide();

}
</script>