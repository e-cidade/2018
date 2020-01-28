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
$oRotulo->label('s156_i_especmed');
$oRotulo->label('z01_nome');
$oRotulo->label('s156_i_procedimento');
$oRotulo->label('sd63_c_nome');
$oRotulo->label('sd63_c_procedimento');
$oRotulo->label('sd04_i_medico');
$oRotulo->label('sd04_i_unidade');
$oRotulo->label('descrdepto');
$oRotulo->label('rh70_descr');
$oRotulo->label('rh70_sequencial');
?>
<form name="form1" method="post" action="">
<center>
  <table border="0">
    <tr>
      <td nowrap title="<?=@$Ts156_i_especmed?>">
        <?
        db_ancora(@$Lsd04_i_medico, '', 3);
        ?>
      </td>
      <td nowrap> 
        <?
        db_input('sd04_i_medico', 10, $Isd04_i_medico, true, 'text', 3, '');
        db_input('z01_nome', 50, $Iz01_nome, true, 'text', 3, '');
        ?>
      </td>
    </tr>
    <tr>
      <td nowrap title="<?=@$Ts156_i_especmed?>">
        <?
        db_ancora(@$Ls156_i_especmed, "js_pesquisas156_i_especmed(true);", $db_opcao);
        ?>
      </td>
      <td nowrap> 
        <?
        db_input('s156_i_especmed', 10, $Is156_i_especmed, true, 'text', $db_opcao,
                 " onchange='js_pesquisas156_i_especmed(false);'"
                );
        db_input('rh70_descr', 50, $Irh70_descr, true, 'text', 3, '');
        db_input('rh70_sequencial', 10, $Irh70_sequencial, true, 'hidden', 3, '');
        ?>
      </td>
    </tr>
    <tr>
      <td nowrap title="<?=@$Tsd04_i_unidade?>">
        <?=@$Lsd04_i_unidade?>
      </td>
      <td nowrap>
        <?
        db_input('sd04_i_unidade', 10, $Isd04_i_unidade, true, 'text', 3);
        db_input('descrdepto', 50, $Idescrdepto, true, 'text', 3, '');;
        ?>
      </td>
    </tr>
    <tr>
      <td nowrap title="<?=@$Ts156_i_procedimento?>">
        <?
        db_ancora(@$Ls156_i_procedimento, "js_pesquisas156_i_procedimento(true);", $db_opcao);
        ?>
      </td>
      <td nowrap> 
        <?
        db_input('sd63_c_procedimento', 10, $Isd63_c_procedimento, true, 'text', $db_opcao, 
                 " onchange='js_pesquisas156_i_procedimento(false);'"
                );
        db_input('s156_i_procedimento', 10, $Is156_i_procedimento, true, 'hidden', 3, '');
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

  if ($F('sd04_i_medico') == '') {

    alert('Informe o profissional.');
    return false;

  }

  if ($F('s156_i_especmed') == '') {

    alert('Informe o vínculo.');
    return false;

  }

  if ($F('sd63_c_procedimento') == '' || $F('s156_i_procedimento') == '') {

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
  oParam.exec          = 'incluirProcedimentoAgendaProfissional';
  oParam.iEspecMed     = $F('s156_i_especmed');
  oParam.iProcedimento = $F('s156_i_procedimento');

  if ($F('s156_i_especmed') != '') {
    js_ajax(oParam, 'js_retornoIncluirProcedimento');
  }

}
function js_retornoIncluirProcedimento(oRetorno) {
  
  oRetorno = eval("("+oRetorno.responseText+")");
  alert(oRetorno.sMessage.urlDecode().replace(/\\n/g, "\n"));
  if (oRetorno.iStatus != 1) {
    return false;
  } else {

    $('s156_i_procedimento').value = '';
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
  oParam.exec          = 'excluirProcedimentoAgendaProfissional';
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
  js_getProcedimentosAgendaProfissional();

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

function js_getProcedimentosAgendaProfissional() {

  var oParam       = new Object();
  oParam.exec      = 'getProcedimentosAgendaProfissional';
  oParam.iEspecMed = $F('s156_i_especmed');

  if ($F('s156_i_especmed') != '') {
    js_ajax(oParam, 'js_retornoGetProcedimentosAgendaProfissional');
  }

}

function js_retornoGetProcedimentosAgendaProfissional(oRetorno) {
  
  oRetorno = eval("("+oRetorno.responseText+")");

  if (oRetorno.iStatus != 1) {
    return false;
  } else {

    var iTam = oRetorno.aProcedimentos.length;
    for (var iCont = 0; iCont < iTam; iCont++) {

      var aLinha = new Array();

      aLinha[0]  = oRetorno.aProcedimentos[iCont].s156_i_codigo;
      aLinha[1]  = oRetorno.aProcedimentos[iCont].sd63_c_procedimento.urlDecode();
      aLinha[2]  = oRetorno.aProcedimentos[iCont].sd63_c_nome.urlDecode();
      aLinha[3]  = '<input type="button" value="Excluir" onclick="js_excluirProcedimento(';
      aLinha[3] += oRetorno.aProcedimentos[iCont].s156_i_codigo+', '+"'";
      aLinha[3] += oRetorno.aProcedimentos[iCont].sd63_c_nome.urlDecode()+"');\">";

      oDBGridProcedimentos.addRow(aLinha);

    }
    oDBGridProcedimentos.renderRows();

  }

}

/* Bloco de funções do grid fim *****/




function js_pesquisas156_i_procedimento(mostra) {

  if ($F('s156_i_especmed') == '' || $F('rh70_sequencial') == '') {
    
    alert('Selecione o vínculo primeiro.');
    return false;

  }
  var sUrl = '';
  sUrl    += 'func_sau_proccbo.php';
  sUrl    += '?chave_rh70_sequencial='+$F('rh70_sequencial');
  sUrl    += '&funcao_js=parent.js_mostraprocedimentos1|sd96_i_procedimento|sd63_c_procedimento|sd63_c_nome';
  sUrl    += '&campoFoco=sd63_c_procedimento';
     
  if (mostra == true) {
    js_OpenJanelaIframe('', 'db_iframe_sau_proccbo', sUrl, 'Pesquisa Procedimentos', true);
  } else {

    if ($F('sd63_c_procedimento') != '') {

      sUrl += '&chave_sd63_c_procedimento='+$F('sd63_c_procedimento')+'&chave_nao_mostra=true';
      js_OpenJanelaIframe('', 'db_iframe_sau_proccbo', sUrl, 'Pesquisa Procedimentos', false);

    } else {

      $('sd63_c_nome').value   = '';
      $('s156_i_procedimento').value = '';

    }

  }
  $('sd63_c_procedimento').focus();

}
function js_mostraprocedimentos1(chave1, chave2, chave3) {

  if (chave1 == '') {
    chave2 = '';
  }
  $('s156_i_procedimento').value = chave1;
  $('sd63_c_procedimento').value = chave2;
  $('sd63_c_nome').value         = chave3;

  db_iframe_sau_proccbo.hide();

}

function js_pesquisas156_i_especmed(mostra) {

  if (mostra == true) {

    js_OpenJanelaIframe('', 'db_iframe_especmedico', 'func_especmedico.php?chave_sd04_i_medico='+
                        document.form1.sd04_i_medico.value+
                        '&funcao_js=parent.js_mostraespecmedico1|sd27_i_codigo|rh70_descr|sd02_i_codigo|'+
                        'descrdepto|sd43_cod_turnat|sd43_v_descricao|sd43_c_horainicial|sd43_c_horafinal|'+
                        'sd27_i_rhcbo', 'Pesquisa', true
                       );

  } else {

    if (document.form1.s156_i_especmed.value != '') {

       sUrl  = 'func_especmedico.php';
       sUrl += '?chave_sd04_i_medico='+document.form1.sd04_i_medico.value;
       sUrl += '&chave_sd27_i_codigo='+document.form1.s156_i_especmed.value;
       sUrl += '&funcao_js=parent.js_mostraespecmedico1|sd27_i_codigo|rh70_descr|sd02_i_codigo|';
       sUrl += 'descrdepto|sd43_cod_turnat|sd43_v_descricao|sd43_c_horainicial|sd43_c_horafinal|';
       sUrl += 'sd27_i_rhcbo&nao_mostra=true';
       js_OpenJanelaIframe('', 'db_iframe_especmedico', sUrl, 'Pesquisa', false);

    } else {

      document.form1.rh70_descr.value      = ''; 
      document.form1.rh70_sequencial.value = ''; 
      $('descrdepto').value                = '';
      $('sd04_i_unidade').value            = '';
      oDBGridProcedimentos.clearAll(true);

    }

  }

}
function js_mostraespecmedico1(chave1, chave2, chave3, chave4, chave5, chave6, chave7, chave8, chave9) {

  if (chave1 == '') {
    chave3 = chave4 = chave5 = chave6 = chave7 = chave8 = chave9 = '';
  }
  $('s156_i_especmed').value = chave1;
  $('rh70_descr').value      = chave2;
  $('sd04_i_unidade').value  = chave3;
  $('descrdepto').value      = chave4;
  $('rh70_sequencial').value = chave9;

  db_iframe_especmedico.hide();
  oDBGridProcedimentos.clearAll(true);
  js_getProcedimentosAgendaProfissional();

}

function js_limpar() {

  $('s156_i_especmed').value     = '';
  $('rh70_descr').value          = '';
  $('sd04_i_unidade').value      = '';
  $('descrdepto').value          = '';
  $('rh70_sequencial').value     = '';
  $('s156_i_procedimento').value = '';
  $('sd63_c_procedimento').value = '';
  $('sd63_c_nome').value         = '';
  oDBGridProcedimentos.clearAll(true);

}

</script>