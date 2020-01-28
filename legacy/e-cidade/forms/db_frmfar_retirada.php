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

//MODULO: Farmácia
$clfar_retirada->rotulo->label();
$clfar_retiradaitens->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("z01_i_cgsund");
$clrotulo->label("fa04_tiporetirada");
$clrotulo->label("fa03_i_codigo");
$clrotulo->label("descrdepto");
$clrotulo->label("fa06_i_matersaude");
$clrotulo->label("fa01_codigobarras");
$clrotulo->label("fa06_t_posologia");
$clrotulo->label("m60_controlavalidade");
$clrotulo->label("fa08_i_cgsund");
$clrotulo->label("z01_v_ender");
$clrotulo->label("z01_v_ident");
$clrotulo->label("m60_codmater");
$clrotulo->label("fa06_i_codigo");
$clrotulo->label("s115_c_cartaosus");
$clrotulo->label("fa41_i_origemreceita");
$clrotulo->label("fa40_c_descr");
$clrotulo->label("fa10_i_programa");
$clrotulo->label("fa04_d_dtvalidade");
$clrotulo->label("m60_descr");

$sWhere = 'fa42_i_departamento = '.db_getsession('DB_coddepto');

$oDaofar_tiporeceitapadrao = db_utils::getdao('far_tiporeceitapadrao');
$sSql                      = $oDaofar_tiporeceitapadrao->sql_query(null, 'fa42_i_tiporeceita', null, $sWhere);
$rs                        = $oDaofar_tiporeceitapadrao->sql_record($sSql);
if ($oDaofar_tiporeceitapadrao->numrows > 0) {
  db_fieldsmemory($rs, 0);
} else {
  $fa42_i_tiporeceita = 0;
}

?>

<form name="form1" method="post" action="">
  <fieldset class='subcontainer' style='width:800px;'>
    <legend>Dados da retirada</legend>
    <table class="form-container" name="tabela1" id="tabela1">

      <tr style="display:none">
          <td class="bold" nowrap='nowrap' title="<?=@$Tfa04_i_unidade?>">
            <?php
              db_input('hiperdia', 5, '', true, 'hidden', 3, "");
              $validadeContinuado = 0;
              db_input('validadeContinuado', 5, '', true, 'hidden', 3, "");
              db_ancora(@$Lfa04_i_unidades, "js_pesquisafa04_i_unidades(true);", 3);
            ?>
          </td>
          <td nowrap='nowrap' colspan="3">
            <?php
              db_input('fa04_i_unidades', 10, @$Ifa04_i_unidades, true, 'text', 3,
                       " onchange='js_pesquisafa04_i_unidades(false);'");
              db_input('descrdepto', 55, @$Idescrdepto, true, 'text', 3, "");
              db_input('fa04_i_codigo', 5, @$Ifa04_i_codigo, true, 'hidden', 3, "");
            ?>
          </td>
        </tr>


        <tr>
          <td class="bold" nowrap='nowrap' title="<?=@$Ts115_c_cartaosus?>">
            <?=@$Ls115_c_cartaosus?>
          </td>
          <td nowrap='nowrap'>
            <?
              db_input('s115_c_cartaosus', 14, $Is115_c_cartaosus, true, 'text', $db_opcao,
                       ' onchange="js_getCgsCns();"');
            ?>
          </td>
          <td class="bold" nowrap='nowrap'>
            <b>Tipo de Retirada:</b>
          </td>
          <td nowrap='nowrap'>
            <?php
              $aOptions=array("1"=>"Normal","2"=>"Não Padronizada");
              db_select("fa04_tiporetirada", $aOptions, $Ifa04_tiporetirada, $db_opcao,
                        "onchange=\"js_tiporeceita(this.value);\"  ");
            ?>
          </td>
        </tr>

        <tr>
          <td class="bold" nowrap='nowrap' title="<?=@$Tfa04_i_cgsund?>">
            <?
            db_ancora(@$Lfa04_i_cgsund, "js_pesquisafa04_i_cgsund(true);", '', '', 'ancora_cgs');
            ?>
          </td>
          <td nowrap='nowrap'>
            <?php
              db_input('fa04_i_cgsund', 10, @$Ifa04_i_cgsund, true, 'text', "", "onchange='js_pesquisafa04_i_cgsund(false); js_init();'");
              db_input('z01_v_nome', 45, @$Iz01_v_nome, true, 'text', 1,
                       "onchange=\"if(document.form1.z01_v_nome.value=='') document.form1.fa04_i_cgsund.value='';\"");
            ?>
          </td>
          <td colspan="2">
            <input type='button' id='novoCgs' name='novoCgs' value='Novo CGS' onclick="js_novoCgs();">
            <input type='button' id='historico' name='historico' value='Histórico' onclick="js_abre_historico();">
            <?php
              if (db_permissaomenu(date('Y'), db_getsession('DB_modulo'), 6990) == 'true') { ?>
                <input type='button' id='continuados' name='continuados' value='Continuados'
                       onclick="js_abre_continuados();" disabled >
            <?php
              } ?>
          </td>
        </tr>


        <tr>
          <td class="bold" nowrap='nowrap' >
            <b>Tipo de Receita:</b>
          </td>
          <td nowrap='nowrap' title="<?=@$Tfa04_d_dtvalidade?>"  >
            <?php
              if (isset($chavepesquisaretirada)) {

                db_input('fa04_i_tiporeceita', 10, @$Ifa04_i_tiporeceita, true, 'text', 3, "");
                db_input('fa03_c_descr', 20, @$Ifa03_c_descr, true, 'text', 3, "");

              } else {

                $sSql = $clfar_tiporeceita->sql_query_file('', 'fa03_i_codigo, fa03_c_descr, fa03_c_requisitante,'.
                                                           'fa03_c_posologia,fa03_i_prescricaomedica,'.
                                                           'fa03_c_profissional,fa03_c_numeroreceita', 'fa03_c_descr',
                                                           'fa03_i_ativa = 1');
                $result_tprec = $clfar_tiporeceita->sql_record($sSql);
            ?>
                <select name="fa04_i_tiporeceita" id="fa04_i_tiporeceita" rel='ignore-css' onchange="js_tpreceita();">
                  <option value=""></option>
                <?php
                  for($y = 0; $y < pg_num_rows($result_tprec); $y++){

                    db_fieldsmemory($result_tprec, $y);?>
                    <?php
                      $sValor  = "$fa03_c_requisitante/$fa03_i_codigo/$fa03_c_posologia/$fa03_i_prescricaomedica/";
                      $sValor .= "$fa03_c_profissional/$fa03_c_numeroreceita";
                    ?>
                    <option value="<?=$sValor?>"
                            <?=(@$fa04_i_tiporeceita == "$fa03_c_requisitante/$fa03_i_codigo")?"selected":""?>>
                      <?=$fa03_c_descr?>
                    </option>
                 <?}?>
                 </select>
            <? } ?>
          </td>
          <td >
            <?php
              db_ancora($Lfa04_i_receita, 'js_pesquisareceita(true)', '');
            ?>

          </td>
          <td>
            <?php
              db_input('fa04_i_receita', 10, $Ifa04_i_receita, true, 'text', $db_opcao, 'onchange="js_pesquisareceita(false);"');
            ?>
          </td>
        </tr>


        <tr>
          <td class="bold" nowrap='nowrap'>
            Validade:
          </td>
          <td>
            <?php db_inputdata('fa04_d_dtvalidade', $fa04_d_dtvalidade_dia, $fa04_d_dtvalidade_mes,
                               $fa04_d_dtvalidade_ano, true, 'text', 3); ?>
          </td>
          <td class="bold" nowrap='nowrap'>
            Número de Notificação:
          </td>
          <td>
            <?php
              db_input('fa04_numeronotificacao', 10, $Ifa04_numeronotificacao, true, 'text', $db_opcao);
            ?>
          </td>
        </tr>


        <tr style=" <?=($oConfigFarmacia->fa02_i_origemreceita == 1 ? "''" : 'display: none;')?>">
          <td class="bold" nowrap='nowrap' title="<?=@$Tfa41_i_origemreceita?>">
            <?php db_ancora($Lfa41_i_origemreceita, 'js_pesquisafa41_i_origemreceita(true)', '')?>
          </td>
          <td nowrap='nowrap' colspan="3">
            <?db_input('fa41_i_origemreceita', 10, $Ifa41_i_origemreceita, true, 'hidden', $db_opcao,
                       'js_pesquisafa41_i_origemreceita(true)');
              db_input('fa40_c_descr', 45, $Ifa40_c_descr, true, 'text', $db_opcao); ?>
          </td>
        </tr>

        <tr>
          <td nowrap title="<?=@$Tfa04_i_profissional?>">
            <?
            db_ancora(@$Lfa04_i_profissional, "js_pesquisafa04_i_profissional(true);", $db_opcao, '',
                      'ancora_profissional'
                     );
            ?>
          </td>
          <td colspan="3">
            <? db_input('fa04_i_profissional',
                        10,
                        @$Ifa04_i_profissional,
                        true,
                        'text',
                        "",
                        " onchange='js_pesquisafa04_i_profissional(false);'");
               db_input('z01_nome', 45, @$z01_nome, true, 'text', 1);?>

            <?
            if (db_permissaomenu(date('Y'), 1000004, 8675) == 'true') {
              ?>
              <input type="button" id="cadProf" title="Cadastro de Profissionais Fora da Rede"
                name="cadProf" value="Cadastro de Profissionais" onclick="js_abreCadProf();">
              <?
            }
            ?>

          </td>
        </tr>


          <tr <?=(!isset($fa08_i_cgsund))?"style=\"display:none\"":""?> id="linha1">
            <td nowrap title="<?=@$Tfa08_i_cgsund?>">
              <? db_ancora(@$Lfa08_i_cgsund, "js_pesquisafa08_i_cgsund(true);", 1); ?>
            </td>
            <td colspan="3">
              <?
                db_input('fa08_i_cgsund', 10, @$Ifa08_i_cgsund, true, 'text', "",
                         " onchange='js_pesquisafa08_i_cgsund(false);'");
                db_input('z01_v_nomecgs', 55, @$z01_v_nomecgs, true, 'text', 1);
              ?>
            </td>
          </tr>
          <tr <?=(!isset($ender))?"style=\"display:none\"":""?>  id="linha1">
            <td nowrap title="<?=@$Tz01_v_ender?>">
              <? db_ancora(@$Lz01_v_ender,"",3); ?>
            </td>
            <td colspan="3">
              <?
                db_input('ender', 45, @$Iz01_v_ender, true, 'text', 1);
                db_input('numero', 10, @$z01_i_numero, true, 'text', 1);
              ?>
            </td>
          </tr>
          <tr <?=(!isset($ident))?"style=\"display:none\"":""?> id="linha1">
            <td nowrap title="<?=@$Tz01_v_ident?>">
              <b>Identidade:</b>
            </td>
            <td colspan="3">
              <? db_input('ident', 15, @$Iz01_v_ident, true, 'text', 1); ?>
            </td>
          </tr>
    </table>
  </fieldset>


  <fieldset class='subcontainer' style='width:800px;'>
    <legend>Medicamentos</legend>
    <table class='form-container' id="tabela_medicamento" >

      <tr>
        <td>
          <?=$Lfa01_codigobarras?>
        </td>
        <td>
          <?php
            db_input('fa01_codigobarras', 20, $Ifa01_codigobarras, true, 'text', 1, " onchange='pesquisaMedicamentoCodigoBarras();'");
          ?>
        </td>
      </tr>
      <tr>
        <td class='bold field-size3' rel="ignore-css" nowrap='nowrap' title="Pesquise o medicamento." >
          <?php
            db_ancora($Lfa06_i_matersaude, "jsPesquisaMedicamento(true);",$db_opcao);
          ?>
        </td>
        <td nowrap='nowrap'>
          <?php

            db_input('fa06_i_matersaude', 10, $Ifa06_i_matersaude, true, 'text', 1, " onchange='jsPesquisaMedicamento(false);'");

            db_input('m60_descr', 45, $Im60_descr, true, 'text',1,'onblur="js_material();" ');
          ?>
          <input name="checkContinuado" type="checkbox" id="checkContinuado" onclick="js_ativaContinuado();" > <b>Continuado</b>
        </td>
      </tr>
      <tr id="linha_continuado" style="display: 'none';">
        <td colspan="2">
          <fieldset class='separator' style='width: 98%;'>
            <legend>Continuados</legend>
            <table >
              <tr>
                <td nowrap="nowrap" class='bold field-size3'>
                  Quantidade:
                </td>
                <td nowrap="nowrap"  >
                  <? db_input('contiQuant', 10, "", true, 'text', 1, 'onchange="$(\'fa06_f_quant\').value=this.value;"'); ?>
                </td>
                <td nowrap="nowrap"  class='bold'>
                  Frequência:
                </td>
                <td nowrap="nowrap" >
                  <? db_input('contiFreq', 10, "", true, 'text', 1, "onchange='js_prazo();'"); ?>
                </td>
                <td nowrap="nowrap"  class='bold'>
                  Margem:
                </td>
                <td nowrap="nowrap" >
                  <? db_input('contimargem', 10, "", true, 'text', 1, "onchange='js_margem();'"); ?>
                </td>
              </tr>
              <tr>
                <td nowrap="nowrap" class="bold field-size3" >
                  <?php
                    db_ancora(@$Lfa10_i_programa, "js_pesquisafa10_i_programa(true);",$db_opcao);
                  ?>
                </td>
                <td nowrap="nowrap" colspan="5">
                <?php
                    db_input('fa10_i_programa', 10, @$Ifa10_i_programa, true, 'text', "",
                             " onchange='js_pesquisafa10_i_programa(false);'");
                    db_input('fa12_c_descricao', 42, @$fa12_c_descricao, true, 'text', 3);
                  ?>
                </td>
              </tr>
            </table>
          </fieldset>
        </td>
      </tr>
      <tr>
        <td class="bold" nowrap="nowrap">
          Quantidade Dispensada:
        </td>
        <td nowrap="nowrap">
          <?php db_input('fa06_f_quant', 10, @$Ifa06_f_quant, true, 'text', "", "onchange='js_quantidade(this.value)'");?>
          <b><a name="lotes" id="lotes" href='' onclick ="js_mostraLotes('','fa06_f_quant'); return false;"
                style="padding-left: 9px; padding-right: 9px;">Ver Lotes</a> </b>
          &nbsp;&nbsp;
          <b>Saldo :</b>
          <?php db_input('quant_disp',5,@$Ifa06_f_quant,true,'text',3,""); ?>
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          <input type = "button" name = "fa06_t_posologia" id = "fa06_t_posologia" value = "Posologia" disabled onclick = "js_lancar();">
          <input type = "button" name = "ultimas" id = "ultimas" value = "Últimas Retiradas" disabled onclick="js_ultimas();">
          <?php
            db_input('lote_edit',10,'Lote',true,'hidden',3,"");
            db_input('lote',10,'Lote',true,'hidden',3,"");
            db_input('pontoPedido',10,'Lote',true,'hidden',3,"");
          ?>
          <input type="hidden" name="validade_edit" id="validade_edit" value="">
          <input type="hidden" name="posologia_edit" id="posologia_edit" value="">
          <input name = "incluir" type = "button" id = "incluir" value = "Incluir" onclick = 'js_incluiremedio();'>
        </td>
      </tr>
    </table>
  </fieldset>

  <fieldset class='subcontainer' style='width:1200px;'>
    <legend>Medicamentos Dispensados</legend>
    <div id="gridRemedios"></div>
    <select name="DadosGridRemedios" id="DadosGridRemedios" style="display:none;"></select>
  </fieldset>


  <div class="container">

    <input name="confirmar" type="button" id="confirmar" value="Confirmar" disabled  onClick='js_confirma();'>
    <input name="comprovante" type="button" id="comprovante" value="Comprovante" disabled onclick='js_imprime();' >
    <input name    = "retirada"
           type    = "button"
           id      = "retirada"
           value   = "Nova Retirada"
           disabled
           onclick = 'location.href="far1_far_retirada001.php"'>
    <input name    = "continuar"
           type    = "button"
           id      = "continuar"
           value   = "Continuar Retirada"
           disabled
           onClick = 'location.href="far1_far_retirada001.php?fa04_i_cgsund="+$(fa04_i_cgsund).value+
                                  "&lContinuar=true&z01_v_nome="+$(z01_v_nome).value'>
    <input nome="m40_codigo" id="m40_codigo" type="hidden" value="">
  </div>

</form>


<?php // PHP responsavel pela busca dos medicamentos prestes a vencer
  $sSql               = $clmatparam->sql_query_file("", "m90_prazovenc", "", "");
  $result_venc        = $clmatparam->sql_record($sSql);
  if (pg_num_rows($result_venc) <= 0 || pg_num_rows($result_venc) == false) {
    db_msgbox("Parametro do material não encontrado!");
  } else {
    db_fieldsmemory($result_venc,0);
  }
  $data_ini           = date("Y-m-d",db_getsession("DB_datausu"));
  $data_fim           = calcula_data($data_ini,$m90_prazovenc);
  $departamento       = db_getsession("DB_coddepto");
  $nome_departamento  = db_getsession("DB_nomedepto");
  //SQL para buscar os medicamentos proximos da data de vencimento
  $sCampos            = " fa01_i_codigo || ' - ' || m60_descr as item, ";
  $sCampos           .= " m60_codmater, ";
  $sCampos           .= " m77_dtvalidade, ";
  $sCampos           .= " m77_lote, ";
  $sCampos           .= " (m71_quant - m71_quantatend) as m70_quant, ";
  $sCampos           .= " m77_dtvalidade - '$data_ini' as dias ";
  $sWhere             = "     m70_coddepto = $departamento ";
  $sWhere            .= " and m77_dtvalidade is not null ";
  $sWhere            .= " and m77_dtvalidade between '$data_ini' and '$data_fim'";
  $sWhere            .= " and m60_ativo = 't'";
  $sWhere            .= " and (m71_quant - m71_quantatend) > 0";
  $oDaoFarMaterSaude  = db_utils::getdao('far_matersaude');
  $sql_venc           = $oDaoFarMaterSaude->sql_query_matmater("",$sCampos,"m77_dtvalidade asc",$sWhere);
  $result_venc        = $oDaoFarMaterSaude->sql_record($sql_venc);

  $num_rows = $oDaoFarMaterSaude->numrows;
  if($oDaoFarMaterSaude->numrows <= 0) {
?>
  <div class="container">

    <table>
      <tr>
        <td align='center'>
          <big><b>Nenhum medicamento pr&oacute;ximo &agrave; data de validade</b></big>
        </td>
      </tr>
    </table>
  </div>
<?php
  } else {
?>
<!-- TABELA COM OS MEDICAMENTOS PRESTES A VENCER -->
<div class="container">
<table border='1'>
  <tr>
    <td colspan='6' align='center' nowrap>
       <b><big>Medicamentos com prazo de validade a vencer</big></b>
    </td>
  </tr>
  <tr>
    <td align='center'>
      <b>Medicamento</b>
    </td>
    <td>
      <b>Cód. Mater</b>
    </td>
    <td>
      <b>Lote</b>
    </td>
    <td>
      <b>Dias</b>
    </td>
    <td>
      <b>Validade</b>
    </td>
    <td>
      <b>Quantidade</b>
    </td>
  </tr>
<?php
function formata_data($dData, $iTipo = 1) {
  if ($iTipo == 1) {

    $dData = explode('/',$dData);
    $dData = $dData[2].'-'.$dData[1].'-'.$dData[0];
    return $dData;

  }

 $dData = explode('-',$dData);
 $dData = $dData[2].'/'.$dData[1].'/'.$dData[0];
 return $dData;

}


  for ($i = 0; $i < $num_rows; $i++) {
    db_fieldsmemory($result_venc,$i);
    $m77_dtvalidade = formata_data($m77_dtvalidade, 2);
    echo "<tr>
            <td>
              $item
            </td>
            <td>
              $m60_codmater
            </td>
            <td>
              $m77_lote
            </td>
            <td>
              $dias
            </td>
            <td>
              $m77_dtvalidade
            </td>
            <td>
              $m70_quant
            </td>
          </tr>";
  }
?>
</table>
</div>
<?php
  }
?>
<script>
var lImpressaoCupom= <?=$oConfigFarmacia->fa02_utilizaimpressoratermica =='t'?'true':'false'?>;
<?
if (isset($lAbreAcompanhamento) && $lAbreAcompanhamento == 'true') {
  echo "js_abreAcompanhamentoHiperdia($iCgs, $iRetirada);";
}
?>
//inicializa rotina
objGridRemedios = new DBGrid('objGridRemedios');
<? if ($fa42_i_tiporeceita != 0) { ?>
     js_select_tipo_receita();
<? } ?>
js_ativaContinuado();
document.getElementById('m60_descr').onblur = function() {

                                                js_material();
                                                oAutoComplete.oObjAutoComplete.hideDiv();
                                              }
//Autocomplete do medicamento
oAutoComplete = new dbAutoComplete(document.form1.m60_descr,'far4_retirada_autonomeRPC.php?tipo=1');
oAutoComplete.setTxtFieldId(document.getElementById('fa06_i_matersaude'));
oAutoComplete.show();
oAutoComplete.setCallBackFunction(function(id, label, oRetorno) {

  if ($F('fa04_i_cgsund') == '') {

    document.form1.m60_descr.value = '';
    alert('Campo CGS não informado!');
    return;
  }
  document.form1.fa06_i_matersaude.value = id;
  document.form1.fa01_codigobarras.value = oRetorno.codigo_barras;
  document.form1.m60_descr.value         = label;

  js_material();

});


// Autocomplete do profissional
oAutoComplete2 = new dbAutoComplete(document.form1.z01_nome,'far4_retirada_autonomeRPC.php?tipo=5');
oAutoComplete2.setTxtFieldId(document.getElementById('fa04_i_profissional'));
oAutoComplete2.setHeightList(180);
oAutoComplete2.show();

// Autocomplete do CGS
oAutoComplete3 = new dbAutoComplete(document.form1.z01_v_nome,'far4_retirada_autonomeRPC.php?tipo=3');
oAutoComplete3.setTxtFieldId(document.getElementById('fa04_i_cgsund'));
oAutoComplete3.setHeightList(390);
oAutoComplete3.show();
oAutoComplete3.setCallBackFunction(function(id,label) {

                                     document.form1.fa04_i_cgsund.value  = id;
                                     document.form1.z01_v_nome.value     = label;
                                     document.form1.continuados.disabled = false;
                                     $('s115_c_cartaosus').value  = '';
                                     js_init();

                                   });

// Autocomplete do CGS
oAutoComplete4 = new dbAutoComplete(document.form1.z01_v_nomecgs,'far4_retirada_autonomeRPC.php?tipo=3');
oAutoComplete4.setTxtFieldId(document.getElementById('fa08_i_cgsund'));
oAutoComplete4.show();
oAutoComplete4.setCallBackFunction(function(id,label) {

                                     document.form1.fa08_i_cgsund.value = id;
                                     document.form1.z01_v_nomecgs.value = label;
                                     js_pesquisafa08_i_cgsund(false);

                                   });

// Autocomplete da Origem da Receita
oAutoComplete5 = new dbAutoComplete(document.form1.fa40_c_descr,'far4_retirada_autonomeRPC.php?tipo=4');
oAutoComplete5.setTxtFieldId(document.getElementById('fa41_i_origemreceita'));
oAutoComplete5.show();
sUrl = 'far1_far_retiradaRPC.php';
function js_ajax( objParam, jsRetorno, sUrl, lAsync) {

  var mRetornoAjax;

  if (sUrl == undefined) {
    sUrl = 'far1_far_retiradaRPC.php';
  }

  if (lAsync == undefined) {
    lAsync = true;
  }

    var objAjax = new Ajax.Request(
                         sUrl,
                         {
                          method    : 'post',
                          asynchronous: lAsync,
                          parameters: 'json='+Object.toJSON(objParam),
                          onComplete: function(objAjax) {

                                        var evlJS    = jsRetorno+'( objAjax );';
                                        return mRetornoAjax = eval( evlJS );

                                    }
                         }
                        );

  return mRetornoAjax;

}
if(($('fa04_i_cgsund').value != "") && ($('fa04_i_tiporeceita').value != "")) {

  var oGet = js_urlToObject();

  if ( !oGet.lContinuar ) {
    js_init();
  }
}

function js_abre_continuados() {

  if(document.form1.fa04_i_cgsund.value != '' && document.form1.fa04_i_cgsund.value == parseInt(document.form1.fa04_i_cgsund.value)) {

    sCgs = 'fa11_i_cgsund='+document.form1.fa04_i_cgsund.value;
    sBotaoContinuado = '&lBotao=true';

    var iTop    = 20;
    var iLeft   = 5;
    var iHeight = screen.availHeight-210;
    var iWidth  = screen.availWidth-35;

    js_OpenJanelaIframe("","db_iframe_continuados","far1_far_controlemed001.php?"+sCgs+sBotaoContinuado,"Medicamentos Continuados", true, iTop, iLeft, iWidth, iHeight);

    if(document.getElementById('fechardb_iframe_continuados') != undefined) {
      document.getElementById('fechardb_iframe_continuados').onclick = function() { js_fechaFrameContinuados(); }
    }

  } else {

    alert('Selecione um CGS Antes!');
    document.form1.continuados.disabled = true;

  }

}

function js_abreCadProf() {

  var iTop    = 20;
  var iLeft   = 5;
  var iHeight = screen.availHeight-210;
  var iWidth  = screen.availWidth-35;

  if ($F('fa04_i_profissional') == '') {

    sGet = 's154_c_nome='+$F('z01_nome')+'&sd03_i_tipo=2&lBotao=true';
    js_OpenJanelaIframe("", "db_iframe_cadprof", "sau1_sau_medicosforarede001.php?"+sGet,
                        "Cadastro de Profissionais Fora da Rede", true, iTop, iLeft, iWidth, iHeight
                       );

  } else {

    var oParam              = new Object();
    oParam.exec             = 'verificaForaRede';
    oParam.iMedico          = $F('fa04_i_profissional');

    if (js_ajax(oParam, 'js_retornoVerificaForaRede', 'sau4_ambulatorial.RPC.php', false)) {

      sGet = 'chavepesquisa='+$F('fa04_i_profissional')+'&lBotao=true';
      js_OpenJanelaIframe('', "db_iframe_cadprof", "sau1_sau_medicosforarede002.php?"+sGet,
                          'Cadastro de Profissionais Fora da Rede', true, iTop, iLeft, iWidth, iHeight
                         );

    } else {
      alert('Profissional selecionado não é um profissional de fora da rede.');
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

function js_fechaFrameContinuados() {

  js_init();
  db_iframe_continuados.hide();

}

function js_init() {

    var oMarcar   = '<input name="marcarTodos" type="button" id="marcarTodos" ';
    oMarcar      += 'value="M" style="margin: 0;" onClick="js_marcarTodos();">';
    var arrHeader = new Array ( "Cód.",
                                "Medicamento",
                                "Próx. Disp.",
                                "Freq.",
                                "Marg.",
                                "Qtde.",
                                "Saldo",
                                "Estoque",
                                "Validade",
                                "Lote ",
                                "Qtde. Disp.",
                                '<label style="margin-left: 20px; margin-right: 20px;">'
                                + "Dispensa"
                                + '</label>'
                                + oMarcar
                              );

    objGridRemedios              = new DBGrid('objGridRemedios');
    objGridRemedios.nameInstance = 'objGridRemedios';
    objGridRemedios.setHeader( arrHeader );
    objGridRemedios.setCellWidth(new Array('5%', '28%', '8%', '5%', '5%', '5%', '5%', '5%', '8%', '5%', '7%', '13%'));
    objGridRemedios.setHeight(140);
    objGridRemedios.show($('gridRemedios'));
    objGridRemedios.setStatus('* Estoque em vermelho significa que material está em ponto de pedido');

    <?if ((isset($oConfigFarmacia->fa02_i_avisoretirada))
          && ($oConfigFarmacia->fa02_i_avisoretirada != "")
          && ($oConfigFarmacia->fa02_i_avisoretirada != 0 )) {?>

        if ($F('fa04_i_cgsund') != '') {

          //Pesquisa Procedimentos
          var objParam                  = new Object();
          objParam.exec                 = "getAvisoRetirada";
          objParam.fa04_i_cgsund        = $F('fa04_i_cgsund');
          objParam.fa02_i_avisoretirada = <?=$oConfigFarmacia->fa02_i_avisoretirada?>;
          js_ajax( objParam, 'js_retornoAvisoRetirada' );

        }

    <?}?>
    if ($F('fa04_i_cgsund') != '') {
      js_getcontinuado();
    }
}

function js_retornoAvisoRetirada(objAjax){

    var objRetorno = eval("("+objAjax.responseText+")");
      if (objRetorno.status == 1) {
            alert('Última Retirada \n Data:'+objRetorno.dData+
                  ' \n Departamento: '+objRetorno.sDepartamento.urlDecode()+
                  ' \n Medicamento: '+objRetorno.sMedicamentos.urlDecode());
              }

}

function js_tpreceita() {

  tipo=document.form1.fa04_i_tiporeceita.value;
  if (tipo != "") {
   vet = tipo.split('/');
     var table = document.getElementById('tabela1');
     id = "tabela1";
     if (vet[0] == 'S') {
        for (var r = 0; r < table.rows.length; r++){
            var id2 = table.rows[r].id;
            if (id2 == 'linha1') {
                table.rows[r].style.display = '';
            }
        }

     } else {
        for (var r = 0; r < table.rows.length; r++) {
            var id2 = table.rows[r].id;
            if (id2 == 'linha1') {
                table.rows[r].style.display = 'none';
            }
        }
    }
    js_init();
  }

}

function js_ativaContinuado() {

  if ($('checkContinuado').checked == true) {
    sEstado = '';
  } else {
    sEstado = 'none';
  }
  var table = document.getElementById('tabela_medicamento');
  for (r = 0; r < table.rows.length; r++) {

      var id2 = table.rows[r].id;
      if (id2 == 'linha_continuado') {
         table.rows[r].style.display = sEstado;
      }

  }
}

function js_select_tipo_receita() {

  var count_opt = document.form1.fa04_i_tiporeceita.length;
  for (var i = 0; i < count_opt; i++) {

    vet_op = document.form1.fa04_i_tiporeceita.options[i].value.split('/');
    <?
      if (!isset($fa42_i_tiporeceita)) {
        echo "alert('Verifique os parametros da farmacia!');return false;";
      }
    ?>
    if (vet_op[1] == <? if(!isset($fa42_i_tiporeceita) || $fa42_i_tiporeceita == null)
                         echo '0';
                       else
                         echo $fa42_i_tiporeceita;
                    ?>) {
      document.form1.fa04_i_tiporeceita.options[i].selected = true;
      js_tpreceita();
      break;
    }
  }

}

/**
 * Função Busca medicamento continuado de um CGS
 */
function js_getcontinuado() {

  //Pesquisa Procedimentos
  var objParam             = new Object();
  objParam.exec            = "getGridRemedioscont";
  objParam.fa04_i_cgsund   = $F('fa04_i_cgsund');
  vet                      = $('fa04_i_tiporeceita').value.split('/');
  if (vet[3] == '') {
    vet[3]='0';
  }
  objParam.prescricao = vet[3];
  js_ajax( objParam,'js_retornoGridRemedioscont');

}

/**
 * Retorno Grid Continuados
 */
function js_retornoGridRemedioscont( objAjax ) {

  var objRetorno = eval("("+objAjax.responseText+")");
  while ($('DadosGridRemedios').length != 0) {
    $('DadosGridRemedios').remove(0);
  }
  if (objRetorno.itens != undefined && objRetorno.itens.length > 0) {

    objRetorno.itens.each(function (objremedio, intIterator) {

      sStr  = 'C';
      sStr += '#'+objremedio.fa10_i_medicamento;
      sStr += '#'+objremedio.m60_descr.urlDecode().substring(0,35);
      sStr += '#'+objremedio.prox_data.urlDecode();
      sStr += '#'+objremedio.fa10_i_prazo.urlDecode();
      sStr += '#'+objremedio.margem.urlDecode();
      sStr += '#'+objremedio.fa10_i_quantidade;
      sStr += '#'+objremedio.saldo;
      sStr += '#'+objremedio.saldo_estoque;
      sStr += '#'+objremedio.validade.urlDecode();
      sStr += '#'+objremedio.label_lote;
      sStr += '#';
      sStr += '#'+objremedio.fa11_t_obs;
      sStr += '#'+objremedio.lote;
      sStr += '#'+objremedio.m64_pontopedido;
      sStr += '#'+objremedio.hiperdia;
      sStr += '#f';
      sStr += '#';
      $('DadosGridRemedios').add(new Option(sStr,$('DadosGridRemedios').length),null);

    });
    js_AtualizaGrid(true);
  }

}

function js_marcarTodos() {

  for(iI = 0; iI < objGridRemedios.getNumRows(); iI++) {

    if ($('marcarTodos').value == 'M' && !$('editLibera' + iI).disabled) {

      $('editLibera' + iI).checked            = true;
      sText                                   = $('DadosGridRemedios').options[iI].text;
      avet                                    = sText.split('#');
      avet[16]                                = 't';
      sText                                   = avet.join('#');
      $('DadosGridRemedios').options[iI].text = sText;

    } else {

      $('editLibera' + iI).checked            = false;
      sText                                   = $('DadosGridRemedios').options[iI].text;
      avet                                    = sText.split('#');
      avet[16]                                = 'f';
      sText                                   = avet.join('#');
      $('DadosGridRemedios').options[iI].text = sText;

    }

  }
  js_AtualizaGrid();
  if ( $('marcarTodos').value == 'M') {
    $('marcarTodos').value = 'D';
  } else {
    $('marcarTodos').value = 'M';
  }

}

function js_margem() {

  if ($('contiFreq').value != '') {

    if (Number($('contimargem').value) > (Number($('contiFreq').value)/2)) {

      alert("Margem tem que ser menor ou igual a metade da frequência.");
      $('contimargem').value = "";

    }

  }
}

function js_prazo() {

  if ($('contimargem').value != '') {

    if (Number($('contimargem').value) > (Number($('contiFreq').value)/2)) {

      alert("Margem tem que ser menor ou igual a metade da frequência.");
      $('contimargem').value = "";

    }
  }

}

/*
 * Estrutura dos dados no select hidden:
 *
 * Os dados são separados por um sustenido (#) no texto do option
 *
 * 0   tipo           - C = continuado  N = Normal A = add adicionar nos continuados
 * 1   cod.           - Codigo do medicamento
 * 2   Medicamento    - Nome do medicamento
 * 3   Prox.Disp.     - Data da proxima dispensação
 * 4   Freq.          - frequencia do continuado
 * 5   Marg.          - margen do continuado
 * 6   Qtd.           - quantidade do continuado
 * 7   Saldo.         - saldo do continuado
 * 8   Estoque        - estoque do medicamento no departamento
 * 9   Validade       - data de validade do medicamento
 * 10  Lote           - Lote do medicamento
 * 11  Qtd. Disp.     - Quantidade que será dispensada
 * 12  Posologia      - Posologia
 * 13  Cod. matestoqueitem - codigo da matestoqueitem selecionado (código sequencial)
 * 14  ponto pedido   - quantidade minima do material
 * 15  hiperdia       - indica se o medicamento pertence ao hiper dia (boolean)
 * 16  Libera tudo    - indica se é para liberar todo saldo disponivel de continuado
 * 17  Ação prog.     - ação programatica do continuado.
 *
 * Ex: N#3#Clonazepan#12/05/2020#30#3#10#30#5048#10/10/2015#ABDDC#10#2x ao dia#28#15#false#false
 */
function js_AtualizaGrid(bAlertas) {

  objGridRemedios.clearAll(true);
  iTam                          = $('DadosGridRemedios').length;
  $('confirmar').disabled       = false;
  $('comprovante').disabled     = false;
  $('retirada').disabled        = false;
  $('validadeContinuado').value = 0;
  aAvencer                      = new Array();
  aPaint                        = new Array();

  for (x=0; x < iTam; x++) {

    sText       = $('DadosGridRemedios').options[x].text;
    avet        = sText.split('#');
    alinha      = new Array();
    alinha[0]   = avet[1];
    alinha[1]   = avet[2];
    if (parseInt(avet[7],10) > 0) {

      sFont1 = '';
      sFont2 = '';

    } else {

      sFont1='<font color="red">';
      sFont2='</font>';

    }
    alinha[2]   = sFont1+avet[3]+sFont2;
    alinha[3]   = avet[4];
    alinha[4]   = avet[5];
    alinha[5]   = avet[6];
    alinha[6]   = avet[7];
    if (avet[14] == '' || parseInt(avet[14],10) < parseInt(avet[8],10)) {

      sFont1 = '';
      sFont2 = '';

    } else {

      sFont1                    = '<font color="red">';
      sFont2                    = '</font>';
      aAvencer[aAvencer.length] = avet[2];

    }

    alinha[7]   = sFont1+avet[8]+sFont2;
    alinha[8]   = avet[9];
    alinha[9]   = avet[10];
    alinha[10]  = '<input type="text" id="editQuant'+x+'" ';
    blokeio = '';
    if (avet[0] != 'N') {

      aPaint[aPaint.length] = x;
      if (avet[0] == 'C') {
        blokeio = 'disabled';
      }
      <? if ($oConfigFarmacia->fa02_i_validavencimento > 1) {?>

           if (avet[9] != '' && avet[9] != undefined) {
             aData      = avet[9].split('/');
             validade   = new Date(aData[2],(aData[1]-1),aData[0]);
             if (avet[3] == '') {
               <? $aVet = explode('-',$dHoje);
               echo" hoje = new Date($aVet[0],".($aVet[1]-1).",$aVet[2]); ";?>
               tratamento = somaDataDiaMesAno(hoje,avet[4],0,0);

             } else {
               aData      = avet[3].split('/');
               tratamento = new Date(aData[2],(aData[1]-1),aData[0]);

             }
             if (validade.getTime() <= tratamento.getTime()) {

               if(bAlertas != undefined && bAlertas == true){
                 alert('Medicamento '+avet[2]+' vai vencer durante o tratamento!');
               }
               <? if ($oConfigFarmacia->fa02_i_validavencimento == 3) { ?>
       
                    $('validadeContinuado').value = 1;
               <? } ?>

             }
           }
      <? } ?>
    }
    if (avet[9] != '' && avet[9].split('/').length == 3) {

      aData      = avet[9].split('/');
      validade   = aData[2].substr(0,4)+aData[1]+aData[0];
      <? $aVet = explode('-',$dHoje);
         echo" hoje = $aVet[0]$aVet[1]$aVet[2]; ";?>
      if (validade <= hoje) {

        if(bAlertas != undefined && bAlertas == true){
          alert(' O lote do medicamento '+avet[2]+' está vencido! ');
        }
        <? if ($oConfigFarmacia->fa02_i_validavencimento == 3) { ?>
           $('validadeContinuado').value = 1;
        <? } ?>
      }

    }
    if (avet[7] == '') {
      if (avet[0] == 'N') {
        saldo = -1;
      } else {
        saldo = 0;
      }
    } else {
      saldo = avet[7]
    }

    if (avet[8] == '') {
      estoque = 0;
    } else {
      estoque = avet[8];
    }
    if (avet[11] > 0) {
      $('confirmar').disabled = false;
      $('retirada').disabled  = false;
    }
    if (avet[16] == 't') {
      alinha[10] += ' value="'+avet[6]+'" size="5" readonly style="background-color: #DEB887;"';
    } else {
      alinha[10] += ' value="'+avet[11]+'" size="5" ';
    }
    alinha[10] += ' onchange="validaQuantidade('+x+','+saldo+','+estoque+')" > ';
    alinha[11]  = '';
    if ($('fa04_tiporetirada').value == '1') {
      alinha[11] += '<input type="button" name="rateiolote" value="Lote" onclick="js_ratioLote('+x+')">';
    }
    alinha[11] += '&nbsp;<input type="button" name="posologia" value="P" onclick="js_lancar('+x+',\''+avet[12]+'\')">';
    alinha[11] += '&nbsp;<input type="button" name="excluir" value="E" onclick="js_excluirlinha('+x+')" '+blokeio+'>';
    if (avet[0] != 'N') {
      if(avet[16]=='t'){
        sLibera = 'checked';
      }else{
        sLibera = '';
      }
      if (avet[7] == 0 || avet[8] == 0) {
        sSemsaldo = 'disabled';
      } else {
        sSemsaldo = '';
      }
      alinha[11] += '<input type="checkbox" id="editLibera'+x+'" '+sSemsaldo+' ';
      alinha[11] += sLibera+' onclick="js_atualizaCampo('+x+',js_libera(this.checked),16)">';
    }
    objGridRemedios.addRow(alinha);

  }
  objGridRemedios.renderRows();
  iTam = aPaint.length;
  for (iY = 0; iY < iTam; iY++) {
    $('objGridRemediosrowobjGridRemedios'+aPaint[iY]).className = 'classContinuado';
  }
  if(bAlertas != undefined && bAlertas == true){
    iTam = aAvencer.length;
    if (iTam > 0) {

      sStr = aAvencer.join(',');
      alert('Medicamento(s) '+sStr+' , esta em ponto de Pedido ');

    }
  }

}
function js_libera(valor){
  if (valor == true) {
    return 't';
  } else {
    return 'f';
  }
}
function validaQuantidade(x,saldo_tratamento,saldo_estoque) {

  if (saldo_tratamento != -1) {

    menor = saldo_tratamento;
    if (saldo_estoque < saldo_tratamento && $F('fa04_tiporetirada') == 1) {
      menor = saldo_estoque;
    }

  } else {
    if ($F('fa04_tiporetirada') == 1) {
      menor = saldo_estoque;
    } else {
      menor = -1;
    }
  }
  if ($('editQuant'+x).value > menor && menor != -1) {

    alert('Quantidade '+$('editQuant'+x).value+' não disponivel, valor disponivel '+menor);
    $('editQuant'+x).value = menor;

  }
  js_atualizaCampo(x,$('editQuant'+x).value,11);

}

function js_ratioLote(x) {

  sText = $('DadosGridRemedios').options[x].text;
  avet  = sText.split('#');
  valor = $('editQuant'+x).value;
  item  = avet[1];

  if (valor != 0) {

    sUrl  = 'far1_mostraitemlote.php';
    sUrl += '?iCodDepto=<?=$departamento?>';
    sUrl += '&nValor='+valor;
    sUrl += '&nValorSolicitado='+valor;
    sUrl += '&fa01_i_codigo='+item;
    sUrl += '&iGrid='+x;
    js_OpenJanelaIframe('','db_iframe_lotes',sUrl,'Lotes ',true);

  }

}

function js_atualizaCampo(x,valor,index) {

  sText                                  = $('DadosGridRemedios').options[x].text;
  avet                                   = sText.split('#');
  avet[index]                            = valor;
  sText                                  = avet.join('#');
  $('DadosGridRemedios').options[x].text = sText;
  js_AtualizaGrid();

}

function js_atualizaPosologia(x,posologia) {

  sText                                  = $('DadosGridRemedios').options[x].text;
  avet                                   = sText.split('#');
  avet[12]                               = posologia;
  sText                                  = avet.join('#');
  $('DadosGridRemedios').options[x].text = sText;
  js_AtualizaGrid();

}
function js_excluirlinha(x) {

  if (confirm('Tem certeza que deseja remover da grade?')) {

    $('DadosGridRemedios').remove(x);
    js_AtualizaGrid();

  }

}

function js_abre_historico() {

  if ($F('fa04_i_cgsund') != '' && $F(fa04_i_cgsund) == parseInt($F('fa04_i_cgsund'))) {

    cgs     = 'cgs_get='+document.form1.fa04_i_cgsund.value;
    nome    = '&nome='+document.form1.z01_v_nome.value;

    iTop    = 20;
    iLeft   = 5;
    iHeight = screen.availHeight-210;
    iWidth  = screen.availWidth-35;

    js_OpenJanelaIframe("", "db_iframe_historico", "far3_historicopaciente_popup.php?"+cgs+nome,
                        "Hist&oacute;rico de Retiradas de Medicamentos",
                        true, iTop, iLeft, iWidth, iHeight);

  } else {
    alert('Selecione um CGS Antes!');
  }

}

function js_pesquisafa04_i_cgsund(mostra) {

  if (mostra == true) {
    js_OpenJanelaIframe('',
                        'db_iframe_cgs_und',
                        'func_cgs_novo.php?funcao_js=parent.js_mostracgs_und1|z01_i_cgsund|z01_v_nome',
                        'Pesquisa',
                        true);
  } else {

     if (document.form1.fa04_i_cgsund.value != '') {

        js_OpenJanelaIframe('',
                            'db_iframe_cgs_und',
                            'func_cgs_novo.php?pesquisa_chave='+document.form1.fa04_i_cgsund.value+
                            '&funcao_js=parent.js_mostracgs_und',
                            'Pesquisa',
                            false);

     } else {

       if(document.form1.continuados != undefined) {
         document.form1.continuados.disabled = true;
       }
       document.form1.z01_v_nome.value = '';

     }
  }
}
function js_mostracgs_und(chave,erro) {

  $('s115_c_cartaosus').value  = '';
  document.form1.z01_v_nome.value = chave;
  if (erro == true) {

    document.form1.fa04_i_cgsund.focus();
    document.form1.fa04_i_cgsund.value = '';
    if (document.form1.continuados != undefined) {
      document.form1.continuados.disabled = true;
    }

  } else {

    if(document.form1.continuados != undefined) {
      document.form1.continuados.disabled = false;
    }

  }
}
function js_mostracgs_und1(chave1,chave2) {

  $('s115_c_cartaosus').value  = '';
  if (document.form1.continuados != undefined) {
    document.form1.continuados.disabled = false;
  }
  document.form1.fa04_i_cgsund.value = chave1;
  document.form1.z01_v_nome.value    = chave2;
  js_init();
  db_iframe_cgs_und.hide();

}

function js_pesquisafa04_i_tiporeceita(mostra) {

  if (mostra == true) {

    js_OpenJanelaIframe('',
                        'db_iframe_far_tiporeceita',
                        'func_far_tiporeceita.php?'+
                        'funcao_js=parent.js_mostrafar_tiporeceita1|fa03_i_codigo|fa03_c_descr',
                        'Pesquisa',true);

  } else {

     if(document.form1.fa04_i_tiporeceita.value != ''){
        js_OpenJanelaIframe('',
                            'db_iframe_far_tiporeceita',
                            'func_far_tiporeceita.php?'+
                            'pesquisa_chave='+document.form1.fa04_i_tiporeceita.value+
                            '&funcao_js=parent.js_mostrafar_tiporeceita',
                            'Pesquisa',
                            false);

     } else {
       document.form1.fa03_i_codigo.value = '';
     }
  }
}

function js_mostrafar_tiporeceita(chave, erro) {

  document.form1.fa03_c_descr.value = chave;
  if (erro == true) {
    document.form1.fa04_i_tiporeceita.focus();
    document.form1.fa04_i_tiporeceita.value = '';
  }
}
function js_mostrafar_tiporeceita1(chave1, chave2) {

  document.form1.fa04_i_tiporeceita.value = chave1;
  document.form1.fa03_c_descr.value       = chave2;
  db_iframe_far_tiporeceita.hide();

}

function js_pesquisafa04_i_profissional(mostra){

  if (document.form1.fa04_i_tiporeceita.value == " ") {

    alert("informe o tipo da receita");
    document.form1.fa04_i_profissional.value = "";

  } else {

    if(mostra == true) {
      js_OpenJanelaIframe('',
                          'db_iframe_medicos',
                          'func_medicos.php?funcao_js=parent.js_mostramedicos1|sd03_i_codigo|z01_nome'+
                          '&lTodosTiposProf=true','Pesquisa',true
                          );

    } else {

      if (document.form1.fa04_i_profissional.value != '') {

        js_OpenJanelaIframe('',
                            'db_iframe_medicos',
                            'func_medicos.php?'+
                            'pesquisa_chave='+document.form1.fa04_i_profissional.value+
                            '&funcao_js=parent.js_mostramedicos&lTodosTiposProf=true',
                            'Pesquisa',
                            false);

      } else {
          document.form1.z01_nome.value = '';
      }

    }
  }
}
function js_mostramedicos(chave,erro) {

  document.form1.z01_nome.value = chave;
  if (erro === true) {

    document.form1.fa04_i_profissional.focus();
    document.form1.fa04_i_profissional.value = '';

  }

}
function js_mostramedicos1(chave1,chave2) {

  document.form1.fa04_i_profissional.value = chave1;
  document.form1.z01_nome.value            = chave2;
  db_iframe_medicos.hide();

}

/**
 * Valida se o endereço foi preenchido
 * @return bool
 */
function validaEnderecoParaRetirada() {

  if (document.form1.ender.value == "") {
    alert("Campo endereço não informado.Cadastre-o no CGS");
    return false;
  }
  if (document.form1.numero.value == "") {

    alert("Campo número não informado.Cadastre-o no CGS");
    return false;
  }
  if (document.form1.ident.value == "") {

    alert("Campo identidade não informado.Cadastre-o no CGS");
    return false;
  }
  str=''+document.form1.ident.value;
  if (str.length != 10) {

    alert("Campo identidade inválido(10 digitos)!");
    return false;
  }

  return true;
}

function validaRetiradaMedicamento() {

  if($('fa04_i_cgsund').value == '') {

    alert('Campo CGS não informado!');
    return false;
  }

  if ($('fa04_i_tiporeceita').value == '') {

    alert('Selecione um tipo de receita!');
    return false;
  }

  vet = document.form1.fa04_i_tiporeceita.value.split("/");

  if (vet[0] == "S" && !validaEnderecoParaRetirada()) {
    return false;
  }

  return true;
}

function jsPesquisaMedicamento(lMostra) {

  if ( !validaRetiradaMedicamento() ) {

    $('fa06_i_matersaude').value = "";
    $('fa01_codigobarras').value = "";
    $('m60_descr').value         = "";
    return false;
  }

  prescricao = '';
  //verificar qual o numero da prescricao
  vet = $('fa04_i_tiporeceita').value.split('/');
  if ((vet[3] != '') && (vet[3] != '0')&&(vet[3] != undefined)) {
    prescricao='prescricao='+vet[3]+'&';
  }

  var sUrl  = 'func_far_matersaude.php?' + prescricao;
  if ( lMostra ) {

    sUrl += 'funcao_js=parent.js_mostramatersaude1|fa01_i_codigo|m60_descr|fa01_codigobarras';
    js_OpenJanelaIframe('', 'db_iframe_far_matersaude', sUrl, 'Pesquisa Medicamento', true);

  } else if ( $F('fa06_i_matersaude') != '' ) {

    sUrl += 'pesquisa_chave='+$F(fa06_i_matersaude)+'&funcao_js=parent.js_mostramatersaude';
    js_OpenJanelaIframe('', 'db_iframe_far_matersaude', sUrl, 'Pesquisa Medicamento', false);
  } else {

    $('fa01_codigobarras').value   = "";
    $('fa06_i_matersaude').value   = "";
    $('fa06_f_quant').value        = "";
    $('m60_descr').value           = "";
  }

}

function pesquisaMedicamentoCodigoBarras () {

  if ( !validaRetiradaMedicamento() ) {

    $('fa06_i_matersaude').value = "";
    $('fa01_codigobarras').value = "";
    $('fa06_f_quant').value      = "";
    return false;
  }

  prescricao = '';
  //verificar qual o numero da prescricao
  vet = $('fa04_i_tiporeceita').value.split('/');
  if ((vet[3] != '') && (vet[3] != '0')&&(vet[3] != undefined)) {
    prescricao='prescricao='+vet[3]+'&';
  }

  var sUrl  = 'func_far_matersaude.php?' + prescricao;
  if ( $F('fa01_codigobarras') != '' ) {

    sUrl += 'codigo_barras='+$F(fa01_codigobarras)+'&funcao_js=parent.js_mostramatersaude';
    js_OpenJanelaIframe('', 'db_iframe_far_matersaude', sUrl, 'Pesquisa Medicamento', false);
  } else {

    $('fa01_codigobarras').value   = "";
    $('fa06_i_matersaude').value   = "";
    $('quant_disp').value          = "";
    $('m60_descr').value           = "";
  }
}

function js_mostramatersaude(chave1,chave2,erro, codigoBarras){

  document.form1.m60_descr.value = chave2;
  if (erro == true) {

    document.form1.fa01_codigobarras.focus();
    document.form1.fa01_codigobarras.value = '';
    document.form1.fa06_i_matersaude.value = '';

  } else {

    $('fa06_i_matersaude').value   = chave1;
    $('fa01_codigobarras').value   = codigoBarras;
    $('fa06_t_posologia').disabled = false;
    $('ultimas').disabled          = false;
    js_material();

  }
}
function js_mostramatersaude1(chave1,chave2,chave3) {

  document.form1.fa06_i_matersaude.value = chave1;
  document.form1.m60_descr.value         = chave2;
  document.form1.fa01_codigobarras.value = chave3;

  db_iframe_far_matersaude.hide();

  $('fa06_t_posologia').disabled         = false;
  $('ultimas').disabled                  = false;
  js_material();

}

function js_mostraLotes(material,nome_up) {

  if ($('fa04_tiporetirada').value == 2) {

    alert('Tipo de retirada não suporta LOTE!');
    return false;

  }
  if((material != null) && (material != '')) {

    campo    = 'iCodMater';
    campo_up = nome_up;
    item     = material;
    valor    = $(nome_up).value

  } else {

    campo    = 'fa01_i_codigo';
    campo_up = 'fa06_f_quant';
    item     = $('fa06_i_matersaude').value;
    valor    = $('fa06_f_quant').value;

  }
  if (valor != 0) {

        var sUrl  = 'far1_mostraitemlote.php?iCodDepto=<?=$departamento?>&nValor='+valor;
        sUrl     += '&nValorSolicitado='+valor;
        sUrl     += '&updateField='+campo_up;
        sUrl     += '&'+campo+'='+item;
        <? if ($oConfigFarmacia->fa02_i_validalote == 1) { ?>
        sUrl     += '&ilancaDireto=1';
        <? } ?>
        js_OpenJanelaIframe('','db_iframe_lotes',sUrl,'Lotes ',true);

    }
    return false;
}
function js_imprime() {

   var obj   = document.form1;
   var query = "";
   if (obj.m40_codigo.value != "") {

     if (!lImpressaoCupom) {

       query  = 'ini='+obj.fa04_i_codigo.value;
       query += '&fim='+obj.fa04_i_codigo.value;
       query += '&iTipoRetirada='+$F('fa04_tiporetirada');
       jan = window.open('far1_atendretira001.php?nvias=1&'+query,
                         '',
                         'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
       jan.moveTo(0,0);
     } else {

       js_impressaoCupom(obj.fa04_i_codigo.value);

     }
   } else {

     $('comprovante').disabled = true;
     js_confirma(true);

   }

}

function js_impressaoCupom(iRetirada) {

  var oParam           = new Object();
  oParam.exec          = 'impressaoComprovante';
  oParam.iTipoRetirada = $F('fa04_tiporetirada');
  oParam.iRetirada     = iRetirada;

  js_divCarregando('Aguarde, imprimindo', 'msgBox');
  var oAjax = new Ajax.Request('far4_impressaoComprovante.RPC.php',
                              {method:'post',
                              asynchronous:false,
                              parameters:'json='+Object.toJSON(oParam),
                              onComplete:js_retornoCupom
                              })
}

function js_retornoCupom(oAjax) {
  js_removeObj('msgBox');
}
function js_pesquisafa08_i_cgsund(mostra) {
  if (mostra == true) {
    js_OpenJanelaIframe('',
                      'db_iframe_cgs_und',
                      'func_cgs_far.php?'+
                     'funcao_js=parent.js_mostracgs_undi1|z01_i_cgsund|z01_v_nome|z01_v_ender|z01_i_numero|z01_v_ident',
                      'Pesquisa',
                      true);
  } else {
    if (document.form1.fa08_i_cgsund.value != '') {
      js_OpenJanelaIframe('',
                          'db_iframe_cgs_und',
                          'func_cgs_far.php?pesquisa_chave='+$F('fa08_i_cgsund')+'&funcao_js=parent.js_mostracgs_undi',
                          'Pesquisa',
                          false);
    } else {
      document.form1.z01_i_cgsund.value = '';
    }
  }
}
function js_mostracgs_undi(chave, chave2, chave3, chave4, erro) {

  document.form1.ender.value         = chave2;
  document.form1.numero.value        = chave3;
  document.form1.ident.value         = chave4;
  document.form1.z01_v_nomecgs.value = chave;
  if (erro == true) {

    document.form1.fa08_i_cgsund.focus();
    document.form1.fa08_i_cgsund.value = '';

  }

}
function js_mostracgs_undi1(chave1, chave2, chave3, chave4, chave5) {

  document.form1.fa08_i_cgsund.value = chave1;
  document.form1.z01_v_nomecgs.value = chave2;
  document.form1.ender.value         = chave3;
  document.form1.numero.value        = chave4;
  document.form1.ident.value         = chave5;
  db_iframe_cgs_und.hide();

}
function js_tpreceita() {
    
  tipo = document.form1.fa04_i_tiporeceita.value;
  if (tipo != "") {

    vet = tipo.split('/');
    var table = document.getElementById('tabela1');
    id = "tabela1";
    if(vet[0] == 'S'){

      for (var r = 0; r < table.rows.length; r++) {

        var id2 = table.rows[r].id;
        if(id2=='linha1'){
          table.rows[r].style.display = '';
        }

      }

    } else {

      for (var r = 0; r < table.rows.length; r++) {

        var id2 = table.rows[r].id;
        if(id2=='linha1'){
          table.rows[r].style.display = 'none';
        }

      }
    }
    js_init();
  }
}
function js_lancar(iGrid,texto) {

  iTop       = (screen.availHeight - 600) / 2;
  iLeft      = (screen.availWidth - 600) / 2;
  posologia = $('posologia_edit').value;
  sGrid     = '';
  if ((iGrid != undefined) && (iGrid != -1)) {

    sGrid     = '&iGrid='+iGrid;
    posologia = texto;

  }
  js_OpenJanelaIframe("",
                      "db_iframe_posologia",
                      "far1_far_posologia001.php?posologiat="+posologia+sGrid,"Pesquisa",true,iTop, iLeft, 600, 200);

}
function js_quantidade(valor) {

  if (valor != "") {

    if(valor > new Number(document.form1.quant_disp.value)){

      alert('Quantidade requisitada maior que a disponível!');
      $('fa06_f_quant').value = '';

    }
    vet = $('fa04_i_tiporeceita').value.split('/');
    <?
      if ($oConfigFarmacia->fa02_i_validalote == 1) {
    ?>
       js_mostraLotes('','fa06_f_quant');
    <?
      }
    ?>
    if (vet[2] == 'S') {
      js_lancar();
    }
  }
}

function js_material() {

  document.form1.quant_disp.value='';
  if (document.form1.m60_descr.value == '') {

    document.form1.fa06_i_matersaude.value = '';
    document.form1.quant_disp.value        = '';

  }
  if ($('fa06_i_matersaude').value != "") {

    //Pesquisa Procedimentos
    var objParam               = new Object();
    objParam.exec              = "ConsultaSaldo";
    objParam.fa06_i_matersaude = $F('fa06_i_matersaude');
    objParam.fa04_i_cgsund     = $F('fa04_i_cgsund');
    js_ajax( objParam, 'js_retornoMaterial' );

  }
}

function js_retornoMaterial(oAjax) {

  var obj                        = eval("("+oAjax.responseText+")");
  if (obj.status == 1) {
    if (obj.iExContinuado == 0) {
      $('quant_disp').value          = obj.quant_disp;
      $('lote_edit').value           = obj.lote;
      $('lote').value                = obj.loteReal;
      $('validade_edit').value       = obj.validade;
      $('pontoPedido').value         = obj.m64_pontopedido;
      $('hiperdia').value            = obj.hiperdia;
      $('fa06_t_posologia').disabled = false;
      $('ultimas').disabled          = false;
    } else {
      //adiciona no grid o continuado que retornou

      if(!js_verificaLanc($F('fa06_i_matersaude'))){
        return false;
      }
      sStr  = 'C';
      sStr += '#'+$F('fa06_i_matersaude');
      sStr += '#'+$F('m60_descr').substring(0,35);
      sStr += '#'+obj.prox_data.urlDecode();
      sStr += '#'+obj.fa10_i_prazo.urlDecode();
      sStr += '#'+obj.fa10_i_margem.urlDecode();
      sStr += '#'+obj.fa10_i_quantidade;
      sStr += '#'+obj.saldo_atual;
      sStr += '#'+obj.quant_disp;
      sStr += '#'+obj.validade.urlDecode();
      sStr += '#'+obj.loteReal;
      sStr += '#';
      sStr += '#';
      sStr += '#'+obj.lote;
      sStr += '#'+obj.m64_pontopedido;
      sStr += '#'+obj.hiperdia;
      sStr += '#f';
      sStr += '#';
      $('DadosGridRemedios').add(new Option(sStr,$('DadosGridRemedios').length),null);
      js_AtualizaGrid();
      $('fa06_i_matersaude').value = '';
      $('m60_descr').value = '';
    }
  } else {
    alert(obj.message.urlDecode());
  }
}

function js_incluiremedio() {

  if ( $F('fa04_i_cgsund') == '' ) {

    alert('Campo CGS não informado!');
    return;
  }

  if($F('fa04_i_tiporeceita') == ""){

    alert("Informe o tipo de receita");
    return false;
  }

  fa06_f_quant = new Number(document.form1.fa06_f_quant.value );
  if ($('fa04_tiporetirada').value == 1 || fa06_f_quant == 0) {

    quant_disp = new Number(document.form1.quant_disp.value );
    if (fa06_f_quant > quant_disp || fa06_f_quant == 0) {

      alert('Informe uma Quantidade Válida!');
      document.form1.fa06_f_quant.value="";
      document.form1.fa06_f_quant.focus();
      return false;

    }
  }

  vet = document.form1.fa04_i_tiporeceita.value.split('/');
  if (document.form1.posologia_edit.value == "") {
    if (vet[2] == 'S') {

       js_lancar();
       alert("Campo Posologia Obrigatório");
       return false;

    }
  }
  if (document.form1.fa04_i_profissional.value == "") {
    if (vet[4] == 'S') {

      alert("Campo Profissional Obrigatório");
      return false;

    }
  }
  if (document.form1.fa04_i_receita.value == "") {
    if (vet[5] == 'S') {

      alert("Campo Numero receita Obrigatório");
      return false;

    }
  }
  if(!js_verificaLanc($F('fa06_i_matersaude'))){
    return false;
  }
  if ($('checkContinuado').checked == true) {
    sStr  = 'A';
    if (new Number($F('contiQuant')) < new Number($F('fa06_f_quant'))) {

      alert(' A quantidade retirada não pode ultrapassar a quantidade do tratamento continuado! ');
      return false;

    }
  } else {
    sStr  = 'N';
  }
  sStr += '#'+$F('fa06_i_matersaude');
  sStr += '#'+$F('m60_descr').substring(0,35);
  sStr += '#';//prox. disp.
  sStr += '#'+$F('contiFreq');
  sStr += '#'+$F('contimargem');
  sStr += '#'+$F('contiQuant');
  sStr += '#'+$F('contiQuant');
  sStr += '#'+$F('quant_disp');
  sStr += '#'+$F('validade_edit');
  sStr += '#'+$F('lote');
  sStr += '#'+$F('fa06_f_quant');
  sStr += '#'+$F('posologia_edit');
  sStr += '#'+$F('lote_edit');
  sStr += '#'+$F('pontoPedido');
  if ($('checkContinuado').checked == true) {
    sStr += '#'+$('hiperdia').value;
  } else {
    sStr += '#false';
  }
  sStr += '#f';
  sStr += '#'+$F('fa10_i_programa');

  $('DadosGridRemedios').add(new Option(sStr,$('DadosGridRemedios').length),null);
  js_AtualizaGrid();

  //blokeia os campos acima  "#DEB887"
  $('fa06_t_posologia').disabled     = true;
  $('ultimas').disabled              = true;
  //cgs
  $('fa04_i_cgsund').disabled        = true;
  //prfissional
  $('fa04_i_profissional').disabled  = true;
  document.links[1].style.color      = "black";
  //tipo receita
  $('fa04_i_tiporeceita').disabled   = true;
  //prfissional
  $('fa04_i_receita').disabled = true;
  //tipo receita
  $('fa04_d_dtvalidade').disabled    = true;
  //requisitante
  $('fa08_i_cgsund').disabled        = true;
  //nome requisitante
  $('z01_nome').disabled             = true;
  //nome CGS
  $('z01_v_nome').disabled           = true;

  //liberar confirmar
  $('confirmar').disabled            = false;
  $('retirada').disabled             = false;
  $('fa06_t_posologia').disabled     = false;
  $('ultimas').disabled              = false;

  //limpar campos
  $('fa06_i_matersaude').value  = '';
  $('fa01_codigobarras').value  = '';
  $('m60_descr').value          = '';
  $('fa06_f_quant').value       = '';
  $('lote_edit').value          = '';
  $('validade_edit').value      = '';
  $('quant_disp').value         = '';
  $('contiQuant').value         = '';
  $('contiFreq').value          = '';
  $('contimargem').value        = '';
  $('checkContinuado').checked  = false;
  $('posologia_edit').value   = '';

  js_ativaContinuado();

}

function js_verificaLanc(iCodigo){
  iTam = $('DadosGridRemedios').length
  for (iX=0; iX < iTam; iX++) {

    aVetmed = $('DadosGridRemedios').options[iX].text.split('#');
    if (iCodigo == aVetmed[1]) {

      alert('Medicamento já lançado!');
      return false;

    }
  }
  return true;
}
function js_confirma(lComprovante) {

  if (lComprovante == undefined) {
    lComprovante = false;
  }

  if ( $F('fa04_i_cgsund') == '' ) {

    alert('Campo CGS não informado!');
    return;
  }


  $('confirmar').disabled = true;
  <?if ($oConfigFarmacia->fa02_b_novaretirada == "f") {?>
  if (confirm('Tem certeza que deseja efetuar a retirada deste(s) medicamento(s)!')) {
  <?}?>
    if (document.form1.fa04_i_tiporeceita.value == "") {

      alert("Informe o tipo de receita");
      $('confirmar').disabled = false;
      return false;

    }
    

    var table = document.getElementById("objGridRemediosbody").rows;
    var enableConfirm = false;
          
    for (tr in table) {
         
       if (typeof table[tr] != 'object') {
          continue;
       }

       if (table[tr].getAttribute('class') != 'classContinuado') {
         continue;
       }

       for (var i = 0; i < table[tr].childNodes.length; i++) {   
           if (typeof table[tr].childNodes[i].getAttribute !==  "undefined") {
               if (table[tr].childNodes[i].getAttribute('id') == "objGridRemediosrow"+tr+"cell10") {
                    var input = table[tr].childNodes[i];  
                    if (input.childNodes[0].getAttribute('value') != "") {
                       enableConfirm = true;
                    }
               }       
           }
           
       }

    }      
  
    if ($F('validadeContinuado') != 0 && enableConfirm) {

      alert("Existem medicamentos próximos da data de vencimento ou vencidos!");
      $('confirmar').disabled = false;
      return false;

    }

    vet = document.form1.fa04_i_tiporeceita.value.split('/');
    if (document.form1.fa04_i_profissional.value == "") {
      if (vet[4] == 'S') {

        alert("Campo Profissional Obrigatório");
        $('confirmar').disabled = false;
        return false;

      }
    }
    if (document.form1.fa04_i_receita.value == "") {
      if (vet[5] == 'S') {

        alert("Campo aNumero receita Obrigatório");
        $('confirmar').disabled = false;
        return false;

      }
    }

    var objParam                = new Object();
    objParam.exec               = "Confirma_Remedio";
    objParam.iTiporetirada      = $('fa04_tiporetirada').value;
    objParam.numero_receita     = $('fa04_i_receita').value;
    objParam.validade_receita   = $('fa04_d_dtvalidade').value;
    objParam.profissional       = $('fa04_i_profissional').value;
    objParam.cgs                = document.form1.fa04_i_cgsund.value;
    objParam.iNumeronotificacao = $('fa04_numeronotificacao').value;
    objParam.lReceitaSistema    = lReceitaSistema;

    if (<?=$oConfigFarmacia->fa02_i_origemreceita?> == 1) {

      if ($F('fa40_c_descr') != '') {
        objParam.sOrigemReceita = document.form1.fa40_c_descr.value;
      }

    }

    tipo = $('fa04_i_tiporeceita').value;

    if (tipo != "") {

      vet = tipo.split('/');

      if (vet[0] == 'S') {

        objParam.tipo_receita = vet[1];

        if (($('fa08_i_cgsund').value != null) && ($('fa08_i_cgsund').value != '')) {
          objParam.requi_cgs = $('fa08_i_cgsund').value;
        }

        objParam.requi_nome   = $('z01_v_nomecgs').value;
        objParam.requi_ender  = $('ender').value;
        objParam.requi_numero = $('numero').value;
        objParam.requi_ident  = $('ident').value;

      } else {
        objParam.tipo_receita = vet[1];
      }

    } else {

      alert('Tipo receita não selecionado');
      return false;

    }

    //dados do grid

    tam           = $('DadosGridRemedios').length;
    aMedicamentos = new Array();

    for(iX = 0; iX < tam; iX++){

      sText = $('DadosGridRemedios').options[iX].text;
      avet  = sText.split('#');
      if (($('editQuant'+iX).value > 0 && $('editQuant'+iX).value != '')
          || avet[16] == 't' || avet[16] == 'true') {

        if (avet[16] == 'f' || avet[16] == 'false') {
          avet[11]    = $('editQuant'+iX).value;
        } else {
          if (parseInt(avet[7],10) < parseInt(avet[8],10)) {
            avet[11] = avet[7];
          } else {
            avet[11] = avet[8];
          }
        }
        avet[2] = ''; // Retiro o nome do medicamento pq dá problema quando tem % no nome
        //É retirado qualquer caracter que possa influenciar na requisição AJAX na pososlogia posição[12]
        var reg = /[^A-Za-z0-9]/g;
        sStr    = retirarAcento(avet[12]);
        sStr    = sStr.replace(reg,' ');
        avet[12] = sStr;
        aMedicamentos[aMedicamentos.length] = avet.join('_|_');

      }
    }
    if (aMedicamentos.length > 0) {
      objParam.aMedicamentos = aMedicamentos;
    } else {

      alert("Nenhum medicamento para retirar!");
      $('confirmar').disabled = false;
      return false;

    }
    objParam.lImprimirComprovante = lComprovante;
    js_divCarregando('Aguarde, salvando retirada de medicamentos...', 'msgBoxA');
    js_ajax( objParam, 'js_retornoConfirma' );

    $('confirmar').disabled = true;

  <?if ($oConfigFarmacia->fa02_b_novaretirada == 'f') {?>

  } else {
    $('confirmar').disabled = false;
  }
  <? } ?>
}

function retirarAcento(text) {

  text = text.replace(new RegExp('[ÁÀÂÃ]','gi'), 'A');
  text = text.replace(new RegExp('[ÉÈÊ]','gi'), 'E');
  text = text.replace(new RegExp('[ÍÌÎ]','gi'), 'I');
  text = text.replace(new RegExp('[ÓÒÔÕ]','gi'), 'O');
  text = text.replace(new RegExp('[ÚÙÛ]','gi'), 'U');
  text = text.replace(new RegExp('[Ç]','gi'), 'C');
  text = text.replace(new RegExp('[àáâã]','gi'), 'a');
  text = text.replace(new RegExp('[éèê]','gi'), 'e');
  text = text.replace(new RegExp('[íìî]','gi'), 'i');
  text = text.replace(new RegExp('[óòôõ]','gi'), 'o');
  text = text.replace(new RegExp('[úùû]','gi'), 'u');
  text = text.replace(new RegExp('[ç]','gi'), 'c');
  return text;

}

function js_retornoConfirma(objAjax) {

  js_removeObj('msgBoxA');
  var objRetorno = eval("("+objAjax.responseText+")");

  if (objRetorno.status == 1) {

    // Verifico se o acompanhamento do hiperdia deve ser aberto
    lHiperdia = js_verificaHiperdia(objRetorno.iRetirada, false);
    iCgs      = $F('fa04_i_cgsund');

    <?
    // Se o parâmetro para verificação dos medicamentos do hiperdia estiver setado
    if ($oConfigFarmacia->fa02_i_verificapacientehiperdia == 1 && $oConfigFarmacia->fa02_b_novaretirada != "t") {
      echo "js_verificaHiperdia(objRetorno.iRetirada);";
    }
    ?>

    //retorno confirmar
    $('fa04_i_codigo').value        = objRetorno.fa04_i_codigo;
    $('m40_codigo').value           = objRetorno.m40_codigo;

    //Blokear todas os campos do formulario
    $('fa06_f_quant').disabled      = true;
    $('fa06_i_matersaude').disabled = true;
    $('m60_descr').disabled         = true;

    //limpando grids
    objGridRemedios.clearAll(true);
    objGridRemedios.renderRows();

    //liberar os outros 3 botões
    $('comprovante').disabled       = false;
    $('retirada').disabled          = false;
    $('continuar').disabled         = false;
    $('confirmar').disabled         = true;

    while ($('DadosGridRemedios').length != 0) {
      $('DadosGridRemedios').remove(0);
    }

    if (objRetorno.lImprimirComprovante == true || '<?=$oConfigFarmacia->fa02_b_comprovante?>' == 't') {
      js_imprime();
    }

    <?
    if ($oConfigFarmacia->fa02_b_novaretirada == "t") {
      // Se o parâmetro para verificação dos medicamentos do h
      if ($oConfigFarmacia->fa02_i_verificapacientehiperdia == 1) {

        echo "location.href = 'far1_far_retirada001.php?lAbreAcompanhamento='";
        echo "+lHiperdia+'&iRetirada='+objRetorno.iRetirada+'&iCgs='+iCgs;";
      } else {

        echo "alert('Retirada efetuada com sucesso!');";
        echo "location.href = 'far1_far_retirada001.php';";
      }

    } else {
      echo "alert('Retirada efetuada com sucesso!');";
    }
    ?>
  } else {
    alert(objRetorno.message.urlDecode());
  }

}

function js_abreAcompanhamentoHiperdia(iCgs, iRetirada) {

  iTop     = 200 / 6;
  iLeft    = 15 / 5;   
  js_OpenJanelaIframe('', 'db_iframe_acompanhamento', 'far4_far_cadacomppachiperdia001.php?'+
                      'iCgs='+iCgs+'&iRetirada='+iRetirada, "Acompanhamento de Paciente do  Hiperdia",
                      true, iTop, iLeft, screen.availWidth - 30, screen.availHeight - 200
                     );

}

function js_verificaHiperdia(iRetirada, lAbrePopUp) {

  if (lAbrePopUp == undefined) {
    lAbrePopUp = true;
  }
  oSel         = $('DadosGridRemedios');
  iNumRowsGrid = oSel.length;

  for (var iCont = 0; iCont < iNumRowsGrid; iCont++) {

    // Se o medicamento foi dispensado
    if ($F('editQuant'+iCont) != '' && $F('editQuant'+iCont) > 0) {

      var aDados = oSel.options[iCont].text.split('#');

      /* Se o medicamento é um medicamento do hiperdia e está nos continuados do paciente
         ou foi marcado para cadastrar nos continuados */
      if (aDados[15] == 'true' && (aDados[0] == 'C' || aDados[0] == 'A')) {

        if (lAbrePopUp) {
          js_abreAcompanhamentoHiperdia($F('fa04_i_cgsund'), iRetirada);
        }
        return 'true';

      }

    }

  }
  return 'false';

}

function js_ultimas() {

  var iTop    = (screen.availHeight - 600) / 5;
  var left    = (screen.availWidth - 600) / 5;   
  var remedio = $('fa06_i_matersaude').value;
  var cgs     = $('fa04_i_cgsund').value;
  if (remedio != '') {

    js_OpenJanelaIframe("",
                        "db_iframe_ultimas",
                        "far1_far_ultimas_retiradas001.php?medicamento="
                        +remedio+"&cgs="+cgs,
                        "Pesquisa",
                        true,
                        iTop,
                        left,
                        800,
                        300);

  } else {
    alert('Remédio não informado!');
  }
}

function js_select_tipo_receita() {
  var count_opt = document.form1.fa04_i_tiporeceita.length;
  for (var i = 0; i < count_opt; i++) {

    vet_op = document.form1.fa04_i_tiporeceita.options[i].value.split('/');
    <?
      if(!isset($fa42_i_tiporeceita)){
        echo "alert('Verifique os parametros da farmacia!');return false;";
      }
    ?>
    if (vet_op[1] == <? if(!isset($fa42_i_tiporeceita) || $fa42_i_tiporeceita == null)
                         echo '0';
                       else
                         echo $fa42_i_tiporeceita;
                    ?>) {
      document.form1.fa04_i_tiporeceita.options[i].selected = true;
      js_tpreceita();
      break;
    }
  }
}

function js_getCgsCns() {

  if ($F('s115_c_cartaosus') == '') {
    return false;
  }
  if ($F('s115_c_cartaosus').length != 15 || isNaN($F('s115_c_cartaosus'))) {

    alert('Número de CNS inválido para busca.');
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

    alert('CNS não encontrado.');
    return false;

  }
  $('fa04_i_cgsund').value = oRetorno.z01_i_cgsund;
  $('z01_v_nome').value    = oRetorno.z01_v_nome.urlDecode();
  js_init();

}

function js_pesquisafa41_i_origemreceita(mostra) {

  if (mostra == true) {

    js_OpenJanelaIframe('',
                        'db_iframe_far_origemreceita',
                        'func_far_origemreceita.php?funcao_js=parent.js_mostraorigemreceita1|fa40_i_codigo|'+
                        'fa40_c_descr&chave_validade=true','Pesquisa',true);

  } else {

    if(document.form1.fa41_i_origemreceita.value != '') {

      js_OpenJanelaIframe('','db_iframe_far_origemreceita',
                          'func_far_origemreceita.php?funcao_js=parent.js_mostraorigemreceita1|fa40_i_codigo|'+
                          'fa40_c_descr&pesquisa_chave='+$F('fa41_i_origemreceita')+'&chave_validade=true',
                          'Pesquisa',true);

    } else {
      document.form1.fa40_c_descr.value = '';
    }

  }

}
function js_mostraorigemreceita(chave, erro) {

  document.form1.fa40_c_descr.value = chave;
  if(erro == true) {

    document.form1.fa41_i_origemreceita.focus();
    document.form1.fa41_i_origemreceita.value = '';

  }

}
function js_mostraorigemreceita1(chave1, chave2) {

  document.form1.fa41_i_origemreceita.value = chave1;
  document.form1.fa40_c_descr.value         = chave2;
  db_iframe_far_origemreceita.hide();

}

function js_preencheMedicoRecemCadastrado(iCod, sNome) {

  $('fa04_i_profissional').value = iCod;
  js_pesquisafa04_i_profissional(false);

}

function js_pesquisareceita(mostra) {

  if ($F('fa04_i_cgsund') == '') {

    alert('Informe o paciente (CGS) primeiro.');
    $('fa04_i_receita').value = '';
    return false;

  }

  if (mostra == true) {

    js_OpenJanelaIframe('', 'db_iframe_sau_receitamedica', 'func_sau_receitamedica.php?'+
                        'funcao_js=parent.js_mostrareceita|s158_i_codigo|db_s158_i_tiporeceita|'+
                        'db_s158_i_profissional|dl_profissional|s158_d_validade'+
                        '&lFiltrarAnuladas=true&lFiltrarAtendidas=true&iChaveCgs='+$F('fa04_i_cgsund'),
                        'Pesquisa de Receitas', true
                       );

  } else {

    if ($F('fa04_i_receita') != '') {

      js_OpenJanelaIframe('', 'db_iframe_sau_receitamedica', 'func_sau_receitamedica.php?'+
                          'chave_s158_i_codigo='+$F('fa04_i_receita')+'&funcao_js=parent.js_mostrareceita|'+
                          's158_i_codigo|db_s158_i_tiporeceita|db_s158_i_profissional|dl_profissional|s158_d_validade'+
                          '&lFiltrarAnuladas=true&lFiltrarAtendidas=true&iChaveCgs='+$F('fa04_i_cgsund')+
                          '&nao_mostra=true',
                          'Pesquisa de Receitas', false
                         );

    } else {

      js_limpaDadosReceita();

    }

  }

}

var lReceitaSistema = true;
function js_mostrareceita(iCodReceita, iTipoReceita, iProfissional, sNomeProf, dValidade) {

  /**
   * Removido aviso de recita inválida. Deve aceitar o código da receita do usuário
   */
  if (iCodReceita == '') {

      lReceitaSistema = false;

      $('fa04_i_cgsund').disabled       = false;
      $('z01_v_nome').disabled          = false;
      $('fa04_i_profissional').disabled = false;
      $('z01_nome').disabled            = false;
      $('s115_c_cartaosus').disabled    = false;
      $('fa04_d_dtvalidade').value      = '';
      $('fa04_i_tiporeceita').value     = 'N/3/N//N/N';
      js_tpreceita();
      objGridRemedios.clearAll(true);
    return false;
  }

  sAtual = '<?=date('Ymd', db_getsession('DB_datausu'))?>';
  sVal   = dValidade.split('-').join('');

  if (sAtual > sVal) { // Receita vencida

    alert('Esta receita venceu dia '+js_formataData(dValidade));
    $('fa04_i_receita').value = '';
    db_iframe_sau_receitamedica.hide();
    return false;

  }

  $('fa04_i_receita').value = iCodReceita;
  js_selectOptionByValue($('fa04_i_tiporeceita'), iTipoReceita);
  js_tpreceita();
  iInd                              = $('fa04_i_tiporeceita').selectedIndex;
  $('fa04_i_tiporeceita').onchange  = function() {
                                        js_selectDisabled($('fa04_i_tiporeceita'), iInd);
                                      };
  $('fa04_i_profissional').value    = iProfissional;
  $('z01_nome').value               = sNomeProf;
  $('fa04_d_dtvalidade').value      = js_formataData(dValidade);
  $('fa04_i_cgsund').disabled       = true;
  $('z01_v_nome').disabled          = true;
  $('fa04_i_profissional').disabled = true;
  $('z01_nome').disabled            = true;
  $('s115_c_cartaosus').disabled    = true;
  $('ancora_cgs').onclick           = '';
  $('ancora_profissional').onclick  = '';

  db_iframe_sau_receitamedica.hide();
  js_abreMedicamentosReceita();

}

function js_limpaDadosReceita() {

  $('fa04_i_receita').value         = '';
  $('fa04_i_profissional').value    = '';
  $('z01_nome').value               = '';
  $('fa04_d_dtvalidade').value      = '';
  $('fa04_i_cgsund').disabled       = false;
  $('z01_v_nome').disabled          = false;
  $('fa04_i_profissional').disabled = false;
  $('z01_nome').disabled            = false;
  $('s115_c_cartaosus').disabled    = false;
  $('ancora_cgs').onclick           = function() {
                                        js_pesquisafa04_i_cgsund(true);
                                      };
  $('ancora_profissional').onclick  = function() {
                                        js_pesquisafa04_i_profissional(true);
                                      };
  $('fa04_i_tiporeceita').onchange  = function() {
                                        js_tpreceita();
                                      };

}

function js_selectOptionByValue(oSel, sValue) {

  for (var iCont = 0; iCont < oSel.options.length; iCont++) {

    if (oSel.options[iCont].value.split('/')[1] == sValue) {

      oSel.selectedIndex = iCont;
      break;

    }

  }

}

function js_selectDisabled(oSel, iValue) {
  oSel.selectedIndex = iValue;
}

function js_formataData(dData) {

  if (dData == undefined || dData.length != 10) {
    return dData;
  }
  return dData.substr(8, 2)+'/'+dData.substr(5, 2)+'/'+dData.substr(0, 4);

}

function js_abreMedicamentosReceita() {

  if ($F('fa04_i_receita') == '') {
    return false;
  }

  sGet  = 's159_i_receita='+$F('fa04_i_receita')+'&fa04_i_cgsund='+$F('fa04_i_cgsund');

  iTop  = (screen.availHeight - 710) / 2;
  iLeft = (screen.availWidth - 800) / 2;   
  js_OpenJanelaIframe('', 'db_iframe_medicamentosreceita', 'sau4_medicamentosreceita.iframe.php?'+sGet,
                      'Medicamentos da Receita', true, iTop, iLeft, 800, 500
                     );


}

/**** funcoes que setam campos do iframe dos continuados */

function js_mostrafar_matersaude(chave, erro) {

  oFormFrame =  document.getElementById('IFdb_iframe_continuados').contentDocument.form1;
  oFormFrame.m60_descr.value = chave;

  if(erro == true) {

    oFormFrame.fa10_i_medicamento.focus();
    oFormFrame.fa10_i_medicamento.value = '';

  }

}
function js_mostrafar_matersaude1(chave1, chave2) {

  oFormFrame =  document.getElementById('IFdb_iframe_continuados').contentDocument.form1;
  oFormFrame.fa10_i_medicamento.value = chave1;
  oFormFrame.m60_descr.value = chave2;
  db_iframe_far_matersaude.hide();

}

function js_mostrafar_programa(chave, erro) {

  oFormFrame =  document.getElementById('IFdb_iframe_continuados').contentDocument.form1;
  oFormFrame.fa12_c_descricao.value = chave;
  if(erro == true) {

    oFormFrame.fa10_i_programa.focus();
    oFormFrame.fa10_i_programa.value = '';

  }

}
function js_mostrafar_programa1(chave1, chave2) {

  oFormFrame =  document.getElementById('IFdb_iframe_continuados').contentDocument.form1;
  oFormFrame.fa10_i_programa.value  = chave1;
  oFormFrame.fa12_c_descricao.value = chave2;
  db_iframe_far_programa.hide();

}
/*  fim das funcoes do iframe dos continuados ****/

<?
  if ($oConfigFarmacia->fa02_i_acaoprog != 0 && $oConfigFarmacia->fa02_i_acaoprog != null) {
    echo "js_pesquisafa10_i_programa(false);";
  }
?>
function js_pesquisafa10_i_programa(mostra) {

  if (mostra == true) {
    js_OpenJanelaIframe('', 'db_iframe_far_programa',
                        'func_far_programa.php?funcao_js=parent.js_mostrafar_programa1|fa12_i_codigo|fa12_c_descricao',
                        'Pesquisa', true);
  } else {

    if (document.form1.fa10_i_programa.value != '') {

      js_OpenJanelaIframe('', 'db_iframe_far_programa',
                          'func_far_programa.php?pesquisa_chave='+document.form1.fa10_i_programa.value+
                          '&funcao_js=parent.js_mostrafar_programa','Pesquisa',false);

    } else {
      document.form1.fa12_i_codigo.value = '';
    }
  }

}
function js_mostrafar_programa(chave,erro) {

  document.form1.fa12_c_descricao.value = chave;
  if (erro == true) {

    document.form1.fa10_i_programa.focus();
    document.form1.fa10_i_programa.value = '';

  }

}
function js_mostrafar_programa1(chave1,chave2) {

  document.form1.fa10_i_programa.value  = chave1;
  document.form1.fa12_c_descricao.value = chave2;
  db_iframe_far_programa.hide();

}
function js_tiporeceita(iOpcao) {
  if (iOpcao == 1) {
    $('lotes').disabled = false;
  } else {
    $('lotes').disabled = true;
  }
}

function js_novoCgs() {

  var sUrl  = 'sau1_cgs_und001.php?funcao_js=parent.js_mostrarNovoCgs';
      sUrl += '&db_menu=false&retornacgs=&retornanome=&redireciona=';

  js_OpenJanelaIframe('', 'db_iframe_cgs_und', sUrl, 'Cadastro de CGM', true);
}

function js_mostrarNovoCgs() {

  $('fa04_i_cgsund').value    = arguments[0];
  $('z01_v_nome').value       = arguments[1];
  $('s115_c_cartaosus').value = '';
}

if ( $('fa01_codigobarras') ) {
  $('fa01_codigobarras').observe('keyup', function(event) {
    if (event.which == 13) {
      document.form1.m60_descr.focus();
    }
  });
}

</script>