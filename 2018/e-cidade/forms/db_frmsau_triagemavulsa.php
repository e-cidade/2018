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
$oDaoSauTriagemAvulsa->rotulo->label();
$oRotulo = new rotulocampo;
$oRotulo->label('s115_c_cartaosus');
$oRotulo->label('z01_v_nome');
$oRotulo->label('fa54_i_codigo');
$oRotulo->label('sd03_i_codigo');
$oRotulo->label('sd04_i_codigo');
$oRotulo->label('sd04_i_unidade');
$oRotulo->label('s155_i_prontuario');
$oRotulo->label('rh70_sequencial');
$oRotulo->label('rh70_descr');
$oRotulo->label('rh70_estrutural');
$oRotulo->label('sd04_i_cbo');

?>
<form name="form1" id="form1" method="post" action="" class="form-container">
  <fieldset>
    <legend>Triagem Avulsa</legend>
    <table>
      <tr style="display: none;">
        <td nowrap title="<?=@$Ts152_i_codigo?>">
          <?=@$Ls152_i_codigo?>
        </td>
        <td colspan="2">
          <?php
          db_input( 's152_i_codigo',      10, $Is152_i_codigo,     true, 'text',   3 );
          db_input( 's155_i_prontuario',  10, $Is155_i_prontuario, true, 'hidden', 3 );
          db_input( 'lFormTriagem',       10, '',                  true, 'hidden', 3 );
          db_input( 'lFiltroUnidade',     10, '',                  true, 'hidden', 3 );
          db_input( 's152_i_login',       10, '',                  true, 'hidden', 3 );
          db_input( 's152_d_datasistema', 10, '',                  true, 'hidden', 3 );
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Ts115_c_cartaosus?>">
          <?=@$Ls115_c_cartaosus?>
        </td>
        <td colspan="2">
          <?php
          db_input('s115_c_cartaosus', 15, $Is115_c_cartaosus, true, 'text', $db_opcao2, ' onchange="js_getCgsCns();"');
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Ts152_i_cgsund?>">
           <?php
           db_ancora(@$Ls152_i_cgsund, "js_pesquisas152_i_cgsund(true);", $db_opcao2);
           ?>
        </td>
        <td nowrap colspan="2">
          <?php
          $sOnChange = "onchange='js_pesquisas152_i_cgsund(false);'";
          db_input( 's152_i_cgsund', 10, $Is152_i_cgsund, true, 'text', $db_opcao2, $sOnChange );
          db_input( 'z01_v_nome',    50, $Iz01_v_nome,    true, 'text', 3 );
          ?>
        </td>
      </tr>
      <tr>
        <td colspan="3">
          <fieldset class="separator">
            <legend>Pressão Arterial</legend>
            <table>
              <tr>
                <td nowrap title="<?=@$Ts152_i_pressaosistolica?>" class="field-size2">
                   <?=@$Ls152_i_pressaosistolica?>
                </td>
                <td>
                  <?php
                  db_input( 's152_i_pressaosistolica', 3, $Is152_i_pressaosistolica, true, 'text', $db_opcao );
                  ?>
                </td>
                <td nowrap title="<?=@$Ts152_i_pressaodiastolica?>" class="field-size2">
                  <?=@$Ls152_i_pressaodiastolica?>
                </td>
                <td>
                  <?php
                  db_input( 's152_i_pressaodiastolica', 3, $Is152_i_pressaodiastolica, true, 'text', $db_opcao );
                  ?>
                </td>
              </tr>
            </table>
          </fieldset>
          <fieldset class="separator">
            <legend>Medidas</legend>
            <table>
              <tr>
                <td nowrap title="<?=@$Ts152_i_cintura?>" class="field-size2">
                  <?=@$Ls152_i_cintura?>
                </td>
                <td>
                  <?php
                  db_input('s152_i_cintura', 3, $Is152_i_cintura, true, 'text', $db_opcao);
                  ?>
                </td>
                <td nowrap title="<?=@$Ts152_n_temperatura?>" class="field-size2">
                  <?=@$Ls152_n_temperatura?>
                </td>
                <td>
                  <?php
                  db_input('s152_n_temperatura', 3, $Is152_n_temperatura, true, 'text', $db_opcao);
                  ?>
                </td>
                <td nowrap title="<?=@$Ts152_n_peso?>">
                  <?=@$Ls152_n_peso?>
                </td>
                <td>
                  <?php
                  db_input('s152_n_peso', 3, $Is152_n_peso, true, 'text', $db_opcao, 'onchange="js_imc();"');
                  ?>
                </td>
                <td nowrap title="<?=@$Ts152_i_altura?>">
                  <?=@$Ls152_i_altura?>
                </td>
                <td>
                  <?php
                  db_input('s152_i_altura', 3, $Is152_i_altura, true, 'text', $db_opcao, 'onchange="js_imc();"');
                  ?>
                </td>
              </tr>
              <tr>
                <td>
                  <b>IMC:</b>
                </td>
                <td colspan="7" nowrap>
                  <?php
                  db_input( 'imc',       3, '', true, 'text', 3 );
                  db_input( 'descrimc', 16, '', true, 'text', 3 );
                  ?>
                </td>
              </tr>
            </table>
          </fieldset>
          <fieldset class="separator">
            <legend>Glicemia</legend>
            <table>
              <tr>
                <td nowrap title="<?=@$Ts152_i_glicemia?>">
                  <?=@$Ls152_i_glicemia?>
                </td>
                <td>
                  <?php
                  $sJavaScript = 'onkeypress="js_glicemia();" onkeyup="js_glicemia();"';
                  db_input( 's152_i_glicemia', 3, $Is152_i_glicemia, true, 'text', $db_opcao, $sJavaScript );
                  ?>
                </td>
              </tr>
              <tr>
                <td nowrap title="<?=@$Ts152_i_alimentacaoexameglicemia?>" colspan="2">
                  <input type="radio" value="1" name="s152_i_alimentacaoexameglicemia" id="tipo1" disabled>
                  Em jejum
                  <input type="radio" value="2" name="s152_i_alimentacaoexameglicemia" id="tipo2" disabled>
                  Pós prandial
                </td>
              </tr>
            </table>
          </fieldset>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Ts152_i_cbosprofissional?>">
          <?php
          $iMedOpcao = 1;
          if( isset( $lProfSaude ) ) {

            if( $lProfSaude == true ) {
              $iMedOpcao = 3;
            }
          }

          $sFiltrar = isset($lFiltroUnidade) && $lFiltroUnidade == 'true' ? 'true' : 'false';
          db_ancora(@$Lsd03_i_codigo, 'js_pesquisaprofissional(true, '.$sFiltrar.');', $iMedOpcao);
          ?>
        </td>
        <td nowrap colspan="2">
          <?php
          $sJavaScript = 'onchange="js_pesquisaprofissional(false, '.$sFiltrar.');"';
          db_input( 'sd03_i_codigo', 10, $Isd03_i_codigo, true, 'text', $iMedOpcao, $sJavaScript );
          db_input( 'z01_nome',      50,              '', true, 'text', 3 );
          ?>
        </td>
      </tr>
      <?php
        if( isset($lProfSaude) && $lProfSaude == true ) {
      ?>
        <tr>
          <td nowrap title="<?=@$Tsd04_i_cbo?>">
             <?php
               db_ancora(@$Lsd04_i_cbo,"js_pesquisasd04_i_cbo(true);",$db_opcao);
             ?>
          </td>
          <td nowrap colspan="2">
            <?php
            $sJavaScript = "onchange='js_pesquisasd04_i_cbo(false);'";
            db_input( 'rh70_sequencial', 10, $Irh70_sequencial, true, 'hidden', $db_opcao );
            db_input( 'rh70_estrutural', 10, $Irh70_estrutural, true, 'text',   $db_opcao, $sJavaScript );
            db_input( 'rh70_descr',      68, $Irh70_descr,      true, 'text',   3 );
            ?>
          </td>
        </tr>
      <?php } ?>
      <tr>
        <td nowrap title="<?=@$Ts152_i_cbosprofissional?>">
          <?php
          db_ancora(@$Lsd04_i_unidade, "js_pesquisaunidade(true);", $db_opcao3);
          ?>
        </td>
        <td nowrap colspan="2">
          <?php
          $sJavaScript = " onchange='js_pesquisaunidade(false);'";
          db_input( 'sd04_i_unidade', 10, $Isd04_i_unidade, true, 'text',   $db_opcao3, $sJavaScript );
          db_input( 'sd04_i_codigo',  10, $Isd04_i_codigo,  true, 'hidden', 3 );
          db_input( 'descrdepto',     50,              '',  true, 'text',   3 );
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Ts152_i_cbosprofissional?>">
          <?php
          db_ancora(@$Ls152_i_cbosprofissional, '', 3);
          ?>
        </td>
        <td nowrap colspan="2">
          <?php
          $sSql = $oDaoFarCbos->sql_query_file();
          $rs   = $oDaoFarCbos->sql_record($sSql);
          $aX   = array();
          if ($oDaoFarCbos->numrows > 0) {

            for ($iCont = 0; $iCont < $oDaoFarCbos->numrows; $iCont++) {

              $oDados                     = db_utils::fieldsmemory($rs, $iCont);
              $aX[$oDados->fa53_i_codigo] = $oDados->fa53_c_descr;
            }
          }

          db_select('fa53_i_codigo', $aX, true, $db_opcao);
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Ts152_d_dataconsulta?>">
          <?=@$Ls152_d_dataconsulta?>
        </td>
        <td nowrap colspan="2">
          <?php
          if( !isset($s152_d_dataconsulta) ) {

            $aDataAtual = explode('/', date('d/m/Y', db_getsession('DB_datausu')));
            $s152_d_dataconsulta_dia = $aDataAtual[0];
            $s152_d_dataconsulta_mes = $aDataAtual[1];
            $s152_d_dataconsulta_ano = $aDataAtual[2];

          }
          db_inputdata(
                        's152_d_dataconsulta',
                        @$s152_d_dataconsulta_dia,
                        @$s152_d_dataconsulta_mes,
                        @$s152_d_dataconsulta_ano,
                        true,
                        'text',
                        $db_opcao
                      );
          ?>
        </td>
      </tr>
    </table>
  </fieldset>
  <?php
  if( isset($lFormTriagem) && $lFormTriagem == 'true' && !isset($lConsulta) && !isset($lLancamentoFaa) ) {
  ?>
    <input name="consultar" type="button" id="consultar" value="Consultar" onclick="js_pesquisaFaa();">
    <input name="<?=($db_opcao == 1 ? 'incluir' : ($db_opcao == 2 || $db_opcao == 22 ? 'alterar' : 'excluir'))?>"
           type="submit"
           id="db_opcao"
           onclick="<?=$db_opcao != 3 ? 'return js_validaEnvio();' : "return confirm('Deseja excluir este registro?');"?>"
           value="Confirmar">
    <input name="prosseguir" id="prosseguir" type="button" value="Prosseguir" onclick="js_prosseguir();">
    <input id="emitir" type="button" value="Emitir FAA"  onclick="js_emitirFaa();">
    <?php
    selectModelosFaa($oSauConfig->s103_i_modelofaa);
  } else if( isset($lConsulta) && $lConsulta == 'true' ) {
  ?>
    <input id="fechar" type="button" value="Fechar"  onclick="parent.db_iframe_triagemavulsa.hide();">
  <?
  } else if (isset($lLancamentoFaa) && $lLancamentoFaa == 'true') {
  ?>
    <input name="<?=($db_opcao == 1 ? 'incluir' : ($db_opcao == 2 || $db_opcao == 22 ? 'alterar' : 'excluir'))?>"
           type="submit"
           id="db_opcao"
           onclick="<?=$db_opcao != 3 ? 'return js_validaEnvio();' : "return confirm('Deseja excluir este registro?');"?>"
           value="Confirmar">
    <input name="prosseguir" id="prosseguir" type="button" value="Prosseguir" onclick="js_prosseguirLancamentoFaa();">
    <input name="voltar" id="voltar" type="button" value="Voltar" onclick="js_voltarLancamentoFaa();">
    <input id="emitir" type="button" value="Emitir FAA"  onclick="js_emitirFaa();">
    <?php
    selectModelosFaa($oSauConfig->s103_i_modelofaa);
  } else {
  ?>
    <input name="<?=($db_opcao == 1 ? 'incluir' : ($db_opcao == 2 || $db_opcao == 22 ? 'alterar' : 'excluir'))?>"
           type="submit"
           id="db_opcao"
           onclick="<?=$db_opcao != 3 ? 'return js_validaEnvio();' : "return confirm('Deseja excluir este registro?');"?>"
           value="<?=($db_opcao == 1 ? 'Incluir' : ($db_opcao == 2 || $db_opcao == 22 ? 'Alterar': 'Excluir'))?>"
           <?=($db_botao == false ? 'disabled' : '')?>>
    <input name="fatorrisco" id="fatorrisco" type="button" value="Fatores de Risco" onclick="js_fatoresRisco();">
    <input id="limpar" type="button" value="Limpar" onclick="document.location.href = 'sau4_sau_triagemavulsa001.php';">
  <?php
  }
  ?>
</form>
<?php
if( !isset($lFormTriagem) || $lFormTriagem != 'true' ) {
?>
<table>
  <tr>
    <td>
      <div id='grid_triagemavulsa' style='width: 100%;'></div>
    </td>
  </tr>
  <tr>
    <td>
      *Obs: somente o último registro lançado pode ser alterado / excluído, desde que pelo mesmo usuário que o lançou.
    </td>
  </tr>
</table>
<?php
}
?>
<script>

<?php
if (!isset($lFormTriagem) || $lFormTriagem != 'true') {
?>
oDBGridTriagemAvulsa = js_criaDataGrid();
<?php
}

if( isset($opcao) ) {
  echo 'js_init();';
} else {

  if (isset($s152_i_cgsund)) {
    echo 'js_pesquisas152_i_cgsund(false);';
  }
}
?>

const MENSAGENS_SAU4_FORMULARIO_TRIAGEMAVULSA = 'saude.ambulatorial.db_frmsau_triagemavulsa.';

function js_init() {

  if (   '<?=isset($s152_i_glicemia) ? $s152_i_glicemia : '' ?>' != '0'
      && '<?=isset($s152_i_glicemia) ? $s152_i_glicemia : '' ?>' != '') {
    
    if ('<?=isset($opcao) ? $opcao : '' ?>' == 'alterar') {

      $('tipo1').disabled = false;
      $('tipo2').disabled = false;
    }

    if ('<?=isset($s152_i_alimentacaoexameglicemia) ? $s152_i_alimentacaoexameglicemia : '' ?>' == '1') {
      $('tipo1').checked = true;
    } else {
      $('tipo2').checked = true;
    }
  }

  js_imc();
}

function js_ajax(oParam, jsRetorno, sUrl) {

  if (sUrl == undefined) {
    sUrl = 'sau4_ambulatorial.RPC.php';
  }

  var objAjax = new Ajax.Request(sUrl, 
                                 {
                                  method: 'post',
                                  asynchronous: false,
                                  parameters: 'json='+Object.toJSON(oParam),
                                  onComplete: function(oAjax) {
                                                var evlJS = jsRetorno+'(oAjax);';
                                                return eval(evlJS);
                                              }
                                 }
                                );
}

function js_validaEnvio() {

  <?php
  if (isset($lFormTriagem) && $lFormTriagem == 'true') {
  ?>
    if ($F('s155_i_prontuario') == '') {
  
      alert( _M( MENSAGENS_SAU4_FORMULARIO_TRIAGEMAVULSA + 'selecione_faa' ) );
      return false;
    }
  <?php
  }
  ?>
  
  if ($F('s152_i_cgsund') == '') {

    alert( _M( MENSAGENS_SAU4_FORMULARIO_TRIAGEMAVULSA + 'selecione_cgs' ) );
    return false;
  }

  if ($F('s152_i_pressaosistolica') == '') {

    alert( _M( MENSAGENS_SAU4_FORMULARIO_TRIAGEMAVULSA + 'indique_pressao_sistolica' ) );
    return false;
  }

  if ($F('s152_i_pressaodiastolica') == '') {

    alert( _M( MENSAGENS_SAU4_FORMULARIO_TRIAGEMAVULSA + 'indique_pressao_diastolica' ) );
    return false;
  }

  if ($F('s152_i_cintura') == '') {

    alert( _M( MENSAGENS_SAU4_FORMULARIO_TRIAGEMAVULSA + 'indique_medida_cintura' ) );
    return false;
  }

  if ($F('s152_n_peso') == '') {

    alert( _M( MENSAGENS_SAU4_FORMULARIO_TRIAGEMAVULSA + 'indique_peso' ) );
    return false;
  }

  var aPeso = $F('s152_n_peso').split('.');
  if (aPeso.length == 2) { // Se possui casas decimais

    if (aPeso[1].length > 3) {

      alert( _M( MENSAGENS_SAU4_FORMULARIO_TRIAGEMAVULSA + 'peso_acima_casas_decimais' ) );
      return false;
    }
  }

  if ($F('s152_n_peso') > 999.999) {

    alert( _M( MENSAGENS_SAU4_FORMULARIO_TRIAGEMAVULSA + 'peso_menor' ) );
    return false;
  }

  if ($F('s152_i_altura') == '') {

    alert( _M( MENSAGENS_SAU4_FORMULARIO_TRIAGEMAVULSA + 'indique_altura' ) );
    return false;
  }

  if ($F('s152_i_altura') > 250) {

    alert( _M( MENSAGENS_SAU4_FORMULARIO_TRIAGEMAVULSA + 'altura_maior' ) );
    return false;
  }

  if ($F('s152_i_glicemia') != '' && $F('s152_i_glicemia') > 0 && !$('tipo1').checked && !$('tipo2').checked) {

    alert( _M( MENSAGENS_SAU4_FORMULARIO_TRIAGEMAVULSA + 'status_alimentacao' ) );
    return false;
  }

  if ($F('sd03_i_codigo') == '') {

    alert( _M( MENSAGENS_SAU4_FORMULARIO_TRIAGEMAVULSA + 'selecione_profissional' ) );
    return false;
  }

  if ($F('sd04_i_codigo') == '' || $F('sd04_i_unidade') == '') {

    alert( _M( MENSAGENS_SAU4_FORMULARIO_TRIAGEMAVULSA + 'selecione_unidade' ) );
    return false;
  }

  if ($F('fa53_i_codigo') == '') {

    alert( _M( MENSAGENS_SAU4_FORMULARIO_TRIAGEMAVULSA + 'selecione_cbos' ) );
    return false;
  }

  if ($F('s152_d_dataconsulta') == '') {

    alert( _M( MENSAGENS_SAU4_FORMULARIO_TRIAGEMAVULSA + 'preencha_data_consulta' ) );
    return false;
  }

  return true;

}

/**** Bloco de funções do grid início */
function js_criaDataGrid() {

  oDBGrid                = new DBGrid('grid_triagemavulsa');
  oDBGrid.nameInstance   = 'oDBGridTriagemAvulsa';
  oDBGrid.hasTotalizador = false;
  oDBGrid.setCellWidth(new Array('5%', '5%', '5%', '5%', '5%', '5%', '10%', '10%', '35%', '10%', '5%'));
  oDBGrid.setHeight(40);
  oDBGrid.allowSelectColumns(false);

  var aHeader = new Array();
  aHeader[0]  = 'Código';
  aHeader[1]  = 'Sistólica';
  aHeader[2]  = 'Diastólica';
  aHeader[3]  = 'Cintura';
  aHeader[4]  = 'Peso';
  aHeader[5]  = 'Altura';
  aHeader[6]  = 'Glicemia (MG/D)';
  aHeader[7]  = 'Alimentação';
  aHeader[8]  = 'Profissional';
  aHeader[9]  = 'Consulta';
  aHeader[10] = 'Opções';
  oDBGrid.setHeader(aHeader);

  var aAligns = new Array();
  aAligns[0]  = 'center';
  aAligns[1]  = 'center';
  aAligns[2]  = 'center';
  aAligns[3]  = 'center';
  aAligns[4]  = 'center';
  aAligns[5]  = 'center';
  aAligns[6]  = 'center';
  aAligns[7]  = 'center';
  aAligns[8]  = 'center';
  aAligns[9]  = 'center';
  aAligns[10] = 'center';
  
  oDBGrid.setCellAlign(aAligns);
  oDBGrid.show($('grid_triagemavulsa'));
  oDBGrid.clearAll(true);

  return oDBGrid;
}

function js_formataData(dData) {
  
  if(dData == undefined || dData.length != 10) {
    return dData;
  }

  return dData.substr(8,2)+'/'+dData.substr(5,2)+'/'+dData.substr(0,4);
}

function js_getUltimaTriagemAvulsa() {

  if ($F('s152_i_cgsund') == '') {
    return false;
  }

  var oParam  = new Object();
  oParam.exec = 'getUltimaTriagemAvulsa';
  oParam.iCgs = $F('s152_i_cgsund');

  js_ajax(oParam, 'js_retornoGetUltimaTriagemAvulsa');
}

function js_retornoGetUltimaTriagemAvulsa(oRetorno) {

  oRetorno = eval("("+oRetorno.responseText+")");

  if (oRetorno.iStatus != 1) {
    return false;
  } else {

    for (iCont = 0; iCont < oRetorno.aTriagens.length; iCont++) {
        
      var aLinha  = new Array();
  
      aLinha[0]   = oRetorno.aTriagens[iCont].s152_i_codigo;
      aLinha[1]   = oRetorno.aTriagens[iCont].s152_i_pressaosistolica;
      aLinha[2]   = oRetorno.aTriagens[iCont].s152_i_pressaodiastolica;
      aLinha[3]   = oRetorno.aTriagens[iCont].s152_i_cintura;
      aLinha[4]   = oRetorno.aTriagens[iCont].s152_n_peso.urlDecode();
      aLinha[5]   = oRetorno.aTriagens[iCont].s152_i_altura;
      aLinha[6]   = oRetorno.aTriagens[iCont].s152_i_glicemia;
      aLinha[7]   = oRetorno.aTriagens[iCont].sAlimentacao.urlDecode();
      aLinha[8]   = oRetorno.aTriagens[iCont].z01_nome.urlDecode();
      aLinha[9]   = js_formataData(oRetorno.aTriagens[iCont].s152_d_dataconsulta.urlDecode());
      if (oRetorno.aTriagens[iCont].lEditar == 'true') { // somente o registro mais recente pode ser alterado / excluído
  
        aLinha[10]  = '<span onclick="js_altExc('+oRetorno.aTriagens[iCont].s152_i_codigo+', \'alterar\');"';
        aLinha[10] += '  class="estiloLinkAltExc"><b>A</b></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
        aLinha[10] += '<span onclick="js_altExc('+oRetorno.aTriagens[iCont].s152_i_codigo+', \'excluir\');"';
        aLinha[10] += ' class="estiloLinkAltExc"><b>E</b></span>';
      } else {
  
        aLinha[10]  = '<span><b>A</b></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
        aLinha[10] += '<span><b>E</b></span>';
      }

      oDBGridTriagemAvulsa.addRow(aLinha);
    }

    oDBGridTriagemAvulsa.renderRows();
  }
}

/* Bloco de funções do grid fim *****/

function js_altExc(iCodigo, sOperacao) {
  document.location.href = 'sau4_sau_triagemavulsa001.php?chavepesquisa='+iCodigo+'&opcao='+sOperacao;
}

function js_glicemia() {

  if ($F('s152_i_glicemia') != '' && $F('s152_i_glicemia') > 0) {

    $('tipo1').disabled = false;
    $('tipo2').disabled = false;
  } else {

    $('tipo1').disabled = true;
    $('tipo1').checked  = false;
    $('tipo2').disabled = true;
    $('tipo2').checked  = false;
  }
}

function js_getCgsCns() {
  
  if ($F('s115_c_cartaosus') == '') {
    return false;
  }
  if ($F('s115_c_cartaosus').length != 15 || isNaN($F('s115_c_cartaosus'))) {
    
    alert( _M( MENSAGENS_SAU4_FORMULARIO_TRIAGEMAVULSA + 'numero_cns_invalido' ) );
    $('s115_c_cartaosus').value = '';
    return false;
  }

  var oParam  = new Object();
  oParam.exec = "getCgsCns";
  oParam.iCns = $F('s115_c_cartaosus');

  js_ajax(oParam, 'js_retornogetCgsCns');
}

function js_retornogetCgsCns(oRetorno) {

  oRetorno = eval("("+oRetorno.responseText+")");

  if (oRetorno.z01_i_cgsund == '') {

    alert( _M( MENSAGENS_SAU4_FORMULARIO_TRIAGEMAVULSA + 'cns_nao_encontrado' ) );
    return false;
  }

  $('s152_i_cgsund').value = oRetorno.z01_i_cgsund;
  $('z01_v_nome').value    = oRetorno.z01_v_nome.urlDecode();
}

function js_pesquisas152_i_cgsund(mostra) {

  if (mostra == true) {

    js_OpenJanelaIframe('', 'db_iframe_cgs_und', 'func_cgs_und.php?funcao_js='+
                        'parent.js_mostracgs_und1|z01_i_cgsund|z01_v_nome', 
                        'Pesquisa', true
                       );
  } else {

     if (document.form1.s152_i_cgsund.value != '') {

        js_OpenJanelaIframe('', 'db_iframe_cgs_und', 'func_cgs_und.php?pesquisa_chave='+
                            document.form1.s152_i_cgsund.value+'&funcao_js=parent.js_mostracgs_und', 
                            'Pesquisa', false
                           );
     } else {

       document.form1.z01_v_nome.value = ''; 
       js_limparGrid();
     }
  }
}

function js_mostracgs_und(chave, erro) {

  js_limparGrid();
  document.form1.z01_v_nome.value = chave; 
  if (erro == true) {

    document.form1.s152_i_cgsund.focus(); 
    document.form1.s152_i_cgsund.value = '';
  } else {
    js_getUltimaTriagemAvulsa();
  }
}

function js_mostracgs_und1(chave1, chave2) {

  js_limparGrid();
  document.form1.s152_i_cgsund.value = chave1;
  document.form1.z01_v_nome.value    = chave2;
  db_iframe_cgs_und.hide();
  js_getUltimaTriagemAvulsa();
}

function js_pesquisaprofissional(mostra, lFiltroUnidade) {

  if (lFiltroUnidade == undefined) {
    lFiltroUnidade = false;
  }
  
  var sGet = '';
  if (lFiltroUnidade) {

    if ($F('sd04_i_unidade') == '') {

      alert( _M( MENSAGENS_SAU4_FORMULARIO_TRIAGEMAVULSA + 'unidade_nao_informada' ) );
      return false;
    }

    sGet = '&chave_sd06_i_unidade='+$F('sd04_i_unidade');
  }

  if (mostra == true) {

    js_OpenJanelaIframe('', 'db_iframe_medicos', 'func_medicos.php?prof_ativo=1&funcao_js='+
                        'parent.js_mostraprofissional|sd03_i_codigo|z01_nome|sd04_i_codigo'+
                        '&campo_sd04_i_codigo=true'+sGet, 'Pesquisa Profissional', true
                       );
  } else {

    if (document.form1.sd03_i_codigo.value != '') { 

      js_OpenJanelaIframe('', 'db_iframe_medicos', 'func_medicos.php?prof_ativo=1&funcao_js='+
                          'parent.js_mostraprofissional|sd03_i_codigo|z01_nome|sd04_i_codigo'+
                          '&campo_sd04_i_codigo=true&nao_mostra=true&chave_sd03_i_codigo='+
                          $F('sd03_i_codigo')+sGet, 'Pesquisa Profissional', false
                         );
    } else {

      document.form1.z01_nome.value = '';
      <?php
      if (!isset($lFiltroUnidade) || $lFiltroUnidade != 'true') {
      ?>
        js_limpaUnidade();
      <?php
      }
      ?>
    }
  }
}

function js_mostraprofissional(chave1, chave2, chave3) {

  if (chave1 == '') {
    chave3 = '';
  }

  document.form1.sd03_i_codigo.value = chave1;
  document.form1.z01_nome.value      = chave2;
  db_iframe_medicos.hide();

  <?php
  if (!isset($lFiltroUnidade) || $lFiltroUnidade != 'true') {
  ?>
    js_limpaUnidade();
    js_pesquisaunidade(true);
  <?php
  } else {
  ?>
    document.form1.sd04_i_codigo.value = chave3;
    js_getCbosProfissional();
  <?php
  }
  ?>
}

function js_pesquisaunidade(mostra) {

  if ($F('sd03_i_codigo') == '') {

    alert( _M( MENSAGENS_SAU4_FORMULARIO_TRIAGEMAVULSA + 'selecione_profissional_primeiro' ) );
    js_limpaUnidade();
    return false;
  }

  if (mostra == true) {

    js_OpenJanelaIframe('', 'db_iframe_unidademedicos', 'func_unidademedicos.php?funcao_js='+
                        'parent.js_mostraunidade|sd04_i_codigo|descrdepto|sd04_i_unidade&chave_sd04_i_medico='+
                        $F('sd03_i_codigo'), 'Pesquisa Unidade', true
                       );
  } else {

    if (document.form1.sd04_i_unidade.value != '') { 

       js_OpenJanelaIframe('', 'db_iframe_unidademedicos', 'func_unidademedicos.php?chave_sd04_i_unidade='+
                           document.form1.sd04_i_unidade.value+'&chave_sd04_i_medico='+$F('sd03_i_codigo')+
                           '&funcao_js=parent.js_mostraunidade|sd04_i_codigo|descrdepto|sd04_i_unidade'+
                           '&nao_mostra=true', 'Pesquisa Unidade', false
                          );
    } else {
      js_limpaUnidade();
    }
  }
}

function js_mostraunidade(chave1, chave2, chave3) {

  if (chave1 == '') {
    chave3 = '';
    document.form1.sd04_i_unidade.focus(); 
    document.form1.sd04_i_unidade.value = '';
  }

  document.form1.sd04_i_codigo.value  = chave1;
  document.form1.descrdepto.value     = chave2;
  document.form1.sd04_i_unidade.value = chave3;

  db_iframe_unidademedicos.hide();
  js_getCbosProfissional();
}

function js_limpaUnidade() {

  document.form1.sd04_i_codigo.value  = '';
  document.form1.descrdepto.value     = '';
  document.form1.sd04_i_unidade.value = '';
}

function js_fatoresRisco() {

  if ($F('s152_i_cgsund') != '') {

    sChave = 'chavepesquisacgs='+$F('s152_i_cgsund');
    js_OpenJanelaIframe('', 'db_iframe_fatoresderisco', 'sau4_consultamedica006.php?'+sChave, 
                        'Fotores de Risco', true
                       );
  } else {
    alert( _M( MENSAGENS_SAU4_FORMULARIO_TRIAGEMAVULSA + 'selecione_cgs' ) );
  }
}

function js_imc() {

  if ($F('s152_n_peso') != '' && $F('s152_i_altura') != '' && $F('s152_i_altura') != '0') {
    
    var nImc       = parseFloat($F('s152_n_peso')) / 
                    ((parseFloat($F('s152_i_altura')) * parseFloat($F('s152_i_altura'))) / 10000);

    $('imc').value = nImc.toString().substr(0, 5);

    if (nImc < 18.5) {
      $('descrimc').value = 'ABAIXO DO PESO';
    } else if (nImc < 25.0) {
      $('descrimc').value = 'PESO NORMAL';
    } else if (nImc < 30.0) {
      $('descrimc').value = 'ACIMA DO PESO';
    } else {
      $('descrimc').value = 'MUITO ACIMA DO PESO';
    }
  }
}

function js_getCbosProfissional() {

  if ($F('sd03_i_codigo') == '' || $F('sd04_i_codigo') == '' || $F('sd04_i_unidade') == '') {
    return false;
  }

  var oParam     = new Object();
  oParam.exec    = 'getCbosProfissional';
  oParam.iUndMed = $F('sd04_i_codigo');

  js_ajax(oParam, 'js_retornoGetCbosProfissional');
}

function js_retornoGetCbosProfissional(oRetorno) {

  oRetorno = eval("("+oRetorno.responseText+")");

  if (oRetorno.iStatus != 1) {
    return false;
  } else {
    js_selecionaCbos(oRetorno.fa54_i_cbos);
  }
}

function js_selecionaCbos(iCodigo) {

  oSel = $('fa53_i_codigo');

  for (var iCont = 0; iCont < oSel.length; iCont++) {

    if (oSel.options[iCont].value == iCodigo) {

      oSel.selectedIndex = iCont;
      return true;
    }
  }

  return false;
}

function js_limparGrid() {

  <?php
  if (!isset($lFormTriagem) || $lFormTriagem != 'true') {
  ?>
    oDBGridTriagemAvulsa.clearAll(true);
  <?php
  }
  ?>
}

function js_pesquisaFaa () {

  js_OpenJanelaIframe('', 'db_iframe_triagem', 'func_triagem.php?funcao_js='+
                      'parent.js_preenchepesquisa|sd24_i_codigo', 'Pesquisa', true
                     );
}

function js_preenchepesquisa(iChave) {

  db_iframe_triagem.hide();
  <?php
    echo " location.href = '".basename($GLOBALS['HTTP_SERVER_VARS']['PHP_SELF']).
         "?chavefaa='+iChave+'&lFormTriagem=true&lFiltroUnidade=true';";
  ?>
}

function js_prosseguir() {

  if ($F('s152_i_codigo') != '' && $F('s155_i_prontuario')) {

    parent.document.formaba.a2.disabled = false;
    parent.iframe_a2.location.href='sau4_triagemproc001.php?chavepesquisaprontuario='+$F('s155_i_prontuario')+'&iCgs='+$F('s152_i_cgsund');
    parent.mo_camada('a2');
  } else {

    alert( _M( MENSAGENS_SAU4_FORMULARIO_TRIAGEMAVULSA + 'lancar_triagem_primeiro' ) );
    return false;
  }
}

/* ======================================

              COMEÇA AQUI 

=========================================*/
function js_emitirFaa() {

  if ($F('s155_i_prontuario') != '') {
     
    var oParam               = new Object();
    oParam.exec              = 'gerarFAATXT';
    oParam.sChaveProntuarios = $F('s155_i_prontuario');
    oParam.iModelo           = $F('s103_i_modelofaa');
    js_webajax(oParam, 'js_retornoEmissaofaa', 'sau4_ambulatorial.RPC.php');
  } else {
    alert( _M( MENSAGENS_SAU4_FORMULARIO_TRIAGEMAVULSA + 'nenhuma_faa_gerar' ) );
  }
}

function js_retornoEmissaofaa (oAjax) {

  oRetorno = eval("("+oAjax.responseText+")");
  if (oRetorno.iStatus == 2) {

    message_ajax(oRetorno.sMessage.urlDecode());
    return false;
  } else {

    if (oRetorno.iTipo == 1) {
      js_emitiefaaPDF (oRetorno);
    } else {
      js_emitirfaaTXT (oRetorno);
    }
  }
}

function js_emitiefaaPDF (oDados) {

  sChave = '?chave_sd29_i_prontuario='+oDados.sChaveProntuarios;
  var WindowObjectReference;
  var strWindowFeatures = "menubar=yes,location=no,resizable=yes,scrollbars=yes,status=yes";
  sArquivo = js_getArquivoFaa($F('s103_i_modelofaa'));
  WindowObjectReference = window.open(sArquivo+sChave,"CNN_WindowName", strWindowFeatures);
}

function js_emitirfaaTXT (oRetorno) {

  iTop    = 20;
  iLeft   = 5;
  iHeight = screen.availHeight-210;
  iWidth  = screen.availWidth-35;
  sChave  = 'sSessionNome='+oRetorno.sSessionNome;

  js_OpenJanelaIframe ('', 'db_iframe_visualizador', 'sau2_fichaatend002.php?'+sChave, 
                       'Visualisador', true, iTop, iLeft, iWidth, iHeight
                      );
}

/*========================================

            TERMINA AQUI

==========================================*/

function js_getArquivoFaa(iCodModelo) {

  oSel = $('sArquivoFaa');
  for (var iCont = 0; iCont < oSel.length; iCont++) {

    if (iCodModelo == oSel.options[iCont].value) {
      return oSel.options[iCont].text;
    }
  }
}

function js_voltarLancamentoFaa() {
	parent.mo_camada('a1');
}

function js_prosseguirLancamentoFaa() {

  parent.document.formaba.a3.disabled = false;
  parent.iframe_a3.location.href      = 'sau4_fichaatendabas003.php?chavepesquisaprontuario='+$F('s155_i_prontuario')+
                                        '&cgs='+$F('s152_i_cgsund');
  parent.mo_camada('a3');
}

function js_pesquisasd04_i_cbo(mostra){

  if(mostra==true){

    sQuery  = 'funcao_js=parent.js_mostrarhcbo1|rh70_estrutural|rh70_descr|sd27_i_rhcbo';
    sQuery += '&chave_sd04_i_unidade='+document.form1.sd04_i_unidade.value;
    sQuery += '&chave_sd04_i_medico='+document.form1.sd03_i_codigo.value;
    js_OpenJanelaIframe('',
                        'db_iframe_especmedico',
                        'func_especmedico.php?'+sQuery,
                        'Pesquisa',
                        true);

  } else {

    if( document.form1.rh70_estrutural.value != '' ) {

      sQuery  = 'chave_rh70_estrutural='+document.form1.rh70_estrutural.value;
      sQuery += '&funcao_js=parent.js_mostrarhcbo1|rh70_estrutural|rh70_descr|sd27_i_rhcbo';
      sQuery += '&chave_sd04_i_unidade='+document.form1.sd04_i_unidade.value;
      sQuery += '&chave_sd04_i_medico='+document.form1.sd03_i_codigo.value;
      js_OpenJanelaIframe('',
                          'db_iframe_especmedico',
                          'func_especmedico.php?'+sQuery,
                          'Pesquisa',true);
      document.form1.rh70_estrutural.value = '';
      document.form1.rh70_descr.value = '';
     } else {
       document.form1.rh70_estrutural.value = '';
     }
  }
}

function js_mostrarhcbo(erro,chave1, chave2, chave3,chave4) {

  document.form1.rh70_descr.value      = chave1;
  document.form1.rh70_estrutural.value = chave2;
  document.form1.rh70_sequencial.value = chave4;
  if (erro == true) {

    document.form1.rh70_estrutural.focus(); 
    document.form1.rh70_estrutural.value = '';
  }
}

function js_mostrarhcbo1(chave1,chave2,chave3,chave4) {

  document.form1.rh70_estrutural.value = chave1;
  document.form1.rh70_descr.value      = chave2;
  document.form1.rh70_sequencial.value = chave3;
  db_iframe_especmedico.hide();

  if(chave2 == ''){
    document.form1.rh70_estrutural.focus(); 
    document.form1.rh70_estrutural.value = ''; 
  }  
}

$('s115_c_cartaosus').className         = 'field-size2';
$('s152_i_cgsund').className            = 'field-size2';
$('z01_v_nome').className               = 'field-size7';
$('s152_i_pressaosistolica').className  = 'field-size1';
$('s152_i_pressaodiastolica').className = 'field-size1';
$('s152_i_cintura').className           = 'field-size1';
$('s152_n_peso').className              = 'field-size1';
$('s152_n_temperatura').className       = 'field-size1';
$('s152_i_altura').className            = 'field-size1';
$('imc').className                      = 'field-size1';
$('descrimc').className                 = 'field-size7';
$('s152_i_glicemia').className          = 'field-size1';
$('sd03_i_codigo').className            = 'field-size2';
$('z01_nome').className                 = 'field-size7';
$('sd04_i_unidade').className           = 'field-size2';
$('descrdepto').className               = 'field-size7';
$('fa53_i_codigo').className            = 'field-size-max';
$('s152_d_dataconsulta').className      = 'field-size2';
</script>