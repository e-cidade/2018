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

define("URL_MESSAGE_FROMPEDIDOTFD", "saude.tfd.db_frmtfd_pedidotfd.");

//MODULO: TFD
$oDaoTfdPedidoTfd->rotulo->label();
$oRotulo = new rotulocampo;
$oRotulo->label('z01_v_nome');
$oRotulo->label('z01_v_cgccpf');
$oRotulo->label('z01_v_ident');
$oRotulo->label('tf23_i_procedimento');
$oRotulo->label('sd63_c_nome');
$oRotulo->label('tf27_i_codigo');
$oRotulo->label('tf26_i_codigo');
$oRotulo->label('tf04_i_codigo');
$oRotulo->label('rh70_descr');
$oRotulo->label('rh70_estrutural');
$oRotulo->label('nome');
$oRotulo->label('descrdepto');
$oRotulo->label('z01_nome');
$oRotulo->label('z01_i_cgsund');
$oRotulo->label('tf01_rhcbosolicitante');

$iCboSolicitanteSelecionado = !empty($tf01_rhcbosolicitante) ? $tf01_rhcbosolicitante : '';
//$oRotulo->label('tf01_v_complespec');
?>
<form name="form1" method="post" action="">
<center>
<table border="0" width="100%" style="padding-left: 11px;">
  <tr>
    <td align="center">
      <table  width="615px">
        <tr>
          <td nowrap title="<?=@$Ttf01_i_codigo?>">
            <?=@$Ltf01_i_codigo?>
          </td>
          <td>
            <?
            db_input('tf01_i_codigo', 10, $Itf01_i_codigo, true, 'text', 3, "");
            ?>
          </td>
          <td nowrap title="<?=@$Ttf01_d_datapedido?>" align="right" style="padding-right: 7px;">
            <label><?=@$Ltf01_d_datapedido?></label>
            <?
            if (!isset($tf01_d_datapedido)) {
              $tf01_d_datapedido = date('d/m/Y', db_getsession('DB_datausu'));
            }
            $dTmp = explode('/', $tf01_d_datapedido);
            if (count($dTmp) == 3) {

              $tf01_d_datapedido_dia = $dTmp[0];
              $tf01_d_datapedido_mes = $dTmp[1];
              $tf01_d_datapedido_ano = $dTmp[2];

            }

            db_inputdata('tf01_d_datapedido', @$tf01_d_datapedido_dia, @$tf01_d_datapedido_mes, @$tf01_d_datapedido_ano,  true, 'text', 1 );
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Ttf01_i_cgsund?>">
            <?
            echo $Ltf01_i_cgsund;
            ?>
          </td>
          <td colspan="3">
            <?
            db_input('tf01_i_cgsund', 10, $Itf01_i_cgsund, true, 'text', 3, '');
            db_input('z01_v_nome', 58, $Iz01_v_nome, true, 'text', 3, '');

            if (isset($tf29_i_prontuario)) {
              db_input('tf29_i_prontuario', 1, '', true, 'text', 3, '');
            }
            if (isset($tf30_i_encaminhamento)) {
              db_input('tf30_i_encaminhamento', 1, '', true, 'text', 3, '');
            }
            ?>
          </td>
        </tr>
        <tr>
          <td title='<?=$Tz01_i_cgsund?>' nowrap>
            <?=@$Lz01_v_cgccpf?>&nbsp;
          </td>
          <td nowrap>
            <?
            db_input('z01_v_cgccpf', 10, @$Iz01_v_cgccpf, true, 'text', 3, '');
            ?>
          </td>
          <td nowrap align="right" style="padding-right: 7px;">
            <label style="margin-right: 25px;"><?=@$Lz01_v_ident?></label>
            <?
            db_input('z01_v_ident', 10, $Iz01_v_ident, true, 'text', 3, '');
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Ttf01_i_rhcbo?>">
            <?
            db_ancora(@$Ltf01_i_rhcbo, "js_pesquisatf01_i_rhcbo(true);", $db_opcao);
            ?>
          </td>
          <td colspan="2">
            <?
            db_input('rh70_estrutural', 10, $Irh70_estrutural, true, 'text', $db_opcao,
                     " onchange='js_pesquisatf01_i_rhcbo(false);'"
                    );
            db_input('tf01_i_rhcbo', 10, $Itf01_i_rhcbo, true, 'hidden', 3);
            db_input('rh70_descr', 58, $Irh70_descr, true, 'text', $db_opcao, '');
            ?>
          </td>
        </tr>
        <tr>
         <td nowrap  title="<?=@$Ttf01_complespec?>">
           <b>Complemento: </b>
         </td>
         <td nowrap colspan="2">
           <?db_input('tf01_complespec', 72, $Itf01_complespec, true, 'text', $db_opcao, '');?>
         </td>
       </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td colspan="3">
      <fieldset style='width: 620px;'> <legend><b>Tratamento</b></legend>
        <table border="0">
          <tr>
             <td nowrap title="<?=@$Ttf01_i_tipotratamento?>" style="padding-bottom: 8px;">
               <?=@$Ltf01_i_tipotratamento?>
             </td>
             <td style="padding-bottom: 8px;">
               <?
               $aX                   = array();
               $sSql                 = $oDaoTfdTipoTratamento->sql_query_file(null, '* ');
               $rsTfd_tipotratamento = $oDaoTfdTipoTratamento->sql_record($sSql);

               for ($iCont = 0; $iCont < $oDaoTfdTipoTratamento->numrows; $iCont++) {

                 $oDados                     = db_utils::fieldsmemory($rsTfd_tipotratamento, $iCont);
                 $aX[$oDados->tf04_i_codigo] = $oDados->tf04_c_descr;

               }
               db_select('tf01_i_tipotratamento', $aX, true, isset($chavepesquisa) ? 3 : 1,
                         ' onchange="js_verificaTrocaTipoTratamento();" onfocus="js_indexTipoTratamento();"'
                        );
               ?>
             </td>
             <td align="right" style="padding-bottom: 8px;">
               <input type="button" name="documentos" id="documentos" value="Documentos Entregues"
                 onclick="js_documentos();">
               <input type="hidden" name="entregues" id="entregues" value="">
               <input type="hidden" name="lEntregues" id="lEntregues" value="">
             </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Ttf23_i_procedimento?>">
              <?
              db_ancora($Ltf23_i_procedimento, "js_pesquisatf23_i_procedimento(true);", $iDbOpcaoRegulado);
              ?>
            </td>
            <td nowrap colspan="2">
              <?
              db_input('sd63_c_procedimento', 10, '', true, 'text', $iDbOpcaoRegulado,
                       " onchange='js_pesquisatf23_i_procedimento(false);'"
                      );
              db_input('tf23_i_procedimento', 1, '', true, 'hidden', 3);
              db_input('sd63_c_nome', 51, $Isd63_c_nome, true, 'text', 3, '');
              if (!isset($lSucesso) || $lSucesso == 'true' || !isset($lProcedimentosAlterados)) {
                $lProcedimentosAlterados = 'false';
              }
              db_input('lProcedimentosAlterados', 1, '', true, 'hidden', 3, "");
              ?>
              <input name="lancar_procedimento" type="button" id="lancar_procedimento" value="Incluir"
                onclick="js_lanca_procedimento();">
              <select multiple  name='select_procedimento[]' id='select_procedimento' style="display: none;">
            </td>
          </tr>
          <tr>
            <td colspan="3">
              <div id='grid_procedimentos' style='width:595px;'></div>
            </td>
          </tr>
        </table>
      </fieldset>
    </td>
  </tr>

  <tr>
    <td colspan="3">
      <fieldset style='width: 620px;'> <legend><b>Informações</b></legend>
        <table border="0">
          <tr>
            <td nowrap title="<?=$Ttf01_i_profissionalsolic?>">
              <?
              db_ancora($Ltf01_i_profissionalsolic, 'js_pesquisatf01_i_profissionalsolic(true);', $db_opcao);
              ?>
            </td>
            <td colspan="4" nowrap>
              <?
              db_input('tf01_i_profissionalsolic', 10, $Itf01_i_profissionalsolic, true, 'hidden',$db_opcao,
                       ' onchange="js_pesquisatf01_i_profissionalsolic(false);"'
                      );
              db_input('z01_nome', 40, $Iz01_nome, true, 'text', 1, "onchange='js_validaAlteracaoNome();'");
              if (db_permissaomenu(date('Y'), 1000004, 8675) == 'true') {
              ?>
                <input type="button" id="cadProf" title="Cadastro de Profissionais Fora da Rede"
                  name="cadProf" value="Cadastro de Profissionais" onclick="js_abreCadProf();">
              <?
              }
              ?>
            </td>
          </tr>

          <tr>
            <td class='bold'>
              CBO Solicitante:
            </td>
            <td colspan="4" nowrap="nowrap">
              <?db_input('iCboSolicitanteSelecionado', 72, "iCboSolicitanteSelecionado", true, 'hidden', 3, '');?>
              <select id='cboSolicitante' name='tf01_rhcbosolicitante' class="field-size-max">
              </select>
            </td>
          </tr>

          <tr>
            <td nowrap title="<?=@$Ttf01_d_datapreferencia?>">
              <?=@$Ltf01_d_datapreferencia?>
            </td>
            <td nowrap>
              <?
              if (isset($tf01_d_datapreferencia) && !empty($tf01_d_datapreferencia)) {

                $dTmp = explode('/', $tf01_d_datapreferencia);
                if (count($dTmp) == 3) {

                  $tf01_d_datapreferencia_dia = $dTmp[0];
                  $tf01_d_datapreferencia_mes = $dTmp[1];
                  $tf01_d_datapreferencia_ano = $dTmp[2];

                }

              } else {


                $tf01_d_datapreferencia = date('d/m/Y', db_getsession('DB_datausu'));
                $dTmp                   = explode('/', $tf01_d_datapreferencia);
                if (count($dTmp) == 3) {

                  $tf01_d_datapreferencia_dia = $dTmp[0];
                  $tf01_d_datapreferencia_mes = $dTmp[1];
                  $tf01_d_datapreferencia_ano = $dTmp[2];

                }

              }
              db_inputdata('tf01_d_datapreferencia', @$tf01_d_datapreferencia_dia, @$tf01_d_datapreferencia_mes,
                           @$tf01_d_datapreferencia_ano, true, 'text', $db_opcao, ""
                          );
              ?>
            </td>
            <td nowrap title="<?=@$Ttf01_i_emergencia?>" colspan="2" align="left">
              <label style="margin-right: 33px;"><?=@$Ltf01_i_emergencia?></label>
              <?
              $aX = array('2'=>'NÃO', '1'=>'SIM');
              db_select('tf01_i_emergencia', $aX, true, $db_opcao);
              ?>
            </td>
            <td align="right">
              <input type="button" name="comunicado" id="comunicado" value="Comunicado" onclick="js_comunicado();"
                <?=(isset($tf01_i_codigo) ? '' : ' disabled')?>>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Ttf01_i_tipotransporte?>">
              <?=@$Ltf01_i_tipotransporte;?>
            </td>
            <td>
              <?
              $aX = array();
              $sSql = $oDaoTfdTipoTransporte->sql_query_file(null, '* ');
              $rsTfd_tipotransporte = $oDaoTfdTipoTransporte->sql_record($sSql);
              for ($iCont = 0; $iCont < $oDaoTfdTipoTransporte->numrows; $iCont++) {

                $oDados = db_utils::fieldsmemory($rsTfd_tipotransporte, $iCont);
                $aX[$oDados->tf27_i_codigo] = $oDados->tf27_c_descr;

              }
              db_select('tf01_i_tipotransporte', $aX, true, $db_opcao, ' onchange="js_passagemPlaca();"');
              ?>
            </td>
            <td nowrap title="<?=@$Ttf01_c_passagemplaca?>" colspan="2">
              <?=@$Ltf01_c_passagemplaca?>
              <?
              db_input('tf01_c_passagemplaca', 10, $Itf01_c_passagemplaca, true, 'text', $db_opcao, '')
              ?>
            </td>
            <td align="right">
              <input type="button" name="situacao" id="situacao" value="Situação" onclick="js_situacao();"
                <?=(isset($tf01_i_codigo) && !empty($tf01_i_codigo) ? '' : ' disabled')?>>
            </td>
          </tr>
          <tr>
            <td>
              <?=$Ltf01_t_obs?>
            </td>
            <td nowrap colspan="4">
              <?
              db_textarea('tf01_t_obs', 2, 61, $Itf01_t_obs, true, 'text', $db_opcao, '');
              ?>
            </td>
          </tr>
        </table>
      </fieldset>
    </td>
  </tr>
</table>
<table style="padding-top: 6px; padding-bottom: 0px; margin: 0;">
  <tr>
    <td align="center">
      <?
      if (isset($chavepesquisa)) {

        $sNome1   = 'alterar';
        $sNome2   = 'Alterar';
        $lAlterar = 'true';

      } else {

        $sNome1   = 'confirmar';
        $sNome2   = 'Confirmar';
        $lAlterar = 'false';

      }
      ?>
      <input type="submit" name="<?=$sNome1?>" id="<?=$sNome1?>" value="<?=$sNome2?>"
        onclick="return js_validaEnvio(<?=$lAlterar?>);">
      <input type="button" name="protocolo" id="protocolo" value="Protocolo" onclick="js_protocolo();"
        <?=(isset($tf01_i_codigo) && !empty($tf01_i_codigo) ? '' : ' disabled')?>>
      <input type="button" name="acompanhantes" id="acompanhantes" value="Acompanhantes" onclick="js_acompanhantes();"
        <?=(isset($tf01_i_codigo) && !empty($tf01_i_codigo) ? '' : ' disabled')?>>
      <input type="button" name="prestadora" id="prestadora" value="Prestadora" onclick="js_prestadora();"
        <?=(isset($tf01_i_codigo) && !empty($tf01_i_codigo) ? '' : ' disabled')?>>
      <input type="button" name="ajuda" id="ajuda" value="Ajuda de Custo" onclick="js_ajuda();"
      <?=(isset($tf01_i_codigo) && !empty($tf01_i_codigo) ? '' : ' disabled')?>>
      <input type="button" name="regulador" id="regulador" value="Regulador" onclick="js_regulador();"
        <?=(db_permissaomenu(date('Y'), db_getsession('DB_modulo'), 8616) == 'true' &&
           isset($tf01_i_codigo) && !empty($tf01_i_codigo) ? '' : ' disabled')?>>
      <input type="button" name="saida" id="saida" value="Saída" onclick="js_saida();"
        <?=(isset($tf01_i_codigo) && !empty($tf01_i_codigo) ? '' : ' disabled')?>>
      <input type="button" name="fecha" id="fecha" value="Fecha"
        onclick="js_fecha(<?=(isset($chavepesquisa) ? 'false' : 'true')?>);">
    </td>
  </tr>
</table>
</center>
</form>

<script>

const URL_MESSAGE_FROMPEDIDOTFD = "saude.tfd.db_frmtfd_pedidotfd.";

oDBGridProcedimentos         = js_cria_datagrid();
iSelectedIndexTipoTratamento = $('tf01_i_tipotratamento').selectedIndex;
js_getInfoCgs();
js_passagemPlaca(false);
js_getProcedimentosPedidoTfd();
js_existeDocObrigatorio();

// Autocomplete do profissional solicitante
oAutoComplete = new dbAutoComplete(document.form1.z01_nome,'far4_retirada_autonomeRPC.php?tipo=5');
oAutoComplete.setTxtFieldId(document.getElementById('tf01_i_profissionalsolic'));
oAutoComplete.setHeightList(180);
oAutoComplete.show();
oAutoComplete.setCallBackFunction( function(id, label) {

                                     $('tf01_i_profissionalsolic').value = id;
                                     $('z01_nome').value                 = label;
                                     buscaCBOSolicitante();
                                   }
                                 );


//Autocomplete da especialidade
oAutoCompleteEspec = new dbAutoComplete(document.form1.rh70_descr,'far4_retirada_autonomeRPC.php?tipo=6');
oAutoCompleteEspec.setTxtFieldId(document.getElementById('rh70_estrutural'));
oAutoCompleteEspec.setHeightList(180);
oAutoCompleteEspec.show();
oAutoCompleteEspec.setCallBackFunction( function(id, label) {

                                          $('rh70_estrutural').value = id;
                                          $('rh70_descr').value      = label;
                                          js_pesquisatf01_i_rhcbo(false);

                                        }
		                                  );

function js_ajax(oParam, jsRetorno, sUrl, lAsync) {

  var mRetornoAjax;

  if (sUrl == undefined) {
    sUrl = 'tfd4_pedidotfd.RPC.php';
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

function js_validaEnvio(lAlterar) {

  if ($F('rh70_estrutural').trim() == '' || $F('tf01_i_rhcbo').trim() == '') {

    alert('Selecione uma especialidade.');
    return false;
  }

  if ($('select_procedimento').length < 1) {

    alert('É necessário lançar ao menos 1 procedimento.');
    return false;
  }

  if (!lAlterar) {

    if ($F('lEntregues') == 'false') {

      alert('Todos os documentos obrigatórios para este tipo de tratamento devem ser entregues.');
      return false;
    }
  }

  if ($F('tf01_d_datapreferencia').trim() == '') {

    alert('Preencha a data de preferência do paciente.');
    return false;
  }

  if (document.form1.z01_nome.value == '') {
    document.form1.tf01_i_profissionalsolic.value = '';
  }

  if ($F('cboSolicitante') == '' || $F('cboSolicitante') == null) {

     alert(_M(URL_MESSAGE_FROMPEDIDOTFD + 'campo_cbo_solicitante'));
     return false;
  }

  return true;

}

function js_getProcedimentosPedidoTfd() {

  if ($F('tf01_i_codigo').trim() == '') {
    return false;
  }

  var oParam     = new Object();
	oParam.exec    = 'getProcedimentosPedidoTfd';
	oParam.iPedido = $F('tf01_i_codigo');

  js_ajax(oParam, 'js_retornogetProcedimentosPedidoTfd');

}

function js_retornogetProcedimentosPedidoTfd(oRetorno) {

  oRetorno = eval("("+oRetorno.responseText+")");

  if (oRetorno.iStatus != 1) {

    alert(oRetorno.sMessage.urlDecode());
    return false;

  } else {

    var iCont = 0;
    oF        = $('select_procedimento');

    oRetorno.oProcedimentos.each(
      function (oProcedimentos) {

      oF.options[iCont]   = new Option(oProcedimentos.sd63_c_procedimento+' ## '+
                                       oProcedimentos.sd63_c_nome.urlDecode(),
                                       oProcedimentos.tf23_i_procedimento
                                      );
      oF.options[iCont].selected = true;
      iCont++;

    });

    js_renderizaGrid();

  }

}

function js_passagemPlaca(lLimpa) {

  if (lLimpa == undefined) {
    lLimpa = true;
  }

  if (lLimpa) {
    $('tf01_c_passagemplaca').value = '';
  }

  if ($F('tf01_i_tipotransporte') == '1' || $F('tf01_i_tipotransporte') == '4') {

    $('tf01_c_passagemplaca').readOnly              = false;
    $('tf01_c_passagemplaca').style.backgroundColor =  "rgb(230, 228, 241)";

  } else {

    $('tf01_c_passagemplaca').readOnly              = true;
    $('tf01_c_passagemplaca').style.backgroundColor =  "rgb(222, 184, 135)";

  }

}

function js_getInfoCgs() {

  var oParam  = new Object();
	oParam.exec = "getInfoCgs";
	oParam.iCgs = $F('tf01_i_cgsund');

  if ($F('tf01_i_cgsund') != '') {
    js_ajax(oParam, 'js_retornogetInfoCgs');
  }

}

function js_retornogetInfoCgs(oRetorno) {

  oRetorno = eval("("+oRetorno.responseText+")");

  $('z01_v_cgccpf').value = oRetorno.z01_v_cgccpf.urlDecode();
  $('z01_v_ident').value  = oRetorno.z01_v_ident.urlDecode();
  $('z01_v_nome').value   = oRetorno.z01_v_nome.urlDecode();

}

/**** Bloco de funções do tipo de tratamento (início) */
function js_indexTipoTratamento() {
  iSelectedIndexTipoTratamento = $('tf01_i_tipotratamento').selectedIndex;
}

function js_verificaTrocaTipoTratamento() {

  var oF                         = $("select_procedimento");
  var oTipo                      = $('tf01_i_tipotratamento')
  var iValor                     = oTipo.value;

  $('tf23_i_procedimento').value = '';
  $('sd63_c_procedimento').value = '';
  $('sd63_c_nome').value         = '';

  if (oF.length > 0 || $F('entregues').trim() != '') {

    if (confirm('Ao trocar o tipo de tratamento, você perderá todos os lançamentos feitos nos'+
      ' procedimentos e nos documentos entregues.\n'+
       'Deseja realmente trocar o tipo de tratamento?')) {

      js_esvaziaProcedimentos();
      js_renderizaGrid();
      $('entregues').value  = '';
      $('lEntregues').value = '';
      js_indexTipoTratamento();
      js_existeDocObrigatorio()

    } else {
      oTipo.selectedIndex = iSelectedIndexTipoTratamento;
    }

  } else {
    js_existeDocObrigatorio();
  }

}

function js_existeDocObrigatorio() {

  var oParam             = new Object();
  oParam.exec            = 'existeDocObrigatorio';
  oParam.iTipoTratamento = $F('tf01_i_tipotratamento');

  js_ajax(oParam, 'js_retornoexisteDocObrigatorio');

}

function js_retornoexisteDocObrigatorio(oRetorno) {

  oRetorno = eval('('+oRetorno.responseText+')');

  if (oRetorno.lPossuiDocObrigatorio) { // possui documentos obrigatórios e eles ainda não foram entregues
    $('lEntregues').value = 'false';
  } else {
    $('lEntregues').value = 'true';
  }

}
/* Bloco de funções do tipo de tratamento (fim) ****/

/**** Bloco de funções botão Documentos Entregues (início) */
function js_documentos() {

  sChave  = '&tf01_i_cgsund='+$F('tf01_i_cgsund')+'&z01_v_nome='+$F('z01_v_nome')+'&tf01_i_tipotratamento=';
  sChave += $F('tf01_i_tipotratamento');
  if ($F('tf01_i_codigo') != '') { // então é alteração
    sChave = sChave + '&tf01_i_codigo='+$F('tf01_i_codigo');
  }

  js_OpenJanelaIframe('', 'db_iframe_documentos', 'tfd4_tfd_documentosentregues001.php?'+sChave,
                      'Documentos do Pedido TFD', true
                     );

}
/* Bloco de funções botão Documentos Entregues (fim) ****/

/**** Bloco de funções botão Situação (início) */
function js_situacao() {

  sChave  = '&tf01_i_cgsund='+$F('tf01_i_cgsund')+'&z01_v_nome='+$F('z01_v_nome')+'&tf28_i_pedidotfd=';
  sChave += $F('tf01_i_codigo');
  if ($F('tf01_i_codigo') != '') {

    js_OpenJanelaIframe('', 'db_iframe_situacao', 'tfd4_tfd_situacaopedidotfd001.php?'+sChave,
                        'Situação do Pedido TFD', true
                       );

  }

}
/* Bloco de funções botão Situação (fim) ****/

/**** Bloco de funções botão comunicado (início) */
function js_comunicado() {

  sChave  = '&tf01_i_cgsund='+$F('tf01_i_cgsund')+'&z01_v_nome='+$F('z01_v_nome')+'&tf21_i_pedidotfd=';
  sChave += $F('tf01_i_codigo');
  if ($F('tf01_i_codigo') != '') {

    js_OpenJanelaIframe('', 'db_iframe_comunicado', 'tfd4_tfd_avisopaciente001.php?'+sChave,
                        'Aviso ao Paciente', true
                       );

  }

}
/* Bloco de funções botão Comunicado (fim) ****/

/**** Bloco de funções botão Acompanhantes (início) */
function js_acompanhantes() {

  sChave  = '&tf01_i_cgsund='+$F('tf01_i_cgsund')+'&z01_v_nome='+$F('z01_v_nome')+'&tf13_i_pedidotfd=';
  sChave += $F('tf01_i_codigo');

  if ($F('tf01_i_codigo') != '') {
   var janela = js_OpenJanelaIframe('', 'db_iframe_acompanhantes', 'tfd4_tfd_acompanhantes001.php?'+sChave, 'Acompanhantes', true, 0, 0);
   janela.setLargura("calc(100% - 15px)");
   janela.setAltura("calc(100% - 25px)");
  }

}
/* Bloco de funções botão Acompanhantes (fim) ****/

/**** Bloco de funções botão Ajuda de Custo (início) */
function js_ajuda() {

  sChave  = '&tf01_i_cgsund='+$F('tf01_i_cgsund')+'&z01_v_nome='+$F('z01_v_nome')+'&tf14_i_pedidotfd=';
  sChave += $F('tf01_i_codigo');
  if ($F('tf01_i_codigo') != '') {

    js_OpenJanelaIframe('', 'db_iframe_ajuda', 'tfd4_tfd_ajudacustopedido001.php?'+sChave,
                        'Ajuda de Custo', true
                       );

  }

}
/* Bloco de funções botão Ajuda de Custo (fim) ****/

/**** Bloco de funções botão Prestadora (início) */
function js_prestadora() {

  sChave  = '&tf01_i_cgsund='+$F('tf01_i_cgsund')+'&z01_v_nome='+$F('z01_v_nome')+'&tf16_i_pedidotfd=';
  sChave += $F('tf01_i_codigo');
  if ($F('tf01_i_codigo') != '') {

    js_OpenJanelaIframe('', 'db_iframe_prestadora', 'tfd4_tfd_agendamentoprestadora001.php?'+sChave,
                        'Agendamento com a Prestadora', true
                       );

  }

}

 /**** Bloco de funções botão Regulador ****/
/**** Bloco de funções botão Regulador ****/
function js_regulador() {

  if ($F('tf01_i_codigo') != '') {

    js_OpenJanelaIframe('', 'db_iframe_regulador', 'tfd4_tfd_selecionarregulador001.php?&tf01_i_codigo=' + $F('tf01_i_codigo'),
                        'Regulador', true
                       );
  }
}



/* Bloco de funções botão Prestadora (fim) ****/

/**** Bloco de funções botão Saída (início) */
function js_saida() {

  sChave  = '&tf01_i_cgsund='+$F('tf01_i_cgsund')+'&z01_v_nome='+$F('z01_v_nome')+'&tf17_i_pedidotfd=';
  sChave += $F('tf01_i_codigo')+'&z01_v_ident='+$F('z01_v_ident')+'&z01_v_cgccpf='+$F('z01_v_cgccpf');
  sChave += '&dataPedido='+$F('tf01_d_datapedido');
  if ($F('tf01_i_codigo') != '') {

    js_OpenJanelaIframe('', 'db_iframe_saida', 'tfd4_tfd_agendasaida001.php?'+sChave,
                        'Saída', true
                       );

  }

}
/* Bloco de funções botão Saída (fim) ****/

/**** Bloco de funções botão Saída (início) */
function js_protocolo() {

  sChave = 'tf01_i_pedidotfd='+$F('tf01_i_codigo');
  if ($F('tf01_i_codigo') != '') {

    jan = window.open('tfd2_protocolopedidotfd002.php?'+sChave, '',
                      'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 '
                     );
    jan.moveTo(0, 0);

  }

}
/* Bloco de funções botão Saída (fim) ****/

/**** Bloco de funções botão Fecha (início) */
function js_fecha(lAlerta) {

  if (lAlerta) {

    if (!confirm('O pedido de TFD ainda não foi confirmado. Deseja fechar sem salvar as informações?')) {
      return false;
    }

  }

  parent.document.formaba.a2.disabled = true;
  (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_a1.location.href   = 'tfd4_tfd_pedidotfd001.php';
  parent.mo_camada('a1');

}
/* Bloco de funções botão Fecha (fim) ****/



/**** Bloco de Funções que tratam do grid / select dos procedimentos (início) */
function js_renderizaGrid() {

  var oF = $('select_procedimento');
  oDBGridProcedimentos.clearAll(true);

  var aLinha = new Array();
  for (i = 0; i < oF.length; i++) {

    aInfo     = oF.options[i].innerHTML.split(' ## ');

    aLinha[0] = aInfo[0];
    aLinha[1] = aInfo[1].substr(0, 54);
    if(<?=$iDbOpcaoRegulado?> == 3) {

      aLinha[2]  = "<span><b>E</b></span>";

    } else {

      aLinha[2]  = "<span onclick=\"js_excluir_item_procedimento("+oF.options[i].value+");\""+
                   " style=\"color: blue; text-decoration: underline; cursor: pointer;\"><b>E</b></span>";

    }
    oDBGridProcedimentos.addRow(aLinha);

  }
  oDBGridProcedimentos.renderRows();

}

function js_excluir_item_procedimento(iVal) {

  var oF = $("select_procedimento");
  for (i = 0; i < oF.length; i++) {

    if (oF.options[i].value == iVal) {

      oF.options[i]                      = null;
      $('lProcedimentosAlterados').value = 'true';
      break;

    }

  }
  js_renderizaGrid();

}

function js_lanca_procedimento() {

  valor = $F('tf23_i_procedimento');
  texto = $F('sd63_c_procedimento')+' ## '+$F('sd63_c_nome');
  if (valor != '' && $F('sd63_c_procedimento').trim() != '') {

    var oF                        = $('select_procedimento');
    var valor_default_novo_option = oF.length;
    var testa                     = false;
    /*
    * testa se o elemento ja foi inserido no select
    */
    for (var x = 0; x < oF.length; x++) {

      if (oF.options[x].value == valor) {

        testa = true;
        break;

      }

    }

    if (testa == false) {
      /*
      * Cria o novo option no select hidden que armazena os procedimentos
      */
      $('lProcedimentosAlterados').value             = 'true';
      var aLinha                                     = new Array();
      oF.options[valor_default_novo_option]          = new Option(texto, valor);
      oF.options[valor_default_novo_option].selected = true;
      js_renderizaGrid();

    }

  }
  texto = $('tf23_i_procedimento').value = '';
  valor = $('sd63_c_nome').value         = '';
  $('sd63_c_procedimento').value         = '';

}

function js_cria_datagrid() {

  oDBGridProcedimentos              = new DBGrid('grid_procedimentos');
  oDBGridProcedimentos.nameInstance = 'oDBGridProcedimentos';
  oDBGridProcedimentos.setCellWidth(new Array('10%', '80%', '10%'));
  oDBGridProcedimentos.setHeight(38);

  //oDBGridProcedimentos.setCheckbox(0);
  var aHeader = new Array();
  aHeader[0]  = 'Procedimento';
  aHeader[1]  = 'Descri&ccedil;&atilde;o';
  aHeader[2]  = 'Excluir';
  oDBGridProcedimentos.setHeader(aHeader);
  //oDBGridProcedimentos.aHeader[11].lDisplayed = false;
  oDBGridProcedimentos.allowSelectColumns(true);
  var aAligns = new Array();
  aAligns[0]  = 'center';
  aAligns[1]  = 'center';
  aAligns[2]  = 'center';

  oDBGridProcedimentos.setCellAlign(aAligns);
  oDBGridProcedimentos.allowSelectColumns(false);
  oDBGridProcedimentos.show($('grid_procedimentos'));
  oDBGridProcedimentos.clearAll(true);

  return oDBGridProcedimentos;

}

function js_esvaziaProcedimentos() {

  sel = $('select_procedimento');
  while(sel.length > 0) {
    sel.options[0] = null;
  }

}
/* Bloco de Funções que tratam do grid / select dos procedimentos (fim) *****/

function js_pesquisatf01_i_rhcbo(mostra) {

  if (mostra == true) {

    js_OpenJanelaIframe('', 'db_iframe_rhcbo', 'func_rhcbosaude.php?funcao_js=parent.js_mostrarhcbo1|'+
                        'rh70_estrutural|rh70_descr|rh70_sequencial', 'Pesquisa', true
                       );

  } else {

    if (document.form1.rh70_estrutural.value != '') {

      js_OpenJanelaIframe('', 'db_iframe_rhcbo', 'func_rhcbosaude.php?pesquisa_chave='+
                          document.form1.rh70_estrutural.value+'&funcao_js=parent.js_mostrarhcbo',
                          'Pesquisa', false
                         );

    } else {

       document.form1.tf01_i_rhcbo.value = '';
       document.form1.rh70_descr.value   = '';
       js_esvaziaProcedimentos();
       js_renderizaGrid();
       $('tf23_i_procedimento').value = $('sd63_c_nome').value = $('sd63_c_procedimento').value = '';

     }

  }

}
function js_mostrarhcbo(chave1, chave2, chave3, erro) {

  document.form1.rh70_estrutural.value = chave1;
  document.form1.rh70_descr.value      = chave2;
  document.form1.tf01_i_rhcbo.value    = chave3;

  if (erro == true) {
    document.form1.rh70_estrutural.focus();
  }

}
function js_mostrarhcbo1(chave1, chave2, chave3) {

  document.form1.rh70_estrutural.value = chave1;
  document.form1.rh70_descr.value      = chave2;
  document.form1.tf01_i_rhcbo.value    = chave3;

  db_iframe_rhcbo.hide();

}

function js_pesquisatf23_i_procedimento(mostra) {

  sChave = '&chave_tf05_i_tipotratamento='+$F('tf01_i_tipotratamento');
  if (mostra == true) {

      js_OpenJanelaIframe('', 'db_iframe_tfd_tipotratamentoproced', 'func_tfd_tipotratamentoproced.php?'+sChave+
                          '&funcao_js=parent.js_mostrasau_procedimento|sd63_c_procedimento|sd63_c_nome|'+
                          'tf05_i_procedimento', 'Pesquisa Procedimento', true
                         );

  } else {

    if ( $F('sd63_c_procedimento') != '') {

      js_OpenJanelaIframe('', 'db_iframe_tfd_tipotratamentoproced', 'func_tfd_tipotratamentoproced.php?'+sChave+
                          '&chave_sd63_c_procedimento='+$F('sd63_c_procedimento')+
                          '&funcao_js=parent.js_mostrasau_procedimento|sd63_c_procedimento|sd63_c_nome|'+
                          'tf05_i_procedimento&nao_mostra=true', 'Pesquisa Procedimento', false
                         );


		} else {

			$('sd63_c_nome').value         = '';
      $('tf23_i_procedimento').value = '';

		}

	}

}

function js_mostrasau_procedimento(chave1, chave2, chave3) {

  if (chave3 == undefined) {
    chave3 = '';
  }
  $('sd63_c_procedimento').value = chave1;
  $('sd63_c_nome').value         = chave2;
  $('tf23_i_procedimento').value = chave3;

  db_iframe_tfd_tipotratamentoproced.hide();
}

String.prototype.trim = function() {
  return this.replace(/^\s+|\s+$/g, "");
}

function js_abreCadProf() {

  iTop  = (screen.availHeight - 650) / 2;
  iLeft = (screen.availWidth - 800) / 2;

  if ($F('tf01_i_profissionalsolic') == '') {

    sGet = 's154_c_nome='+$F('z01_nome')+'&sd03_i_tipo=2&lBotao=true';

    js_OpenJanelaIframe('', "db_iframe_cadprof", "sau1_sau_medicosforarede001.php?"+sGet,
                        'Cadastro de Profissionais Fora da Rede', true, iTop, iLeft, 800, 300
                       );

  } else {

    var oParam     = new Object();
    oParam.exec    = 'verificaForaRede';
    oParam.iMedico = $F('tf01_i_profissionalsolic');

    if (js_ajax(oParam, 'js_retornoVerificaForaRede', 'sau4_ambulatorial.RPC.php', false)) {

      sGet = 'chavepesquisa='+$F('tf01_i_profissionalsolic')+'&lBotao=true';

      js_OpenJanelaIframe('', 'db_iframe_cadprof', 'sau1_sau_medicosforarede002.php?'+sGet,
                          'Cadastro de Profissionais Fora da Rede', true, iTop, iLeft, 800, 300
                         );

    } else {

      var sMsg = 'Profissional selecionado não é um profissional de fora da rede.';
      sMsg += "\nLimpe o nome do profissional selecionado antes de cadastrar um novo profissional.";
      alert(sMsg);
    }

  }

}

function js_retornoVerificaForaRede(oRetorno) {

  oRetorno = eval("("+oRetorno.responseText+")");
  if (oRetorno.lForaRede == 'true') {
    return true;
  } else {
    return false;
  }

}

function js_pesquisatf01_i_profissionalsolic(mostra) {

  if (mostra == true) {

    js_OpenJanelaIframe('',
                        'db_iframe_medicos',
                        'func_medicos.php?funcao_js=parent.js_mostramedicos1|sd03_i_codigo|z01_nome'+
                        '&lTodosTiposProf=true','Pesquisa',true
                        );

  } else {

    if (document.form1.tf01_i_profissionalsolic.value != '') {

      js_OpenJanelaIframe('',
                          'db_iframe_medicos',
                          'func_medicos.php?pesquisa_chave='+document.form1.tf01_i_profissionalsolic.value+
                          '&funcao_js=parent.js_mostramedicos&lTodosTiposProf=true',
                          'Pesquisa', false
                         );

    } else {
        document.form1.z01_nome.value = '';
    }

  }


}
function js_mostramedicos(chave, erro) {

  document.form1.z01_nome.value = chave;
  if (erro == true) {

    document.form1.tf01_i_profissionalsolic.focus();
    document.form1.tf01_i_profissionalsolic.value = '';
    return false;
  }
  buscaCBOSolicitante();

}
function js_mostramedicos1(chave1, chave2) {

  document.form1.tf01_i_profissionalsolic.value = chave1;
  document.form1.z01_nome.value                 = chave2;
  db_iframe_medicos.hide();
  buscaCBOSolicitante();

}

function js_preencheMedicoRecemCadastrado(iCod) {

  $('tf01_i_profissionalsolic').value = iCod;
  js_pesquisatf01_i_profissionalsolic(false);

}

/**
 * Busca CBO do médico
 */
function buscaCBOSolicitante() {

  var oParametros     = new Object();
  oParametros.exec    = "getUnidadeCBOMedico";
  oParametros.iMedico = $F('tf01_i_profissionalsolic');

  js_divCarregando("Aguarde... buscando CBO do médico.", "db_msgbox");

  var oRequest = new Object();
  oRequest.method     = 'post';
  oRequest.parameters = 'json='+Object.toJSON(oParametros);
  oRequest.onComplete = js_retornoGetCboSolicitante;

  new Ajax.Request("sau4_medicos.RPC.php", oRequest);

}

function js_retornoGetCboSolicitante(oAjax) {

  js_removeObj("db_msgbox");

  var oRetorno    = eval( "(" +oAjax.responseText+ ")" );
  var lSelecionar = false;
  var aDadosCBO   = new Array();
  oRetorno.aDados.each(function (oMedico) {

    if (oMedico.codigo_cbo == '') {
      return;
    }

    var sStringCbo = oMedico.estrutura_cbo + " - " + oMedico.nome_cbo.urlDecode();
    aDadosCBO.push(new Option(sStringCbo, oMedico.codigo_cbo));

    if ( !empty($F('iCboSolicitanteSelecionado')) && $F('iCboSolicitanteSelecionado') == oMedico.codigo_cbo ) {
      lSelecionar = true;
    }

  });

  if (aDadosCBO.length == 0) {

    alert(_M(URL_MESSAGE_FROMPEDIDOTFD+"medico_sem_cbo"));
    return false;
  }

  $('cboSolicitante').options.length = 0;
  aDadosCBO.each(function (oOption) {

    $('cboSolicitante').add(oOption);
  });

  if ( lSelecionar ) {
    $('cboSolicitante').value = $F('iCboSolicitanteSelecionado');
  }

}


if ($F('tf01_i_profissionalsolic') != '') {
  buscaCBOSolicitante();
}


function js_validaAlteracaoNome() {

  if ($F('z01_nome') == '') {

    $('cboSolicitante').options.length = 0;
    $('tf01_i_profissionalsolic').value = '';
  }
}
</script>
