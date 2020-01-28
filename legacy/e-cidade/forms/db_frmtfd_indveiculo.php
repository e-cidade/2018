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

//MODULO: TFD
$oDaoCgsUnd->rotulo->label();
$oDaoTfdVeiculoDestino->rotulo->label();

$oRotulo = new rotulocampo;
$oRotulo->label("tf29_i_prontuario");
$oRotulo->label("tf30_i_encaminhamento");
$oRotulo->label("tf01_i_cgsund");
$oRotulo->label("s115_c_cartaosus");
$oRotulo->label("j13_codi");
$oRotulo->label("ve61_veicmotoristas");
$oRotulo->label("z01_nome");
$oRotulo->label("ve01_placa");
$oRotulo->label("tf17_c_localsaida");
$oRotulo->label("tf03_c_descr");
?>
<form name="form1" method="post" action="">
  <div class="container">
    <fieldset style='width: 92%;'>
      <legend>Vincule o Passageiro ao Veículo de Saída</legend>
      <?php
      db_input('tf18_i_codigo', 10, $Itf18_i_codigo, true, 'hidden', $db_opcao, "");
      ?>
      <table border="0" width="100%">
        <tr>
          <td nowrap title="<?=$Ttf18_i_veiculo?>">
            <?php
            db_ancora( $Ltf18_i_veiculo, "js_pesquisatf18_i_veiculo(true);", "");
            ?>
          </td>
          <td nowrap colspan="4">
            <?php
            db_input('tf18_i_veiculo', 10, $Itf18_i_veiculo, true, 'text', $db_opcaoNaoMudar,
                     " onchange='js_pesquisatf18_i_veiculo(false);'"
                    );
            db_input('ve01_placa', 50, $Ive01_placa, true, 'text', 3, '');
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=$Tve61_veicmotoristas?>">
            <?php
            db_ancora($Ltf18_i_motorista, "js_pesquisatf18_i_motorista(true);", $db_opcao);
            ?>
          </td>
          <td  nowrap colspan="4">
            <?php
            db_input('tf18_i_motorista', 10, $Itf18_i_motorista, true, 'text', $db_opcao,
                     " onchange='js_pesquisatf18_i_motorista(false);'"
                    );
            db_input('z01_nome', 50, $Iz01_nome, true, 'text', 3, '');
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=$Ttf18_i_destino?>" >
            <?php
            db_ancora('<b>Destino</b>', "js_pesquisatf18_i_destino(true);",  $db_opcaoNaoMudar);
            ?>
          </td>
          <td nowrap colspan="4">
            <?php
            db_input('tf18_i_destino', 10, $Itf18_i_destino, true, 'text', $db_opcaoNaoMudar,
                     " onchange='js_pesquisatf18_i_destino(false);'"
                    );
            db_input('tf03_c_descr', 50, $Itf03_c_descr, true, 'text', 3, '');
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=$Ttf18_d_datasaida?>">
            <?=$Ltf18_d_datasaida?>
          </td>
          <td nowrap align="left">
            <?php
            db_inputdata('tf18_d_datasaida', @$tf18_d_datasaida_dia, @$tf18_d_datasaida_mes, @$tf18_d_datasaida_ano,
                          true, 'text', $db_opcaoNaoMudar, ' onchange="js_validaGrade();"', '', '',
                          ' parent.js_validaGrade(); '
                        );
            ?>
          </td>
          <td nowrap title="<?=$Ttf18_c_horasaida?>" align="left">
            <?php
            echo $Ltf18_c_horasaida;
            if ($oParametros->tf11_i_utilizagradehorario == 1) {

              $db_opcaosaida = 3;
              if ($db_opcaoNaoMudar == 3) {
                $aX = array("$tf18_c_horasaida ## $tf18_c_localsaida ## $total" => $tf18_c_horasaida);
              } else {
                $aX = array('' => '');
              }
              db_select('tf18_c_horasaida', $aX, true, $db_opcao, " onchange=\"js_loadGridCgs();\"");

            } else {

              $db_opcaosaida = $db_opcao;
              db_input('tf18_c_horasaida', 10, $Itf18_c_horasaida, true, 'text', $db_opcaoNaoMudar,
                       ' onchange="js_validaGrade();"'.
                       "onKeyUp=\"mascara_hora(this.value, 'tf18_c_horasaida',  event);\" "
                      );

            }
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=$Ttf17_c_localsaida?>">
            <?=$Ltf17_c_localsaida?>
          </td>
          <td nowrap colspan="4">
            <?php
            db_input('tf17_c_localsaida', 64, $Itf17_c_localsaida, true, 'text', $db_opcaosaida, '');
            ?>
          </td>
        </tr>

        <tr>
          <td nowrap title="<?=$Ttf18_d_dataretorno?>">
            <?=$Ltf18_d_dataretorno?>
          </td>
          <td nowrap align="left">
            <?php
            db_inputdata('tf18_d_dataretorno', @$tf18_d_dataretorno_dia, @$tf18_d_dataretorno_mes, @$tf18_d_dataretorno_ano,
                          true, 'text', $db_opcao
                        );
            ?>
          </td>
          <td nowrap title="<?=$Ttf18_c_horaretorno?>" align="left">
            <?php
            echo $Ltf18_c_horaretorno;

            db_input('tf18_c_horaretorno', 10, $Itf18_c_horaretorno, true, 'text', $db_opcao,
                     "onKeyUp=\"mascara_hora(this.value, 'tf18_c_horaretorno',  event);\" "
                    );
            ?>
            &nbsp;&nbsp;
            <input name="retorno" type="button" id="retorno" value="Retorno" onclick="js_retorno();"
              <?=(isset($tf18_i_codigo) && !empty($tf18_i_codigo) ? '' : 'disabled')?>>
          </td>
        </tr>
      </table>

      <table>
        <tr>
          <td>
            <fieldset style = 'width: 92%;'>
              <?php
              if ($oParametros->tf11_i_utilizagradehorario == 2) {
                echo '<legend><b>Lotação do Veiculo</b></legend>';
              } else {
                echo '<legend><b>Lotação do Dia</b></legend>';
              }
              ?>
              <table border="0" width="90%">
                <tr>
                  <td nowrap>
                    <b>Total lugares: </b>
                  </td>
                  <td nowrap>
                    <?php
                    if (!isset($total)) {
                      $total = 0;
                    }
                    db_input('total', 1, "", true, 'text', 3, '');
                    ?>
                  </td>
                  <td nowrap>
                    <b> - Pacientes: </b>
                  </td>
                  <td nowrap >
                    <?php
                    if (!isset($numPac)) {
                      $numPac = 0;
                    }
                    db_input('numPac', 1, "", true, 'text', 3, '');
                    ?>
                  </td>
                  <td nowrap>
                    <b> - Acompanhantes: </b>
                  </td>
                  <td nowrap>
                    <?php
                    if (!isset($numAcomp)) {
                      $numAcomp = 0;
                    }
                    db_input('numAcomp', 1, "", true, 'text', 3, '');
                    ?>
                  </td>
                  <td nowrap>
                    <b> + Crianças de colo: </b>
                  </td>
                  <td nowrap>
                    <?php
                    if (!isset($numColo)) {
                      $numColo = 0;
                    }
                    db_input('numColo', 1, "", true, 'text', 3, '');
                    ?>
                  </td>
                  <td nowrap>
                    <b> = Lugares livres: </b>
                  </td>
                  <td nowrap>
                    <?php
                    if (!isset($livre)) {
                      $livre = 0;
                    }
                    db_input('livre', 1, "", true, 'text', 3, '');?>
                  </td>
                </tr>
              </table>
            </fieldset>
          </td>
        </tr>

        <tr>
          <td style="text-align:center">
            <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();">
            <input name="limpar" type="button" id="limpar" value="Limpar" onclick="js_limpar()">
          </td>
        </tr>

      </table>

    </fieldset>
  </div>

  <div class="container">
    <fieldset style='width: 1000px;'>
      <legend>Pacientes</legend>
      <div id='grid_pedidostfd' style='width: 100%;'></div>
      <input name="numero" id="numero" type="hidden" value="0">
      <input name="sPassageirosSelecionados" id="sPassageirosSelecionados" type="hidden" value="" >
      <input name="sPassageirosCGS" id="sPassageirosCGS" type="hidden" value="" >
    </fieldset>
    <input name="<?=( (isset($tf18_i_codigo)) && ($tf18_i_codigo != '') )? 'alterar' : 'confirmar' ?>" type="submit"
          id="confirmar" value="Confirmar" onclick="return js_montastr();">
    <input name="lista" type="button" id="lista" value="Lista DAER" onclick="js_listaDaer();"
      <?=(isset($tf18_i_codigo) && !empty($tf18_i_codigo) ? '' : 'disabled')?>>
  </div>

</form>
<script>

oDBGridPedidostfd = js_criaDataGrid();
sUrl = 'tfd4_pedidotfd.RPC.php';
$('tf18_d_datasaida').onblur = '';

<?php
if ((isset($tf18_i_codigo)) && ($tf18_i_codigo != '')) {
   echo 'js_validaGrade();';
}
?>

function js_ajax(oParam, jsRetorno) {

	var objAjax = new Ajax.Request(
                         sUrl,
                         {
                          method    : 'post',
                          asynchronous: false,
                          parameters: 'json='+Object.toJSON(oParam),
                          onComplete: function(objAjax) {
                          				var evlJS = jsRetorno+'(objAjax);';
                                  return eval(evlJS);
                          			}
                         }
                        );

}

/**** Bloco de funções botão retorno (início) */
function js_retorno() {

  sChave = '&tf31_i_veiculodestino='+$F('tf18_i_codigo');

  if($F('tf18_i_codigo') != '') {
    js_OpenJanelaIframe('', 'db_iframe_retorno', 'tfd4_tfd_passageiroretorno001.php?'+sChave, 'Retorno', true);
  }

}
/* Bloco de funções botão Retorno (fim) ****/



function js_listaDaer() {

 	if ($F('tf18_i_destino') == '') {

	  alert('Informe o destino.');
	  return false;

	}
	if ($F('tf18_i_veiculo') == '') {

	  alert('Informe o veículo.');
	  return false;

	}

	if ($F('tf18_d_datasaida') == '') {

	  alert('Informe a data de saída.');
	  return false;

	}

	if ($F('tf18_c_horasaida') == null || $F('tf18_c_horasaida') == '') {

	  alert('Informe a hora de saída');
	  return false;

	}

	iCodVeiculo = 'codveiculo='+$F('tf18_i_veiculo');
	iCodDestino = '&coddestino='+$F('tf18_i_destino');
	sDatasaida  = '&datasaida='+$F('tf18_d_datasaida');
	iHora       = '&hora='+$F('tf18_c_horasaida');

  sChavePesquisa = iCodVeiculo+iCodDestino+sDatasaida+iHora;

  if ($F('tf18_i_codigo') != undefined && $F('tf18_i_codigo') != '') {

    oJan = window.open('tfd2_listapassageirodaer002.php?'+sChavePesquisa, '',
                       'width='+(screen.availWidth-5)+',height='+
                       (screen.availHeight-40)+',scrollbars=1,location=0 '
                      );
    oJan.moveTo(0, 0);

  } else {

    alert('Código para geração do relatório não informado.');
    return false;

  }

}

function js_limparInformacoes() {

  oDBGridPedidostfd.clearAll(true);
  oDBGridPedidostfd.renderRows();

  $('numero').value             = 0;
  $('retorno').disabled         = true;
  $('lista').disabled           = true;
  $('confirmar').name           = 'confirmar';
  if($('tf18_i_veiculo').value != '' && <? echo $oParametros->tf11_i_utilizagradehorario; ?> != '2') {
	  $('total').value = 0;
	  $('numPac').value             = 0;
	  $('numAcomp').value           = 0;
	  $('numColo').value            = 0;
	  $('livre').value              = 0;
  }
  $('tf18_i_codigo').value      = '';
  $('tf18_d_dataretorno').value = '';
  $('tf18_c_horaretorno').value = '';

}

/**** Bloco de funções do grid início */

function js_criaDataGrid() {

  oDBGrid                = new DBGrid('grid_pedidostfd');
  oDBGrid.nameInstance   = 'oDBGridPedidostfd';
  oDBGrid.hasTotalizador = false;
  oDBGrid.setCellWidth(new Array('4%', '10%', '35%', '33%', '5%', '7%', '7%'));
  oDBGrid.setHeight(120);
  oDBGrid.allowSelectColumns(false);

  var aHeader = new Array();
  aHeader[0]  = 'TFD';
  aHeader[1]  = 'CGS';
  aHeader[2]  = 'Paciente';
  aHeader[3]  = 'Prestadora';
  aHeader[4]  = '<input type="button" id="marcarTodos" onclick="js_marcarTodos();" value="M">';
  aHeader[5]  = 'Fica';
  aHeader[6]  = 'Colo';
  oDBGrid.setHeader(aHeader);

  var aAligns = new Array();
  aAligns[0]  = 'center';
  aAligns[1]  = 'center';
  aAligns[2]  = 'left';
  aAligns[3]  = 'left';
  aAligns[4]  = 'center';
  aAligns[5]  = 'center';
  aAligns[6]  = 'center';

  oDBGrid.setCellAlign(aAligns);
  oDBGrid.show($('grid_pedidostfd'));
  oDBGrid.clearAll(true);

  return oDBGrid;

}

/****** fim bloco do Grid **/


/*
 * ==========================================================================
 * -> Percorre o Grid marcando ou desmarcando todos os Pacientes de uma vez
 * ==========================================================================
*/
function js_marcarTodos() {

  oElementos = document.getElementsByName('ckbox');
  if (document.getElementById('marcarTodos').value == 'M') {

    for(i = 0; i < oElementos.length; i++) {

      if (!oElementos[i].checked) {
        oElementos[i].click();
      }

    }
    document.getElementById('marcarTodos').value = 'D';

  } else {

    for(iCont = 0; iCont < oElementos.length; iCont++) {

      if (oElementos[iCont].checked) {
        oElementos[iCont].click();
      }

    }
    document.getElementById('marcarTodos').value = 'M';

  }

}

/*
 * ====================================================================
 * -> Função para abrir a func_veiculosalt
 *
 * -> A variavel iParam com o valor 1 deve ser setada para que a func
 *    retorne a capacidade do veiculo junto na função, pois sem ela
 *    o retorno traz apenas a placa.
 * ====================================================================
 */
function js_pesquisatf18_i_veiculo(mostra) {

  if (mostra == true) {

    js_OpenJanelaIframe('', 'db_iframe_veiculos', 'func_veiculosalt.php?funcao_js=parent.'+
                        'js_mostraveiculo1|ve01_codigo|ve01_quantcapacidad|ve01_placa', 'Pesquisa', true
                       );

  } else {

    if (document.form1.tf18_i_veiculo.value != '') {

      js_OpenJanelaIframe('', 'db_iframe_veiculos', 'func_veiculosalt.php?pesquisa_chave='+
                          document.form1.tf18_i_veiculo.value+'&funcao_js=parent.js_mostraveiculo&iParam=1',
                          'Pesquisa', false
                         );

    } else {

      document.form1.ve01_placa.value = '';
      js_limparInformacoes();

    }

  }

}

/*
 * ================================================================
 * -> Seta os valores do veiculo e tenta dar um load no Grid
 *    obs: o load só é efetuado se todas as variaveis necessarias
 *         para  a busca das informações estiverem setadas.
 * ================================================================
 */
function js_mostraveiculo(chave, capacidade, erro) {

  document.form1.ve01_placa.value = chave;
  if(<? echo $oParametros->tf11_i_utilizagradehorario; ?> == '2') {

    document.form1.total.value = capacidade;
    document.form1.livre.value = capacidade;
  }
  if (erro == true) {

    document.form1.tf18_i_veiculo.focus();
    document.form1.tf18_i_veiculo.value = '';
    js_limparInformacoes();

  } else {
    js_loadGridCgs();
  }

}

/*
 * ================================================================================
 * -> Seta os valores para veiculo e depois tenta dar um load no grid.
 *    obs: Se o total de pessoar a viajar não for gerenciado pela grade de horarios
 *         então a capacidade passa a ser gerenciada pela capacidade do veiculo.
 *
 *      tf11_i_utilizagradehorario = 1 (gerenciado pela grade de horarios)
 *      tf11_i_utilizagradehorario = 2 (gerenciado pela capacidade do veiculo)
 *
 * ================================================================================
 */
function js_mostraveiculo1(chave1, chave2, chave3) {

  js_limparInformacoes();
  document.form1.tf18_i_veiculo.value = chave1;
  if(<? echo $oParametros->tf11_i_utilizagradehorario; ?> == '2') {
    document.form1.total.value = chave2;
    document.form1.livre.value = chave2;
  }
  document.form1.ve01_placa.value     = chave3;
  db_iframe_veiculos.hide();
  js_loadGridCgs();

}

function js_pesquisatf18_i_motorista(mostra) {

  if (mostra == true) {

    js_OpenJanelaIframe('', 'db_iframe_veicmotoristas', 'func_veicmotoristasalt.php?'+
                        'funcao_js=parent.js_mostramotorista1|ve05_codigo|z01_nome',
                        'Pesquisa', true
                       );

  } else {

    if (document.form1.tf18_i_motorista.value != '') {

      js_OpenJanelaIframe('', 'db_iframe_veicmotoristas', 'func_veicmotoristasalt.php?pesquisa_chave='+
                          document.form1.tf18_i_motorista.value+'&funcao_js=parent.js_mostramotorista',
                          'Pesquisa', false
                         );

    } else {
      document.form1.z01_nome.value = '';
    }

  }

}

function js_mostramotorista(chave, erro) {

  document.form1.z01_nome.value = chave;
  if (erro == true) {

    document.form1.tf18_i_motorista.focus();
    document.form1.tf18_i_motorista.value = '';

  }

}

function js_mostramotorista1(chave1, chave2) {

  document.form1.tf18_i_motorista.value = chave1;
  document.form1.z01_nome.value         = chave2;
  db_iframe_veicmotoristas.hide();

}

function js_pesquisatf18_i_destino(mostra) {

  if (mostra == true) {

    js_OpenJanelaIframe('', 'db_iframe_destino', 'func_tfd_destino.php?funcao_js=parent.js_mostradestino1'+
                        '|tf03_i_codigo|tf03_c_descr&chave_validade=true', 'Pesquisa', true
                       );

  } else {

    if (document.form1.tf18_i_destino.value != '') {

      js_OpenJanelaIframe('', 'db_iframe_destino', 'func_tfd_destino.php?pesquisa_chave='+
                          document.form1.tf18_i_destino.value+'&funcao_js=parent.js_mostradestino&chave_validade=true',
                          'Pesquisa', false
                         );

    } else {

      document.form1.tf03_c_descr.value = '';
      js_limparInformacoes();

    }

  }

}

function js_mostradestino(chave, erro) {

  document.form1.tf03_c_descr.value = chave;
  if (erro == true) {

    document.form1.tf18_i_destino.focus();
    document.form1.tf18_i_destino.value = '';
    js_limparInformacoes();

  } else {

    js_loadGridCgs();

  }

}

function js_mostradestino1(chave1,chave2) {


  document.form1.tf18_i_destino.value = chave1;
  document.form1.tf03_c_descr.value   = chave2;
  db_iframe_destino.hide();
  js_loadGridCgs();

}


function js_validaGrade() {

  if (js_validaDbData($('tf18_d_datasaida'))) {

    if (<?
        if (isset($oParametros->tf11_i_utilizagradehorario) && !empty($oParametros->tf11_i_utilizagradehorario)) {
          echo $oParametros->tf11_i_utilizagradehorario;
        } else {
          echo '2';
        }
        ?> == 1 && <?=$db_opcaoNaoMudar?> != 3) {
      js_getHorariosData();

    } else {

      js_loadGridCgs();

    }

  } else {
    js_limparInformacoes();
  }

}

function js_getHorariosData() {

  if ($F('tf18_d_datasaida') == '') {
    return false;
  }
  if ($F('tf18_i_destino') == '') {
    return false;
  }


  var oParam      = new Object();
  oParam.exec     = 'getHorariosData';
  oParam.dData    = $F('tf18_d_datasaida');
  oParam.iDestino = $F('tf18_i_destino');
  js_ajax(oParam, 'js_retornogetHorariosData');

}

function js_retornogetHorariosData(oRetorno) {

  iTam = $('tf18_c_horasaida').options.length;
  for (iCont = 0; iCont < iTam; iCont++) { // for para remover todos os options

    $('tf18_c_horasaida').options[0] = null;

  }

  oRetorno = eval("("+oRetorno.responseText+")");

  if (oRetorno.iStatus == 1) {

    iCont = 0;
    oRetorno.oHorarios.each(
    function (oHorario) {

       $('tf18_c_horasaida').options[iCont] = new Option(oHorario.sHora.urlDecode(), oHorario.sHora.urlDecode()+
                                                         ' ## '+oHorario.sLocalSaida.urlDecode()+' ## '+
                                                         oHorario.iLotacao
                                                        );
       iCont++;

    }
                           );

    js_loadGridCgs();
    js_selecionaHorario();
    js_localSaida();

  } else {
    alert('Nao foi possível encontrar horários de saída para a data indicada.');
  }

}

function js_selecionaHorario() {

  oSel = $('tf18_c_horasaida');
  for (iCont = 0; iCont < oSel.length; iCont++) {

    if (oSel.options[iCont].innerHTML ==
        '<?=isset($tf18_c_horasaida) && !empty($tf18_c_horasaida) ? $tf18_c_horasaida : -1?>') {

      oSel.options[iCont].selected = true;
      break;

    }

  }

}

function js_localSaida() {

  aLocal = $F('tf18_c_horasaida').split(' ## ');
  if (<?echo $oParametros->tf11_i_utilizagradehorario; ?> == '1') {
    $('tf18_c_localsaida').value = aLocal[1];
    document.form1.total.value   = aLocal[2];
  }
  document.form1.livre.value   = parseInt(document.form1.total.value, 10) -
                                 (parseInt(document.form1.numAcomp.value, 10) +
                                  parseInt(document.form1.numPac.value, 10)) +
                                  parseInt(document.form1.numColo.value, 10);


}

function js_loadGridCgs() {

	iCodigo        = $F('tf18_i_codigo');
  js_limparInformacoes();

  if ($F('tf18_i_veiculo') == '') {
    return false;
  }
  if ($F('tf18_i_destino') == '') {
    return false;
  }
  if ($F('tf18_d_datasaida') == '') {
    return false;
  }
  if ($F('tf18_c_horasaida') == '') {
    return false;
  }

  var oParam     = new Object();
  oParam.exec    = 'getCgsDataSaida';
  aVet           = $F('tf18_d_datasaida').split('/');
  oParam.sData   = aVet[2]+'-'+aVet[1]+'-'+aVet[0];
  oParam.iCodigo = iCodigo;

  <?
  if ($oParametros->tf11_i_utilizagradehorario == 1) {
  ?>
    oParam.sHora = $('tf18_c_horasaida').options[$('tf18_c_horasaida').selectedIndex].text;
  <?
  } else {
  ?>
    oParam.sHora = $F('tf18_c_horasaida');
  <?
  }
  ?>
  oParam.iDestino = $F('tf18_i_destino');
  oParam.iVeiculo = $F('tf18_i_veiculo');

  js_ajax(oParam, 'js_retornoGridCgs');

}

function js_retornoGridCgs(oRetorno) {

  oRetorno = eval("("+oRetorno.responseText+")");
  if (oRetorno.iStatus == 1) {

    for(iCont = 0; iCont < oRetorno.aListaCgs. length; iCont++) {

       var aLinha = new Array();
       aLinha[0]  = oRetorno.aListaCgs[iCont].tf01_i_codigo;
       aLinha[1]  = oRetorno.aListaCgs[iCont].z01_i_cgsund;
       aLinha[2]  = oRetorno.aListaCgs[iCont].z01_v_nome;
       aLinha[3]  = oRetorno.aListaCgs[iCont].z01_nome;

       sChecado   = oRetorno.aListaCgs[iCont].vinculado == 1 ? 'checked' : '';
       sDisabled  = oRetorno.aListaCgs[iCont].vinculado == 1 ? '' : ' disabled';

       aLinha[4]  = '<input type="checkbox" name="ckbox" id="check'+iCont+'" ';
       aLinha[4] += ' value="'+oRetorno.aListaCgs[iCont].tipo+'##';
       aLinha[4] += oRetorno.aListaCgs[iCont].tf01_i_codigo+'##'+oRetorno.aListaCgs[iCont].z01_i_cgsund;
       aLinha[4] += '##'+iCont+'" ';
       aLinha[4] += ' onclick="js_calcmarcar(this, '+oRetorno.aListaCgs[iCont].tipo+')" '+sChecado+' >';

       sChecado   = oRetorno.aListaCgs[iCont].tf19_i_fica == 1 ? 'checked' : '';

       aLinha[5]  = '<input type="checkbox" name="ckboxfica" id="checkfica'+iCont+'" ';
       aLinha[5] += ' value="'+oRetorno.aListaCgs[iCont].z01_i_cgsund+'" '+sChecado+sDisabled+'>';

       sChecado   = oRetorno.aListaCgs[iCont].tf19_i_colo == 1 ? 'checked' : '';

       aLinha[6]  = '<input type="checkbox" name="ckboxcolo" id="checkcolo'+iCont+'" ';
       aLinha[6] += ' value="'+oRetorno.aListaCgs[iCont].z01_i_cgsund+'" ';
       aLinha[6] += ' onclick="js_calcmarcar2(this, '+oRetorno.aListaCgs[iCont].tipo+')" '+sChecado+sDisabled+' >';

       oDBGridPedidostfd.addRow(aLinha);

    }

    document.form1.numero.value = oRetorno.aListaCgs.length;
    oDBGridPedidostfd.renderRows();

    // Se trouxe passageiros vinculados a algum veículo, entra em modo alteração
    if (oRetorno.iVeiculoDestino != '') {

      $('tf18_i_codigo').value      = oRetorno.iVeiculoDestino;
      $('tf18_d_dataretorno').value = oRetorno.dDataRetorno;
      $('tf18_c_horaretorno').value = oRetorno.sHoraRetorno;
      $('confirmar').name           = 'alterar';
      $('retorno').disabled         = false;
      $('lista').disabled           = false;

    }
    js_getLotacaoDataHora();
    js_localSaida();

  } else {

    oDBGridPedidostfd.clearAll(true);
    oDBGridPedidostfd.renderRows();
    <?
      if ($oParametros->tf11_i_utilizagradehorario != 2 && isset($tf18_i_veiculo) && $tf18_i_veiculo ==  "") {
        echo "document.form1.total.value    = 0;";
        echo "document.form1.livre.value    = 0;";
        echo "$('numero').value             = 0;";
        echo "document.form1.numAcomp.value = 0;";
        echo "document.form1.numPac.value   = 0;";
      }
    ?>
    alert('Nenhum CGS encontrado.');

  }

}

function js_getLotacaoDataHora() {

  if ($F('tf18_i_veiculo') == '') {
    return false;
  }
  if ($F('tf18_i_destino') == '') {
    return false;
  }
  if ($F('tf18_d_datasaida') == '') {
    return false;
  }
  if ($F('tf18_c_horasaida') == '') {
    return false;
  }
  var oParam      = new Object();
  oParam.exec     = "getLotacaoDataHora";
  aVet            = $F('tf18_d_datasaida').split('/');
  oParam.sData    = aVet[2]+'-'+aVet[1]+'-'+aVet[0];
  if (<?echo $oParametros->tf11_i_utilizagradehorario;?> == '1') {
    oParam.sHora = $('tf18_c_horasaida').options[$('tf18_c_horasaida').selectedIndex].text;
  } else {
	oParam.sHora = $('tf18_c_horasaida').value;
  }
  oParam.iDestino = $F('tf18_i_destino');
  oParam.iVeiculo = $F('tf18_i_veiculo');
  js_ajax(oParam, 'js_retornogetLotacaoDataHora');
}

function js_retornogetLotacaoDataHora(oRetorno) {

  oRetorno = eval("("+oRetorno.responseText+")");
  if (oRetorno.iStatus == 1) {

    document.form1.numAcomp.value = oRetorno.iAcomp;
    document.form1.numPac.value   = oRetorno.iPac;
    document.form1.numColo.value  = oRetorno.iColo;

  } else {

    document.form1.numAcomp.value = 0;
    document.form1.numPac.value   = 0;

  }
}


function js_calcmarcar(marca, tipo) {

  // numero do id dos checkbox fica e colo
  sId = marca.value.split('##')[3];
  if (marca.checked == true) {

    if (document.form1.livre.value == 0) {

      marca.checked = false;
      alert('Não hà mais lugares disponíveis!');
      return false;

    }
    if (tipo == 1) {
      document.form1.numPac.value++;
    } else {
      document.form1.numAcomp.value++;
    }

    document.form1.livre.value--;
    $('checkfica'+sId).disabled = false;
    $('checkcolo'+sId).disabled = false;

  } else {

    if (tipo == 1) {
      document.form1.numPac.value--;
    } else {
      document.form1.numAcomp.value--;
    }

    if ($('checkcolo'+sId).checked) {
      document.form1.numColo.value--;
    } else {
      document.form1.livre.value++;
    }

    $('checkfica'+sId).checked  = false;
    $('checkcolo'+sId).checked  = false;
    $('checkfica'+sId).disabled = true;
    $('checkcolo'+sId).disabled = true;

  }

}

function js_calcmarcar2(marca, tipo) {

  if (marca.checked == false) {

    if (document.form1.livre.value == 0) {

      marca.checked = true;
      alert('Não hà mais lugares disponíveis!');
      return false;

    }
    document.form1.numColo.value--;
    document.form1.livre.value--;

  } else {

    document.form1.numColo.value++;
    document.form1.livre.value++;

  }

}

function js_validaData() {

  aIni = document.form1.tf18_d_datasaida.value.split('/');
  aFim = document.form1.tf18_d_dataretorno.value.split('/');
  dIni = new Date(aIni[2], aIni[1], aIni[0]);
  dFim = new Date(aFim[2], aFim[1], aFim[0]);

	if(dFim < dIni) {

	  alert('Data de retorno não pode ser menor que a data de saída.');
	  document.form1.tf18_d_dataretorno.value = '';
		document.form1.tf18_d_dataretorno.focus();
	  return false;

	}

  if (aIni[0] == aFim[0] && aIni[1] == aFim[1] && aIni[2] == aFim[2]) {

    aHoraIni = $F('tf18_c_horasaida').split(' ## ')[0];
    aHoraIni = aHoraIni.split(':');
    aHoraFim = $F('tf18_c_horaretorno').split(':');

    if (parseInt(aHoraFim[0], 10) < parseInt(aHoraIni[0], 10)) {

      alert('Hora de retorno não pode ser menor que a hora de saída.');
      return false;

    } else if (parseInt(aHoraFim[0], 10) == parseInt(aHoraIni[0], 10)) {

      if (parseInt(aHoraFim[1], 10) < parseInt(aHoraIni[1], 10)) {

        alert('Hora de retorno não pode ser menor que a hora de saída.');
        return false;

      }

    }

  }

  return true;

}

function js_montastr() {

  if ($F('tf18_i_veiculo') == '') {

    alert('Informe o veículo.');
    return false;

  }

  if ($F('tf18_i_destino') == '') {

    alert('Informe o destino.');
    return false;

  }

  if ($F('tf18_d_datasaida') == '') {

    alert('Informe a data de saída');
    return false;

  }

  if ($F('tf18_c_horasaida') == '' || $F('tf18_c_horasaida') == null) {

    alert('Informe a hora de saída');
    return false;

  }

  if ($F('tf18_d_dataretorno') == '') {

    alert('Informe a data de retorno');
    return false;

  }

  if ($F('tf18_c_horaretorno') == '' || $F('tf18_c_horaretorno') == null) {

    alert('Informe a hora de retorno');
    return false;

  }

  if (!js_validaData()) {
    return false;
  }

  var iContChecked = 0;
  iTam             = document.form1.numero.value;
  if (iTam > 0) {

    var sPassageirosSelecionados  = '';
    var sPassageirosCGS           = '';
    var sSep                      = '';
    var iFica                     = '';
    var iColo                     = '';

    for (iCont = 0; iCont < iTam; iCont++) {

      if (document.getElementById("check"+iCont).checked) {

        aValor = $('check'+iCont).value.split('##');
        iFica  = $('checkfica'+iCont).checked ? 1 : 2;
        iColo  = $('checkcolo'+iCont).checked ? 1 : 2;
        /*
        A string dos passageiros selecionados é disposta da seguinte forma:
          CGS,TFD,TIPO,FICA,COLO#CGS,TFD,TIPO,FICA,COLO...
        */
        sPassageirosSelecionados += sSep+aValor[2]+','+aValor[1]+','+aValor[0]+','+iFica+','+iColo;
        sPassageirosCGS  += sSep+aValor[2];
        sSep              = '#';
        iContChecked++;

      }

    }
    if (iContChecked == 0) {

      alert('Selecione passageiro! ');
      return false;

    }

    document.form1.sPassageirosSelecionados.value  = sPassageirosSelecionados;
    document.form1.sPassageirosCGS.value           = sPassageirosCGS;

  } else {

    alert('Selecione passageiro! ');
    return false;

  }

  return true;

}

function js_limpar() {
  location.href = 'tfd4_indveiculo001.php';
}

function js_pesquisa() {

  js_OpenJanelaIframe('', 'db_iframe_tfd_veiculodestino', 'func_tfd_veiculodestino.php?funcao_js='+
                      'parent.js_preenchepesquisa|tf18_i_codigo', 'Pesquisa', true
                     );

}

function js_preenchepesquisa(chave) {

  db_iframe_tfd_veiculodestino.hide();
  <?
  echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?tf18_i_codigo='+chave";
  ?>

}

</script>