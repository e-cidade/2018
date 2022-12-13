<?
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

$oDaoTfdAgendaSaida->rotulo->label();
$oDaoTfdVeiculoDestino->rotulo->label();
$oRotulo = new rotulocampo;
$oRotulo->label('tf01_i_cgsund');
$oRotulo->label('z01_v_nome');
$oRotulo->label('tf25_i_destino');
$oRotulo->label('tf02_i_lotacao');
$oRotulo->label('tf03_c_descr');
$oRotulo->label('tf10_i_prestadora');
$oRotulo->label('z01_nome');
$oRotulo->label("tf29_i_prontuario");
$oRotulo->label("tf30_i_encaminhamento");
$oRotulo->label("tf01_i_cgsund");
$oRotulo->label("s115_c_cartaosus");
$oRotulo->label("j13_codi");
$oRotulo->label("ve61_veicmotoristas");
$oRotulo->label("ve01_placa");
?>
<form name="form1" method="post" action="">
  <center>
    <table border="0" style="width: 98%;">
      <tr style="display: none;">
        <td nowrap title="<?=@$Ttf17_i_codigo?>">
          <?=@$Ltf17_i_codigo?>
        </td>
        <td> 
          <?
          db_input('tf17_i_codigo', 10, $Itf17_i_codigo, true, 'text', 3, '')
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Ttf17_i_pedidotfd?>">
          <?=@$Ltf17_i_pedidotfd?>
        </td>
        <td> 
          <?
          db_input('tf17_i_pedidotfd', 10, $Itf17_i_pedidotfd, true, 'text', 3, '');
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Ttf01_i_cgsund?>">
          <b>Paciente:</b>
        </td>
        <td nowrap> 
          <?
          db_input('tf01_i_cgsund', 10, $Itf01_i_cgsund, true, 'text', 3, '');
          db_input('z01_v_nome', 50, $Iz01_v_nome, true, 'text', 3, '');
          db_input('z01_v_ident', 10, @$Iz01_v_ident, true, 'hidden', $db_opcao, "");
          db_input('z01_v_cgccpf', 10, @$Iz01_v_cgccpf, true, 'hidden', $db_opcao, "");
          db_input('tf11_i_utilizagradehorario', 10, @$tf11_i_utilizagradehorario, true, 'hidden', $db_opcao, "");
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Ttf25_i_destino?>">
          <?=@$Ltf25_i_destino?>
        </td>
        <td> 
          <?
          db_input('tf25_i_destino', 10, $Itf25_i_destino, true, 'text', 3, '');
          db_input('tf03_c_descr', 50, $Itf03_c_descr, true, 'text', 3, '');
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Ttf10_i_prestadora?>">
          <?=@$Ltf10_i_prestadora?>
        </td>
        <td> 
          <?
          db_input('tf10_i_prestadora', 10, $Itf10_i_prestadora, true, 'text', 3, '');
          db_input('z01_nome', 50, $Iz01_nome, true, 'text', 3, '');
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Ttf16_d_dataagendamento?>">
          <b>Agendamento:</b>
        </td>
        <td> 
          <?
          db_inputdata('tf16_d_dataagendamento', @$tf16_d_dataagendamento_dia, @$tf16_d_dataagendamento_mes, 
                       @$tf16_d_dataagendamento_ano, true, 'text', 3, ''
                      );
          db_input('tf16_c_horaagendamento', 10, $Iz01_nome, true, 'text', 3, '');
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap colspan="2">
          <fieldset style='width: 98%;'> <legend><b>Saída</b></legend>
            <table border="0">
              <tr>
                <td nowrap title="<?=@$Ttf17_d_datasaida?>">
                  <?=@$Ltf17_d_datasaida?>
                </td>
                <td nowrap> 
                  <?
                  db_inputdata('tf17_d_datasaida', @$tf17_d_datasaida_dia, @$tf17_d_datasaida_mes, @$tf17_d_datasaida_ano, 
                               true, 'text', $db_opcao, ' onchange="js_validaGrade();"', '', '', 'parent.js_validaGrade();'
                              );
                  ?>
                </td>
                <td nowrap title="<?=@$Ttf17_c_horasaida?>" align="left">
                  <?=@$Ltf17_c_horasaida?>
                  <?
                  if($tf11_i_utilizagradehorario == 1 && $db_opcao != 3) { // utiliza a grade de horário
                
                    $db_opcaosaida = 3;
                    $aX = array(''=>'');
                    db_select('tf17_c_horasaida', $aX, true, $db_opcao, " onchange=\"js_localSaida();\"");            

                  } else {
                     
                    $db_opcaosaida = $db_opcao;
                    db_input('tf17_c_horasaida', 5, $Itf17_c_horasaida, true, 'text', $db_opcao,
                             'onKeyUp="mascara_hora(this.value,\'tf17_c_horasaida\', event)"');
                     
                  }
                  ?>
                </td>
              </tr>
              <tr>
                <td nowrap title="<?=@$Ttf17_c_localsaida?>">
                  <?=@$Ltf17_c_localsaida?>
                </td>
                <td colspan="3"> 
                   <?
                   db_input('tf17_c_localsaida', 66, $Itf17_c_localsaida, true, 'text', $db_opcaosaida, "");
                   ?>
                </td>
              </tr>
              <tr  style=" <? echo ($db_indicarVeiculo == 1 ? '' : 'display: none;'); ?>">
                <td nowrap title="<?=@$Ttf18_d_dataretorno?>">
                  <b>Data retorno:</b>
                </td>
                <td nowrap align="left"> 
                  <?
                  db_inputdata('tf18_d_dataretorno', @$tf18_d_dataretorno_dia, @$tf18_d_dataretorno_mes, @$tf18_d_dataretorno_ano, 
                               true, 'text', 1
                              );
                  ?>
                </td>
                <td nowrap title="<?=@$Ttf18_c_horaretorno?>" align="left">
                  <b> Hora retorno: </b>
                  <?        
                  db_input('tf18_c_horaretorno', 6, @$Itf18_c_horaretorno, true, 'text', 1,
                           "onKeyUp=\"mascara_hora(this.value, 'tf18_c_horaretorno',  event);\" "
                          );
                  ?>
                </td>
              </tr>
            </table> 
            <!-- 
                PARTE INCOPORADA AO FORMULÁRIO PARA TORNAR POSSÍVEL 
                           A INDICAÇÃO DE UM VEICULO.
                           
            -->
            <fieldset style=" <? echo ($db_indicarVeiculo == 1 ? '' : 'display: none;'); ?>">
              <legend><b> Veículo </b></legend>
              <table border="0" width="98%">
                <tr>
                  <td>
                    <?
                    db_input('tf18_i_codigo', 10, @$Itf18_i_codigo, true, 'hidden', 3, "");
                    db_ancora(@$Ltf18_i_veiculo, "js_pesquisatf18_i_veiculo(true);", 1);
                    ?>
                  </td>
                  <td nowrap colspan="4"> 
                    <?
                    db_input('tf18_i_veiculo', 10, @$Itf18_i_veiculo, true, 'text', 1, 
                             " onchange='js_pesquisatf18_i_veiculo(false);'"
                            );
                    db_input('ve01_placa', 53, @$Ive01_placa, true, 'text', 3, '');                       
                    ?>
                  </td>
                </tr>
                <tr>
                  <td nowrap title="<?=@$Tve61_veicmotoristas?>">
                    <?
                    db_ancora(@$Ltf18_i_motorista, "js_pesquisatf18_i_motorista(true);", 1);
                    ?>
                  </td>
                  <td  nowrap colspan="4"> 
                    <?
                    db_input('tf18_i_motorista', 10, @$Itf18_i_motorista, true, 'text', 1, 
                             " onchange='js_pesquisatf18_i_motorista(false);'"
                            );
                    db_input('motorista_nome', 53, @$Iz01_nome, true, 'text', 3, '');
                    ?>
                  </td>
                </tr>
              </table>
              <!-- 
                 FIELDSET COM OS DADOS DE LOTAÇÃO
              -->
              <center>
                <fieldset align="center" style='width: 60%;'>
                  <?
                  if ($tf11_i_utilizagradehorario == 2) { 
                    echo '<legend><b>Lotação do Veiculo</b></legend>';
                  } else {
                    echo '<legend><b>Lotação do Dia</b></legend>';  
                  }      
                  ?>
                  <table>
                    <tr>
                      <td nowrap>
                        <b>Total lugares: </b>
                      </td>
                      <td nowrap>
                        <?
                        if (!isset($total)) {
                          $total = 0;
                        }
                        db_input('total', 2, "", true, 'text', 3, '');
                        ?>
                      </td>
                      <td nowrap>
                        <b> - Pacientes: </b>
                      </td>
                      <td nowrap >
                        <?
                        if (!isset($numPac)) {
                          $numPac = 0;
                        } 
                        db_input('numPac', 2, "", true, 'text', 3, '');
                        ?>
                      </td> 
                      <td nowrap>
                        <b> - Acompanhantes: </b>
                      </td>
                      <td nowrap>
                        <?
                        if (!isset($numAcomp)) {
                          $numAcomp = 0;
                        }
                        db_input('numAcomp', 2, "", true, 'text', 3, '');
                        ?>
                      </td>
                      <td nowrap>
                        <b> + Crianças de colo: </b> 
                      </td>
                      <td nowrap>
                        <?
                        if (!isset($numColo)) {
                          $numColo = 0;
                        }
                        db_input('numColo', 2, "", true, 'text', 3, '');
                        ?>
                      </td>
                      <td nowrap>
                        <b> = Lugares livres: </b>
                      </td>
                      <td nowrap>
                        <?
                        if (!isset($livre)) {
                          $livre = 0;
                        }
                        db_input('livre', 2, "", true, 'text', 3, '');
                        ?>
                      </td>
                    </tr>                  
                  </table>
                </fieldset>
                <fieldset style='width: 97%; margin: 0px; padding: 0px; padding-bottom: 4px;'>
                  <legend><b>Pacientes</b></legend> 
                  <table border="0" style='width:98%;'>
                    <tr>
                      <td>      
                        <div id='grid_pedidostfd' style='width: 100%;'></div>
                        <input name="numero" id="numero" type="hidden" value="0">
                        <input name="sPassageirosSelecionados" id="sPassageirosSelecionados" type="hidden" value="">
                        <input name="sPassageirosCGS" id="sPassageirosCGS" type="hidden" value="" >
                        <?
                        db_input("lAlterarSaida", 10, @$IlAlterarSaida, true, 'hidden', 3, "");
                        ?>
                      </td>
                    </tr>
                  </table>
                </fieldset>  
              </center>
            </fieldset>
          </fieldset>
        </td>
      </tr>
    </table>
  </center>
  <input name="<?=($db_opcao==1?"incluir":"alterar")?>" type="submit" 
         id="db_opcao" value="<?=($db_opcao==1?"Incluir":"Alterar")?>" 
         <?=($db_botao==false?"disabled":"")?>  onclick="return js_validaEnvio();">
  <?
    if($db_opcao == 2 && $lAlterarSaida == 1) {
  ?>
      <input name="excluir" type="submit" id="excluir" value="Excluir" 
             onclick="return confirm('Deseja excluir este agendamento de saída?');">
  <?    
    }
  ?>
  <input name="fechar" type="button" id="fechar" value="Fechar" onclick="parent.db_iframe_saida.hide();">
</form>

<script>
oDBGridPedidostfd            = js_criaDataGrid();
var lCorrenteIncluido        = 0;
$('tf17_d_datasaida').onblur = '';
var sUrl                     = 'tfd4_pedidotfd.RPC.php';

if ($('tf18_i_veiculo').value != "") {
  js_pesquisatf18_i_veiculo(false);
}
if ($('tf18_i_motorista').value != "") {
  js_pesquisatf18_i_motorista(false);
}
if ($('tf17_d_datasaida').value != '' 
	&& $('tf11_i_utilizagradehorario').value == '1') {

  if (<?=$db_opcao?> != 3) { 

    js_getHorariosData();
	$('total').value = $F('tf17_c_horasaida').split(' ## ')[2];
    $('livre').value = $('total').value;
		
  }

}
/*
 *========================================================
 *              BLOCO DE FUNÇÕES DO GRID      
 *========================================================
 */
function js_criaDataGrid() {

  oDBGrid                = new DBGrid('grid_pedidostfd');
  oDBGrid.nameInstance   = 'oDBGridPedidostfd';
  oDBGrid.hasTotalizador = false;
  oDBGrid.setCellWidth(new Array('10%', '10%', '40%', '10%', '15%', '30%','10%'));
  oDBGrid.setHeight(70);
  oDBGrid.allowSelectColumns(false);

  var aHeader = new Array();
  aHeader[0]  = 'TFD';
  aHeader[1]  = 'CGS';
  aHeader[2]  = 'Paciente';
  aHeader[3]  = 'RG';
  aHeader[4]  = 'CPF';
  aHeader[5]  = 'Prestadora';
  aHeader[6]  = '<input type="button" id="marcarTodos" onclick="js_marcarTodos();" value="M">';
  aHeader[7]  = 'Fica';
  aHeader[8]  = 'Colo';
  oDBGrid.setHeader(aHeader);

  var aAligns = new Array();
  aAligns[0]  = 'center';
  aAligns[1]  = 'center';
  aAligns[2]  = 'left';
  aAligns[3]  = 'center';
  aAligns[4]  = 'center';
  aAligns[5]  = 'left';
  aAligns[6]  = 'center';
  aAligns[7]  = 'center';
  aAligns[8]  = 'center';
  
  oDBGrid.setCellAlign(aAligns);
  oDBGrid.show($('grid_pedidostfd'));
  oDBGrid.clearAll(true);

  return oDBGrid;

}

function js_marcarTodos() {
    
  oElementos = document.getElementsByName('ckbox');
  if (document.getElementById('marcarTodos').value == 'M') {

    for (i = 0; i < oElementos.length; i++) {
       
    if (!oElementos[i].checked) {
      oElementos[i].click();
    }

  }
  document.getElementById('marcarTodos').value = 'D';

  } else {
   
    for (iCont = 0; iCont < oElementos.length; iCont++) {
       
      if (oElementos[iCont].checked) {
        oElementos[iCont].click();
      }
    
    }
    document.getElementById('marcarTodos').value = 'M';

  }

}
    
/*  
 * ==================================================
 *     BLOCO DE FUNÇÕES PARA PROCURAR VEÍCULO
 * ==================================================
 */
function js_pesquisatf18_i_veiculo(lMostra) {

  if (lMostra == true) {

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

function js_mostraveiculo(sPlaca, iCapacidade, lErro) {
	
  document.form1.ve01_placa.value = sPlaca;
  if ($('tf11_i_utilizagradehorario').value == '2') {

    document.form1.total.value = iCapacidade; 
    document.form1.livre.value = iCapacidade; 

  }
  if (lErro == true) {
    
    document.form1.tf18_i_veiculo.focus(); 
    document.form1.tf18_i_veiculo.value = ''; 
    js_limparInformacoes();

  } else {
    js_loadGridCgs();
  }

}

function js_mostraveiculo1(iVeiculo, iCapacidade, sPlaca) {

  document.form1.tf18_i_veiculo.value = iVeiculo;
  if($('tf11_i_utilizagradehorario').value == '2') {

    document.form1.total.value = iCapacidade;
    document.form1.livre.value = iCapacidade;

  }
  document.form1.ve01_placa.value = sPlaca;
  db_iframe_veiculos.hide();
  js_loadGridCgs();

}

/*
 * ==============================================
 *   BLOCO DE FUNÇÕES PARA PROCURAR MOTORISTA
 * ==============================================
 */
function js_pesquisatf18_i_motorista(lMostra) {

  if (lMostra == true) {

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
      document.form1.motorista_nome.value = ''; 
    }

  }

}

function js_mostramotorista(sNomeMotorista, sErro) {

  document.form1.motorista_nome.value = sNomeMotorista; 
  if (sErro == true) {

    document.form1.tf18_i_motorista.focus(); 
    document.form1.tf18_i_motorista.value = '';

  }
  
}

function js_mostramotorista1(iCodigoMotorista, sNomeMotorista) {

  document.form1.tf18_i_motorista.value = iCodigoMotorista;
  document.form1.motorista_nome.value   = sNomeMotorista;
  db_iframe_veicmotoristas.hide();
  
}

/*
 * ===============================================================
 *   BLOCO DE FUNÇÕES DE RECEPÇÃO DE INFORMAÇÕES DA VIAGEM TFD
 * ===============================================================
 */
function js_loadGridCgs() {

	iCodigo        = $F('tf18_i_codigo');
  js_limparInformacoes();
  if ($F('tf18_i_veiculo') == '') {
    return false;
  }
  if ($F('tf25_i_destino') == '') {
    return false;
  }
  if ($F('tf17_d_datasaida') == '') {
    return false;
  }
  if ($('tf17_c_horasaida').type != 'text' && $F('tf17_c_horasaida')[0] == '') {
    return false;
  } else if ($('tf17_c_horasaida').type == 'text' && $F('tf17_c_horasaida') == '') {
    return false;
  }
  var oParam   = new Object();
  oParam.exec  = 'getCgsDataSaida';
  aVet         = $F('tf17_d_datasaida').split('/');
  oParam.sData = aVet[2]+'-'+aVet[1]+'-'+aVet[0];
  oParam.iCodigo = iCodigo;
  if ($('tf17_c_horasaida').type != 'text') {
    oParam.sHora = $('tf17_c_horasaida').options[$('tf17_c_horasaida').selectedIndex].text;
  } else {
    oParam.sHora = $F('tf17_c_horasaida');
  }
  oParam.iDestino = $F('tf25_i_destino');
  oParam.iVeiculo = $F('tf18_i_veiculo');
  js_ajax(oParam, 'js_retornoGridCgs');

}

function js_retornoGridCgs(oRetorno) {

  oRetorno = eval("(" + oRetorno.responseText + ")");
  if (oRetorno.iStatus == 1) {

    for (iCont = 0; iCont < oRetorno.aListaCgs.length; iCont++) {

      var aLinha = new Array();
      aLinha[0]  = oRetorno.aListaCgs[iCont].tf01_i_codigo;
      aLinha[1]  = oRetorno.aListaCgs[iCont].z01_i_cgsund;
      if (aLinha[1] == $('tf01_i_cgsund').value) {
        lCorrenteIncluido = 1;
      }
      aLinha[2]  = oRetorno.aListaCgs[iCont].z01_v_nome;
      aLinha[3]  = oRetorno.aListaCgs[iCont].z01_v_ident;
      aLinha[4]  = oRetorno.aListaCgs[iCont].z01_v_cgccpf;
      aLinha[5]  = oRetorno.aListaCgs[iCont].z01_nome;
  
      sChecado   = oRetorno.aListaCgs[iCont].vinculado == 1 ? 'checked' : '';
      sDisabled  = oRetorno.aListaCgs[iCont].vinculado == 1 ? '' : ' disabled';

      aLinha[6]  = '<input type="checkbox" name="ckbox" id="check'+(iCont+1)+'" ';
      aLinha[6] += ' value="'+oRetorno.aListaCgs[iCont].tipo+'##';
      aLinha[6] += oRetorno.aListaCgs[iCont].tf01_i_codigo+'##'+oRetorno.aListaCgs[iCont].z01_i_cgsund;
      aLinha[6] += '##'+(iCont + 1)+'" ';
      aLinha[6] += ' onclick="js_calcmarcar(this, '+oRetorno.aListaCgs[iCont].tipo+')" '+sChecado+' >';

      sChecado   = oRetorno.aListaCgs[iCont].tf19_i_fica == 1 ? 'checked' : '';

      aLinha[7]  = '<input type="checkbox" name="ckboxfica" id="checkfica'+(iCont + 1)+'" ';
      aLinha[7] += ' value="'+oRetorno.aListaCgs[iCont].z01_i_cgsund+'" '+sChecado+sDisabled+'>';

      sChecado   = oRetorno.aListaCgs[iCont].tf19_i_colo == 1 ? 'checked' : '';

      aLinha[8]  = '<input type="checkbox" name="ckboxcolo" id="checkcolo'+ (iCont + 1) +'" ';
      aLinha[8] += ' value="'+oRetorno.aListaCgs[iCont].z01_i_cgsund+'" ';
      aLinha[8] += ' onclick="js_calcmarcar2(this, '+oRetorno.aListaCgs[iCont].tipo+')" '+sChecado+sDisabled+' >';

      oDBGridPedidostfd.addRow(aLinha);

    }
    if (lCorrenteIncluido == 0) {
   
      js_incluirPacienteCorrente();
      document.form1.numero.value = oRetorno.aListaCgs.length + 1;
      
    } else {
      document.form1.numero.value = oRetorno.aListaCgs.length;
    }
    oDBGridPedidostfd.renderRows();
    /* Se trouxe passageiros vinculados a algum veículo, entra em modo alteração */
    if (oRetorno.iVeiculoDestino != '') {
       
      $('tf18_i_codigo').value      = oRetorno.iVeiculoDestino;
      $('tf18_d_dataretorno').value = oRetorno.dDataRetorno;
      $('tf18_c_horaretorno').value = oRetorno.sHoraRetorno;

    }
    js_getLotacaoDataHora();
    js_localSaida();

  } else {
    
    oDBGridPedidostfd.clearAll(true);
    js_incluirPacienteCorrente();
    document.form1.numero.value = 1;
    oDBGridPedidostfd.renderRows();
  
  }

}

function js_incluirPacienteCorrente() {

  var aLinha = new Array();
  aLinha[0]  = $('tf17_i_pedidotfd').value;
  aLinha[1]  = $('tf01_i_cgsund').value;
  aLinha[2]  = $('z01_v_nome').value;
  aLinha[3]  = $('z01_v_ident').value;
  aLinha[4]  = $('z01_v_cgccpf').value;
  aLinha[5]  = $('z01_nome').value;
  aLinha[6]  = '<input type="checkbox" name="ckbox" id="check'+0+'" ';
  aLinha[6] += ' value= 1##';
  aLinha[6] += $('tf17_i_pedidotfd').value+'##'+$('tf01_i_cgsund').value;
  aLinha[6] += '##0 ';
  aLinha[6] += ' onclick="js_calcmarcar(this, 1)" >';    
  sChecado   = '';
  aLinha[7]  = '<input type="checkbox" name="ckboxfica" id="checkfica0" ';
  aLinha[7] += ' value="'+$('tf01_i_cgsund').value+'" >';
  sChecado   = '';
  aLinha[8]  = '<input type="checkbox" name="ckboxcolo" id="checkcolo'+ 0 +'" ';
  aLinha[8] += ' value="'+$('tf01_i_cgsund').value+'" ';
  aLinha[8] += ' onclick="js_calcmarcar2(this, '+1+')" >';
  oDBGridPedidostfd.addRow(aLinha);
  oDBGridPedidostfd.renderRows();
    
}

function js_getLotacaoDataHora() {

  if ($F('tf18_i_veiculo') == '') {
    return false;
  }
  if ($F('tf25_i_destino') == '') {
    return false;
  }
  if ($F('tf17_d_datasaida') == '') {
    return false;
  }
  if ($F('tf17_c_horasaida') == '') {
    return false;
  }
  var oParam      = new Object();
  oParam.exec     = "getLotacaoDataHora";
  aVet            = $F('tf17_d_datasaida').split('/');
  oParam.sData    = aVet[2]+'-'+aVet[1]+'-'+aVet[0];
  if ($('tf17_c_horasaida').type != 'text') {
    oParam.sHora = $('tf17_c_horasaida').options[$('tf17_c_horasaida').selectedIndex].text;
  } else {
    oParam.sHora = $('tf17_c_horasaida').value;  
  }
  oParam.iDestino = $F('tf25_i_destino');
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


/*
 * =========================================================
 *            BLOCO DE FUNÇÕES GENÉRICAS
 * =========================================================
 */

function js_limparInformacoes() {

  oDBGridPedidostfd.clearAll(true);
  oDBGridPedidostfd.renderRows();
  $('numero').value             = 0;
  $('numPac').value             = 0;
  $('numAcomp').value           = 0;
  $('numColo').value            = 0;         

}

function js_ajax(oParam, jsRetorno) {

  var objAjax = new Ajax.Request(
                                 sUrl, 
                                 {
                                    method: 'post',
                                    asynchronous: false,
                                    parameters: 'json='+Object.toJSON(oParam),
                                    onComplete: function(objAjax) {

                                                  var evlJS = jsRetorno+'(objAjax);';
                                                  return eval(evlJS);
                                                  
                                                }
                                 }
                                );


}

function js_validaEnvio() {

  if($F('tf17_i_pedidotfd').trim() == '') {

    alert('Código do pedido não informado.');
    return false;
    
  }
  
  if($F('tf17_d_datasaida').trim() == '') {

    alert('Informe a data de saída.');
    return false;
    
  } else {

    var aData      = $F('tf17_d_datasaida').split('/');
    var oData      = new Date(aData[2], aData[1] - 1, aData[0]); // o mês começa em 0, por isso que é subtraído 1
    var oDataAtual = new Date(); 
    var oDataOntem = new Date(oDataAtual.getTime() - 86400000);
    
    if(oData < oDataOntem) {

      alert('A data escolhida é muito antiga.')
      return false;

    }

  }

  if( $F('tf17_c_horasaida') == null || $F('tf17_c_horasaida').trim() == '') {

    alert('Informe o horário de saída.');
    return false;
    
  }
  
  if ($('numero').value != '0') {
    
    if ($F('tf18_d_dataretorno') == '') {

    alert('Informe a data de retorno');
      return false;

    }

    if ($F('tf18_c_horaretorno') == '' || $F('tf18_c_horaretorno') == null) {

      alert('Informe a hora de retorno');
      return false; 

    }
    
  }

  if(!js_validaHora()) {
    return false;
  }

  if($F('tf17_c_localsaida').trim() == '') {

    alert('Informe o local de saída.');
    return false;
    
  }

  var iContChecked = 0;
  var iTam         = document.form1.numero.value;
  if (iTam > 0) {

    var sPassageirosSelecionados  = '';
    var sPassageirosCGS           = '';
    var sSep                      = '';
    var iFica                     = '';
    var iColo                     = '';
    var iIni                      = lCorrenteIncluido == 0 ? 0 : 1;
    for (iCont = 0; iCont < iTam; iCont++) {

      if (document.getElementById("check"+(iCont+iIni)).checked) {

        aValor = $('check'+(iCont+iIni)).value.split('##');
        iFica  = $('checkfica'+(iCont+iIni)).checked ? 1 : 2;
        iColo  = $('checkcolo'+(iCont+iIni)).checked ? 1 : 2;
        /*
        A string dos passageiros selecionados é disposta da seguinte forma:
          CGS,TFD,TIPO,FICA,COLO#CGS,TFD,TIPO,FICA,COLO...
        */
        sPassageirosSelecionados += sSep+aValor[2]+','+aValor[1]+','+aValor[0]+','+iFica+','+iColo;
        sPassageirosCGS          += sSep+aValor[2];
        sSep                      = '#';
        iContChecked++;
        

      }

    }
    document.form1.sPassageirosSelecionados.value  = sPassageirosSelecionados;
    document.form1.sPassageirosCGS.value           = sPassageirosCGS;

  } 

  if(!js_validaDataHoraAgendamento()) {
    return false;
  }

  if($('lAlterarSaida').value == 2 && $('sPassageirosSelecionados').value == "") {

    alert('Nenhum paciente foi selecionado.');
    return false;
      
  }

  if ($('sPassageirosCGS').value == '' && $('lAlterarSaida').value == 1) {
    return confirm("Nenhum paciente foi selecionado para a Indicação de veículo. \nDeseja proseguir?");
  } 
  return true;

}

function js_validaDataHoraAgendamento() {


  var aDataSaida = $F('tf17_d_datasaida').split('/');
  var aHoraSaida = $F('tf17_c_horasaida').split(' ## ')[0].split(':');
  // o mês começa em 0, por isso que é subtraído 1
  var oDataSaida = new Date(aDataSaida[2], aDataSaida[1] - 1, aDataSaida[0], aHoraSaida[0], aHoraSaida[1]); 

  var aDataAgend = $F('tf16_d_dataagendamento').split('/');
  var aHoraAgend = $F('tf16_c_horaagendamento').split(':');
  // o mês começa em 0, por isso que é subtraído 1
  var oDataAgend = new Date(aDataAgend[2], aDataAgend[1] - 1, aDataAgend[0], aHoraAgend[0], aHoraAgend[1]);

  if (oDataSaida > oDataAgend) {

    alert('Não é possível agendar a saída para uma data superior a data do agendamento com a prestadora.');
    return false;

  }
  return true;

}

function js_validaHora() {

  sHorario = $F('tf17_c_horasaida');
  if ($('tf17_c_horasaida').type != 'text') {
    sHorario = sHorario.split(' ## ')[0]; 
  }
  if (sHorario.length != 5) {
      
    alert('Preencha corretamente o horário.');
    return false;
   
  }
  hr_ini  = (sHorario.substring(0, 2));
  mi_ini  = (sHorario.substring(3, 5));
  if (isNaN(hr_ini) || isNaN(mi_ini)) {
        
    alert('Preencha corretamente o horário.');
    return false;

  }
  if (parseInt(hr_ini, 10) > 24 || parseInt(hr_ini, 10) < 0) {

    alert('Preencha corretamente o horário.');
	return false;
	
  }
  if (parseInt(mi_ini, 10) > 59 || parseInt(mi_ini, 10) < 0) {

	alert('Preencha corretamente o horário.');
	return false;
		
  }
  return true;

}

function js_getHorariosData() {

  if ($F('tf17_d_datasaida') == '') {
    return false;
  }
  if ($F('tf25_i_destino') == '') {
  return false;
  }

  var oParam      = new Object();
  oParam.exec     = 'getHorariosData';
  oParam.dData    = $F('tf17_d_datasaida');
  oParam.iDestino = $F('tf25_i_destino');
  js_ajax(oParam, 'js_retornogetHorariosData');

}

function js_retornogetHorariosData(oRetorno) {
    
  var iTam  = $('tf17_c_horasaida').options.length;
  var iCont = 0;
  for (iCont = 0; iCont < iTam; iCont++) {

    $('tf17_c_horasaida').options[0] = null;

  }

  oRetorno = eval("("+oRetorno.responseText+")");
  if (oRetorno.iStatus == 1) {

    iCont = 0;
    oRetorno.oHorarios.each(
                            function (oHorario) {

                                                 $('tf17_c_horasaida').options[iCont] = 
                                                   new Option(oHorario.sHora.urlDecode(), oHorario.sHora.urlDecode()+
                                                                ' ## '+oHorario.sLocalSaida.urlDecode()+' ## '+
                                                                 oHorario.iLotacao
                                                             );
                                                 iCont++;

                                                }
                           );
    js_loadGridCgs();
    if ('1' != '<?=$db_opcao?>') {
      js_selecionaHorario();
    }
    js_localSaida();

  } else {
    alert('Não foi possível encontrar horários de saída para a data indicada.');
  }

}


function js_selecionaHorario() {
  
  var oSel = $('tf17_c_horasaida');
  for(i = 0; i < oSel.length; i++) {
    
    if(oSel.options[i].innerHTML == '<?=(isset($tf17_c_horasaida) && !empty($tf17_c_horasaida) ? 
                                         $tf17_c_horasaida : -1)?>') {

      oSel.options[i].selected = true;
      break;

    }

  }

}

function js_localSaida() {

  aLocal = $F('tf17_c_horasaida').split(' ## ');
  if ($('tf17_c_horasaida').type != 'text') {  

    aLocal = $F('tf17_c_horasaida').split(' ## ');
    $('tf17_c_localsaida').value = aLocal[1];
    $('total').value             = aLocal[2];

  }
  document.form1.livre.value   = parseInt(document.form1.total.value, 10) 
                                 - (parseInt(document.form1.numAcomp.value, 10) 
                                 +  parseInt(document.form1.numPac.value, 10))
                                 + parseInt(document.form1.numColo.value, 10);

}

function js_validaGrade() {
    
  if (js_validaDbData($('tf17_d_datasaida'))) {

    if ($('tf11_i_utilizagradehorario').value == '1' && <?=$db_opcaoNaoMudar?> != 3) {
         
      js_getHorariosData();

    } else {

      js_loadGridCgs();
  
    }

  } else {
    js_limparInformacoes();
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


String.prototype.trim = function() {
  return this.replace(/^\s+|\s+$/g, "");
}
</script>