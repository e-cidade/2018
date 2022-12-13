<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBseller Servicos de Informatica
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

//MODULO: pessoal
$clcadferia->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
$clrotulo->label("DBtxt23");
$clrotulo->label("DBtxt25");
if(!isset($paga_13)){
  $result_cfpess = $clcfpess->sql_record($clcfpess->sql_query_file(db_anofolha(),db_mesfolha(),db_getsession("DB_instit"),"r11_pagarferias as ponto,r11_13ferias as paga_13"));
  if($clcfpess->numrows > 0){
    db_fieldsmemory($result_cfpess, 0);
  }
}
$iPeriodoaquisitivo   = @$_POST['periodoaquisitivo'];
$sPeriodosvencidosate = @$_POST['periodosvencidosate'];
require_once(modification("libs/db_app.utils.php"));

db_app::load("scripts.js");
db_app::load("prototype.js");

$db_opcao            = 1;
$lComplementarAberta = true;

try {

  if (DBPessoal::verificarUtilizacaoEstruturaSuplementar()) {

    $lSalario            = FolhaPagamentoSalario::hasFolha();
    $lSalarioAberta      = FolhaPagamentoSalario::hasFolhaAberta();
    $lComplementarAberta = FolhaPagamentoComplementar::hasFolhaAberta();

    if (!$lSalario) {

      $db_opcao = 3;
      db_msgbox('Não é possível realizar o cadastro de férias, pois o ponto não encontra-se inicializado.');
    } else {

      if (!$lSalarioAberta && !$lComplementarAberta) {

        $db_opcao = 3;
        db_msgbox('Não é possível cadastrar férias, pois todas as folhas disponíveis estão fechadas.');
      }
    }
  }

  if(isset($r30_regist)) {

    $nDiasServidor   = ServidorRepository::getInstanciaByCodigo($r30_regist, DBPessoal::getAnoFolha(), DBPessoal::getMesFolha() )->getDiasGozoFerias();
    $nDiasServidor   = $nDiasServidor === '' || $nDiasServidor < 30 ? 30 : $nDiasServidor;
    $nDiasGozoFerias = $nDiasServidor;
  }
} catch(Exception $oException) {

  db_msgbox($oException->getMessage());
  db_redireciona('pes4_cadferia001.php');
}

?>

<form name="form1" id='form1' method="post" action="pes4_cadferia004.php">
<center>
<input type="hidden" value="<?=$iPeriodoaquisitivo ?>"   id="periodoaquisitivo"   name = 'periodoaquisitivo' >
<input type="hidden" value="<?=$sPeriodosvencidosate ?>" id="periodosvencidosate" name = 'periodosvencidosate' >
<input type="hidden" value="<?php echo DBPessoal::verificarUtilizacaoEstruturaSuplementar() ? '1' : '0'; ?>" id="db_complementar" name = 'db_complementar' >

<table border="0">
  <tr>
    <td align="center">
      <hr>
      <b><?=$r30_regist." - ".$z01_nome?></b>
      <hr>
    </td>
  </tr>
  <tr>
    <td>
      <fieldset>
  <table>
    <tr>
            <td style="text-align: right;">
               <?=$Lr30_tipoapuracaomedia?>
            </td>
            <td>
               <?
               if ((isset($enviar_selecao) && $enviar_selecao != "") ||
                    isset($campomatriculas) && $campomatriculas != "") {
                 $r30_tipoapuracaomedia     = @$_POST["r30_tipoapuracaomedia"]!=""?$_POST["r30_tipoapuracaomedia"]:$_GET["r30_tipoapuracaomedia"];
                 $r30_periodolivrefinal_ano = @$_POST["r30_periodolivrefinal_ano"]!=""?@$_POST["r30_periodolivrefinal_ano"]:@$_GET["r30_periodolivrefinal_ano"];
                 $r30_periodolivrefinal_mes = @$_POST["r30_periodolivrefinal_mes"]!=""?@$_POST["r30_periodolivrefinal_mes"]:@$_GET["r30_periodolivrefinal_mes"];
                 $r30_periodolivrefinal_dia = @$_POST["r30_periodolivrefinal_dia"]!=""?@$_POST["r30_periodolivrefinal_dia"]:@$_GET["r30_periodolivrefinal_dia"];
                 $r30_periodolivreinicial_ano = @$_POST["r30_periodolivreinicial_ano"]!=""?@$_POST["r30_periodolivreinicial_ano"]:@$_GET["r30_periodolivreinicial_ano"];
                 $r30_periodolivreinicial_mes = @$_POST["r30_periodolivreinicial_mes"]!=""?@$_POST["r30_periodolivreinicial_mes"]:@$_GET["r30_periodolivreinicial_mes"];
                 $r30_periodolivreinicial_dia = @$_POST["r30_periodolivreinicial_dia"]!=""?@$_POST["r30_periodolivreinicial_dia"]:@$_GET["r30_periodolivreinicial_dia"];
               }
               $aTipos = array(1 => "Período Aquisitivo Normal",
                               2 => "Período Específico"
                              );
                db_select('r30_tipoapuracaomedia', $aTipos, true, ($dbopcao?3:1)==1?$db_opcao:3, "onchange='js_showCamposMedia()'")?>
            </td>
          </tr>
    <tr>
      <td nowrap title="<?=@$Tr30_perai?>" align="right">
        <?
        db_input('r30_regist', 7, 0, true, 'hidden', 3);
        db_input('campomatriculas', 4, 0, true, 'hidden', 3);

        db_input('mensagemlote', 4, 0,'', 'hidden', 3);

        db_input('perini_ano', 4, 0, true, 'hidden', 3);
        db_input('perini_mes', 4, 0, true, 'hidden', 3);
        db_input('perini_dia', 4, 0, true, 'hidden', 3);
        db_input('perfim_ano', 4, 0, true, 'hidden', 3);
        db_input('perfim_mes', 4, 0, true, 'hidden', 3);
        db_input('perfim_dia', 4, 0, true, 'hidden', 3);
        db_input('tipofer', 4, 0, true, 'hidden', 3);
        db_input('pontofer', 4, 0, true, 'hidden', 3);
        db_input('pagafer13', 4, 0, true, 'hidden', 3);
        db_input('r44_selec', 4, 0, true, 'hidden', 3);
        db_input('retorno', 4, 0, true, 'hidden', 3);
        db_input('diferenca', 4, 0, true, 'hidden', 3);
        db_input('preanopagto', 4, 0, true, 'hidden', 3);
        db_input('premespagto', 4, 0, true, 'hidden', 3);
        db_input('filtraferiasprocessadas', 4, 0, true, 'hidden', 3);

        db_ancora("<b>Período Aquisitivo:</b>", "", 3);
        ?>
      </td>
      <td colspan="3">
        <?
        db_inputdata('r30_perai', @$r30_perai_dia, @$r30_perai_mes, @$r30_perai_ano, true, 'text', ($dbopcao?3:1)==1?$db_opcao:3, "onChange='js_verificaaquiini();'", "", "", "parent.js_verificaaquiini();");
        ?>
        &nbsp;&nbsp;<b>a</b>&nbsp;&nbsp;
        <?
        db_inputdata('r30_peraf', @$r30_peraf_dia, @$r30_peraf_mes, @$r30_peraf_ano, true, 'text', ($dbopcao?3:1)==1?$db_opcao:3, "onChange='js_verificaaquifim();'", "", "", "parent.js_verificaaquifim();");
        db_input('r30_regist', 10, $Ir30_regist, true, 'hidden', 3);
        ?>
      </td>
    </tr>
    <tr id='linhadatasespecificas' style='display: none'>
      <td align="right">
        <b>
          <b>Período Específico:</b>
        </b>
      </td>
      <td colspan="3">
        <?
        db_inputdata('r30_periodolivreinicial',
                     @$r30_periodolivreinicial_dia,
                     @$r30_periodolivreinicial_mes,
                     @$r30_periodolivreinicial_ano,
                     true, 'text', ($dbopcao?3:1)==1?$db_opcao:3,
                     "onchange='js_calcFim();'",
                     "", "", "js_calcFim();"
                     );
        ?>
        &nbsp;&nbsp;<b>a</b>&nbsp;&nbsp;
        <?
        db_inputdata('r30_periodolivrefinal',
                     @$r30_periodolivrefinal_dia,
                     @$r30_periodolivrefinal_mes,
                     @$r30_periodolivrefinal_ano,
                     true, 'text', ($dbopcao?3:1)==1?$db_opcao:3,
                     "onchange='js_calcIni();'",
                     "", "", "js_calcIni();"
                    );
        ?>
    </tr>
    <tr>
      <td nowrap title="<?=@$Tr30_faltas?>" align="right">
        <?
        db_ancora(@$Lr30_faltas, "", 3);
        ?>
      </td>
      <td>
        <?
        db_input('r30_faltas', 7, $Ir30_faltas, true, 'text', ($dbopcao?3:1)==1?$db_opcao:3,"onchange=\"js_faltas('vfalta','','','',this.value,document.form1.navos.value!=''?document.form1.navos.value:0);\"");
        ?>
      </td>
      <td nowrap title="<?=@$Tr30_ndias?>" align="right">
        <?
        db_ancora(@$Lr30_ndias, "", 3);
        ?>
      </td>
      <td>
        <?
        if(!isset($r30_ndias) || (isset($r30_ndias) && trim($r30_ndias) == "")){

          $r30_ndias = $nDiasServidor;

        }
        db_input('r30_ndias', 7, $Ir30_ndias, true, 'text', 3);
        db_input('nDiasGozoFerias', 7, 1, true, 'hidden', 3);
        ?>
        <input type='hidden' id='gozar_old' size="5" />
      </td>
    </tr>
    <?
    if($dbopcao == false){
    ?>
    <tr>
      <td nowrap title="Forma de pgto" align="right">
        <?
        db_ancora("<b>Forma de pgto:</b>", "", 3);
        ?>
      </td>
      <td colspan="3">
<?


        $mtipo = ($nDiasServidor == 30) ? '01' : '12';

        $arr_fpagto = Array(
                            "01"=>"01 - 30 dias ferias",
                            "02"=>"02 - 20 dias ferias",
                            "03"=>"03 - 15 dias ferias",
                            "04"=>"04 - 10 dias ferias",
                            "05"=>"05 - 20 dias ferias + 10 dias abono",
                            "06"=>"06 - 15 dias ferias + 15 dias abono",
                            "07"=>"07 - 10 dias ferias + 20 dias abono",
                            "08"=>"08 - 30 dias abono",
                            "12"=>"12 - Dias Livre"
                           );
        db_select("mtipo", $arr_fpagto, true, $db_opcao,"onchange='js_validamtipo();'");
        ?>
      </td>
    </tr>
    <?
    }else{
      db_input('mtipo', 7, $Ir30_ndias, true, 'hidden', 3);
    }
    ?>
    <tr>
      <td nowrap title="<?=@$Tr30_abono?>" align="right">
        <?
        db_ancora(@$Lr30_abono, "", 3);
        ?>
      </td>
      <td>
        <?
        db_input('r30_abono', 7, $Ir30_abono, true, 'text', 3);
        ?>
      </td>
      <td nowrap title="<?=@$Tr30_proc1?>" align="right">
        <?
        db_ancora("<b>Pago em:</b>", "", 3);
        ?>
      </td>
      <td>
        <?
        db_input('r30_proc1', 7, $Ir30_proc1, true, 'text', 3);
        ?>
      </td>
    </tr>
    <tr>
      <td nowrap title="Dias a gozar" align="right">
        <?
        db_ancora("<b>Dias a gozar:</b>", "", 3);
        ?>
      </td>
      <td>
        <?
        db_input('nsaldo', 7, $Ir30_ndias, true, 'text', $db_opcao, 'onchange="js_verificadataini(1)"');
        ?>
      </td>
      <td nowrap title="Dias a abonar" align="right">
        <?
        db_ancora("<b>Dias a abonar:</b>", "", 3);
        ?>
      </td>
      <td>
        <?
        db_input('nabono', 7, $Ir30_ndias, true, 'text', 3);
        db_input('navos', 7, $Ir30_ndias, true, 'hidden', 3);
        db_input('antes', 7, 0, true, 'hidden', 3);
        ?>
      </td>
    </tr>
    <?
    if($dbopcao == false){
    ?>
    <tr>
      <td nowrap title="Período a gozar" align="right">
        <?
        db_ancora("<b>Período a gozar:</b>", "", 3);
        ?>
      </td>
      <td colspan="3">
        <?
        if(!isset($r30_per1i)){
          $r30_per1i = "";
          $r30_per1i_dia = "";
          $r30_per1i_mes = "";
          $r30_per1i_ano = "";

          $r30_per1f = "";
          $r30_per1f_dia = "";
          $r30_per1f_mes = "";
          $r30_per1f_ano = "";
        }


        db_inputdata('r30_per1i', @$r30_per1i_dia, @$r30_per1i_mes, @$r30_per1i_ano, true, 'text', $db_opcao, "onchange='js_verificadataini(1);'","","","parent.js_verificadataini(1);");
        ?>
        &nbsp;&nbsp;<b>a</b>&nbsp;&nbsp;
        <?
        db_inputdata('r30_per1f', @$r30_per1f_dia, @$r30_per1f_mes, @$r30_per1f_ano, true, 'text', $db_opcao, "onchange='js_verificadatafim(1);'","","","parent.js_verificadatafim(1);");
        ?>
      </td>
    </tr>
    <?
    }
    ?>
  <?
  if($dbopcao == true && !isset($mtipo)){
  ?>
        </table>
      </fieldset>
    </td>
  </tr>
  <tr>
    <td>
      <fieldset>
        <legend>
          <b>Período de gozo</b>
        </legend>
        <table>
          <tr>
            <td nowrap title="Período a gozar" align="right">
              <?
              db_ancora("<b>Primeiro período:</b>", "", 3);
              ?>
            </td>
            <td colspan="3">
              <?
              db_inputdata('r30_per1i', @$r30_per1i_dia, @$r30_per1i_mes, @$r30_per1i_ano, true, 'text', 3, "");
              ?>
              &nbsp;&nbsp;<b>a</b>&nbsp;&nbsp;
              <?
              db_inputdata('r30_per1f', @$r30_per1f_dia, @$r30_per1f_mes, @$r30_per1f_ano, true, 'text', 3, "");
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="Saldo" align="right">
              <?
              db_ancora("<b>Saldo:</b>", "", 3);
              ?>
            </td>
            <td>
              <?
              if(!isset($saldo)){
                $saldo = "10";
              }
              $arr_saldo = Array("10"=>"Férias","09"=>"Abono");
              db_select("saldo", $arr_saldo, true, 1, "onchange='js_habilitaperiodo(1);'");
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="Período a gozar" align="right">
              <?
              db_ancora("<b>Segundo período:</b>", "", 3);
              ?>
            </td>
            <td colspan="3">
              <?
              db_inputdata('r30_per2i', @$r30_per2i_dia, @$r30_per2i_mes, @$r30_per2i_ano, true, 'text', $db_opcao, "onchange='js_verificadataini(2);'","","","parent.js_verificadataini(2);");
              ?>
              &nbsp;&nbsp;<b>a</b>&nbsp;&nbsp;
              <?
              db_inputdata('r30_per2f', @$r30_per2f_dia, @$r30_per2f_mes, @$r30_per2f_ano, true, 'text', $db_opcao, "onchange='js_verificadatafim(2);'","","","parent.js_verificadatafim(2);");
              ?>
            </td>
          </tr>
  <?
  }else{
    if($dbopcao == true){
  ?>
        </table>
      </fieldset>
    </td>
  </tr>
  <tr>
    <td>
      <fieldset>
        <table>
          <tr>
            <td nowrap title="Período a gozar" align="right">
              <?
              db_ancora("<b>Período a gozar:</b>", "", 3);
              ?>
            </td>
            <td colspan="3">
              <?
              db_inputdata('r30_per2i', @$r30_per2i_dia, @$r30_per2i_mes, @$r30_per2i_ano, true, 'text', $db_opcao, "onchange='js_verificadataini(2);'","","","parent.js_verificadataini(2);");
              ?>
              &nbsp;&nbsp;<b>a</b>&nbsp;&nbsp;
              <?
              db_inputdata('r30_per2f', @$r30_per2f_dia, @$r30_per2f_mes, @$r30_per2f_ano, true, 'text', $db_opcao, "onchange='js_verificadatafim(2);'","","","parent.js_verificadatafim(2);");
              ?>
            </td>
          </tr>
    <?
    }
    ?>
  <?
  }
  ?>
          <tr>
            <td nowrap title="Digite o Ano / Mês de competência" align="right">
              <?
              db_ancora("<b>Ano / Mês pagamento:</b>", "", 3);
              ?>
            </td>
            <td>
              <?
              if(!isset($anopagto)){
                $anopagto = db_anofolha();
              }
              if(!isset($mespagto)){
                $mespagto = db_mesfolha();
              }
              db_input("DBtxt23", 4, $IDBtxt23, true, "text", $db_opcao,"","anopagto");
              ?>
              &nbsp;/&nbsp;
              <?
              db_input("DBtxt25", 2, $IDBtxt25, true, "text", $db_opcao,"","mespagto");
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="Pagar férias" align="right">
              <?
              db_ancora("<b>Pagar férias: </b>", "", 3);
              ?>
            </td>
            <td>
              <?
              if(!isset($ponto)){
                $ponto = "S";
              }

              $aFolhaPagamento = array(
                'S' => 'Salário',
                'C' => 'Complementar'
              );

              if (!$lComplementarAberta) {
                unset($aFolhaPagamento['C']);
              }

              db_select("ponto", $aFolhaPagamento, true, $db_opcao);
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="Pagar somente 1/3 férias" align="right">
              <?
              db_ancora("<b>Pagar somente 1/3 férias:</b>", "", 3);
              ?>
            </td>
            <td>
              <?
              if(!isset($paga_13)){
                $paga_13 = "f";
              }
              $arr_SorN = Array("t"=>"Sim","f"=>"Não");
              db_select("paga_13", $arr_SorN, true, $db_opcao);
              ?>
            </td>
          </tr>

          <tr>
            <td >
              &nbsp;
            </td>
            <td>
              &nbsp;
            </td>
          </tr>

          <tr>
            <td nowrap title="Observações" align="right">
              <b>Direito a Férias:</b>
            </td>
            <td>
              <?php db_select("direitoferias", array(1=>'SIM', 2=>'NÃO'), true, $db_opcao, 'onchange="js_direitoferias();"'); ?>
            </td>
          </tr>

          <tr>
            <td nowrap title="Observações" align="right">
              <b>Observações:</b>
            </td>
            <td>
              <?
                db_textarea("r30_obs",5, 45,  "", true,null, $db_opcao)
              ?>
            </td>
          </tr>

        </table>
      </fieldset>
    </td>
  </tr>
</table>
</center>
<input name="enviar" type="button" id="db_opcao" value="Processar dados" <?= ($dbopcao?3:1)== 1?($db_opcao == 3?'disabled':''):'disabled' ?> onclick="<?= ($db_opcao == 3)?'return false;':'js_verificadados(false);' ?>">
<?php if (db_getsession("DB_id_usuario") == 1) { ?>
  <input name="enviar" type="button" id="db_opcao" value="Processar dados com Debug" <?= ($dbopcao?3:1)==1?($db_opcao==3?'disabled':' '):'disabled' ?> onclick="js_verificadados(true);">
<?php }

if (!isset($retorno)) {
  echo " <input name=\"voltar\" type=\"button\" id=\"voltar\" value=\"Voltar\" onclick=\"location.href = 'pes4_cadferia001.php';\"> ";
} else {
  echo " <input name=\"voltar\" type=\"button\" id=\"voltar\" value=\"Nova seleção\" onclick=\"location.href = 'pes4_cadferialote001.php';\"> ";

  if (isset($campomatriculas) && trim($campomatriculas) != "") {
    echo " <input name=\"proximo\" type=\"submit\" id=\"proximo\" value=\"Próximo\"> ";
    echo " <input name=\"btnJanelaFerias\" type=\"button\" id=\"btnJanelaFerias\" value=\"Ver Férias Cadastradas\" onclick=\"js_showFeriasCasdastradasNoLote()\"> ";
  }
}
?>
</form>
<script>

/**
 * Preenche os datas do período especifico
 */
function js_preenchePeriodoEspecifico() {
  if ($F('r30_tipoapuracaomedia') != 2) {
    return false;
  }

  var iMes = +$F('mespagto')-1,
      iAno = $F('anopagto');

  $('r30_periodolivreinicial').value = '';
  $('r30_periodolivrefinal').value = '';

  if (iMes >= 0 && iMes < 12) {

    var oDataFinal = new Date(iAno, iMes, 0),
        oDataInicial = new Date(iAno, iMes-12, 0);

    $('r30_periodolivreinicial').value = oDataInicial.toLocaleDateString().replace(/(\d{2})-(\d{2})-(\d+)/, "$1/$2/$3");
    $('r30_periodolivrefinal').value = oDataFinal.toLocaleDateString().replace(/(\d{2})-(\d{2})-(\d+)/, "$1/$2/$3");
  }
}

$('anopagto').observe('blur', function() {
  js_preenchePeriodoEspecifico();
})

$('mespagto').observe('blur', function() {
  js_preenchePeriodoEspecifico();
})

js_preenchePeriodoEspecifico();



function js_verificadados(debug){

  if ($F("ponto") == "C" && $F("db_complementar") == "1") {

    var oFolhaComplementar = new DBViewFormularioFolha.ValidarFolhaPagamento();
    var lFolhaComplementar = oFolhaComplementar.verificarFolhaPagamentoAberta(oFolhaComplementar.TIPO_FOLHA_COMPLEMENTAR, null, null);

    if ( !lFolhaComplementar ) {

      alert('Nenhuma folha complementar aberta.');
      return false;
    }
  }


  document.form1.db_opcao.disabled=true;

  x = document.form1;
<?
if(isset($dbopcao) && $dbopcao == true){
?>
  if(document.form1.mtipo.value == "09"){
    somatest = new Number(x.r30_peraf_dia.value);
    somadias = new Number(x.r30_peraf_dia.value);
    somadias+= new Number(x.nsaldo.value);
    somadias-= new Number(1);
    per2i = new Date(x.r30_per2i_ano.value,(x.r30_per2i_mes.value - 1),x.r30_per2i_dia.value);
    per2f = new Date(x.r30_per2f_ano.value,(x.r30_per2f_mes.value - 1),somadias);
    diaci = new Date(<?=db_anofolha()?>,(<?=db_mesfolha()?> - 1),1);
    diacf = new Date(<?=db_anofolha()?>,(<?=db_mesfolha()?> - 1),<?=db_dias_mes(db_anofolha(),db_mesfolha())?> + 180);

    if(per2i >= diaci && per2f <= diacf && per2f > per2i){
    }else{
      x.r30_per2i_dia.select();
      x.r30_per2i_dia.focus();
      alert("A data para gozo deve ficar entre o primeiro dia do mês de competência\n e até 180 dias após o fim do período de competência");
    }
  }

<?
}

?>
  if ($('r30_tipoapuracaomedia')) {

    if ($F('r30_tipoapuracaomedia') == '2') {

      if ($F('r30_periodolivreinicial') == "" || $F('r30_periodolivrefinal') == "") {

        alert('Periodo Específico está informado incorretamente.\nDeverá ser informado periodo inicial e o final');
        return false;
      }
    }
  }
  erro = 0;
  if(document.form1.anopagto){
    if(document.form1.anopagto.value < <?=db_anofolha()?>){
      alert("Ano de pagamento deve ser maior que o ano corrente da folha.");
      document.form1.anopagto.select();
      document.form1.anopagto.focus();
      erro ++;
    }else if(document.form1.mespagto.value < 0 || document.form1.mespagto.value > 12){
      alert("Mês de pagamento inválido.");
      document.form1.mespagto.select();
      document.form1.mespagto.focus();
      erro ++;
    }else{
      ano = new Number(document.form1.anopagto.value);
      mes = new Number(document.form1.mespagto.value);
    }
  }

  if(document.form1.nDiasGozoFerias.value > 30 && (document.form1.r30_ndias.value == 0 || document.form1.r30_ndias.value.trim() == '') && document.form1.r30_faltas.value > 0) {

    if(document.form1.voltar.value.toLowerCase().indexOf('nova') == -1) {

      if(confirm('Este funcionário perdeu o direito à férias - Motivo: faltas.\n\nConfirma gravação do período de Férias?')){

        if(document.form1.semdireito == undefined || document.form1.semdireito.value != 'semdireito') {

          obj = document.createElement('input');
          obj.setAttribute('name','semdireito');
          obj.setAttribute('type','hidden');
          obj.setAttribute('value','semdireito');
          document.form1.appendChild(obj);
        }

        document.form1.action = 'pes4_cadferia004.php';
        document.form1.submit();
      }
      return;
    }
  }

  if(erro == 0){

    if(document.form1.r30_per1i_dia && $F('direitoferias') == 1){

      if (document.form1.mtipo.value != 13 && (document.form1.r30_per1i_dia.value == "" || document.form1.r30_per1i_mes.value == "" || document.form1.r30_per1i_ano.value == "") ) {

        alert("Informe o período de gozo inicial.");
        document.form1.db_opcao.disabled=false;
        document.form1.r30_per1i.select();
        document.form1.r30_per1i.focus();
        erro ++;

      } else if(document.form1.mtipo.value != 13 && document.form1.r30_per1i_dia && $F('direitoferias') == 1) {

        if(document.form1.r30_per1i_dia.value == "" || document.form1.r30_per1i_mes.value == "" || document.form1.r30_per1i_ano.value == ""){
          alert("Informe o período de gozo final.");
          document.form1.db_opcao.disabled=false;
          document.form1.r30_per1i.select();
          document.form1.r30_per1i.focus();
          erro ++;
        }

      }

    }

    if(document.form1.r30_per2i_dia && $F('direitoferias') == 1){
      if(document.form1.r30_per2i_dia.value == "" || document.form1.r30_per2i_mes.value == "" || document.form1.r30_per2i_ano.value == ""){
        alert("Informe o período de gozo inicial.");
        document.form1.db_opcao.disabled=false;
        document.form1.r30_per2i.select();
        document.form1.r30_per2i.focus();
        erro ++;
      }else if(document.form1.r30_per2f_dia && $F('direitoferias') == 1){
        if(document.form1.r30_per2f_dia.value == "" || document.form1.r30_per2f_mes.value == "" || document.form1.r30_per2f_ano.value == ""){
          alert("Informe o período de gozo final.");
          document.form1.db_opcao.disabled=false;
          document.form1.r30_per2f.select();
          document.form1.r30_per2f.focus();
          erro ++;
        }
      }
    }

    if ( document.form1.r30_perai.value == "" || document.form1.r30_peraf.value == "" ) {
      alert('Informe o período aquisitivo!');
      document.form1.db_opcao.disabled=false;
      document.form1.r30_pera1.focus();
      erro ++;
    }

   /*
    * Verificamos se o periodo de gozo de ferias é o mesmo periodo de dias selecionado na opção Forma de pgto:
    *
    */
    if (erro == 0 && $F('direitoferias') == 1) {

     /*
       01=>01 - 30 dias ferias
       02=>02 - 20 dias ferias
       03=>03 - 15 dias ferias
       04=>04 - 10 dias ferias
       05=>05 - 20 dias ferias + 10 dias abono
       06=>06 - 15 dias ferias + 15 dias abono
       07=>07 - 10 dias ferias + 20 dias abono
       08=>08 - 30 dias abono
       12=>12 - Dias Livre
     */
     iPerIni  = document.form1.r30_per1i_ano.value+'/'+document.form1.r30_per1i_mes.value+'/'+document.form1.r30_per1i_dia.value;
     iPerFim  = document.form1.r30_per1f_ano.value+'/'+document.form1.r30_per1f_mes.value+'/'+document.form1.r30_per1f_dia.value;
     iPerDias = js_diferenca_datas(iPerIni,iPerFim, 'd');
      if (document.form1.mtipo.value == "01" && iPerDias != 30 ) {
        erro++;
      } else if(document.form1.mtipo.value == "02" && iPerDias != 20){
        erro++;
      } else if(document.form1.mtipo.value == "03" && iPerDias != 15){
        erro++;
      } else if(document.form1.mtipo.value == "04" && iPerDias != 10){
        erro++;
      } else if(document.form1.mtipo.value == "05" && iPerDias != 20){
        erro++;
      } else if(document.form1.mtipo.value == "06" && iPerDias != 15){
        erro++;
      } else if(document.form1.mtipo.value == "07" && iPerDias != 10){
        erro++;
      } else if(document.form1.mtipo.value == "08" && iPerDias != 30){
        erro++;
      } else if(document.form1.mtipo.value == "12" && iPerDias != 30){
        //Livre - não exibe mensagem
      }
      if (erro>0) {
       alert("Período de gozo informado possui intervalo de dias diferente dos Dias a gozar");
       document.form1.db_opcao.disabled=false;
       document.form1.r30_per1i.focus();
      }

    }

    if(erro == 0){
      if(mes < 10){
        mes = "0"+mes;
      }

      anomes = ano+''+mes;
      anomes = new Number(anomes);

      if(anomes < <?=db_anofolha().db_mesfolha()?>){
        alert("Ano / Mês de pagamento deve ser superior ao Ano / Mês corrente da folha.");
        document.form1.anopagto.select();
        document.form1.anopagto.focus();
      }else{
        perai = x.r30_perai_ano.value+'-'+x.r30_perai_mes.value+'-'+x.r30_perai_dia.value;
        peraf = x.r30_peraf_ano.value+'-'+x.r30_peraf_mes.value+'-'+x.r30_peraf_dia.value;

        js_faltas("perafast",perai,peraf,'','','',debug);
      }
    }

  }

}
function js_faltas(opcao, perai, peraf, antes, nfalt, navos, debug) {

  if (debug == null) {
    debug = false;
  }
  qry = 'opcao='+opcao;
  qry+= '&perai='+perai;
  qry+= '&peraf='+peraf;
  qry+= '&antes='+antes;
  qry+= '&registro=<?=@$r30_regist?>';
  qry+= '&nfalt='+nfalt;
  qry+= '&navos='+navos;
  qry+= '&difperaq='+document.form1.diferenca.value;
  qry+= '&mensagemlote='+document.form1.mensagemlote.value;
  qry+= '&tipomedia=1';
  qry+= '&nDiasGozoFerias='+document.form1.nDiasGozoFerias.value;
  if ( debug == true) {
    qry+= '&debug=true';
  }

  if ($('r30_tipoapuracaomedia') && $('r30_tipoapuracaomedia') == '2') {

     qry+= '&tipomedia=2'
     qry+= '&periodolivreini='+$('r30_periodolivreinicial');
     qry+= '&periodolivrefinal='+$('r30_periodolivrefinal');
  }
  if(opcao == "vmtipo"){
    mtipo = document.form1.mtipo.options[document.form1.mtipo.selectedIndex].value;
    ndias = document.form1.r30_ndias.value;
    qry+= '&mtipo='+mtipo;
    qry+= '&ndias='+ndias;
  }else if(opcao == "vafast"){
    qry+= '&ini='+document.form1.r30_per1i_ano.value+'-'+document.form1.r30_per1i_mes.value+'-'+document.form1.r30_per1i_dia.value;
    qry+= '&fim='+document.form1.r30_per1f_ano.value+'-'+document.form1.r30_per1f_mes.value+'-'+document.form1.r30_per1f_dia.value;
  }
  js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_faltas','func_scriptsdb.php?'+qry,'Pesquisa', false);

}



$('gozar_old').value = $F('r30_ndias');

function js_direitoferias() {

  var lDireito = document.getElementById('direitoferias').value;
  var sConfirm = "Confirma Lançamento sem direito ?";

     obj = document.createElement('input');
     obj.setAttribute('name','semdireito');
     obj.setAttribute('id','semdireito');
     obj.setAttribute('type','text');
     obj.setAttribute('value','semdireito');

  if (lDireito == 2) {

     $('r30_ndias').value      = '0';
     $('nsaldo').value         = '0';
     $("mtipo").options.length = 0;
     $("mtipo").options[0]     = new Option("12 - 0 dias férias","12");

			if (confirm(sConfirm)) {

			  qry = 'opcao=vfalta';
			  qry+= '&perai=';
			  qry+= '&peraf=';
			  qry+= '&antes=';
			  qry+= '&registro=<?=@$r30_regist?>';
			  qry+= '&nfalt=';
			  qry+= '&navos=12';
			  qry+= '&difperaq='+$F('diferenca');
			  qry+= '&mensagemlote=n';
			  qry+= '&tipomedia=1';
			  qry+= '&iVfal=0';
        qry+= '&nDiasGozoFerias=' +document.form1.nDiasGozoFerias.value;
        js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_faltas','func_scriptsdb.php?'+qry,'Pesquisa', false);

			}
  }

  if (lDireito == 1){
    $('r30_ndias').value            = $F('gozar_old');
    $('nsaldo').value               = $F('gozar_old');
    document.form1.mtipo.options[0] = new Option("01 - 30 dias ferias","01");
    document.form1.mtipo.options[1] = new Option("02 - 20 dias ferias","02");
    document.form1.mtipo.options[2] = new Option("03 - 15 dias ferias","03");
    document.form1.mtipo.options[3] = new Option("04 - 10 dias ferias","04");
    document.form1.mtipo.options[4] = new Option("05 - 20 dias ferias + 10 dias abono","05");
    document.form1.mtipo.options[5] = new Option("06 - 15 dias ferias + 15 dias abono","06");
    document.form1.mtipo.options[6] = new Option("07 - 10 dias ferias + 20 dias abono","07");
    document.form1.mtipo.options[7] = new Option("08 - 30 dias abono","08");
    document.form1.mtipo.options[8] = new Option("12 - Dias Livre","12");


    if ( $F('r30_ndias') > 30 ) {
      $('mtipo').value = 12;
    }
  }

}

function js_verificaaquiini(){
  x = document.form1;

  x.navos.value = "0";
  x.antes.value = "";

  dia = new Number(x.r30_perai_dia.value);
  mes = new Number(x.r30_perai_mes.value);
  ano = new Number(x.r30_perai_ano.value);
  perai = new Date(ano, (mes - 1), dia);
  if(ano > <?=(db_anofolha() + 1)?>){
    alert("Período aquisitivo inválido. Verifique o ano do período inicial.");
    x.r30_perai_dia.value = '';
    x.r30_perai_mes.value = '';
    x.r30_perai_ano.value = '';
    x.r30_perai.value = '';
    x.r30_perai.focus();
  }else if(x.r30_perai_dia.value != "" && x.r30_perai_mes.value != "" && x.r30_perai_ano.value != ""){
    dia -= 1;
    mes -= 1;
    perat = new Date(ano, mes, 1);
    ano += 1;
    peraf = new Date(ano, mes, dia);
    diacf = new Date(<?=db_anofolha()?>, (<?=db_mesfolha()?> - 1), 1);

    if(perat > diacf){
      // Para prefeituras que pagam as férias antecipadas
      if(confirm("O período aquisitivo ainda não venceu.\n\nContinua geração das Férias?")){
        x.r30_peraf_dia.value = peraf.getDate()<10?"0"+peraf.getDate():peraf.getDate();
        x.r30_peraf_mes.value = (peraf.getMonth() + 1)<10?"0"+(peraf.getMonth() + 1):(peraf.getMonth() + 1);
        x.r30_peraf_ano.value = peraf.getFullYear();
        x.r30_peraf.value = (peraf.getDate()<10?"0"+peraf.getDate():peraf.getDate())+'/'+((peraf.getMonth() + 1)<10?"0"+(peraf.getMonth() + 1):(peraf.getMonth() + 1))+'/'+(peraf.getFullYear());
      }else{
        if(document.form1.proximo){
          document.form1.proximo.click();
        }else{
          document.form1.voltar.click();
        }
      }
    }else{
      x.r30_peraf_dia.value = peraf.getDate()<10?"0"+peraf.getDate():peraf.getDate();
      x.r30_peraf_mes.value = (peraf.getMonth() + 1)<10?"0"+(peraf.getMonth() + 1):(peraf.getMonth() + 1);
      x.r30_peraf_ano.value = peraf.getFullYear();
      x.r30_peraf.value = (peraf.getDate()<10?"0"+peraf.getDate():peraf.getDate())+'/'+((peraf.getMonth() + 1)<10?"0"+(peraf.getMonth() + 1):(peraf.getMonth() + 1))+'/'+(peraf.getFullYear());

      navos = (((peraf - perai) / 86400000) / 15);

      document.form1.diferenca.value = navos.toFixed(0);

      if(navos < 0){
        navos *= -1;
      }
      if(navos > 12){
        navos = 12;
      }
      x.navos.value = navos;
      x.antes.value = x.r30_peraf_ano.value+'-'+x.r30_peraf_mes.value+'-'+x.r30_peraf_dia.value;
      js_faltas('vfalta','','','',x.r30_faltas.value,x.navos.value);
    }
  }else{
    x.r30_peraf_dia.value = '';
    x.r30_peraf_mes.value = '';
    x.r30_peraf_ano.value = '';
    x.r30_peraf.value = '';
  }
}
function js_verificaaquifim(){

  x = document.form1;

  diai = new Number(x.r30_perai_dia.value);
  mesi = new Number(x.r30_perai_mes.value);
  anoi = new Number(x.r30_perai_ano.value);
  mesi-= 1;

  diaf = new Number(x.r30_peraf_dia.value);
  mesf = new Number(x.r30_peraf_mes.value);
  anof = new Number(x.r30_peraf_ano.value);
  mesf-= 1;

  erro = 0;

  if(x.r30_perai_dia.value == "" || x.r30_perai_mes.value == "" || x.r30_perai_ano.value == ""){
    alert("Informe o período aquisitivo inicial.");
    //x.r30_perai_dia.select();
    //x.r30_perai_dia.focus();
    x.r30_perai.select();
    x.r30_perai.focus();
    erro++;
  }else if(x.r30_peraf_dia.value != "" && x.r30_peraf_mes.value != "" && x.r30_peraf_ano.value != ""){
    perai = new Date(anoi, mesi, diai);
    peraf = new Date(anof, mesf, diaf);
    if(peraf < perai){
      alert("Período aquisitivo final inválido.");
      x.r30_peraf_dia.value = '';
      x.r30_peraf_mes.value = '';
      x.r30_peraf_ano.value = '';
      x.r30_peraf.value = '';
//      x.r30_peraf_dia.focus();
      x.r30_peraf.focus();
      erro++;
    }else{
      perano = (peraf - perai);
      perano = new Date(perano);
      perano/= 86400000;
      perano = new Number(perano);
      perano = Math.floor(perano);

      campodiferenca= (((peraf - perai) / 86400000) / 15);
      document.form1.diferenca.value = Math.floor(campodiferenca);
      perat = new Date(anof, mesf, 1);
      diacf = new Date(<?=db_anofolha()?>, (<?=db_mesfolha()?> - 1), 1);
      if(perat > diacf){
        if(!confirm("O período aquisitivo ainda não venceu.\n\nConfima geração das Férias?")){
          if(document.form1.proximo){
            document.form1.proximo.click();
          }else{
            document.form1.voltar.click();
          }
        }
      }
      if(perano > 365){
        alert("ALERTA: Período aquisitivo maior que 1 (um) ano.");
      }
    }
  }else{
    erro++;
  }

  if(erro == 0){
    antes = new Date(x.antes.value.substring(0,4),(x.antes.value.substring(5,7) - 1),x.antes.value.substring(8,10));
    if(peraf <= antes){
      navos = 0;
      if(anof.valueOf() == anoi.valueOf()){
        navos = mesf - mesi;
      }else{
        navos = (12 - mesi ) + mesf;
      }
      if((diaf - diai) > 14){
        navos++;
      }
      x.navos.value = navos;
    }
    js_faltas("faltas",anoi+'-'+mesi+'-'+diai,anof+'-'+mesf+'-'+diaf,'','','');
    js_faltas('vfalta','','','',x.r30_faltas.value,x.navos.value);
  }
}
function js_verificadataini(campo){

  x = document.form1;

  evaldiai = eval("x.r30_per"+campo+"i_dia");
  evalmesi = eval("x.r30_per"+campo+"i_mes");
  evalanoi = eval("x.r30_per"+campo+"i_ano");
  evaldatacompletai = eval("x.r30_per"+campo+"i");

  evaldiaf = eval("x.r30_per"+campo+"f_dia");
  evalmesf = eval("x.r30_per"+campo+"f_mes");
  evalanof = eval("x.r30_per"+campo+"f_ano");

  evaldatacompletaf = eval("x.r30_per"+campo+"f");

  if(evaldiai.value!= "" && evalmesi.value != "" && evalanoi.value != ""){
    nsaldo = new Number(x.nsaldo.value);
    if(nsaldo > 0){
      somadias = new Number(evaldiai.value);
      somadias+= new Number(nsaldo);
      somadias-= new Number(1);
    }else{
      somadias = 0;
    }

    qualmess = new Number(evalmesi.value);
    qualmess-= new Number(1);


    per2i = new Date(evalanoi.value,qualmess,evaldiai.value,1,0,0);

    per2f = new Date(evalanoi.value,qualmess,somadias,1,0,0);

    diaci = new Date(<?=db_anofolha()?>,(<?=db_mesfolha()?> - 1),1);
    diacf = new Date(<?=db_anofolha()?>,(<?=db_mesfolha()?> - 1),(<?=db_dias_mes(db_anofolha(),db_mesfolha())?> + 180));

    if(per2i >= diaci && per2f <= diacf){
      if(per2i > per2f){
        per2f = per2i;
      }
      evaldiaf.value = per2f.getDate()<10?"0"+per2f.getDate():per2f.getDate();
      evalmesf.value = (per2f.getMonth() + 1)<10?"0"+(per2f.getMonth() + 1):(per2f.getMonth() + 1);
      evalanof.value = per2f.getFullYear();
      <?
     if(isset($dbopcao) && $dbopcao == false){
     ?>
     js_faltas('vafast','','','','','');
     <?
     }
     ?>
    }else{
      alert("A data para gozo deve ficar entre o primeiro dia do mês de competência\n e até 180 dias após o fim do período de competência");
      evaldiaf.value = '';
      evalmesf.value = '';
      evalanof.value = '';
      evaldiai.value = '';
      evalmesi.value = '';
      evalanoi.value = '';

      evaldiai.focus();
    }
  }else{
    evaldiaf.value = '';
    evalmesf.value = '';
    evalanof.value = '';
  }
  if (evaldiai.value != '') {
    evaldatacompletai.value = evaldiai.value+'/'+evalmesi.value+'/'+evalanoi.value;
	}

  if (evaldiaf.value != '') {
    evaldatacompletaf.value = evaldiaf.value+'/'+evalmesf.value+'/'+evalanof.value;
  }

}



function js_verificadatafim(campo){
  x = document.form1;

  evaldiai = eval("x.r30_per"+campo+"i_dia");
  evalmesi = eval("x.r30_per"+campo+"i_mes");
  evalanoi = eval("x.r30_per"+campo+"i_ano");

  evaldiaf = eval("x.r30_per"+campo+"f_dia");
  evalmesf = eval("x.r30_per"+campo+"f_mes");
  evalanof = eval("x.r30_per"+campo+"f_ano");

  if(evaldiai.value != "" && evalmesi.value != "" && evalanoi.value != ""){
    if(evaldiaf.value != "" && evalmesf.value != "" && evalanof.value != ""){
      qualmesi = new Number(evalmesi.value);
      qualmesi-= new Number(1);

      qualmesf = new Number(evalmesf.value);
      qualmesf-= new Number(1);

      per2i = new Date(evalanoi.value,qualmesi,evaldiai.value);
      per2f = new Date(evalanof.value,qualmesf,evaldiaf.value);

      qualmess = new Number(<?=db_mesfolha()?>);
      qualmess-= new Number(1);

      qualdias = new Number(<?=db_dias_mes(db_anofolha(),db_mesfolha())?>);
      qualdias+= new Number(180);

      diaci = new Date(<?=db_anofolha()?>,qualmess,1);
      diacf = new Date(<?=db_anofolha()?>,qualmess,qualdias);

      if(per2f > diacf){
        alert("A data para gozo deve ficar entre o primeiro dia do mês de competência\n e até 180 dias após o fim do período de competência");
        evaldiaf.value = '';
        evalmesf.value = '';
        evalanof.value = '';

        evaldiaf.focus();
      }else if(per2i > per2f){
        alert("A data final para gozo deve ser inferior à data inicial.");
        evaldiaf.value = '';
        evalmesf.value = '';
        evalanof.value = '';

        evaldiaf.focus();
      }
    }else{
      <?
      if(isset($dbopcao) && $dbopcao == false){
      ?>
      js_faltas('vafast','','','','','');
      <?
      }
      ?>
    }
  }else{
    alert("Informe o período para gozo inicial.");
    evaldiaf.value = '';
    evalmesf.value = '';
    evalanof.value = '';
    evaldiai.focus();
  }
}
function js_habilitaperiodo(opcao){
  if(document.form1.saldo.selectedIndex == 0){
    document.form1.dtjs_r30_per2i.disabled = false;
    document.form1.dtjs_r30_per2f.disabled = false;
    document.form1.r30_per2i_dia.readOnly              = false;
    document.form1.r30_per2i_dia.style.backgroundColor = "";
    document.form1.r30_per2i_mes.readOnly              = false;
    document.form1.r30_per2i_mes.style.backgroundColor = "";
    document.form1.r30_per2i_ano.readOnly              = false;
    document.form1.r30_per2i_ano.style.backgroundColor = "";

    document.form1.r30_per2f_dia.readOnly              = false;
    document.form1.r30_per2f_dia.style.backgroundColor = "";
    document.form1.r30_per2f_mes.readOnly              = false;
    document.form1.r30_per2f_mes.style.backgroundColor = "";
    document.form1.r30_per2f_ano.readOnly              = false;
    document.form1.r30_per2f_ano.style.backgroundColor = "";
    if(opcao == 1){
      js_tabulacaoforms('form1','r30_per2i_dia',false,1,'r30_per2i_dia',false);
    }
    document.form1.mtipo.value = '09';
    document.form1.nabono.value = "0";
    document.form1.nsaldo.value = "<?=@$nsaldo?>";

  }else{
    document.form1.dtjs_r30_per2i.disabled = true;
    document.form1.dtjs_r30_per2f.disabled = true;
    document.form1.r30_per2i_dia.readOnly              = true;
    document.form1.r30_per2i_dia.style.backgroundColor = "#DEB887";
    document.form1.r30_per2i_mes.readOnly              = true;
    document.form1.r30_per2i_mes.style.backgroundColor = "#DEB887";
    document.form1.r30_per2i_ano.readOnly              = true;
    document.form1.r30_per2i_ano.style.backgroundColor = "#DEB887";

    document.form1.r30_per2i_dia.value = "";
    document.form1.r30_per2i_mes.value = "";
    document.form1.r30_per2i_ano.value = "";

    document.form1.r30_per2f_dia.readOnly              = true;
    document.form1.r30_per2f_dia.style.backgroundColor = "#DEB887";
    document.form1.r30_per2f_mes.readOnly              = true;
    document.form1.r30_per2f_mes.style.backgroundColor = "#DEB887";
    document.form1.r30_per2f_ano.readOnly              = true;
    document.form1.r30_per2f_ano.style.backgroundColor = "#DEB887";

    document.form1.r30_per2f_dia.value = "";
    document.form1.r30_per2f_mes.value = "";
    document.form1.r30_per2f_ano.value = "";

    if(opcao == 1){
      js_tabulacaoforms('form1','enviar',true,1,'enviar',true);
    }
    document.form1.mtipo.value = '10';
    document.form1.nabono.value = document.form1.nsaldo.value;
    document.form1.nsaldo.value = "0";

  }
}
function js_habilitaperiodoper1(opcao){

	if(opcao == 0){

	  document.getElementById("r30_per1i").disabled = false;
	  document.getElementById("r30_per1i").style.backgroundColor = "";

	  document.getElementById("r30_per1f").disabled = false;
	  document.getElementById("r30_per1f").style.backgroundColor = "";


    document.form1.dtjs_r30_per1i.disabled = false;
    document.form1.dtjs_r30_per1f.disabled = false;
    document.form1.r30_per1i_dia.readOnly              = false;
    document.form1.r30_per1i_dia.style.backgroundColor = "";
    document.form1.r30_per1i_mes.readOnly              = false;
    document.form1.r30_per1i_mes.style.backgroundColor = "";
    document.form1.r30_per1i_ano.readOnly              = false;
    document.form1.r30_per1i_ano.style.backgroundColor = "";

    document.form1.r30_per1f_dia.readOnly              = false;
    document.form1.r30_per1f_dia.style.backgroundColor = "";
    document.form1.r30_per1f_mes.readOnly              = false;
    document.form1.r30_per1f_mes.style.backgroundColor = "";
    document.form1.r30_per1f_ano.readOnly              = false;
    document.form1.r30_per1f_ano.style.backgroundColor = "";

  } else {

	  document.getElementById("r30_per1i").disabled = true;
	  document.getElementById("r30_per1i").style.backgroundColor = "#DEB887";

	  document.getElementById("r30_per1f").disabled = true;
	  document.getElementById("r30_per1f").style.backgroundColor = "#DEB887";

    document.form1.dtjs_r30_per1i.disabled = true;
    document.form1.dtjs_r30_per1f.disabled = true;
    document.form1.r30_per1i_dia.readOnly              = true;
    document.form1.r30_per1i_dia.style.backgroundColor = "#DEB887";
    document.form1.r30_per1i_mes.readOnly              = true;
    document.form1.r30_per1i_mes.style.backgroundColor = "#DEB887";
    document.form1.r30_per1i_ano.readOnly              = true;
    document.form1.r30_per1i_ano.style.backgroundColor = "#DEB887";

    document.form1.r30_per1i_dia.value = "";
    document.form1.r30_per1i_mes.value = "";
    document.form1.r30_per1i_ano.value = "";
    document.form1.r30_per1i.value = "";

    document.form1.r30_per1f_dia.readOnly              = true;
    document.form1.r30_per1f_dia.style.backgroundColor = "#DEB887";
    document.form1.r30_per1f_mes.readOnly              = true;
    document.form1.r30_per1f_mes.style.backgroundColor = "#DEB887";
    document.form1.r30_per1f_ano.readOnly              = true;
    document.form1.r30_per1f_ano.style.backgroundColor = "#DEB887";

    document.form1.r30_per1f_dia.value = "";
    document.form1.r30_per1f_mes.value = "";
    document.form1.r30_per1f_ano.value = "";
    document.form1.r30_per1f.value = "";

  }
}
function js_montaselect(ndias, menor30){


  var recalcular = ndias > 0;
   ndias = new Number(ndias);
  if(menor30 == false || (menor30 == true && ndias != 30)){
    for(i=0;i<document.form1.mtipo.length;i++){
      document.form1.mtipo.options[i] = null;
      i = -1;
    }
  }

  if (menor30 == false && ndias == 30) {

    document.form1.mtipo.options[0] = new Option("01 - 30 dias ferias","01");
    document.form1.mtipo.options[1] = new Option("02 - 20 dias ferias","02");
    document.form1.mtipo.options[2] = new Option("03 - 15 dias ferias","03");
    document.form1.mtipo.options[3] = new Option("04 - 10 dias ferias","04");
    document.form1.mtipo.options[4] = new Option("05 - 20 dias ferias + 10 dias abono","05");
    document.form1.mtipo.options[5] = new Option("06 - 15 dias ferias + 15 dias abono","06");
    document.form1.mtipo.options[6] = new Option("07 - 10 dias ferias + 20 dias abono","07");
    document.form1.mtipo.options[7] = new Option("08 - 30 dias abono","08");
    document.form1.mtipo.options[8] = new Option("12 - Dias Livre","12");

 } else {

   document.form1.mtipo.options[0] = new Option("12 - Dias Livre","12");
   document.form1.mtipo.options[1] = new Option("13 - "+ndias+" dias abono" ,"13");
   var iFerias = Math.ceil(ndias/3*2);
   var iAbono  = Math.floor(ndias/3);
   document.form1.mtipo.options[2] = new Option("14 - "+iFerias+" ferias + "+iAbono+" dias abono","14");
   document.form1.mtipo.options[3] = new Option("15 - "+ndias+" dias férias","15");
 }

 if ( $F('r30_ndias') > 30 ) {
   $('mtipo').value = 12;
 }
 js_validamtipo(recalcular);
}

function js_validamtipo(recalcularDias) {

  if (recalcularDias == null) {
    recalcularDias = true;
  }
  valmtipo = new Number(document.form1.mtipo.options[document.form1.mtipo.selectedIndex].value);
  valntipo = new Number(document.form1.mtipo.options[document.form1.mtipo.selectedIndex].value);
  valorndt = new Number(document.form1.r30_ndias.value);

  var iValorSelecionado = $F('mtipo');

  if ( iValorSelecionado == 12 ) {
    $('nabono').readOnly = false;
    $('nabono').disabled = false;
    $('nabono').removeClassName('readOnly');
    $('nabono').setStyle({
      backgroundColor : '#FFFFFF'
    });
  } else {

    $('nabono').readOnly = true;
    $('nabono').removeClassName('readOnly');
  }

 //document.form1.r30_obs.value = '';

  if (valntipo >= 1 && valntipo <= 4 || valntipo == 13 || valntipo == 12) {

    if ( document.form1.mtipo.length > 3 && (valntipo != 13  && valntipo != 15) ) {

      document.form1.nabono.value = 0;
      <?php
        if (isset($dbopcao) && $dbopcao == false) {
          echo "if (recalcularDias) {
                  js_faltas('vmtipo', '', '', '', '', '');
               }";
          echo "js_habilitaperiodoper1('0');";
        }
      ?>

      if(valntipo == 12) {
        document.form1.nsaldo.value = document.form1.r30_ndias.value;
      }

    } else {

      document.form1.nsaldo.value = 0;
      document.form1.nabono.value = 0;
      if (valmtipo == 1 || valntipo == 12) {

        document.form1.nsaldo.value = document.form1.r30_ndias.value;
        js_habilitaperiodoper1(0);
      } else {

        if (valntipo == 13) {
          document.form1.r30_obs.value = 'ABONO EM PECÚNIA DOS DIAS DE FÉRIAS';
        } else {
        	document.form1.r30_obs.value = '';
        }

        document.form1.nabono.value = document.form1.r30_ndias.value;
        js_habilitaperiodoper1(1);

      }

      js_verificadataini(1);

    }

  } else if (valmtipo == "05") {


    if(valorndt == 30){
      document.form1.nsaldo.value = (valorndt - 10);
      document.form1.nabono.value = 10;
    }else{

      var iFerias = Math.ceil(valorndt / 3 * 2);
      var iAbono  = Math.floor(valorndt / 3);

      document.form1.nsaldo.value = iFerias;//(valorndt/3*2);
      document.form1.nabono.value = iAbono;//valorndt/3;
    }
    js_habilitaperiodoper1(0);
    js_verificadataini(1);

  } else if (valmtipo == "06") {

    document.form1.nsaldo.value = (valorndt - 15);
    document.form1.nabono.value = 15;
    js_habilitaperiodoper1(0);
    js_verificadataini(1);

  } else if (valmtipo == "07") {

    document.form1.nsaldo.value = (valorndt - 20);
    document.form1.nabono.value = 20;
    js_habilitaperiodoper1(0);
    js_verificadataini(1);

  } else if (valmtipo == "08") {

    document.form1.nsaldo.value = 0;
    document.form1.nabono.value = valorndt;
    js_habilitaperiodoper1(0);
    js_verificadataini(1);

  } else if (valmtipo == "14") {

    if(valorndt == 30){
      document.form1.nsaldo.value = (valorndt - 10);
      document.form1.nabono.value = 10;
    }else{

      var iFerias = Math.ceil(valorndt / 3 * 2);
      var iAbono  = Math.floor(valorndt / 3);

      document.form1.nsaldo.value = iFerias;
      document.form1.nabono.value = iAbono;
    }
    js_habilitaperiodoper1(0);
    js_verificadataini(1);

  } else if (valmtipo == "15") {

      document.form1.nsaldo.value = valorndt;
      document.form1.nabono.value = 0;
      js_habilitaperiodoper1(0);
      js_verificadataini(1);
  }

}

<?
if (isset($dbopcao) && $dbopcao == true && !isset($mtipo)) {

  echo "js_habilitaperiodo(2);";

} else if(isset($dbopcao) && $dbopcao == false) {

  echo "js_verificaaquiini();";
  echo "js_montaselect(document.form1.r30_ndias.value, false);";
  echo "js_validamtipo();";

  if($nDiasGozoFerias > 30) {
    echo "js_faltas('vfalta','','','',document.form1.r30_faltas.value?document.form1.r30_faltas.value:0, document.form1.navos.value ? document.form1.navos.value:12);";
  }

}
?>
document.form1.db_opcao.disabled=false;
if(typeof document.form1.db_opcao.length != 'undefined' && document.form1.db_opcao.length && document.form1.db_opcao.length > 1) {
    document.form1.db_opcao[0].disabled = false;
    document.form1.db_opcao[1].disabled = false;
}

function js_showCamposMedia() {

   switch ($F('r30_tipoapuracaomedia')) {

     case '1':

       $('linhadatasespecificas').style.display = 'none';
       $('r30_periodolivrefinal').value         = '';
       $('r30_periodolivreinicial').value       = '';
       break;

     case '2':

       $('linhadatasespecificas').style.display='';
       break;
   }

}
js_showCamposMedia();

function js_showFeriasCasdastradasNoLote() {

  js_divCarregando("Aguarde, Pesquisando Férias...","msgBox");
  var oParam  = new Object();
  oParam.exec =  'getFeriasCadastradas'
  var oAjax = new Ajax.Request(
                        'pes4_feriaslote.RPC.php',
                         {
                          method    : 'post',
                          parameters: 'json='+Object.toJSON(oParam),
                          onComplete: js_retornogetFerias
                          }
                        );
}

function js_retornogetFerias(oAjax) {

  js_removeObj('msgBox');
  var oRetorno  = eval("("+oAjax.responseText+")");
  var iWidth    = document.width/2;
  var iHeight   = document.scrollHeight/1.3;
  oWindowFerias = new windowAux('wndFerias', 'Férias já Lançadas no Lote', iWidth, iHeight);
  oWindowFerias.setShutDownFunction (function(){
    oWindowFerias.destroy();
  });

  var sContent = "<div><fieldset id='ctnGrid'></fieldset></div>";
  oWindowFerias.setContent(sContent);
  oMessageBoard  = new DBMessageBoard('msgboard1',
                                      'Férias Cadastradas',
                                      'Férias cadastradas por lote para o período '
                                       +oRetorno.iMesUsu+'/'+oRetorno.iAnoUsu,
                                      $('windowwndFerias_content'));

  oGridFerias  = new DBGrid('gridFerias');
  oGridFerias.nameInstance = 'oGridFerias';
  oGridFerias.setHeader(new Array("Matricula", "Nome", "Dias", "Início", "Término"));
  oGridFerias.show($('ctnGrid'));
  oGridFerias.clearAll(true);
  oRetorno.itens.each(function(oLinha, id) {

     var aLinha = new Array();
     aLinha[0]  = oLinha.rh93_regist;
     aLinha[1]  = oLinha.z01_nome.urlDecode();
     aLinha[2]  = oLinha.r30_ndias;
     aLinha[3]  = js_formatar(oLinha.r30_per1i,'d');
     aLinha[4]  = js_formatar(oLinha.r30_per1f,'d');
     oGridFerias.addRow(aLinha);

  });
  oWindowFerias.show();
  oGridFerias.renderRows();

}
//calcula a data final
function js_calcFim(){

 var datafinal = eval("x.r30_periodolivrefinal");


  x = document.form1;

  evaldiai = eval("x.r30_periodolivreinicial_dia");
  evalmesi = eval("x.r30_periodolivreinicial_mes");
  evalanoi = eval("x.r30_periodolivreinicial_ano");
  evaldatacompletai = eval("x.r30_periodolivreinicial");

   if(evaldiai.value!= "" && evalmesi.value != "" && evalanoi.value != ""){

    //retorna true ou false se o ano é bissesto a para total de dias
      nsaldo = new Number(364);//364 para fechar o calculo de ferias

      somadias = new Number(evaldiai.value);
      somadias += new Number(nsaldo);

      var anoAtual = evalanoi;
      var anoNext = new Number(evalanoi.value);


      //se ano atual for bissesto diminui  mais um dia para fechar o calculo de ferias
      if (checkleapyear(anoAtual.value)  ) {
        somadias += new Number(1);
        //se data for maior que 29/02 em ano bissesto diminui mais um dia para fechar calculo
        if( evalmesi.value > 02 ){
          somadias -= new Number(1);
        }
      }

      //calcula proximo ano
      anoNext += new Number(1);

      //se ano posterior for bissesto e mes mair que 02 soma  mais um dia para fechar o calculo de ferias
      if(checkleapyear(anoNext) && (evalmesi.value > 2 ) ) {
        somadias += new Number(1);
      }

      qualmess = new Number(evalmesi.value);
      qualmess -= new Number(1);

      datafim = new Date(evalanoi.value,qualmess,somadias,1,0,0);

      evaldiaf.value = datafim.getDate()<10?"0"+datafim.getDate():datafim.getDate();
      evalmesf.value = (datafim.getMonth() + 1)<10?"0"+(datafim.getMonth() + 1):(datafim.getMonth() + 1);
      evalanof.value = datafim.getFullYear();

      if (evaldiaf.value != '') {
        datafinal.value = evaldiaf.value+'/'+evalmesf.value+'/'+evalanof.value;
      }

      $('r30_periodolivrefinal').value = datafinal.value;

    }
}
//calcula a data inicial
function js_calcIni(){

  var datainicial = eval("x.r30_periodolivreinicial");

  x = document.form1;

  evaldiaf = eval("x.r30_periodolivrefinal_dia");
  evalmesf = eval("x.r30_periodolivrefinal_mes");
  evalanof = eval("x.r30_periodolivrefinal_ano");
  evaldatacompletaf = eval("x.r30_periodolivrefinal");

   if(evaldiaf.value!= "" && evalmesf.value != "" && evalanof.value != ""){

      nsaldo = new Number(365);

      subtraidias  = new Number(evaldiaf.value);
      subtraidias -= new Number(nsaldo);
      subtraidias += new Number(1);


      //se o ano  anterior for bissesto diminui mais um dia para subtraidias para fechar calculo
      if (checkleapyear(evalanof.value - 1)){
      subtraidias -= new Number(1);
        if ( evalmesf.value > 02 ){
          subtraidias += new Number(1);
        }
      }

      //se ano atual bissesto e mes maior que 02 diminui um dia para fechar calculo
      if (checkleapyear(evalanof.value) ){
           if ( evalmesf.value > 02 ){
          subtraidias -= new Number(1);
          }
        }


      qualmess  = new Number(evalmesf.value);
      qualmess -= new Number(1);


      dataini = new Date(evalanof.value, qualmess, subtraidias);

      evaldiai.value = dataini.getDate()<10?"0"+dataini.getDate():dataini.getDate();
      evalmesi.value = (dataini.getMonth() + 1)<10?"0"+(dataini.getMonth() + 1):(dataini.getMonth() + 1);
      evalanoi.value = dataini.getFullYear();


      if (evaldiai.value != '') {
        datainicial.value = evaldiai.value+'/'+evalmesi.value+'/'+evalanoi.value;
      }

      $('r30_periodolivreinicial').value = datainicial.value;

    }

}

$('r30_ndias').observe("change", function() {
  js_montaselect($F('r30_ndias'), $F('r30_ndias') < 30);
});
window.onerror = function (msg, url, lineNo, columnNo, error) {
  var string = msg.toLowerCase();
  var substring = "script error";
  if (string.indexOf(substring) > -1){
    alert('Script Error: See Browser Console for Detail');
  } else {
    alert(msg, url, lineNo, columnNo, error);
  }
  return false;
};
</script>
