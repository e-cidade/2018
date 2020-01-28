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

//MODULO: ambulatorial
$oDaoSauReceitaMedica->rotulo->label();
$oRotulo = new rotulocampo;
$oRotulo->label('z01_nome');
$oRotulo->label('fa03_i_codigo');
$oRotulo->label('s159_i_receita');
$oRotulo->label('s162_i_prontuario');
$oRotulo->label('s159_i_medicamento');
$oRotulo->label('s159_i_formaadm');
$oRotulo->label('s159_t_posologia');
$oRotulo->label('s159_n_quant');
$oRotulo->label('s115_c_cartaosus');
$oRotulo->label('z01_i_cgsund');
$oRotulo->label('z01_v_nome');
$oRotulo->label('sd03_i_crm');
$oRotulo->label('z02_i_cns');
$oRotulo->label('m60_descr');
?>
<form name="form1" method="post" action=''>
<center>
<table border="0">
  <tr>
    <td nowrap>
      <fieldset style='width: 96%;'> <legend><b>Receita:</b></legend>
        <table>
          <tr>
            <td nowrap title="<?=@$Ts162_i_prontuario?>">
              <?=@$Ls162_i_prontuario?>
            </td>
            <td>
              <?
              db_input('s162_i_prontuario',10,$Is162_i_prontuario,true,'text',3,'');
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Ts159_i_receita?>">
              <?=@$Ls159_i_receita?>
            </td>
            <td nowrap>
              <?
              db_input('s158_i_codigo',10,$Is158_i_codigo,true,'text',3,'');

              echo $Ls158_i_tiporeceita;

              $sSql = $oDaoFarTipoReceita->sql_query_file('', 'fa03_i_codigo, fa03_c_descr',
                                                          'fa03_c_descr', 'fa03_i_ativa = 1'
                                                         );
              $rs   = $oDaoFarTipoReceita->sql_record($sSql);
              $aX   = array();
              for ($iCont = 0; $iCont < $oDaoFarTipoReceita->numrows; $iCont++) {

                $oDados                     = db_utils::fieldsmemory($rs, $iCont);
                $aX[$oDados->fa03_i_codigo] = $oDados->fa03_c_descr;

              }
              db_select('s158_i_tiporeceita', $aX, true, $db_opcao, '');

              echo $Ls158_d_validade;

              db_inputdata('s158_d_validade', @$s158_d_validade_dia, @$s158_d_validade_mes,
                           @$s158_d_validade_ano, true, 'text', $db_opcao, ''
                          );
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Ts158_t_prescricao?>">
              <?=@$Ls158_t_prescricao?>
            </td>
            <td>
              <?
              db_textarea('s158_t_prescricao',1,60,$Is158_t_prescricao,true,'text',$db_opcao,'');
              ?>
            </td>
          </tr>
        </table>
      </fieldset>
    </td>
  </tr>
  <tr>
    <td nowrap>
      <fieldset style='width: 96%;'> <legend><b>Paciente:</b></legend>
        <table>
          <tr>
            <td nowrap title="<?=@$Ts115_c_cartaosus?>">
              <?=@$Ls115_c_cartaosus?>
            </td>
            <td>
              <?
              db_input('s115_c_cartaosus', 15, $Is115_c_cartaosus, true, 'text', 3, '');
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Tz01_i_cgsund?>">
              <?=$Lz01_i_cgsund?>
            </td>
            <td nowrap>
              <?
              db_input('z01_i_cgsund', 10, $Iz01_i_cgsund, true, 'text', 3, '');
              db_input('z01_v_nome', 50, $Iz01_v_nome, true, 'text', 3, '');
              ?>
              <input type='button' id='historico' name='historico' value='Histórico de Retiradas' onclick="js_abreHistorico();">
            </td>
          </tr>
        </table>
      </fieldset>
    </td>
  </tr>

  <tr>
    <td nowrap>
      <fieldset style='width: 96%;'> <legend><b>Profissional:</b></legend>
        <table>
          <tr>
            <td nowrap title="<?=@$Ts158_i_profissional?>">
              <?
              db_ancora(@$Ls158_i_profissional,'js_pesquisas158_i_profissional(true);',3);
              ?>
            </td>
            <td>
              <?
              db_input('s158_i_profissional',10,$Is158_i_profissional,true,'text',3,'');
              db_input('z01_nome',50,$Iz01_nome,true,'text',3,'');
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Tsd03_i_crm?>">
              <?=$Lsd03_i_crm?>
            </td>
            <td>
              <?
              db_input('sd03_i_crm',8,$Isd03_i_crm,true,'text',3,'');
              echo $Lz02_i_cns;
              db_input('z02_i_cns',15,$Iz02_i_cns,true,'text',3,'');
              ?>
            </td>
          </tr>
        </table>
      </fieldset>
    </td>
  </tr>
  <tr>
    <td nowrap>
      <fieldset style='width: 96%;'> <legend><b>Medicamentos:</b></legend>
        <table>
          <tr>
            <td nowrap title="<?=@$Ts159_i_medicamento?>">
              <?
              db_ancora(@$Ls159_i_medicamento,'js_pesquisas159_i_medicamento(true);',$db_opcao);
              db_input('s159_i_codigo',10,'',true,'hidden',3,'');
              ?>
            </td>
            <td nowrap>
              <?
              db_input('s159_i_medicamento',10,$Is159_i_medicamento,true,'text',$db_opcao,
                       'onchange="js_pesquisas159_i_medicamento(false);"'
                      );
              db_input('m60_descr',50,$Im60_descr,true,'text',$db_opcao,'');
              ?>
            </td>
            <td nowrap>
              <b>Saldo:</b>
              <?
              db_input('iSaldo',6,'',true,'text',3,'');
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Ts159_t_posologia?>">
              <?=@$Ls159_t_posologia?>
            </td>
            <td nowrap>
              <?
              db_textarea('s159_t_posologia',1,36,$Is159_t_posologia,true,'text',$db_opcao,'');
              ?>
              <div style="display: inline-block; vertical-align: top; margin-top: 6px;">
              <?
              echo $Ls159_i_formaadm;

              $sSql = $oDaoSauFormaAdmMedicamento->sql_query_file(null, 's160_i_codigo, s160_c_descr', 's160_c_descr');
              $rs   = $oDaoSauFormaAdmMedicamento->sql_record($sSql);
              $aX   = array();
              for ($iCont = 0; $iCont < $oDaoSauFormaAdmMedicamento->numrows; $iCont++) {

                $oDados                     = db_utils::fieldsmemory($rs, $iCont);
                $aX[$oDados->s160_i_codigo] = $oDados->s160_c_descr;

              }
              db_select('s159_i_formaadm', $aX, true, $db_opcao, '');
              ?>
              </div>
            </td>
            <td nowrap title="<?=@$Ts159_n_quant?>">
              <?
              echo $Ls159_n_quant;
              db_input('s159_n_quant',6,$Is159_n_quant,true,'text',$db_opcao);
              ?>
            </td>
          </tr>
          <tr>
            <td colspan="3" align="center">
              <input name="lancar" type="button" id="lancar" value="Lançar" onclick="js_lancarRemedio();">
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              <input name="limpar" type="button" id="limpar" value="Limpar" onclick="js_limparInfoRemedio();">
            </td>
          </tr>
        </table>
      </fieldset>
    </td>
  </tr>
  <tr>
    <td nowrap>
      <table border="0" width="90%">
        <tr>
          <td>
            <div id='grid_remedios' style='width: 100%;'></div>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
</center>
<table>
  <tr>
    <td nowrap align="center">
      <?
      $sDisplay = 'none';
      if ($db_opcao != 1 && !$lImpedirAlt) {
        $sDisplay = "''";
      }

      echo '<span id="modelo" style="display: '.$sDisplay.';">';
      $aX    = array();
      $aX[0] = 'Modelo Padrão';
      // Obtenho todos os templates cadastrados para receita médica
      $sSql = $oDaoDbDocumentoTemplate->sql_query_file(null, 'db82_sequencial, db82_descricao', 'db82_descricao',
                                                       'db82_templatetipo = 12'
                                                      );
      $rs   = $oDaoDbDocumentoTemplate->sql_record($sSql);
      for ($iCont = 0; $iCont < $oDaoDbDocumentoTemplate->numrows; $iCont++) {

        $oDados                       = db_utils::fieldsmemory($rs, $iCont);
        $aX[$oDados->db82_sequencial] = $oDados->db82_descricao;

      }
      echo '<b>Modelo:</b>';
      db_select('iTemplate', $aX, true, $db_opcao, '');
      echo '<input name="emitir" type="button" id="emitir" value="Emitir Receita" onclick="js_emitirReceita();">';
      echo '</span>';
      ?>
      <input name="<?=($db_opcao == 1 ? 'incluir' : ($db_opcao == 2 || $db_opcao == 22 ? 'alterar' : 'excluir'))?>"
        type="button" id="db_opcao"
        value="<?=($db_opcao == 1 ? 'Incluir' : ($db_opcao == 2 || $db_opcao == 22 ? 'Alterar' : 'Excluir'))?>"
        <?=($db_botao == false ? 'disabled' : '')?>
        <?=$lImpedirAlt ? 'disabled' : ''?>
        onclick="js_incAltReceitaMedica(this.name)">
      <input name="nova" type="button" id="nova" value="Nova Receita" onclick="js_nova();">
      <input name="anular" type="button" id="anular" value="Anular Receita" onclick="js_anularReceitaMedica();"
        <?=$lImpedirAlt ? 'disabled' : ''?>
        style="display: <?=$db_opcao == 1 ? 'none' : "''"?>">
      <input name="receitas" type="button" id="receitas" value="Receitas" onclick="js_pesquisaReceitas();">
    </td>
  </tr>
</table>
</form>

<script>

oDBGridRemedios = js_criaDataGrid();
js_getCgsFaa();
js_getInfoProfissional();
<?
if ($db_opcao != 1) {
  echo "\njs_getRemediosReceita();\n";
}
?>


//Autocomplete do medicamento
oAutoComplete = new dbAutoComplete($('m60_descr'), 'far4_retirada_autonomeRPC.php?tipo=1');
oAutoComplete.setTxtFieldId($('s159_i_medicamento'));
oAutoComplete.show();
oAutoComplete.setCallBackFunction(function(iId, sLabel) {

  $('s159_i_medicamento').value = iId;
  $('m60_descr').value          = sLabel;
  js_getSaldoTotalMedicamento();

});


function js_ajax(oParam, jsRetorno, sUrl, lAsync) {

  var mRetornoAjax;

  if (sUrl == undefined) {
    sUrl = 'sau4_ambulatorial.RPC.php';
  }

  if (lAsync == undefined) {
    lAsync = false;
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

/**** Bloco de funções do grid início */
function js_criaDataGrid() {

  var oDBGrid            = new DBGrid('grid_remedios');
  oDBGrid.nameInstance   = 'oDBGridRemedios';
  oDBGrid.hasTotalizador = false;
  oDBGrid.setCellWidth(new Array('10%', '40%', '10%', '20%', '20%'));
  oDBGrid.setHeight(60);
  oDBGrid.allowSelectColumns(false);

  var aHeader = new Array();
  aHeader[0] = 'Código';
  aHeader[1] = 'Medicamento';
  aHeader[2] = 'Qtde.';
  aHeader[3] = 'Uso';
  aHeader[4] = 'Opções';
  oDBGrid.setHeader(aHeader);

  var aAligns = new Array();
  aAligns[0]  = 'left';
  aAligns[1]  = 'left';
  aAligns[2]  = 'left';
  aAligns[3]  = 'left';
  aAligns[4]  = 'center';
  oDBGrid.setCellAlign(aAligns);

  oDBGrid.show($('grid_remedios'));
  oDBGrid.clearAll(true);

  return oDBGrid;

}

function js_getRemediosReceita() {

  var oParam      = new Object();
  oParam.exec     = 'getRemediosReceita';
  oParam.iReceita = $F('s158_i_codigo');

  oDBGridRemedios.clearAll(true); // Limpo o grid
  if ($F('s158_i_codigo').trim() != '') {
    js_ajax(oParam, 'js_retornoGetRemediosReceita');
  }

}

function js_retornoGetRemediosReceita(oRetorno) {

  var oRetorno = eval("("+oRetorno.responseText+")");

  if (oRetorno.iStatus == 1) {

    for (var iCont = 0; iCont < oRetorno.aMedicamentos.length; iCont++) {

      with (oRetorno.aMedicamentos[iCont]) {

        oDBGridRemedios.addRow(js_criaLinhaGrid(s159_i_codigo, s159_i_formaadm, s159_i_medicamento,
                                                m60_descr.urlDecode(), s159_n_quant, s160_c_descr.urlDecode(),
                                                s159_t_posologia.urlDecode(), iCont
                                               )
                              );

      }

    }

    oDBGridRemedios.renderRows();

  }

}

function js_criaLinhaGrid(s159_i_codigo, s159_i_formaadm, s159_i_medicamento,
                          m60_descr, s159_n_quant, s160_c_descr, s159_t_posologia, iId) {

  var aLinha  = new Array();
  var sHidden = '<input type="hidden" id="s159_i_codigo'+iId+'" value="'+s159_i_codigo+'">';
  sHidden    += '<input type="hidden" id="s159_i_medicamento'+iId+'" value="'+s159_i_medicamento+'">';
  sHidden    += '<input type="hidden" id="s159_i_formaadm'+iId+'" value="'+s159_i_formaadm+'">';
  sHidden    += '<input type="hidden" id="s159_t_posologia'+iId+'" value="'+s159_t_posologia+'">';
  sHidden    += '<input type="hidden" id="m60_descr'+iId+'" value="'+m60_descr+'">';
  sHidden    += '<input type="hidden" id="s160_c_descr'+iId+'" value="'+s160_c_descr+'">';
  sHidden    += '<input type="hidden" id="s159_n_quant'+iId+'" value="'+s159_n_quant+'">';
  aLinha[0]   = s159_i_medicamento+sHidden;
  aLinha[1]   = m60_descr;
  aLinha[2]   = s159_n_quant;
  aLinha[3]   = s160_c_descr;
  aLinha[4]   = '<input type="button" onclick="js_alterarRemedio('+iId+');" value="Alterar">';
  aLinha[4]  += '&nbsp;&nbsp;&nbsp;';
  aLinha[4]  += '<input type="button" onclick="js_excluirRemedio('+iId+');" value="Excluir">';

  return aLinha;

}

function js_validaInfoRemedio() {

  if ($F('s159_i_medicamento').trim() == '') {

    alert('Medicamento não informado.');
    return false;

  }

  if ($F('s159_t_posologia').trim() == '') {

    alert('Informe a posologia.');
    return false;

  }

  if ($F('s159_i_formaadm').trim() == '') {

    alert('Informe a forma de administração do medicamento.');
    return false;

  }

  if ($F('s159_n_quant').trim() == '') {

    alert('Informe a quantidade a ser lançada.');
    return false;

  }

  if (parseInt($F('s159_n_quant'), 10) > parseInt($F('iSaldo'), 10)) {

    if ( !confirm('Quantidade maior que o saldo disponível. Deseja continuar?') ) {
      return false;
    }
  }

  return true;

}

function js_lancarRemedio() {

  if (js_validaInfoRemedio()) {

    oDBGridRemedios.addRow(js_criaLinhaGrid($F('s159_i_codigo'), $F('s159_i_formaadm'), $F('s159_i_medicamento'),
                                            $F('m60_descr'), $F('s159_n_quant'),
                                            $('s159_i_formaadm').options[$('s159_i_formaadm').selectedIndex].innerHTML,
                                            $F('s159_t_posologia'), oDBGridRemedios.getNumRows()
                                           )
                          );
    oDBGridRemedios.renderRows();
    js_limparInfoRemedio();

  }

}

function js_limparInfoRemedio() {

  $('s159_i_codigo').value           = '';
  $('s159_i_medicamento').value      = '';
  $('m60_descr').value               = '';
  $('iSaldo').value                  = '';
  $('s159_t_posologia').value        = '';
  $('s159_n_quant').value            = '';
  $('s159_i_formaadm').selectedIndex = 0;

}

/* FUNÇÕES DO GRID - FIM *************************/

function js_alterarRemedio(iId) {

  if (iId == undefined) {

    alert('Informe o medicamento a ser alterado.');
    return false;

  }
  $('s159_i_codigo').value           = $F('s159_i_codigo'+iId);
  $('s159_i_medicamento').value      = $F('s159_i_medicamento'+iId);
  $('m60_descr').value               = $F('m60_descr'+iId);
  $('s159_t_posologia').value        = $F('s159_t_posologia'+iId);
  $('s159_n_quant').value            = $F('s159_n_quant'+iId);
  js_selectOptionByValue($('s159_i_formaadm'), $F('s159_i_formaadm'+iId));
  js_getSaldoTotalMedicamento();
  js_removeLinhaGrid(iId);

}

function js_selectOptionByValue(oSel, sValue) {

  for (var iCont = 0; iCont < oSel.options.length; iCont++) {

    if (oSel.options[iCont].value == sValue) {

      oSel.selectedIndex = iCont;
      break;

    }

  }

}

function js_excluirRemedio(iId) {

  if (iId == undefined) {

    alert('Informe o medicamento a ser alterado.');
    return false;

  }

  $('s159_i_codigo').value           = $F('s159_i_codigo'+iId);
  if (confirm('Deseja excluir o medicamento '+$F('m60_descr'+iId)+'?')) {
    js_removeLinhaGrid(iId);
  }

}

/*
function js_excluirMedicamentoReceita(iCod) {

  if (iCod == undefined || iCod == '') {

    alert('Informe o medicamento a ser excluído da receita.');
    return false;

  }

  var oParam           = new Object();
  oParam.exec          = 'excluirMedicamentoReceita';
  oParam.s159_i_codigo = iCod;

  return js_ajax(oParam, 'js_retornoExcluirMedicamentoReceita');

}

function js_retornoExcluirMedicamentoReceita(oRetorno) {

  oRetorno = eval("("+oRetorno.responseText+")");

  alert(oRetorno.sMessage.urlDecode().replace(/\\n/g, "\n"));
  if (oRetorno.iStatus == 1) {
    return true;
  } else {
    return false;
  }

}
*/

function js_selectOptionByValue(oSel, sValue) {

  for (var iCont = 0; iCont < oSel.options.length; iCont++) {

    if (oSel.options[iCont].value == sValue) {

      oSel.selectedIndex = iCont;
      break;

    }

  }

}

function js_removeLinhaGrid(iIdLinha) {

  var iInd            = 0;
  var iRows           = oDBGridRemedios.getNumRows();
  var aCod            = new Array();
  var aCodMed         = new Array();
  var aDescrMed       = new Array();
  var aPosologia      = new Array();
  var aQuant          = new Array();
  var aCodFormaAdm    = new Array();
  var aDescrFormaAdm  = new Array();
  for (var iCont = 0; iCont < iRows; iCont++) {

    if (iIdLinha != iCont) {

      aCod[iInd]           = $F('s159_i_codigo'+iCont);
      aCodMed[iInd]        = $F('s159_i_medicamento'+iCont);
      aDescrMed[iInd]      = $F('m60_descr'+iCont);
      aPosologia[iInd]     = $F('s159_t_posologia'+iCont);
      aQuant[iInd]         = $F('s159_n_quant'+iCont);
      aCodFormaAdm[iInd]   = $F('s159_i_formaadm'+iCont);
      aDescrFormaAdm[iInd] = $F('s160_c_descr'+iCont);
      iInd++;

    }

  }

  oDBGridRemedios.clearAll(true);

  for (var iCont = 0; iCont < iInd; iCont++) {

    oDBGridRemedios.addRow(js_criaLinhaGrid(aCod[iCont], aCodFormaAdm[iCont], aCodMed[iCont], aDescrMed[iCont],
                                            aQuant[iCont], aDescrFormaAdm[iCont], aPosologia[iCont], iCont
                                           )
                          );

  }
  oDBGridRemedios.renderRows();

}

function js_validaEnvio(sOp) {


  if (sOp == 'alterar') {

    if ($F('s158_i_codigo').trim() == '') {

      alert('Informe a receita a ser alterada.');
      return false;

    }

  }

  if ($F('s162_i_prontuario').trim() == '') {

    alert('Informe a FAA.');
    return false;

  }

  if ($F('s158_i_tiporeceita').trim() == '') {

    alert('Informe o tipo de receita.');
    return false;

  }

  if ($F('s158_d_validade').trim() == '') {

    alert('Informe a data de validade da receita.');
    return false;

  }

  if ($F('s158_t_prescricao').trim() == '') {

    alert('Informe a prescrição médica.');
    return false;

  }

  if ($F('s158_i_profissional').trim() == '') {

    alert('Informe o profissional.');
    return false;

  }

  if (oDBGridRemedios.getNumRows() <= 0) {

    alert('Informe pelo menos um medicamento para a receita.');
    return false;

  }

  return true;

}

function js_incAltReceitaMedica(sOp) {

  if (!js_validaEnvio(sOp)) {
    return false;
  }

  var iRows                  = oDBGridRemedios.getNumRows();
  var oParam                 = new Object();

  oParam.exec                = sOp+'ReceitaMedica';

  oParam.s162_i_prontuario   = $F('s162_i_prontuario');
  oParam.s158_i_codigo       = $F('s158_i_codigo');
  oParam.s158_i_tiporeceita  = $F('s158_i_tiporeceita');
  oParam.s158_d_validade     = $F('s158_d_validade');
  oParam.s158_t_prescricao   = encodeURIComponent( tagString( $F('s158_t_prescricao') ) );
  oParam.s158_i_profissional = $F('s158_i_profissional');

  oParam.aMedicamentos       = new Array();
  for (var iCont = 0; iCont < iRows; iCont++) {

    oParam.aMedicamentos[iCont] = new Object();
    oParam.aMedicamentos[iCont].s159_i_medicamento = $F('s159_i_medicamento'+iCont);
    oParam.aMedicamentos[iCont].s159_t_posologia   =  encodeURIComponent( tagString( $F('s159_t_posologia'+iCont) ) );
    oParam.aMedicamentos[iCont].s159_n_quant       = $F('s159_n_quant'+iCont);
    oParam.aMedicamentos[iCont].s159_i_formaadm    = $F('s159_i_formaadm'+iCont);

  }

  js_ajax(oParam, 'js_retornoIncAltReceitaMedica');

}

function js_retornoIncAltReceitaMedica(oRetorno) {

  oRetorno = eval("("+oRetorno.responseText+")");

  alert(oRetorno.sMessage.urlDecode().replace(/\\n/g, "\n"));
  if (oRetorno.iStatus == 1) {

    $('db_opcao').name        = 'alterar';
    $('db_opcao').value       = 'Alterar';
    $('s158_i_codigo').value  = oRetorno.s158_i_codigo;
    $('anular').style.display = '';
    $('modelo').style.display = '';
    var iRows                 = oDBGridRemedios.getNumRows();
    for (var iCont = 0; iCont < iRows; iCont++) {

      $('s159_i_codigo'+iCont).value = oRetorno.aCodMed[iCont];

    }

    return true;

  } else {
    return false;
  }

}

function js_anularReceitaMedica() {

  if ($F('s158_i_codigo') == '') {

    alert('Informe a receita a ser anulada.');
    return false;

  }

  sGet  = 's161_i_receita='+$F('s158_i_codigo');

  iTop  = (screen.availHeight - 440) / 2;
  iLeft = (screen.availWidth - 800) / 2;
  js_OpenJanelaIframe('', 'db_iframe_anularreceita', 'sau4_sau_receitamedicaanulada.iframe.php?'+sGet,
                      'Anulação de Receita Médica', true, iTop, iLeft, 700, 340
                     );

}

function js_getCgsFaa() {

  if ($F('s162_i_prontuario').trim() == '') {
    return false;
  }

  var oParam  = new Object();
  oParam.exec = 'getCgsFaa';
  oParam.iFaa = $F('s162_i_prontuario');

  js_ajax(oParam, 'js_retornoGetCgsFaa', 'sau4_sau_encaminhamentos.RPC.php');

}
function js_retornoGetCgsFaa(oRetorno) {

  oRetorno = eval("("+oRetorno.responseText+")");

  if (oRetorno.iStatus == 1) {

    $('z01_i_cgsund').value = oRetorno.iCgs;
    $('z01_v_nome').value   = oRetorno.sNome.urlDecode();
    js_getCnsCgs();
    return true;

  } else {
    return false;
  }

}

function js_getCnsCgs() {

  if ($F('z01_i_cgsund').trim() == '') {
    return false;
  }

  var oParam  = new Object();
  oParam.exec = 'getTodosCnsCgs';
  oParam.iCgs = $F('z01_i_cgsund');

  js_ajax(oParam, 'js_retornoGetCnsCgs');

}
function js_retornoGetCnsCgs(oRetorno) {

  oRetorno = eval("("+oRetorno.responseText+")");

  if (oRetorno.iStatus == 1) {

    $('s115_c_cartaosus').value = oRetorno.aCartoes[0].s115_c_cartaosus;
    return true;

  } else {
    return false;
  }

}

function js_getInfoProfissional() {

  if ($F('s158_i_profissional').trim() == '') {
    return false;
  }

  var oParam           = new Object();
  oParam.exec          = 'getInfoProfissional';
  oParam.iProfissional = $F('s158_i_profissional');

  js_ajax(oParam, 'js_retornoGetInfoProfissional');

}
function js_retornoGetInfoProfissional(oRetorno) {

  oRetorno = eval("("+oRetorno.responseText+")");

  if (oRetorno.iStatus == 1) {

    $('sd03_i_crm').value = oRetorno.oProfissional.sd03_i_crm;
    $('z02_i_cns').value  = oRetorno.oProfissional.z02_i_cns;
    return true;

  } else {
    return false;
  }

}

function js_getSaldoTotalMedicamento() {

  if ($F('s159_i_medicamento').trim() == '') {
    return false;
  }

  var oParam          = new Object();
  oParam.exec         = 'getSaldoTotalMedicamento';
  oParam.iMedicamento = $F('s159_i_medicamento');

  js_ajax(oParam, 'js_retornoGetSaldoTotalMedicamento', 'far4_farmacia.RPC.php');

}
function js_retornoGetSaldoTotalMedicamento(oRetorno) {

  oRetorno = eval("("+oRetorno.responseText+")");

  if (oRetorno.iStatus == 1) {

    $('iSaldo').value = oRetorno.m70_quant;
    return true;

  } else {

    $('iSaldo').value = 0;
    return false;

  }

}

function js_abreHistorico() {

  if ($F('z01_i_cgsund') != '' && $F(z01_i_cgsund) == parseInt($F('z01_i_cgsund'), 10)) {

    sCgs  = 'cgs_get='+$F('z01_i_cgsund');
    sNome = '&nome='+$F('z01_v_nome');
    iTop  = (screen.availHeight - 640) / 2;
    iLeft = (screen.availWidth - 1000) / 2;
    js_OpenJanelaIframe('', 'db_iframe_historico', 'far3_historicopaciente_popup.php?'+sCgs+sNome,
                        'Hist&oacute;rico', true, iTop, iLeft, 1000, 440
                       );

  } else {
    alert('Selecione um CGS!');
  }

}

function js_pesquisas159_i_medicamento(mostra) {

  if (mostra == true) {

    js_OpenJanelaIframe('', 'db_iframe_far_matersaude', 'func_far_matersaude.php?'+
                        'funcao_js=parent.js_mostramatersaude1|fa01_i_codigo|m60_descr',
                        'Pesquisa de Medicamentos', true
                       );

  } else {

    if (document.form1.s159_i_medicamento.value != '') {

      js_OpenJanelaIframe('', 'db_iframe_far_matersaude', 'func_far_matersaude.php?'+
                          'pesquisa_chave='+$F(s159_i_medicamento)+'&funcao_js=parent.js_mostramatersaude',
                          'Pesquisa', false
                         );

    } else {

      document.form1.m60_descr.value  = '';
      $('iSaldo').value               = '';

    }

  }

}
function js_mostramatersaude(chave, erro) {

  document.form1.m60_descr.value = chave;
  if (erro == true) {

    document.form1.s159_i_medicamento.focus();
    document.form1.s159_i_medicamento.value = '';

  }
  js_getSaldoTotalMedicamento();

}
function js_mostramatersaude1(chave1, chave2) {

  document.form1.s159_i_medicamento.value = chave1;
  document.form1.m60_descr.value          = chave2;
  db_iframe_far_matersaude.hide();
  js_getSaldoTotalMedicamento();

}

function js_nova(iChave) {

  if (iChave == undefined) {

    window.location.href = 'sau4_sau_receitamedica001.php?s158_i_profissional='+$F('s158_i_profissional')+
                           '&s162_i_prontuario='+$F('s162_i_prontuario')+'&z01_nome='+$F('z01_nome');

  } else {
    window.location.href = 'sau4_sau_receitamedica001.php?chavepesquisa='+iChave;
  }

}

function js_pesquisaReceitas() {

  if ($F('s162_i_prontuario') == '') {

    alert('Informe uma FAA para pesquisar as receitas.');
    return false;

  }

  sGet  = 's162_i_prontuario='+$F('s162_i_prontuario');
  sGet += '&z01_i_cgsund='+$F('z01_i_cgsund');
  sGet += '&z01_v_nome='+$F('z01_v_nome');

  iTop  = (screen.availHeight - 540) / 2;
  iLeft = (screen.availWidth - 800) / 2;
  js_OpenJanelaIframe('', 'db_iframe_receitas', 'sau4_receitasmedicasfaa.iframe.php?'+sGet,
                      'Receitas Médicas', true, iTop, iLeft, 800, 440
                     );

}

function js_emitirReceita() {

  if ($F('s158_i_codigo') == '') {

    alert('Informe a receita a ser emitida.');
    return false;

  }

  if ($('iTemplate') == undefined || $('iTemplate') == null || $('iTemplate').length <= 0) {

    alert('Informe o modelo de receita a ser emitido.');
    return false;

  }

  sGet = 'iReceita='+$F('s158_i_codigo')+'&iTemplate='+$F('iTemplate');
  oJan = window.open('sau2_receitamedica001.php?'+sGet, '', 'width='+(screen.availWidth - 5)+',height='+
                     (screen.availHeight - 40)+',scrollbars=1,location=0 '
                    );
  oJan.moveTo(0, 0);

}

/*
function js_pesquisas158_i_profissional(mostra) {
  if (mostra==true) {
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_medicos','func_medicos.php?funcao_js=parent.js_mostramedicos1|z01_nome|z01_nome','Pesquisa',true);
  } else {
     if (document.form1.s158_i_profissional.value != '') {
        js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_medicos','func_medicos.php?pesquisa_chave='+document.form1.s158_i_profissional.value+'&funcao_js=parent.js_mostramedicos','Pesquisa',false);
     } else {
       document.form1.z01_nome.value = '';
     }
  }
}
function js_mostramedicos(chave,erro) {
  document.form1.z01_nome.value = chave;
  if (erro==true) {
    document.form1.s158_i_profissional.focus();
    document.form1.s158_i_profissional.value = '';
  }
}
function js_mostramedicos1(chave1,chave2) {
  document.form1.s158_i_profissional.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe_medicos.hide();
}
*/
</script>