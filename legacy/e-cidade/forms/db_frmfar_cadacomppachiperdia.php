<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

//MODULO: Farmacia
$oDaoFarCadAcompPacHiperdia->rotulo->label();
$oDaoSauTriagemAvulsa->rotulo->label();

$oRotulo = new rotulocampo;
$oRotulo->label('z01_v_nome');
$oRotulo->label('fa54_i_codigo');
$oRotulo->label('sd03_i_codigo');
$oRotulo->label('sd04_i_codigo');
$oRotulo->label('sd04_i_unidade');

if (isset($lBuscaCgs)) {

  $iModulo   = 2;
  $iOpcaoCgs = 1;

} else {

  if (!isset($iModulo)) {
    $iModulo = 1;
  }
  $iOpcaoCgs = 3;

}
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap colspan="3">
      <fieldset style='width: 96%;'> <legend><b>Paciente</b></legend>
        <table width="100%">
          <tr>
            <td nowrap title="<?=@$Tfa50_i_cgsund?>">
              <?
              db_ancora(@$Lfa50_i_cgsund, "js_pesquisafa50_i_cgsund(true);", $iOpcaoCgs);
              ?>
            </td>
            <td nowrap> 
              <?
              db_input('fa50_i_cgsund', 10, $Ifa50_i_cgsund, true, 'text', $iOpcaoCgs,
                       'onChange="js_pesquisafa50_i_cgsund(false);"');
              db_input('z01_v_nome', 50, $Iz01_v_nome, true, 'text', 3, '');

              // Retirada
              db_input('fa55_i_retirada', 10, $Ifa50_i_cgsund, true, 'hidden', 3, '');

              // Código do Cadastro / Acompanhamento
              db_input('fa50_i_codigo', 10, $Ifa50_i_codigo, true, 'hidden', 3, '');

              // Código da Triagem Avulsa. Utilizado para a alteração e exclusão
              db_input('s152_i_codigo', 10, $Ifa50_i_codigo, true, 'hidden', 3, '');

              // Tipo de registro (cadastro ou acompanhamento)
              db_input('fa50_i_tipo', 10, $Ifa50_i_codigo, true, 'hidden', 3, '');
              
              // Modulo que foi executado a rotina
              // 1 = Modulo Farmacia 
              // 2 = Modulo Hiperdia 
              db_input('iModulo', 10, $Ifa50_i_cgsund, true, 'hidden', 3, '');
              ?>
            </td>
            <td nowrap title="<?=@$Ts152_d_dataconsulta?>" align="right">
              <?
              echo $Ls152_d_dataconsulta;
              ?>
            </td>
            <td nowrap>
              <?
              if (!isset($s152_d_dataconsulta)) {
                
                $aDataAtual = explode('/', date('d/m/Y', db_getsession('DB_datausu')));
                $s152_d_dataconsulta_dia = $aDataAtual[0];
                $s152_d_dataconsulta_mes = $aDataAtual[1];
                $s152_d_dataconsulta_ano = $aDataAtual[2];
          
              }
              db_inputdata('s152_d_dataconsulta', @$s152_d_dataconsulta_dia, @$s152_d_dataconsulta_mes, 
                           @$s152_d_dataconsulta_ano, true, 'text', $db_opcao, ""
                          );
              ?>
            </td>
          </tr>
        </table>
      </fieldset>
    </td>
  </tr>


  <tr>
    <td valign="top" nowrap width="15%">
      <fieldset style='width: 75%;'> <legend><b>Dados Clínicos</b></legend>
        <?
        db_input('statusHipertensao', 1, '', true, 'checkbox', $db_opcao, '');
        echo '<b>Hipertensão</b>';
        db_input('statusDiabetes', 1, '', true, 'checkbox', $db_opcao, '');
        echo '<b>Diabetes</b>';
        ?>
      </fieldset>
      <fieldset style='width: 75%;'> <legend><b>Pressão Arterial</b></legend>
        <table>
          <tr>
            <td nowrap title="<?=@$Ts152_i_pressaosistolica?>">
               <?=@$Ls152_i_pressaosistolica?>
            </td>
            <td> 
              <?
              db_input('s152_i_pressaosistolica', 3, $Is152_i_pressaosistolica, true, 'text', $db_opcao, "");
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Ts152_i_pressaodiastolica?>">
              <?=@$Ls152_i_pressaodiastolica?>
            </td>
            <td> 
              <?
              db_input('s152_i_pressaodiastolica', 3, $Is152_i_pressaodiastolica, true, 'text', $db_opcao, "");
              ?>
            </td>
          </tr>
        </table>
      </fieldset>
    </td>

    <td rowspan="2" valign="top">
      <?
      if ($lCadastrado && (!isset($fa50_i_tipo) || $fa50_i_tipo == 2)) {

        $sTituloFieldset = 'Intercorrências desde a última consulta referida pelo paciente';
        $sSql            = $oDaoFarComplicacoes->sql_query_file(null, '*', 'fa51_i_codcomplicacao',
                                                                'fa51_i_codcomplicacao != 2002'
                                                               );

      } else {

        $sTituloFieldset = 'Presença de Complicações';
        $sSql            = $oDaoFarComplicacoes->sql_query_file(null, '*', 'fa51_i_codcomplicacao',
                                                                'fa51_i_codcomplicacao < 2007'
                                                               );

      }
      $rs = $oDaoFarComplicacoes->sql_record($sSql);
      ?>
      <fieldset style='width: 75%;'> <legend><b><?=$sTituloFieldset?></b></legend>
        <table>
          <?
          for ($iCont = 0; $iCont < $oDaoFarComplicacoes->numrows; $iCont++) {

            $oDados = db_utils::fieldsmemory($rs, $iCont);
          ?>
          <tr>
            <td nowrap>
              <input type="checkbox" id="ckComplic<?=$iCont?>" name="aComplicacoes[]"
                value="<?=$oDados->fa51_i_codigo.' ## '.$oDados->fa51_i_codcomplicacao?>"
                <?=isset($opcao) ? (in_array($oDados->fa51_i_codigo, $aComplicAltExc) ? 'checked' : '') : ''?>>
              <?=$oDados->fa51_c_descr?>
            </td>
          </tr>
          <?
          }
          ?>
          <tr style="display: none;">
            <td>
              <input type="hidden" name="numCk" id="numCk" value="<?=$oDaoFarComplicacoes->numrows?>">
            </td>
          </tr>
        </table>
      </fieldset>
    </td>

    <td rowspan="2" valign="top">
      <?
      $sSql = $oDaoFarMedicamentoHiperdia->sql_query_file(null, '*', 'fa43_c_codhiperdia',
                                                          "fa43_c_codhiperdia != '00'"
                                                         );
      $rs   = $oDaoFarMedicamentoHiperdia->sql_record($sSql);
      ?>
      <fieldset style='width: 75%;'> <legend><b>Tratamento p/ Hipertensão Arterial e Diabetes Melitus</b></legend>
        <table>
          <tr>
            <td colspan="2">
              <input type="checkbox" id="ckNaoMedicamentoso" name="ckNaoMedicamentoso" value="true" 
                onclick="js_naoMedicamentoso();">
              Não Medicamentoso
            </td>
          </tr>
          <?
          $iNumSelects = 0;
          for ($iCont = 0; $iCont < $oDaoFarMedicamentoHiperdia->numrows; $iCont++) {

            $oDados = db_utils::fieldsmemory($rs, $iCont);
          ?>
          <tr>
            <td nowrap>
              <?
              echo $oDados->fa43_c_descr;
              ?>
            </td>
            <td nowrap>
              <?
              if ($oDados->fa43_c_codhiperdia != '06') { // Todos os medicamentos menos insulina

                echo '<select id="selMed'.$iNumSelects.'">';
                echo '<option value="0">Não</option>';
                echo '<option value="0.5">1/2 Comp / Dia</option>';
                echo '<option value="1.0">1 Comp / Dia</option>';
                $iQuant = 1.0;
                while ($iQuant < $oDados->fa43_n_dosagemmax) {

                  $iValor = $oDados->fa43_n_dosagemmax < $iQuant + 1.0 ? $oDados->fa43_n_dosagemmax : $iQuant + 1.0;
                  echo '<option value="'.number_format($iValor, 1).'">'.$iValor.' Comp / Dia</option>';
                  $iQuant++;

                }
                echo '</select>';

                // Campo hidden com o codigo do medicamento e o codigo hiperdia:
                echo '<input type="hidden" id="medHidden'.$iNumSelects.'" value="'.$oDados->fa43_i_codigo.
                     ' ## '.$oDados->fa43_c_codhiperdia.'">';
                $iNumSelects++;

              } else { // Insulina

                echo '<input id="quantInsulina" name="quantInsulina" maxlength="3" size="2"
                        onkeyup="js_ValidaCampos(this, 1, \'\', \'\', \'\', event);"> ';
                echo 'Unidades / Dia';

                // Campo hidden com o codigo da insulina:
                echo '<input type="hidden" id="medInsulina" value="'.$oDados->fa43_i_codigo.'">';

              }

              ?>
            </td>
          </tr>
          <?
          } // fim do for que gera as informações dos medicamentos
          ?>
          <tr>
            <td colspan="2">
              <!-- Campo hidden com o número de medicamentos com a quantidade em select: -->
              <input type="hidden" id="numMedSelect" value="<?=$iNumSelects?>">

              Outros Medicamentos
              <input type="checkbox" id="ckOutrosMedicamentos" name="ckOutrosMedicamentos" value="true"
                <?=isset($opcao) && $fa50_i_outrosmedicamentos == 1 ? 'checked' : ''?>>

              <!-- Select multiple com os codigos e quantidades dos medicamentos selecionados -->
              <select multiple id="aMedicamentos" name="aMedicamentos[]" style="display: none;"></select>
            </td>
          </tr>
        </table>
      </fieldset>
    </td>
  </tr>


  <tr>
    <td valign="top" nowrap width="15%">
      <fieldset style='width: 75%;'> <legend><b>Medidas</b></legend>
        <table cellspacing="6">
          <tr>
            <td nowrap title="<?=@$Ts152_i_cintura?>">
              <?=@$Ls152_i_cintura?>
            </td>
            <td> 
              <?
              db_input('s152_i_cintura', 3, $Is152_i_cintura, true, 'text', $db_opcao, "");
              ?>
            </td>
            <td nowrap title="<?=@$Ts152_n_temperatura?>">
              <?=@$Ls152_n_temperatura?>
            </td>
            <td> 
              <?
              db_input('s152_n_temperatura', 3, $Is152_n_temperatura, true, 'text', $db_opcao, "");
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Ts152_n_peso?>">
              <?=@$Ls152_n_peso?>
            </td>
            <td> 
              <?
              db_input('s152_n_peso', 3, $Is152_n_peso, true, 'text', $db_opcao, 'onchange="js_imc();"');
              ?>
            </td>
            <td nowrap title="<?=@$Ts152_i_altura?>">
              <?=@$Ls152_i_altura?>
            </td>
            <td> 
              <?
              db_input('s152_i_altura', 3, $Is152_i_altura, true, 'text', $db_opcao, 'onchange="js_imc();"');
              ?>
            </td>
          </tr>
          <tr>
            <td>
              <b>IMC:</b>
            </td>
            <td colspan="3" nowrap>
              <?
              db_input('imc', 3, '', true, 'text', 3, "");
              db_input('descrimc', 16, '', true, 'text', 3, "");
              ?>
            </td>
          </tr>
        </table>
      </fieldset>
    </td>
  </tr>


  <?
  // No cadastro de paciente, não possuir informação de exames realizados
  $sStyle = $lCadastrado && (!isset($fa50_i_tipo) || $fa50_i_tipo == 2) ? '' : 'display: none;';
  ?>
  <tr style="<?=$sStyle?>">
    <td valign="top" nowrap colspan="3">
      <fieldset style='width: 75%;'> <legend><b>Exames Realizados</b></legend>
        <table>
          <tr>
            <?
            $sSql = $oDaoFarExames->sql_query_file(null, '*', 'fa47_i_codigo');
            $rs   = $oDaoFarExames->sql_record($sSql);
            for ($iCont = 0; $iCont < $oDaoFarExames->numrows; $iCont++) {

              $oDados = db_utils::fieldsmemory($rs, $iCont);

            ?>
              <td>
                <input type="checkbox" id="ckExames<?=$iCont?>" name="aExames[]" value="<?=$oDados->fa47_i_codigo?>"
                  <?=isset($opcao) ? (in_array($oDados->fa47_i_codigo, $aExamesAltExc) ? 'checked' : '') : ''?>>
                <?=$oDados->fa47_c_descr?>
              </td>
            <?
            }
            ?>
          </tr>
        </table>
      </fieldset>
    </td>
  </tr>


  <tr>
    <td valign="top" nowrap width="15%">
      <fieldset style='width: 75%;'> <legend><b>Glicemia</b></legend>
        <table>
          <tr>
            <td nowrap title="<?=@$Ts152_i_glicemia?>">
              <?=@$Ls152_i_glicemia?>
            </td>
            <td> 
              <?
              db_input('s152_i_glicemia', 3, $Is152_i_glicemia, true, 'text', $db_opcao, 
                       ' onkeypress="js_glicemia();" onkeyup="js_glicemia();" '
                      );
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Ts152_i_alimentacaoexameglicemia?>" colspan="2">
              <input type="radio" value="1" name="s152_i_alimentacaoexameglicemia" id="tipo1" disabled>
              Em jejum &nbsp;&nbsp;&nbsp;&nbsp;

              <input type="radio" value="2" name="s152_i_alimentacaoexameglicemia" id="tipo2" disabled>
              Pós prandial
            </td>
          </tr>
        </table>
      </fieldset>
    </td>

    <td nowrap colspan="2">
      <fieldset style='width: 75%;'> <legend><b>Profissional responsável pelo atendiamento</b></legend>
        <table>
          <tr>
            <td nowrap title="<?=@$Ts152_i_cbosprofissional?>">
              <?
              db_ancora(@$Lsd03_i_codigo, "js_pesquisaprofissional(true);", $db_opcao);
              ?>
            </td>
            <td nowrap> 
              <?
              db_input('sd03_i_codigo', 10, $Isd03_i_codigo, true, 'text', $db_opcao, 
                       " onchange='js_pesquisaprofissional(false);'"
                      );
              db_input('z01_nome', 50, '', true, 'text', 3, '');
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Ts152_i_cbosprofissional?>">
              <?
              db_ancora(@$Lsd04_i_unidade, "js_pesquisaunidade(true);", $db_opcao);
              ?>
            </td>
            <td nowrap> 
              <?
              db_input('sd04_i_unidade', 10, $Isd04_i_unidade, true, 'text', $db_opcao, 
                       " onchange='js_pesquisaunidade(false);'"
                      );
              db_input('sd04_i_codigo', 10, $Isd04_i_codigo, true, 'hidden', $db_opcao, '');
              db_input('descrdepto', 50, '', true, 'text', 3, '');
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Ts152_i_cbosprofissional?>">
              <?
              db_ancora(@$Ls152_i_cbosprofissional, '', 3);
              ?>
            </td>
            <td nowrap> 
              <?
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
        </table>
      </fieldset>
    </td>
  </tr>
</table>
</center>

<input name="<?=($db_opcao == 1 ? 'incluir' : ($db_opcao == 2 || $db_opcao == 22 ? 'alterar' : 'excluir'))?>" 
  type="submit" id="db_opcao" <?=isset($lDesabilita) ? 'disabled' : ''?>
  onclick="<?=$db_opcao != 3 ? 'return js_validaEnvio();' : "return confirm('Deseja excluir este registro?');"?>"
  value="<?=($db_opcao == 1 ? 'Incluir' : ($db_opcao == 2 || $db_opcao == 22 ? 'Alterar': 'Excluir'))?>" 
  <?=($db_botao == false ? 'disabled' : '')?>>
<input name="limpar" type="button" id="limpar" value="Limpar" onclick="js_limpar();" 
  <?=isset($lDesabilita) ? 'disabled' : ''?>>
<input name="fatorrisco" id="fatorrisco" type="button" value="Fatores de Risco" onclick="js_fatoresRisco();">
<? if ($iModulo == 1) { ?>
<input name="fechar" id="fechar" type="button" value="Fechar" onclick="parent.db_iframe_acompanhamento.hide();">
<? } ?>
</form>

<!-- Tabela do Grid -->
<table border="0" width="100%">
  <tr>
    <td>
      <div id='grid_acompanhamento' style='width: 100%;'></div>
    </td>
  </tr>
  <tr>
    <td>
      *Obs: somente o último registro lançado pode ser alterado / excluído, desde que pelo mesmo usuário que o lançou.
    </td>
  </tr>
</table>

<script>

js_verificaHipertensaoDiabetes();
oDBGridAcompanhamento = js_criaDataGrid();
js_getAcompanhamentos();
<?
if (isset($opcao) && $fa50_i_naomedicamentoso == 1) {
  echo "$('ckNaoMedicamentoso').click();";
} elseif (isset($opcao) && $fa50_i_naomedicamentoso == 2) {
  echo "js_getMedicamentosCadAcomp();";
}

if (isset($opcao)) {
  echo 'js_init();';
}
?>

function js_init() {

  if ('<?=isset($s152_i_glicemia) ? $s152_i_glicemia : '' ?>' != '0' 
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

  var mRetornoAjax;

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
                                                return mRetornoAjax = eval(evlJS);
                                  		        }
                                 }
                                );

  return mRetornoAjax;

}

function js_validaEnvio() {
 
  var iNumSelect = parseInt($F('numMedSelect'), 10);
  var iNumCk     = parseInt($F('numCk'), 10);
  var oSel       = $('aMedicamentos');
  var sValor     = '';

  js_esvaziaSelect(oSel);

  if (!js_verificaHipertensaoDiabetes()) {

    alert('O paciente deve possuir como fator de risco Hipertensão ou Diabetes.');
    return false;

  }

  if ($F('fa50_i_cgsund') == '') {

    alert('Selecione um CGS.');
    return false;

  }

  if ($F('s152_i_pressaosistolica') == '') {

    alert('Indique a pressão sistólica do paciente. ');
    return false;

  }

  if ($F('s152_i_pressaosistolica') >= 140) {

    if (!$('statusHipertensao').checked || !$('statusHipertensao').disabled) {

      alert('Pressão arterial sistólica maior ou igual a 140. '+
            'O fator de risco Hipertensão deve ser informado para este paciente.'
           );
      return false;

    }

  }

  if ($F('s152_i_pressaodiastolica') == '') {

    alert('Indique a pressão diastólica do paciente.');
    return false;

  }

  if ($F('s152_i_pressaodiastolica') >= 90) {

    if (!$('statusHipertensao').checked || !$('statusHipertensao').disabled) { 

      alert('Pressão arterial diastólica maior ou igual a 90. '+
            'O fator de risco Hipertensão deve ser informado para este paciente.'
           );
      return false;

    }

  }

  if ($F('s152_i_cintura') == '') {

    alert('Indique a medida da cintura do paciente (em cm).');
    return false;

  }

  if ($F('s152_n_peso') == '') {

    alert('Indique o peso do paciente (em kg).');
    return false;

  }

  if ($F('s152_n_peso') > 999.999) {

    alert('O peso do paciente tem que ser menor que 1000 kg.');
    return false;

  }

  var aPeso = $F('s152_n_peso').split('.');
  if (aPeso.length == 2) { // Se possui casas decimais

    if (aPeso[1].length > 3) {

      alert('O peso do paciente não pode ter mais que 3 casas decimais.');
      return false;

    }

  }

  if ($F('s152_i_altura') == '') {

    alert('Indique a altura do paciente.');
    return false;

  }

  if ($F('s152_i_altura') > 250) {

    alert('A altura do paciente não pode ser maior que 250 cm.');
    return false;

  }

  if ($F('s152_i_glicemia') != '' && $F('s152_i_glicemia') > 0 && !$('tipo1').checked && !$('tipo2').checked) {

    alert('Selecione o status da alimentação.');
    return false;

  }

  if ($F('sd03_i_codigo') == '') {

    alert('Selecione o profissional de atendimento.');
    return false;

  }

  if ($F('sd04_i_codigo') == '' || $F('sd04_i_unidade') == '') {

    alert('Selecione a unidade de atendimento');
    return false;

  }

  if ($F('fa53_i_codigo') == '') {

    alert('Selecione o CBOS do profissional.');
    return false;

  }

  if ($F('s152_d_dataconsulta') == '') {

    alert('Preencha a data da consulta.');
    return false;

  }

  var iCodComplic;
  for (var iCont = 0; iCont < iNumCk; iCont++) {
    
    if ($('ckComplic'+iCont).checked) {

      iCodComplic = $F('ckComplic'+iCont).split(' ## ')[1];
      /* se a complicação marcada for Pé diabético ou Amputação por diabetes, 
         o paciente deve possuir o fator de risco diabetes */
      if (iCodComplic == 2004 || iCodComplic == 2005) {

        if (!$('statusDiabetes').checked || !$('statusDiabetes').disabled) { 

          alert('Para marcar as complicacões Pé Diabético ou Amputação por Diabetes, '+
                'o paciente deve possuir o fator de risco Diabetes tipo 1 ou Diabetes tipo 2.');
          return false;

        }

      }

    }

  }

  var sCodMedHip;
  if (!$('ckNaoMedicamentoso').checked) {
    
    for (var iCont = 0; iCont < iNumSelect; iCont++) {
    
      sCodMedHip = $F('medHidden'+iCont).split(' ## ')[1];
      if ($F('selMed'+iCont) != 0) { // o medicamento foi selecionado

        /* Se o medicamento  marcado for Hidroclorotiazida, propanolol ou catopril, 
           o paciente deve possuir o fator de risco Hipertensão */
        if (sCodMedHip == '01' || sCodMedHip == '02' || sCodMedHip == '03') {
        
          if (!$('statusHipertensao').checked || !$('statusHipertensao').disabled) { 
        
            alert('Para marcar os medicamentos Hidroclorotiazida, Propanolol ou Catopril, '+
                  'o paciente deve possuir o fator de risco Hipertensão Arterial.');
            return false;
        
          }
        
        } else {

        /* O medicamento  marcado foi Glibenclamida ou Metformina, 
           então, o paciente deve possuir o fator de risco Diabetes */

          if (!$('statusDiabetes').checked || !$('statusDiabetes').disabled) { 
   
            alert('Para marcar os medicamentos Glibenclamida ou Metformina, '+
                  'o paciente deve possuir o fator de risco Diabetes tipo 1 ou Diabetes tipo 2.');
            return false;
   
          }

        }

        sValor                                 = $F('medHidden'+iCont).split(' ## ')[0] +' ## '+$F('selMed'+iCont);
        oSel.options[oSel.length]              = new Option(sValor, sValor);
        oSel.options[oSel.length - 1].selected = true;

      }
    
    }
    if ($F('quantInsulina') != '') {

      if ($F('quantInsulina') > 0) {

        if ($F('quantInsulina') > 100) {

          alert('A dose máxima diária de insulina é 100 unidades.');
          return false;

        } else {

          if (!$('statusDiabetes').checked || !$('statusDiabetes').disabled) { 
   
            alert('Para marcar o medicamento Insulina, '+
                  'o paciente deve possuir o fator de risco Diabetes tipo 1 ou Diabetes tipo 2.');
            return false;
   
          }

          sValor                                 = $F('medInsulina')+' ## '+$F('quantInsulina');
          oSel.options[oSel.length]              = new Option(sValor, sValor);
          oSel.options[oSel.length - 1].selected = true;

        }

      }

    }

    if (oSel.length == 0 && !$('ckOutrosMedicamentos').checked) {

      alert('O paciente é medicamentoso mas nenhum medicamento foi marcado para ele.');
      return false;

    }

  }
 
  // Habilito os checkbox para que eles sejam enviados, caso estejam marcados
  $('statusDiabetes').disabled    = false;
  $('statusHipertensao').disabled = false;

  return true;

}

function js_verificaHipertensaoDiabetes() {

  var oParam  = new Object();
	oParam.exec = 'verificaHipertensaoDiabetes';
	oParam.iCgs = $F('fa50_i_cgsund');

  return js_ajax(oParam, 'js_retornoVerificaHipertensaoDiabetes');

}
function js_retornoVerificaHipertensaoDiabetes(oRetorno) {
  
  oRetorno = eval("("+oRetorno.responseText+")");
  
  if (oRetorno.lHipertensao == 'true') {

    $('statusHipertensao').checked  = true;
    $('statusHipertensao').disabled = true;

  } else {
    $('statusHipertensao').disabled = false;
  }

  if (oRetorno.lDiabetes == 'true') {

    $('statusDiabetes').checked  = true;
    $('statusDiabetes').disabled = true;

  } else {
    $('statusDiabetes').disabled = false;
  }

  if (oRetorno.lHipertensao == 'true' || oRetorno.lDiabetes == 'true') {
    return true;
  } else {
    return false;
  }

}

function js_desabilitaSelect(oSel, iIndex) {
  oSel.selectedIndex = iIndex;
}

function js_esvaziaSelect(oSel) {

  while(oSel.length > 0) {
    oSel.options[0] = null;
  }

}

function js_naoMedicamentoso() {
  
  var iNumSelect = parseInt($F('numMedSelect'), 10);
  if ($('ckNaoMedicamentoso').checked) {
    
    for (var iCont = 0; iCont < iNumSelect; iCont++) {
    
      $('selMed'+iCont).selectedIndex = 0;
      $('selMed'+iCont).onchange      = function () { js_desabilitaSelect(this, 0);};
    
    }
    $('quantInsulina').value                 = '';
    $('quantInsulina').disabled              = true;
    $('quantInsulina').style.backgroundColor = 'rgb(222, 184, 135)';
    $('ckOutrosMedicamentos').checked        = false;
    $('ckOutrosMedicamentos').disabled       = true;

  } else {

    for (var iCont = 0; iCont < iNumSelect; iCont++) {
    
      $('selMed'+iCont).onchange = '';
    
    }
    $('quantInsulina').disabled              = false;
    $('quantInsulina').style.backgroundColor = '';
    $('ckOutrosMedicamentos').disabled       = false;

  }

}

function js_pesquisaprofissional(mostra) {

  if (mostra == true) {

    js_OpenJanelaIframe('', 'db_iframe_medicos', 'func_medicos.php?prof_ativo=1&funcao_js='+
                        'parent.js_mostraprofissional1|sd03_i_codigo|z01_nome&campoFoco=sd03_i_codigo',
                        'Pesquisa Profissional', true
                       );

  } else {

    if (document.form1.sd03_i_codigo.value != '') { 

       js_OpenJanelaIframe('', 'db_iframe_medicos', 'func_medicos.php?prof_ativo=1&pesquisa_chave='+
                           document.form1.sd03_i_codigo.value+
                           '&funcao_js=parent.js_mostraprofissional','Pesquisa Profissional',
                           false
                          );

    } else {

      document.form1.z01_nome.value = '';
      js_limpaUnidade();

    }

  }

}
function js_mostraprofissional(chave, erro) {

  document.form1.z01_nome.value = chave;
  if (erro == true) { 

    document.form1.sd03_i_codigo.focus(); 
    document.form1.sd03_i_codigo.value = '';

  }
  js_limpaUnidade();
  js_pesquisaunidade(true);

}
function js_mostraprofissional1(chave1, chave2) {

  document.form1.sd03_i_codigo.value = chave1;
  document.form1.z01_nome.value      = chave2;
  db_iframe_medicos.hide();
  js_limpaUnidade();
  js_pesquisaunidade(true);

}

function js_pesquisaunidade(mostra) {

  if ($F('sd03_i_codigo') == '') {

    alert('Selecione um profissional primeiro.');
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

/**** Bloco de funções do grid início */
function js_criaDataGrid() {

  oDBGrid                = new DBGrid('grid_acompanhamento');
  oDBGrid.nameInstance   = 'oDBGridAcompanhamento';
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
  oDBGrid.show($('grid_acompanhamento'));
  oDBGrid.clearAll(true);

  return oDBGrid;

}

function js_formataData(dData) {
  
  if(dData == undefined || dData.length != 10) {
    return dData;
  }
  return dData.substr(8,2)+'/'+dData.substr(5,2)+'/'+dData.substr(0,4);

}

function js_getAcompanhamentos() {

  if ($F('fa50_i_cgsund') == '') {
    return false;
  }

  var oParam  = new Object();
	oParam.exec = 'getAcompanhamentos';
	oParam.iCgs = $F('fa50_i_cgsund');

  js_ajax(oParam, 'js_retornoGetAcompanhamentos');

}

function js_retornoGetAcompanhamentos(oRetorno) {

  oRetorno = eval("("+oRetorno.responseText+")");

  if (oRetorno.iStatus != 1) {

    oDBGridAcompanhamento.clearAll(true);
    oDBGridAcompanhamento.renderRows();
    return false;

  } else {

    for (var iCont = 0; iCont < oRetorno.aAcompanhamentos.length; iCont++) {
        
      var aLinha = new Array();
  
      aLinha[0]  = oRetorno.aAcompanhamentos[iCont].fa50_i_codigo;
      aLinha[1]  = oRetorno.aAcompanhamentos[iCont].s152_i_pressaosistolica;
      aLinha[2]  = oRetorno.aAcompanhamentos[iCont].s152_i_pressaodiastolica;
      aLinha[3]  = oRetorno.aAcompanhamentos[iCont].s152_i_cintura;
      aLinha[4]  = oRetorno.aAcompanhamentos[iCont].s152_n_peso.urlDecode();
      aLinha[5]  = oRetorno.aAcompanhamentos[iCont].s152_i_altura;
      aLinha[6]  = oRetorno.aAcompanhamentos[iCont].s152_i_glicemia;
      aLinha[7]  = oRetorno.aAcompanhamentos[iCont].sAlimentacao.urlDecode();
      aLinha[8]  = oRetorno.aAcompanhamentos[iCont].z01_nome.urlDecode();
      aLinha[9]  = js_formataData(oRetorno.aAcompanhamentos[iCont].s152_d_dataconsulta.urlDecode());
      // somente o registro mais recente pode ser alterado / excluído
      if (oRetorno.aAcompanhamentos[iCont].lEditar == 'true') {
  
        aLinha[10]  = '<span onclick="js_altExc('+oRetorno.aAcompanhamentos[iCont].fa50_i_codigo+', \'alterar\');"';
        aLinha[10] += '  class="estiloLinkAltExc"><b>A</b></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
        aLinha[10] += '<span onclick="js_altExc('+oRetorno.aAcompanhamentos[iCont].fa50_i_codigo+', \'excluir\');"';
        aLinha[10] += ' class="estiloLinkAltExc"><b>E</b></span>';
  
      } else {
  
        aLinha[10]  = '<span><b>A</b></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
        aLinha[10] += '<span><b>E</b></span>';
  
      }

      oDBGridAcompanhamento.addRow(aLinha);

    }
    oDBGridAcompanhamento.renderRows();

  }

}

/* Bloco de funções do grid fim *****/

function js_altExc(iCodigo, sOperacao) {

  document.location.href = 'far4_far_cadacomppachiperdia001.php?chavepesquisa='+iCodigo+'&opcao='+sOperacao+
                           '&iCgs='+$F('fa50_i_cgsund')+'&iModulo=<?=$iModulo?>';

}


function js_limpar() {

  <?
  echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]).
       "?iCgs='+\$F('fa50_i_cgsund')+'&iRetirada='+\$F('fa55_i_retirada')+".
       "'&lDesabilita=true';";
  ?>

}

function js_fatoresRisco() {

  if ($F('fa50_i_cgsund') != '') {

    sChave = 'chavepesquisacgs='+$F('fa50_i_cgsund');
    js_OpenJanelaIframe('', 'db_iframe_fatoresderisco', 'sau4_consultamedica006.php?'+sChave, 
                        'Fotores de Risco', true
                       );

  } else {
    alert('Selecione um CGS.');
  }

}

function js_getMedicamentosCadAcomp() {

  if ($F('fa50_i_codigo') != '') {

    var oParam       = new Object();
	  oParam.exec      = 'getMedicamentosCadAcomp';
	  oParam.iCadAcomp = $F('fa50_i_codigo');
    
    js_ajax(oParam, 'js_retornoGetMedicamentosCadAcomp');

  }

}

function js_retornoGetMedicamentosCadAcomp(oRetorno) {

  iNumSelect = parseInt($F('numMedSelect'), 10);
  oRetorno   = eval("("+oRetorno.responseText+")");

  if (oRetorno.iStatus != 1) {
    return false;
  } else {

    for (var iCont = 0; iCont < oRetorno.aMedicamentos.length; iCont++) {

      // verifico se é a insulina
      if (oRetorno.aMedicamentos[iCont].fa49_i_medicamento == $F('medInsulina')) {

        $('quantInsulina').value = oRetorno.aMedicamentos[iCont].fa49_n_quantidade.split('.')[0];
        continue;
        
      }

      // verifico se é alguns dos medicamentos que a quantidade está nos selects
      for (var iCont2 = 0; iCont2 < iNumSelect; iCont2++) {
        
        // Verifico se o codigo do medicamento que veio da requisição é o mesmo do elemento que estou verificando
        if ($F('medHidden'+iCont2).split(' ## ')[0] == oRetorno.aMedicamentos[iCont].fa49_i_medicamento) {

          // procuro nos valores do select, o valor que veio da requisição
          var oSel = $('selMed'+iCont2);
          for (var iCont3 = 0; iCont3 < oSel.length; iCont3++) {
            
            if (oSel.options[iCont3].value == oRetorno.aMedicamentos[iCont].fa49_n_quantidade) {

              oSel.selectedIndex = iCont3;
              break;

            }

          }
          break;

        }

      }

    }

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

//CGS
function js_pesquisafa50_i_cgsund (mostra) {

  sQuery = 'func_cgs_und.php?funcao_js=parent.js_mostracgs|z01_i_cgsund|z01_v_nome';
  if (mostra == true) {

    js_OpenJanelaIframe('top.corpo',
                        'db_iframe_cgs_und',
                        sQuery,
                        'Pesquisa',
                        true);

  } else {

     if ($F('fa50_i_cgsund') != '') {

        sQuery += '&chave_z01_i_cgsund='+$F('fa50_i_cgsund');
        sQuery += '&nao_mostra=true';
        js_OpenJanelaIframe('top.corpo',
                            'db_iframe_cgs_und',
                            sQuery,
                            'Pesquisa',
                            false);

     } else {
       $('z01_v_nome').value = '';
     }

  }
}
function js_mostracgs(chave1, chave2){

  $('fa50_i_cgsund').value = chave1;
  $('z01_v_nome').value    = chave2;
  if (chave1 == '') {
    $('fa50_i_cgsund').focus();
  } else {

    js_verificaHipertensaoDiabetes();
    js_getAcompanhamentos();

  }
  db_iframe_cgs_und.hide();

}
</script>