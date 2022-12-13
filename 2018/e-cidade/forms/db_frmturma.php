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

//MODULO: educação
$clturma->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ed29_c_descr");
$clrotulo->label("ed16_i_capacidade");
$clrotulo->label("ed35_i_qtdperiodo");
$clrotulo->label("ed246_i_turno");
$clrotulo->label("ed11_i_codcenso");
$clrotulo->label("ed31_i_regimemat");
$clrotulo->label("ed223_i_regimematdiv");
$clrotulo->label("ed223_i_serie");
$clrotulo->label("ed336_vagas");
$clrotulo->label("ed336_turnoreferente");
$clrotulo->label("iAlunosMatriculados");
$clrotulo->label("ed132_censoetapa");
$clrotulo->label("ed18_c_nome");


$ed57_i_escola = db_getsession("DB_coddepto");
$result        = $clescola->sql_record($clescola->sql_query($ed57_i_escola));

db_fieldsmemory( $result, 0 );

if ($db_opcao != 1 && isset($ed57_i_codigo) && !isset($excluir)) {

  $oTurma         = new Turma($ed57_i_codigo);
  $aVagasOcupadas = $oTurma->getVagasOcupadas();
  $aVagas         = $oTurma->getVagas();

  /**
   * Todo, quando implementado controle de vagas por turno quando turno for integral, tem que separa
   * iAlunosMatriculados por referencia
   *
   */
  foreach ($aVagas as $iTurnoReferente => $iNumeroVagas) {
    $ed336_vagas = $iNumeroVagas;
  }

  $iAlunosMatriculados = 0;
  foreach ($aVagasOcupadas as $iTurnoReferente => $iVagasOcupadas) {
    $iAlunosMatriculados = $iVagasOcupadas;
  }
}
?>
<form name="form1" method="post" action="">
  <center>
    <table border="0" width="100%">
      <tr>
        <td nowrap title="<?=@$Ted57_i_codigo?>" width="15%">
          <label for="ed57_i_codigo">
            <?=@$Led57_i_codigo?>
          </label>
        </td>
        <td>
          <?db_input('ed57_i_codigo',15,$Ied57_i_codigo,true,'text',3,"")?>
          <spam id ='codigoInep'>
            <label for="ed57_i_codigoinep">
              <?=@$Led57_i_codigoinep?>
            </label>
            <?db_input('ed57_i_codigoinep',10,$Ied57_i_codigoinep,true,'text',$db_opcao,"")?>
          </spam>
          <label for="ed57_i_tipoturma">
            <?=@$Led57_i_tipoturma?>
          </label>
          <?php
          $x = array('1'=>'NORMAL','2'=>'EJA','3'=>'MULTIETAPA', '6' => 'PROGRESSÃO PARCIAL', '7' => 'CORREÇÃO DE FLUXO');
          if ($db_opcao == 2) {
            if ($ed57_i_tipoturma == 6) {
              $x = array('6' => 'PROGRESSÃO PARCIAL');
            } else if ($ed57_i_tipoturma != 6) {
              $x = array('1'=>'NORMAL','2'=>'EJA','3'=>'MULTIETAPA', '7' => 'CORREÇÃO DE FLUXO');
            }
          }
          db_select('ed57_i_tipoturma',$x,true,$db_opcao,"onchange='js_validaTipoTurma()'");
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Ted57_c_descr?>">
          <label for="ed57_c_descr">
            <?=@$Led57_c_descr?>
          </label>
        </td>
        <td>
          <?php
          db_input('ed57_c_descr',80,$Ied57_c_descr,true,'text',$db_opcao,
            " onKeyUp=\"js_ValidaCamposEdu(this,2,'$GLOBALS[Sed57_c_descr]','f','t',event);\"")
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Ted57_i_escola?>">
          <label for="ed57_i_escola">
            <?php db_ancora(@$Led57_i_escola,"",3);?>
          </label>
        </td>
        <td>
          <?php
          db_input('ed57_i_escola', 15, $Ied57_i_escola, true, 'text', 3, "");
          db_input('ed18_c_nome',   60, $Ied18_c_nome,   true, 'text', 3, '');
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Ted57_i_calendario?>">
          <label for="ed57_i_calendario">
            <?php db_ancora(@$Led57_i_calendario,"js_pesquisaed57_i_calendario(true);",$db_opcao1);?>
          </label>
        </td>
        <td>
          <?php
          db_input('ed57_i_calendario',15,$Ied57_i_calendario,true,'text',$db_opcao1,
            " onchange='js_pesquisaed57_i_calendario(false);'");
          db_input('ed52_c_descr',40,@$Ied52_c_descr,true,'text',3,'');
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Ted57_i_base?>">
          <label for="ed57_i_base">
            <?php
            db_ancora(@$Led57_i_base,"js_pesquisaed57_i_base(true);",$db_opcao1);
            ?>
          </label>
        </td>
        <td>
          <?php
          db_input('ed57_i_base',15,$Ied57_i_base,true,'text',$db_opcao1," onchange='js_pesquisaed57_i_base(false);'");
          db_input('ed31_c_descr',40,@$Ied31_c_descr,true,'text',3,'');
          db_input('ed29_i_ensino',10,@$Ied29_i_codigo,true,'hidden',3,'');
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Ted29_c_descr?>">
          <label for="ed29_i_codigo">
            <?=@$Led29_c_descr?>
          </label>
        </td>
        <td>
          <?php
          db_input('ed29_i_codigo',   15, @$Ied29_i_codigo,    true, 'text',   3);
          db_input('ed29_c_descr',    40, @$Ied29_c_descr,     true, 'text',   3);
          db_input('ed36_c_abrev',    2,  @$Ied36_c_abrev,     true, 'hidden', 3);
          db_input('ed29_c_historico',1,  @$Ied29_c_historico, true, 'hidden', 3);
          ?>
        </td>
      </tr>
      <tr id='regime_matricula'>
        <td nowrap title="<?=@$Ted31_i_regimemat?>">
          <label for="ed31_i_regimemat"> <?=@$Led31_i_regimemat?> </label>
        </td>
        <td>
          <?php
          db_input('ed31_i_regimemat', 15, @$Ied31_i_regimemat, true, 'text',   3);
          db_input('ed218_c_nome',     40, @$Ied218_c_nome,     true, 'text',   3);
          db_input('ed218_c_divisao',  1,  @$Ied218_c_divisao,  true, 'hidden', 3);
          ?>
        </td>
      </tr>
      <tbody id="div_divisao"></tbody>
      <tbody id="div_etapa"></tbody>
      <?php
      if ($db_opcao == 2 || $db_opcao == 3 && !isset($excluir)) {

        $sWhere       = "ed220_i_turma = $ed57_i_codigo and ed133_ano = {$iAnoEtapaCenso} ";
        $sSqlQuery    = $clturmaserieregimemat->sql_query_censo("", "*", "ed223_i_ordenacao", $sWhere );
        $result_etapa = $clturmaserieregimemat->sql_record($sSqlQuery);

        $ed219_i_codigo = pg_result($result_etapa,0,'ed219_i_codigo');
        $ed219_c_nome   = pg_result($result_etapa,0,'ed219_c_nome');
        if ($ed219_i_codigo != "") {
          ?>
          <tr>
            <td nowrap title="<?=@$Ted223_i_regimematdiv?>" valign="top">
              <label for="ed219_i_codigo"><?=@$Led223_i_regimematdiv?></label>
            </td>
            <td>
              <?php
              db_input('ed219_i_codigo', 15, @$Ied219_i_codigo, true, 'text', 3);
              db_input('ed219_c_nome',   40, @$Ied219_c_nome,   true, 'text', 3);
              ?>
            </td>
          </tr>
          <?php
        }
        ?>
        <tr>
          <td nowrap title="<?=@$Ted223_i_serie?>" valign="top">
            <?=@$Led223_i_serie?>
          </td>
          <td>
            <?php
            for ($p = 0; $p < $clturmaserieregimemat->numrows; $p++) {

              db_fieldsmemory($result_etapa,$p);
              if ($clturmaserieregimemat->numrows == 1) {

                $desab   = "disabled";
                $check   = "checked";
                $clique1 = "";

              } else {

                $desab = "disabled";
                if ($ed220_c_historico == "S") {
                  $check = "checked";
                } else {
                  $check = "";
                }
                $clique1 = "onclick=\"js_verificaetapahist($p)\"";
              }
              if ($ed29_c_historico == "N") {
                $visible = "hidden";
              } else {
                $visible = "visible";
              }
              echo '<table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td width="15%">';
              echo ' <input type="checkbox" name="etapa" id="etapa'. $p .'" value="" disabled checked> ';
              echo ' <label for="etapa'. $p .'" >'.$ed11_i_codigo.' - '.$ed11_c_descr . '</label>';
              echo '</td><td width="30%">';
              echo ' <b>Proc. Avaliação: </b>'.$ed40_i_codigo.' - '.$ed40_c_descr;
              echo '</td><td>';
              echo '<spam id ="aprovaAutomatico">';
              echo ' <b>Aprovação Automática: </b>';
              $x = array('N'=>'NÃO','S'=>'SIM');
              db_select('ed220_c_aprovauto',$x,true,$db_opcao," onchange=\"js_aprovauto(this.value,$ed220_i_codigo);\"");
              echo '</spam>';
              echo '<span id="checkhist" style="visibility:hidden">';
              echo ' <input type="checkbox" name="etapahistorico" id="etapahistorico" value="'.$ed220_i_codigo.'" '.
                $desab.' '.$check.' '.$clique1.'> Incluir no Histórico <br>';
              echo '</span>';
              echo '</td></tr></table>';
            }
            ?>
          </td>
        </tr>
        <?php
      }
      ?>
      <tbody id="div_censoetapa">
      </tbody>
      <?php
      if ($db_opcao == 2 || $db_opcao == 3 && !isset($excluir)) {
        ?>
        <tr id ='etapaCenso'>
          <td nowrap title="<?=@$Ted132_censoetapa?>">
            <label for="ed132_censoetapa"><?db_ancora(@$Led132_censoetapa,"",3);?> </label>
          </td>
          <td>
            <?php
            db_input('ed132_censoetapa',15,$Ied132_censoetapa,true,'text',3,'');
            db_input('ed266_c_descr',40,@$Ied266_c_descr,true,'text',3,'');
            ?>
          </td>
        </tr>
        <?php
      }
      ?>
      <tr>
        <td nowrap title="<?=@$Ted57_i_turno?>">
          <label for="ed57_i_turno"><?db_ancora(@$Led57_i_turno,"js_pesquisaed57_i_turno(true);",$db_opcao);?></label>
        </td>
        <td>
          <?php
          db_input('ed336_turnoreferente',10,$Ied336_turnoreferente,true,'hidden',$db_opcao);
          db_input('ed57_i_turno',15,$Ied57_i_turno,true,'text',3," onchange='js_pesquisaed57_i_turno(false);'");
          db_input('ed15_c_nome',40,@$Ied15_c_nome,true,'text',3,'');
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Ted57_i_sala?>">
          <label for="ed57_i_sala"><?db_ancora(@$Led57_i_sala,"js_pesquisaed57_i_sala(true);",$db_opcao);?></label>

        </td>
        <td>
          <?php
          db_input('ed57_i_sala',15,$Ied57_i_sala,true,'text',3," onchange='js_pesquisaed57_i_sala(false);'");
          db_input('ed16_c_descr',40,@$Ied16_c_descr,true,'text',3,'');
          echo "<label for='ed16_i_capacidade'>{$Led16_i_capacidade} </label>";
          db_input('ed16_i_capacidade',5,@$Ied16_i_capacidade,true,'text',3,'');
          db_input('iVagasSala', 10, '', true, 'hidden', 3 );
          ?>
        </td>
      </tr>
      <tr id="linhaFrequencia">
        <td nowrap title="<?=@$Ted57_c_medfreq?>">
          <label for="ed57_c_medfreq"> <?=@$Led57_c_medfreq?> </label>
        </td>
        <td>
          <?php
          $x = array(''=>'','PERÌODOS'=>'PERÍODOS','DIAS LETIVOS'=>'DIAS LETIVOS');
          db_select('ed57_c_medfreq',$x,true,$db_opcao,"");
          ?>
          <spam id = 'tipoAtendimento'>
            <label for="ed57_i_tipoatend"><?=@$Led57_i_tipoatend?></label>
            <?
            $x = array('0'=>'NÃO SE APLICA','1'=>'CLASSE HOSPITALAR','2'=>'UNIDADE DE INTERNAÇÃO','3'=>'UNIDADE PRISIONAL');
            db_select('ed57_i_tipoatend',$x,true,$db_opcao,"");
            ?>
          </spam>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Ted57_t_obs?>">
          <label for="ed57_t_obs"> <?=@$Led57_t_obs?></label>
        </td>
        <td>
          <table>
            <tr>
              <td>
                <?db_textarea('ed57_t_obs',3,90,$Ied57_t_obs,true,'text',$db_opcao,"","","",200)?>
              </td>
            </tr>
          </table>
        </td>
      </tr>
      <tr id='turnoAdicional'>
        <td nowrap title="<?=@$Ted246_i_turno?>">
          <label for="ed246_i_turno"><?db_ancora(@$Led246_i_turno,"js_pesquisaed246_i_turno(true);",$db_opcao);?></label>
        </td>
        <td>
          <?php
          db_input('ed246_i_turno',15,$Ied246_i_turno,true,'text',1," onchange='js_pesquisaed246_i_turno(false);'");
          db_input('ed15_c_nomeadd',40,@$Ied15_c_nomeadd,true,'text',3,'');
          ?>
        </td>
      </tr>
      <tr id='trMaisEducacao'>
        <td nowrap title="<?=@$Ted57_censoprogramamaiseducacao?>">
          <label for="ed57_censoprogramamaiseducacao"> <?=@$Led57_censoprogramamaiseducacao?> </label>
        </td>
        <td>
          <?php

          if (empty($ed57_censoprogramamaiseducacao) && isset( $ed57_i_tipoturma ) && $ed57_i_tipoturma != 2) {
            $ed57_censoprogramamaiseducacao = 'f';
          }
          $x = array('' => '', 't'=>'SIM', 'f'=>'NÃO');
          db_select('ed57_censoprogramamaiseducacao', $x, true, $db_opcao, '');
          ?>
        </td>
      </tr>
      <?
      $visivel = "hidden";

      if ($db_opcao == 2 || $db_opcao == 3 && !isset($excluir)) {

        for ($p = 0; $p < $clturmaserieregimemat->numrows; $p++) {

          db_fieldsmemory($result_etapa,$p);

          $aEtapasEnsinoRegular = array(30, 31, 32, 33, 34, 39, 40);
          if (isset($ed36_c_abrev) && $ed36_c_abrev == "ER" && in_array($ed133_censoetapa, $aEtapasEnsinoRegular)) {

            $visivel          = "visible";
            $ver_cursoprofiss = "OK";
            break;
          }

          $aEtapasEnsinoEspecial = array(30, 31, 32, 33, 34, 39, 40, 62, 63);
          if (isset($ed36_c_abrev) && $ed36_c_abrev == "ES" && in_array($ed133_censoetapa, $aEtapasEnsinoEspecial) ) {

            $visivel          = "visible";
            $ver_cursoprofiss = "OK";
            break;
          }

          $aEtapasEJA = array(62, 63);
          if(isset($ed36_c_abrev) && $ed36_c_abrev == "EJ" && in_array($ed133_censoetapa, $aEtapasEJA) ) {

            $visivel          = "visible";
            $ver_cursoprofiss = "OK";
            break;
          }

          $aEtapasProfissionalizantes = array(30,31,32,33,34,39,40,73,74,64,67,68);
          if (isset($ed36_c_abrev) && $ed36_c_abrev == "EP" && in_array($ed133_censoetapa, $aEtapasProfissionalizantes) ) {

            $visivel          = "visible";
            $ver_cursoprofiss = "OK";
            break;
          }
        }
      }
      ?>
      <tr name="cursoprofiss" id="cursoprofiss" style="visibility:<?=$visivel?>">
        <td nowrap title="<?=@$Ted57_i_censocursoprofiss?>">
          <label for="ed57_i_censocursoprofiss">
            <?=@$Led57_i_censocursoprofiss?>
          </label>
        </td>
        <td>
          <?php
          db_input('ed57_i_censocursoprofiss',15,$Ied57_i_censocursoprofiss,true,'text',3,
            " onchange='js_pesquisaed57_i_censocursoprofiss(false);'");
          db_input('ed247_c_descr',40,@$Ied247_c_descr,true,'text',3);
          db_input('ver_cursoprofiss',2,@$Iver_cursoprofiss,true,'hidden',3);
          db_input('ed10_censocursoprofiss',15,@$Ied10_censocursoprofiss,true,'hidden',3);
          ?>
        </td>
      </tr>
    </table>
  </center>
  <input name="etapa_turma" type="hidden" value="">
  <input name="linhaproc" type="hidden" value="">
  <input name="ed37_c_tipo" type="hidden" value="<?=@$ed37_c_tipo?>">
  <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>"
         type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>"
    <?=($db_botao==false?"disabled":"")?>
    <?=$db_opcao==1?"onclick='return js_validacaoInc();'":"onclick='return js_validacaoAltExc();'"?>>
  <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();"
    <?=($db_botao2==false?"disabled":"")?>>
  <input name="novo" type="button" id="novo" value="Novo Registro" onclick="js_novo()"
    <?=$db_opcao==1?"disabled":""?> <?=($db_botao2==false?"disabled":"")?>>
</form>
<iframe src="" name="iframe_verifica" id="iframe_verifica" width="0" height="0" frameborder="0"></iframe>
<script>

  var lEnsinoInfantil     = false;
  var lTurnoIntegral      = false;
  var aLinhasCriadas      = new Array();
  var iOpcao              = <?=$db_opcao;?>;
  var lTurnoAtualIntegral = false;
  var sTurnoAnterior      = '';
  var iTurnoAnterior      = null;

  if ( iOpcao != 1 ) {

    js_verificaEnsinoIsInfantil();
    js_verificaTurnoIntegral();
    js_adicionaLinhaVagasTurma();
    buscaVagasTurma();
    verificaCensoCursoProfissionalizante();
    lTurnoAtualIntegral = lTurnoIntegral;
    sTurnoAnterior      = $F("ed15_c_nome");
    iTurnoAnterior      = $F("ed57_i_turno");
  }

  /**
   * Permite a inclusão de caracteres como º ª no nome da turma
   */
  $("ed57_c_descr").removeAttribute("onblur");
  $("ed57_c_descr").removeAttribute("onkeydown");
  $("ed57_c_descr").removeAttribute("oninput");

  function js_pesquisaed57_i_calendario(mostra) {
    if (mostra == true) {

      js_OpenJanelaIframe('','db_iframe_calendarioturma',
        'func_calendarioturma.php?funcao_js=parent.js_mostracalendario1|ed52_i_codigo|ed52_c_descr',
        'Pesquisa de Calendários',true);

    } else {

      if (document.form1.ed57_i_calendario.value != '') {

        js_OpenJanelaIframe('','db_iframe_calendarioturma',
          'func_calendarioturma.php?pesquisa_chave='+document.form1.ed57_i_calendario.value+
          '&funcao_js=parent.js_mostracalendario','Pesquisa',false);

      } else {

        document.form1.ed52_c_descr.value = '';
        limpaprocaval();

      }
    }
  }

  function js_mostracalendario(chave,erro) {

    document.form1.ed52_c_descr.value = chave;
    if (erro == true) {

      document.form1.ed57_i_calendario.focus();
      document.form1.ed57_i_calendario.value = '';

    }

    limpaprocaval();
  }

  function js_mostracalendario1(chave1,chave2) {

    document.form1.ed57_i_calendario.value = chave1;
    document.form1.ed52_c_descr.value      = chave2;
    limpaprocaval();
    db_iframe_calendarioturma.hide();

  }

  function js_pesquisaed57_i_base(mostra) {

    var lBaseAtiva = true;

    if (mostra == true) {

      js_OpenJanelaIframe('','db_iframe_base','func_baseturma.php?funcao_js=parent.js_mostrabase1|ed31_i_codigo|'+
        'ed31_c_descr|ed29_i_codigo|ed29_c_descr|ed36_c_abrev|ed218_i_codigo|ed218_c_nome|'+
        'ed218_c_divisao|ed31_c_medfreq|ed29_i_ensino|ed29_c_historico|ed10_censocursoprofiss&lBaseAtiva='+lBaseAtiva,
        'Pesquisa de Bases Curriculares',true);

    } else {

      if (document.form1.ed57_i_base.value != '') {

        js_OpenJanelaIframe('','db_iframe_base','func_baseturma.php?pesquisa_chave='+document.form1.ed57_i_base.value+
          '&funcao_js=parent.js_mostrabase&lBaseAtiva='+lBaseAtiva,'Pesquisa',false);

      } else {

        document.form1.ed31_c_descr.value             = '';
        document.form1.ed29_i_codigo.value            = '';
        document.form1.ed29_c_descr.value             = '';
        document.form1.ed36_c_abrev.value             = '';
        document.form1.ed31_i_regimemat.value         = '';
        document.form1.ed218_c_nome.value             = '';
        document.form1.ed218_c_divisao.value          = '';
        document.form1.ed57_c_medfreq.value           = '';
        document.form1.ed29_i_ensino.value            = '';
        document.form1.ed29_c_historico.value         = '';
        document.form1.ed57_i_censocursoprofiss.value = '';
        document.form1.ed247_c_descr.value            = '';
        document.form1.ed10_censocursoprofiss.value   = '';

      }
    }

    $('div_etapa').innerHTML = '';
    $('div_divisao').innerHTML = '';
    document.form1.etapa_turma.value = "";

  }

  function js_mostrabase(chave1,chave2,chave3,chave4,chave5,chave6,chave7,chave8,chave9,chave10,chave11,erro) {

    document.form1.ed31_c_descr.value     = chave1;
    document.form1.ed29_i_codigo.value    = chave2;
    document.form1.ed29_c_descr.value     = chave3;
    document.form1.ed36_c_abrev.value     = chave4;
    document.form1.ed31_i_regimemat.value = chave5;
    document.form1.ed218_c_nome.value     = chave6;
    document.form1.ed218_c_divisao.value  = chave7;
    document.form1.ed57_i_turno.value     = '';
    document.form1.ed15_c_nome.value      = '';

    if (chave8 == "P") {
      freq = "PERÌODOS";
    } else if (chave8 == "D") {
      freq = "DIAS LETIVOS";
    } else {
      freq = "";
    }

    document.form1.ed57_c_medfreq.value         = freq;
    document.form1.ed29_i_ensino.value          = chave9;
    document.form1.ed29_c_historico.value       = chave10;
    document.form1.ed10_censocursoprofiss.value = chave11;

    if (chave11 !== undefined || chave11 != '') {
      verificaCensoCursoProfissionalizante();
    }

    if (erro == true) {

      document.form1.ed57_i_base.focus();
      document.form1.ed57_i_base.value = '';

    } else {

      if (chave7 == "S") {
        js_divisoes(document.form1.ed57_i_base.value);
      } else {
        js_etapa(chave5);
      }
    }
    js_verificaEnsinoIsInfantil();
    js_validaTipoTurma();
  }

  function js_mostrabase1(chave1,chave2,chave3,chave4,chave5,chave6,chave7,chave8,chave9,chave10,chave11,chave12) {

    document.form1.ed57_i_base.value      = chave1;
    document.form1.ed31_c_descr.value     = chave2;
    document.form1.ed29_i_codigo.value    = chave3;
    document.form1.ed29_c_descr.value     = chave4;
    document.form1.ed36_c_abrev.value     = chave5;
    document.form1.ed31_i_regimemat.value = chave6;
    document.form1.ed218_c_nome.value     = chave7;
    document.form1.ed218_c_divisao.value  = chave8;

    if (chave9 == "P") {
      freq = "PERÌODOS";
    } else {
      freq = "DIAS LETIVOS";
    }

    document.form1.ed57_c_medfreq.value         = freq;
    document.form1.ed29_i_ensino.value          = chave10;
    document.form1.ed29_c_historico.value       = chave11;
    document.form1.ed57_i_turno.value           = '';
    document.form1.ed15_c_nome.value            = '';
    document.form1.ed10_censocursoprofiss.value = chave12;

    if (chave12 !== undefined || chave12 != '') {
      verificaCensoCursoProfissionalizante();
    }

    if (chave8 == "S") {
      js_divisoes(chave1);
    } else {
      js_etapa(chave6);
    }
    db_iframe_base.hide();
    js_verificaEnsinoIsInfantil();
  }

  function js_pesquisaed57_i_censocursoprofiss(mostra) {

    if (mostra == true) {

      js_OpenJanelaIframe('','db_iframe_censocursoprofiss',
        'func_censocursoprofiss.php?funcao_js=parent.js_mostracensocursoprofiss1|'+
        'ed247_i_codigo|ed247_c_descr','Pesquisa de Cursos Profissionalizantes',true);

    }
  }

  function js_mostracensocursoprofiss1(chave1,chave2) {

    document.form1.ed57_i_censocursoprofiss.value = chave1;
    document.form1.ed247_c_descr.value            = chave2;
    db_iframe_censocursoprofiss.hide();

  }

  function js_pesquisaed57_i_turno(mostra) {

    if (document.form1.ed29_i_codigo.value == "") {

      alert("Informe a Base Curricular!");
      document.form1.ed29_i_codigo.value               = '';
      document.form1.ed57_c_medfreq.value              = '';
      document.form1.ed29_c_descr.value                = '';
      document.form1.ed57_i_base.style.backgroundColor = '#99A9AE';
      document.form1.ed57_i_base.focus();

    } else {

      if (mostra == true) {

        js_OpenJanelaIframe('','db_iframe_turno','func_turnoreferencia.php?curso='+document.form1.ed29_i_codigo.value+
          '&funcao_js=parent.js_mostraturno1|ed15_i_codigo|ed15_c_nome|ed231_i_referencia','Pesquisa de Turnos',true);
      }
    }
  }

  function js_mostraturno1(iCodigo, sTurno, iReferencia) {

    document.form1.ed57_i_turno.value         = iCodigo;
    document.form1.ed15_c_nome.value          = sTurno;
    document.form1.ed336_turnoreferente.value = iReferencia;
    document.form1.ed57_i_sala.value          = "";
    document.form1.ed16_c_descr.value         = "";
    document.form1.ed16_i_capacidade.value    = "";
    db_iframe_turno.hide();

    if (document.form1.ed246_i_turno.value != "" && document.form1.ed246_i_turno.value == iCodigo) {

      alert("Turno principal escolhido("+sTurno+") é igual ao turno adicional.\nInforme outro turno ou troque o turno adicional!");
      document.form1.ed57_i_turno.value         = "";
      document.form1.ed15_c_nome.value          = "";
      document.form1.ed336_turnoreferente.value = "";
    }

    js_limparLinhas();
    js_verificaTurnoIntegral();
    js_validaTipoTurma();
  }

  function js_pesquisaed220_i_procedimento(linhaproc,calendario,caldescr) {

    if (calendario == "") {
      alert("Informe o Calendário!");
    } else {

      js_OpenJanelaIframe('','db_iframe_procedimento','func_procedimentoturma.php?calendario='+calendario+
        '&caldescr='+caldescr+'&funcao_js=parent.js_mostraprocedimento1|ed40_i_codigo|ed40_c_descr',
        'Pesquisa de Procedimentos de Avaliação',true);
      document.form1.linhaproc.value = linhaproc;

    }
  }

  function js_mostraprocedimento1(chave1,chave2) {

    eval('document.form1.ed220_i_procedimento'+document.form1.linhaproc.value+'.value = chave1');
    eval('document.form1.ed40_c_descr'+document.form1.linhaproc.value+'.value = chave2');
    db_iframe_procedimento.hide();

  }

  function js_pesquisaed132_censoetapa(mostra) {

    if (document.form1.ed57_i_base.value == "") {

      alert("Informe a Base Curricular!");
      document.form1.ed132_censoetapa.value = '';
      document.form1.ed57_i_base.style.backgroundColor='#99A9AE';
      document.form1.ed57_i_base.focus();

    } else {

      js_OpenJanelaIframe('','db_iframe_censoetapa',
        'func_censoetapaturma.php?abrevtipoensino='+document.form1.ed36_c_abrev.value +
        '&iCursoEdu=' + $F('ed29_i_codigo') + '&iCalendario=' + $F('ed57_i_calendario') +
        '&funcao_js=parent.js_mostracensoetapa1|ed266_i_codigo|ed266_c_descr',
        'Pesquisa de Etapas do Censo',true);

    }
  }

  function js_mostracensoetapa1(chave1,chave2) {

    document.form1.ed132_censoetapa.value = chave1;
    document.form1.ed266_c_descr.value     = chave2;
    db_iframe_censoetapa.hide();

  }

  function js_pesquisaed57_i_sala(mostra) {

    if (document.form1.ed57_i_turno.value == "") {

      alert("Informe o Turno!");
      document.form1.ed57_i_sala.value = '';
      document.form1.ed57_i_turno.style.backgroundColor='#99A9AE';
      document.form1.ed57_i_turno.focus();

    } else if (document.form1.ed57_i_calendario.value == "") {

      alert("Informe o Calendário!");
      document.form1.ed57_i_sala.value = '';
      document.form1.ed57_i_calendario.style.backgroundColor='#99A9AE';
      document.form1.ed57_i_calendario.focus();

    } else {

      if (mostra == true) {

        if (document.form1.ed57_i_codigo.value == "") {
          turma = 0;
        } else {
          turma = document.form1.ed57_i_codigo.value;
        }
        js_OpenJanelaIframe('','db_iframe_sala','func_salaturma.php?turma='+turma+
          '&curso='+document.form1.ed29_i_codigo.value+'&turno='+document.form1.ed57_i_turno.value+
          '&calendario='+document.form1.ed57_i_calendario.value+
          '&funcao_js=parent.js_mostrasala1|ed16_i_codigo|ed16_c_descr|ed16_i_capacidade',
          'Pesquisa de Salas',true);
      } else {

        if (document.form1.ed57_i_sala.value != '') {

          js_OpenJanelaIframe('','db_iframe_sala','func_salaturma.php?turma='+turma+
            '&curso='+document.form1.ed29_i_codigo.value+'&turno='+document.form1.ed57_i_turno.value+
            '&calendario='+document.form1.ed57_i_calendario.value+
            '&pesquisa_chave='+document.form1.ed57_i_sala.value+'&funcao_js=parent.js_mostrasala',
            'Pesquisa',false);

        } else {
          document.form1.ed16_c_descr.value = '';
        }
      }
    }
  }

  function js_mostrasala(chave1,erro,chave2) {

    document.form1.ed16_c_descr.value      = chave1;
    document.form1.ed16_i_capacidade.value = chave2;
    document.form1.ed336_vagas.value   = chave2;

    if (erro == true) {

      document.form1.ed57_i_sala.focus();
      document.form1.ed57_i_sala.value = '';

    } else {

      if (document.form1.ed57_i_codigo.value == "") {
        turma = 0;
      } else {
        turma = document.form1.ed57_i_codigo.value;
      }
      iframe_verifica.location.href = "edu1_turma004.php?turma="+turma+"&escola="+document.form1.ed57_i_escola.value+
        "&turno="+document.form1.ed57_i_turno.value+
        "&calendario="+document.form1.ed57_i_calendario.value+
        "&sala="+document.form1.ed57_i_sala.value;
    }
  }

  function js_mostrasala1(chave1,chave2,chave3) {

    document.form1.ed57_i_sala.value       = chave1;
    document.form1.ed16_c_descr.value      = chave2;
    document.form1.ed16_i_capacidade.value = chave3;
    $('iVagasSala').value                 = chave3;

    js_adicionaLinhaVagasTurma();

    if (document.form1.ed57_i_codigo.value == "") {
      turma = 0;
    } else {
      turma = document.form1.ed57_i_codigo.value;
    }
    iframe_verifica.location.href = "edu1_turma004.php?turma="+turma+
      "&escola="+document.form1.ed57_i_escola.value+
      "&turno="+document.form1.ed57_i_turno.value+
      "&calendario="+document.form1.ed57_i_calendario.value+"&sala="+chave1;
    db_iframe_sala.hide();
  }

  function js_pesquisa() {

    js_OpenJanelaIframe('','db_iframe_turma','func_turma.php?funcao_js=parent.js_preenchepesquisa|ed57_i_codigo',
      'Pesquisa de Turmas',true);

  }

  function js_preenchepesquisa(chave) {

    db_iframe_turma.hide();
    <?php
    if ($db_opcao != 1) {
      echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
    }
    ?>

  }

  function js_pesquisaed246_i_turno(mostra) {

    if (document.form1.ed29_i_codigo.value == "") {

      alert("Informe a Base Curricular!");
      document.form1.ed29_i_codigo.value               = '';
      document.form1.ed57_c_medfreq.value              = '';
      document.form1.ed29_c_descr.value                = '';
      document.form1.ed57_i_base.style.backgroundColor = '#99A9AE';
      document.form1.ed57_i_base.focus();

    } else if (document.form1.ed57_i_turno.value == "") {

      alert("Primeiro informe o turno principal da turma!");
      document.form1.ed57_i_turno.style.backgroundColor='#99A9AE';
      document.form1.ed57_i_turno.focus();

    } else {

      if (mostra == true) {

        js_OpenJanelaIframe('','db_iframe_turnoadd','func_turnoaddturma.php?curso='+document.form1.ed29_i_codigo.value+
          '&turnoprinc='+document.form1.ed57_i_turno.value+'&funcao_js=parent.js_mostraturnoadd1|'+
          'ed15_i_codigo|ed15_c_nome','Pesquisa de Turno Adicional',true);

      } else {

        if (document.form1.ed246_i_turno.value != '') {

          js_OpenJanelaIframe('','db_iframe_turnoadd','func_turnoaddturma.php?curso='+document.form1.ed29_i_codigo.value+
            '&turnoprinc='+document.form1.ed57_i_turno.value+
            '&pesquisa_chave='+document.form1.ed246_i_turno.value+'&funcao_js=parent.js_mostraturnoadd',
            'Pesquisa',false);

        } else {
          document.form1.ed15_c_nomeadd.value = '';
        }
      }
    }
  }

  function js_mostraturnoadd(chave,erro) {

    document.form1.ed15_c_nomeadd.value = chave;
    if (erro == true) {

      document.form1.ed246_i_turno.focus();
      document.form1.ed246_i_turno.value = '';

    }
  }

  function js_mostraturnoadd1(chave1,chave2) {

    document.form1.ed246_i_turno.value  = chave1;
    document.form1.ed15_c_nomeadd.value = chave2;
    db_iframe_turnoadd.hide();

  }

  function js_novo() {
    parent.location.href="edu1_turmaabas001.php";
  }

  function js_divisoes(codbase) {

    document.form1.etapa_turma.value = "";
    if (codbase == 0) {

      $('div_divisao').innerHTML = "";
      return false;

    }
    js_divCarregando("Aguarde, buscando registro(s)","msgBox");
    var sAction = 'PesquisaDivisao';
    var url     = 'edu1_turmaRPC.php';
    parametros  = 'sAction='+sAction+'&base='+codbase;
    var oAjax = new Ajax.Request(url,{method    : 'post',
      parameters: parametros,
      onComplete: js_retornaPesquisaDivisao
    });
  }

  function js_retornaPesquisaDivisao(oAjax) {

    js_removeObj("msgBox");
    var oRetorno = eval("("+oAjax.responseText+")");
    sHtml  = '<tr>';
    sHtml += ' <td valign="top"><b><?=@$Led223_i_regimematdiv?></b>';
    sHtml += ' </td>';
    sHtml += ' <td>';

    if (oRetorno.length == 0) {

      sHtml += '  Nenhuma divisão cadastrada para o regime de matrícula selecionado.';
      sHtml += '  <input type="hidden" name="divisao" id="divisao" value="N">';

    } else {

      cont = 0;
      for (var i = 0;i < oRetorno.length; i++) {

        cont++;
        with (oRetorno[i]) {

          sHtml += '  <input type="radio" name="divisao" id="divisao" value="'+ed219_i_codigo+'" '+
            ' onclick="js_etapadivisao(this.value);"> '+ed219_c_nome.urlDecode();
          if ((cont%3) == 0) {
            sHtml += '<br>';
          }
        }
      }
    }
    sHtml += ' </td>';
    sHtml += '</tr>';
    $('div_divisao').innerHTML = sHtml;
  }

  function js_etapadivisao(coddivisao) {

    document.form1.etapa_turma.value = "";
    $('div_etapa').innerHTML = '';
    js_divCarregando("Aguarde, buscando registro(s)","msgBox");
    var sAction = 'PesquisaEtapaDivisao';
    var url     = 'edu1_turmaRPC.php';
    parametros  = 'sAction='+sAction+'&coddivisao='+coddivisao+'&codregime='+$F('ed31_i_regimemat');
    parametros += '&codensino='+$F('ed29_i_ensino');
    parametros += '&iCalendario='+$F('ed57_i_calendario');
    parametros += '&iBase='+$F('ed57_i_base');
    var oAjax = new Ajax.Request(url,{method    : 'post',
      parameters: parametros,
      onComplete: js_retornaPesquisaEtapaDivisao
    });

  }

  function js_retornaPesquisaEtapaDivisao(oAjax) {

    js_removeObj("msgBox");
    var oRetorno = eval("("+oAjax.responseText+")");
    sHtml  = '<tr>';
    sHtml += ' <td valign="top"><b><?=@$Led223_i_serie?></b>';
    sHtml += ' </td>';
    sHtml += ' <td>';

    if (oRetorno.length == 0) {

      sHtml += '  Nenhuma disciplina cadastrada na base curricular selecionada..';
      sHtml += '  <input type="hidden" name="etapa" id="etapa" value="N">';

    } else {

      for (var i = 0;i < oRetorno.length; i++) {

        with (oRetorno[i]) {

          if (oRetorno.length == 1) {

            desab   = "disabled";
            check   = "checked";
            clique  = "onclick=\"js_verificaetapa("+i+","+ed11_i_codcenso.urlDecode()+",'"+ed266_c_descr.urlDecode()+"')\"";
            clique1 = "onclick=\"js_verificaetapahist("+i+")\"";

          } else {

            desab   = "";
            check   = "";
            clique  = "onclick=\"js_verificaetapa("+i+","+ed11_i_codcenso.urlDecode()+",'"+ed266_c_descr.urlDecode()+"')\"";
            clique1 = "onclick=\"js_verificaetapahist("+i+")\"";

          }
          if ($('ed29_c_historico').value == "N") {

            check   = "checked";
            visible = "hidden";

          } else {
            visible = "visible";
          }
          sHtml += '  <table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td width="15%">';
          sHtml += '  <input type="hidden" name="etapacenso" id="etapacenso" value="'+ed11_i_codcenso.urlDecode()+'">';
          sHtml += '  <input type="hidden" name="descretapacenso" id="descretapacenso" value="'+ed266_c_descr.urlDecode()+'">';
          sHtml += '  <input type="checkbox" name="etapa" id="etapa" ';
          sHtml += '     value="'+ed223_i_codigo+'" '+clique+'> '+ed11_i_codigo.urlDecode()+' - '+ed11_c_descr.urlDecode();
          sHtml += '  </td><td>';
          sHtml += '  <span id="procavalauto'+i+'">';
          sHtml += '  </span>';
          sHtml += '  <span id="checkhist" style="visibility:hidden">';
          sHtml += '   <input type="checkbox" name="etapahistorico" id="etapahistorico" ';
          sHtml += '          value="" '+desab+' '+check+' '+clique1+'> Incluir no Histórico <br>';
          sHtml += '  </span>';
          sHtml += '  </td></tr></table>';
        }
      }
    }
    sHtml += ' </td>';
    sHtml += '</tr>';
    $('div_etapa').innerHTML = sHtml;
  }

  function js_etapa(codregime) {

    document.form1.etapa_turma.value = "";
    js_divCarregando("Aguarde, buscando registro(s)","msgBox");
    var sAction = 'PesquisaEtapa';
    var url     = 'edu1_turmaRPC.php';
    parametros  = 'sAction='+sAction+'&codregime='+codregime+'&codensino='+$('ed29_i_ensino').value+
      '&codbase='+$('ed57_i_base').value + "&iCalendario=" + $F('ed57_i_calendario');
    var oAjax = new Ajax.Request(url,{method    : 'post',
      parameters: parametros,
      onComplete: js_retornaPesquisaEtapa
    });
  }

  function js_retornaPesquisaEtapa(oAjax) {

    js_removeObj("msgBox");
    var oRetorno = eval("("+oAjax.responseText+")");
    sHtml = '<tr>';
    sHtml += ' <td valign="top"><b><?=@$Led223_i_serie?></b>';
    sHtml += ' </td>';
    sHtml += ' <td>';

    if (oRetorno.length == 0) {

      sHtml += '  Nenhuma disciplina cadastrada na base curricular selecionada.';
      sHtml += '  <input type="hidden" name="etapa" id="etapa" value="N">';

    } else {

      for (var i = 0;i < oRetorno.length; i++) {

        with (oRetorno[i]) {

          if (oRetorno.length == 1) {

            desab   = "disabled";
            check   = "checked";
            clique  = "onclick=\"js_verificaetapa("+i+","+ed11_i_codcenso.urlDecode()+",'"+ed266_c_descr.urlDecode()+"')\"";
            clique1 = "onclick=\"js_verificaetapahist("+i+")\"";

          } else {

            desab   = "";
            check   = "";
            clique  = "onclick=\"js_verificaetapa("+i+","+ed11_i_codcenso.urlDecode()+",'"+ed266_c_descr.urlDecode()+"')\"";
            clique1 = "onclick=\"js_verificaetapahist("+i+")\"";

          }

          if ($('ed29_c_historico').value == "N") {

            check   = "checked";
            visible = "hidden";

          } else {
            visible = "visible";
          }
          sHtml += '  <table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td width="15%">';
          sHtml += '  <input type="hidden" name="etapacenso" id="etapacenso" value="'+ed11_i_codcenso.urlDecode()+'">';
          sHtml += '  <input type="hidden" name="descretapacenso" id="descretapacenso" ';
          sHtml += '          value="'+ed266_c_descr.urlDecode()+'">';
          sHtml += '  <input type="checkbox" name="etapa" id="etapa'+ i +'" ';
          sHtml += '         value="'+ed223_i_codigo+'" '+clique+'> ';
          sHtml += '  <label for="etapa'+ i +'">'+ ed11_i_codigo.urlDecode()+' - '+ed11_c_descr.urlDecode()+'</label>';
          sHtml += '  </td><td>';
          sHtml += '  <span id="procavalauto'+i+'">';
          sHtml += '  </span>';
          sHtml += '  <span id="checkhist" style="visibility:hidden">';
          sHtml += '   <input type="checkbox" name="etapahistorico" id="etapahistorico" ';
          sHtml += '          value="" '+desab+' '+check+' '+clique1+'> Incluir no Histórico <br>';
          sHtml += '  </span>';
          sHtml += '  </td></tr></table>';
        }
      }
    }
    sHtml += ' </td>';
    sHtml += '</tr>';
    $('div_etapa').innerHTML = sHtml;
  }

  function js_validacaoInc() {

    if ( $F('ed57_c_descr') == '' ) {

      alert("Informe o nome da turma.");
      return false;
    }

    if (document.form1.ver_cursoprofiss.value == "OK" && document.form1.ed57_i_censocursoprofiss.value == "") {

      alert("Informe o Curso Profissionalizante!");
      return false;

    }

    if (document.form1.ed132_censoetapa && document.form1.ed132_censoetapa.value == "") {

      alert("Informe a Etapa Censo!");
      return false;

    }

    if (document.form1.divisao) {

      tam = document.form1.divisao.length;
      if (tam == undefined) {

        if (document.form1.divisao.value == "N") {

          alert("Informe alguma divisão do Regime de Matrícula!");
          return false;

        } else {

          if (document.form1.divisao.checked == false) {

            alert("Informe alguma divisão do Regime de Matrícula!");
            return false;

          }
        }

      } else {

        cont = 0;
        for (i = 0; i < tam; i++) {

          if (document.form1.divisao[i].checked == true) {
            cont++;
          }
        }

        if (cont == 0) {

          alert("Informe alguma divisão do Regime de Matrícula!");
          return false;

        }
      }
    }

    if (document.form1.etapa) {

      tam = document.form1.etapa.length;
      etapa_reg = "";
      etapa_sep = "";

      if (tam == undefined) {

        if (document.form1.etapa.value == "N") {

          alert("Informe alguma etapa!");
          return false;

        } else {

          if (document.form1.etapa.checked == false) {

            alert("Informe alguma etapa!");
            return false;

          } else {

            if (document.form1.etapahistorico.checked == true) {
              historico = "S";
            } else {
              historico = "N";
            }
            etapa_reg = document.form1.etapa.value+"|"+historico+"|0";
          }
        }

        if (document.form1.ed220_i_procedimento0.value == "") {

          alert("Informe o procedimento de avaliação para a(s) etapa(s) selecionada(s)!");
          return false;

        }
      } else {

        cont_hist = 0;
        for (i = 0; i < tam; i++) {

          if (document.form1.etapahistorico[i].checked == true) {

            historico = "S";
            cont_hist++;

          } else {
            historico = "N";
          }

          if (document.form1.etapa[i].checked == true) {

            etapa_reg += etapa_sep+document.form1.etapa[i].value+"|"+historico+"|"+i;
            etapa_sep = ",";

            if (eval('document.form1.ed220_i_procedimento'+i+'.value==""')) {

              alert("Informe o procedimento de avaliação para a(s) etapa(s) selecionada(s)!");
              return false;

            }
          }
        }
        if (etapa_reg == "") {

          alert("Informe alguma etapa!");
          return false;

        }

        if (cont_hist == 0) {

          alert("Alguma das etapas deve estar marcada como Incluir no Histórico!");
          return false;

        }
      }
      document.form1.etapa_turma.value = etapa_reg;
    }
    return true;
  }

  function js_validacaoAltExc() {

    if( empty( $F('ed57_i_sala') ) ) {

      alert( 'Dependência não informada.' );
      return false;
    }

    /**
     * Verifica se o turno foi alterado e lança mensagem avisando o usuário como ficarão os vínculos das matrículas
     * ao alterar o turno
     */
    if ( iTurnoAnterior != $F("ed57_i_turno") ) {

      var sMensagem = "";

      if ( !lTurnoAtualIntegral && lTurnoIntegral && lEnsinoInfantil ) {

        sMensagem += "Turno da turma será alterado para ( "+ $F("ed15_c_nome").trim() +" ), ";
        sMensagem += "porém os alunos ficarão vinculados apenas a ";
        sMensagem += "referência ( "+ sTurnoAnterior.trim() +" ). ";
        sMensagem += '\nPara alterar o vinculo dos alunos com os turnos acesse: \n';
        sMensagem += "\t- Procedimentos > Matrículas > Alterar Turno Matrícula Ed. Infantil";
      } else {

        sMensagem += 'Turno da turma será alterado para ( '+ $F("ed15_c_nome").trim() +' ).\n';
        sMensagem += "O sistema irá vincular todas as matrículas para o turno ( "+ $F("ed15_c_nome").trim() +" ).";
      }

      if (!confirm(sMensagem) ) {
        return false;
      }
    }

    if (document.form1.ver_cursoprofiss.value == "OK" && document.form1.ed57_i_censocursoprofiss.value == "") {

      alert("Informe o Curso Profissionalizante!");
      return false;

    }

    if (document.form1.ed132_censoetapa && document.form1.ed132_censoetapa.value == "") {

      alert("Informe a Etapa Censo!");
      return false;

    }

    tam       = document.form1.etapahistorico.length;
    etapa_reg = "";
    etapa_sep = "";
    if (tam == undefined) {
      etapa_reg = document.form1.etapahistorico.value+"|S|0";
    } else {

      cont_hist = 0;
      for (i = 0; i < tam; i++) {

        if (document.form1.etapahistorico[i].checked == true) {

          cont_hist++;
          etapa_reg += etapa_sep+document.form1.etapahistorico[i].value+"|S|"+i;
          etapa_sep = ",";

        } else {

          etapa_reg += etapa_sep+document.form1.etapahistorico[i].value+"|N|"+i;
          etapa_sep = ",";

        }
      }
      if (cont_hist == 0) {

        alert("Alguma das etapas deve estar marcada como Incluir no Histórico!");
        return false;

      }
    }
    document.form1.etapa_turma.value = etapa_reg;
    return true;
  }

  function js_verificaetapa(linha,codcenso,descrcenso) {

    var aEtapasEnsinoRegular         = [30, 31, 32, 33, 34, 39, 40];
    var aEtapasEnsinoEspecial        = [30, 31, 32, 33, 34, 39, 40, 62, 63];
    var aEtapasEJA                   = [62, 63];
    var aEtapasProfissionalizantes   = [30,31,32,33,34,39,40,73,74,64,67,68];
    document.form1.etapa_turma.value = "";

    tam = document.form1.etapa.length;
    if (tam == undefined) {

      if (document.form1.etapa.checked == false) {
        document.form1.etapahistorico.checked = false;
      } else {
        document.form1.etapahistorico.checked = true;
      }

      if (document.form1.etapa.checked == false) {

        $('div_censoetapa').innerHTML                            = "";
        $('procavalauto0').innerHTML                             = "";
        document.getElementById('cursoprofiss').style.visibility = "hidden";
        document.form1.ed57_i_censocursoprofiss.value            = "";
        document.form1.ed247_c_descr.value                       = "";
        document.form1.ver_cursoprofiss.value                    = "";

      } else {

        verificaCensoCursoProfissionalizante();

        codcenso = document.form1.etapacenso.value;
        if (document.form1.ed36_c_abrev.value == "ER" && aEtapasEnsinoRegular.in_array(codcenso) ) {

          document.getElementById('cursoprofiss').style.visibility = "visible";
          document.form1.ver_cursoprofiss.value                    = "OK";

        } else if (document.form1.ed36_c_abrev.value == "ES" && aEtapasEnsinoEspecial.in_array(codcenso) ) {

          document.getElementById('cursoprofiss').style.visibility = "visible";
          document.form1.ver_cursoprofiss.value                    = "OK";

        } else if (document.form1.ed36_c_abrev.value == "EJ" && aEtapasEJA.in_array(codcenso) ) {

          document.getElementById('cursoprofiss').style.visibility = "visible";
          document.form1.ver_cursoprofiss.value = "OK";

        } else if ( document.form1.ed36_c_abrev.value == "EP" && aEtapasProfissionalizantes.in_array(codcenso) ) {

          document.getElementById('cursoprofiss').style.visibility = "visible";
          document.form1.ver_cursoprofiss.value = "OK";
        }
        sHtml =  '<tr>';
        sHtml += ' <td>';
        sHtml += '  <b><?=@$Led132_censoetapa?></b>';
        sHtml += ' </td>';
        sHtml += ' <td>';
        sHtml += '  <input type="text" name="ed132_censoetapa" id="ed132_censoetapa" size="15" maxlength="15" ';
        sHtml += '          value="'+codcenso+'" style="background:#DEB887" readonly>';
        sHtml += '  <input type="text" name="ed266_c_descr" id="ed266_c_descr" size="40" maxlength="40" ';
        sHtml += '         value="'+descrcenso+'" style="background:#DEB887" readonly>';
        sHtml += ' </td>';
        sHtml += '</tr>';
        $('div_censoetapa').innerHTML = sHtml;

        sHtml  = '  <label for="ed220_i_procedimento0" >';
        sHtml += '    <a class="dbancora" href="javascript:js_pesquisaed220_i_procedimento(0,document.form1.ed57_i_calendario.value,document.form1.ed52_c_descr.value);"><b>Proc.Avaliação:</b></a>';
        sHtml += '  </label>';
        sHtml += '  <input type="text" name="ed220_i_procedimento0" id="ed220_i_procedimento0" size="15" maxlength="15" ';
        sHtml += '          value="" style="background:#DEB887" readonly>';
        sHtml += '  <input type="text" name="ed40_c_descr0" id="ed40_c_descr0" size="40" maxlength="40" ';
        sHtml += '         value="" style="background:#DEB887" readonly>';
        sHtml += '  <spam id ="aprovaAutomatico">';
        sHtml += '    <label for="ed220_c_aprovauto0">';
        sHtml += '      <b>Aprov.Automática:</b>';
        sHtml += '    </label>';
        sHtml += '    <select name="ed220_c_aprovauto0" id="ed220_c_aprovauto0">';
        sHtml += '      <option value="N">NÃO</option>';
        sHtml += '      <option value="S">SIM</option>';
        sHtml += '    </select>';
        sHtml += '  </spam>';
        $('procavalauto0').innerHTML = sHtml;

      }

    } else {

      if (document.form1.etapa[linha].checked == false) {

        document.form1.etapahistorico[linha].checked = false;
        $('procavalauto'+linha).innerHTML = "";

      } else {

        document.form1.etapahistorico[linha].checked = true;

        sHtml  = '  <label for="ed220_i_procedimento'+linha+'">';
        sHtml += '    <a class="dbancora" href="javascript:js_pesquisaed220_i_procedimento('+linha+',document.form1.ed57_i_calendario.value,document.form1.ed52_c_descr.value);"><b>Proc.Avaliação:</b></a>';
        sHtml += '  </label>';
        sHtml += '  <input type="text" name="ed220_i_procedimento'+linha+'" id="ed220_i_procedimento'+linha+'" ';
        sHtml += '         size="5" maxlength="5" value="" style="background:#DEB887" readonly>';
        sHtml += '  <input type="text" name="ed40_c_descr'+linha+'" id="ed40_c_descr'+linha+'" ';
        sHtml += '         size="30" maxlength="30" value="" style="background:#DEB887" readonly>';
        sHtml += '  <spam id ="aprovaAutomatico">';
        sHtml += '    <label for="ed220_c_aprovauto' + linha + '">';
        sHtml += '      <b>Aprov.Automática:</b>';
        sHtml += '    </label>';
        sHtml += '    <select name="ed220_c_aprovauto'+linha+'" id="ed220_c_aprovauto'+linha+'">';
        sHtml += '     <option value="N">NÃO</option>';
        sHtml += '     <option value="S">SIM</option>';
        sHtml += '    </select>';
        sHtml += '  </spam>';
        $('procavalauto'+linha).innerHTML = sHtml;

      }

      chekado     = "";
      sep_chekado = "";

      for (i = 0; i < tam; i++) {

        if (document.form1.etapa[i].checked == true) {

          codcenso   = document.form1.etapacenso[i].value;
          descrcenso = document.form1.descretapacenso[i].value;

          if (document.form1.ed36_c_abrev.value == "ER" && aEtapasEnsinoRegular.in_array(codcenso) ) {

            chekado    += sep_chekado+codcenso;
            sep_chekado = ",";

          } else if (document.form1.ed36_c_abrev.value == "ES" && aEtapasEnsinoEspecial.in_array(codcenso) ) {

            chekado    += sep_chekado+codcenso;
            sep_chekado = ",";

          } else if (document.form1.ed36_c_abrev.value == "EJ" && aEtapasEJA.in_array(codcenso) ) {

            chekado += sep_chekado+codcenso;
            sep_chekado = ",";

          } else if ( document.form1.ed36_c_abrev.value == "EP" && aEtapasProfissionalizantes.in_array(codcenso) ) {

            chekado += sep_chekado+codcenso;
            sep_chekado = ",";
          }
        }
      }

      conta        = 0;
      aEtapasCenso = [];

      for( var iContador = 0; iContador < tam; iContador++) {

        if(    document.form1.etapa[ iContador ].checked == true
          && !js_search_in_array( aEtapasCenso, document.form1.etapacenso[ iContador ].value )
        ) {

          aEtapasCenso.push( document.form1.etapacenso[ iContador ].value );
          conta++;
        }
      }

      if (chekado == "") {

        document.getElementById('cursoprofiss').style.visibility = "hidden";
        document.form1.ed57_i_censocursoprofiss.value            = "";
        document.form1.ed247_c_descr.value                       = "";
        document.form1.ver_cursoprofiss.value                    = "";

      } else {

        document.getElementById('cursoprofiss').style.visibility = "visible";
        document.form1.ver_cursoprofiss.value                    = "OK";

      }

      if (conta == 0) {
        sHtml = '';
      } else if (conta == 1) {

        sHtml  =  '<tr>';
        sHtml += ' <td>';
        sHtml += '   <label for="ed132_censoetapa">';
        sHtml += '     <b><?=@$Led132_censoetapa?></b>';
        sHtml += '   </label>';
        sHtml += ' </td>';
        sHtml += ' <td>';
        sHtml += '  <input type="text" name="ed132_censoetapa" id="ed132_censoetapa" size="15" maxlength="15" ';
        sHtml += '         value="'+codcenso+'" style="background:#DEB887" readonly>';
        sHtml += '  <input type="text" name="ed266_c_descr" id="ed266_c_descr" size="40" maxlength="40" ';
        sHtml += '         value="'+descrcenso+'" style="background:#DEB887" readonly>';
        sHtml += ' </td>';
        sHtml += '</tr>';

      } else {

        sHtml =  '<tr>';
        sHtml += ' <td>';
        sHtml += '   <label for="ed132_censoetapa">';
        sHtml += '     <a class="dbancora" href="javascript:js_pesquisaed132_censoetapa();"><b><?=@$Led132_censoetapa?></b></a>';
        sHtml += '   </label>';
        sHtml += ' </td>';
        sHtml += ' <td>';
        sHtml += '  <input type="text" name="ed132_censoetapa" id="ed132_censoetapa" size="15" maxlength="15"';
        sHtml += '         value="" style="background:#DEB887" readonly>';
        sHtml += '  <input type="text" name="ed266_c_descr" id="ed266_c_descr" size="40" maxlength="40"';
        sHtml += '         value="" style="background:#DEB887" readonly>';
        sHtml += ' </td>';
        sHtml += '</tr>';

      }
      $('div_censoetapa').innerHTML = sHtml;
    }
    js_validaTipoTurma();
  }

  function js_verificaetapahist(linha) {

    document.form1.etapa_turma.value = "";
    if (document.form1.etapahistorico[linha].checked == true) {
      document.form1.etapa[linha].checked = true;
    }
  }

  function limpaprocaval() {

    if (document.form1.etapa) {

      tam = document.form1.etapa.length;
      if (tam == undefined) {

        document.form1.ed220_i_procedimento0.value = "";
        document.form1.ed40_c_descr0.value = "";

      } else {

        for (i = 0; i < tam; i++) {

          if (eval('document.form1.ed220_i_procedimento'+i)) {

            eval('document.form1.ed220_i_procedimento'+i+'.value = ""');
            eval('document.form1.ed40_c_descr'+i+'.value = ""');

          }
        }
      }
    }
  }

  function js_aprovauto(valor,codigo) {

    js_divCarregando("Aguarde, atualizando registro","msgBox");
    var sAction = 'AtualizaAuto';
    var url     = 'edu1_turmaRPC.php';
    parametros  = 'sAction='+sAction+'&valorauto='+valor+'&codtsrmat='+codigo;
    var oAjax = new Ajax.Request(url,{method    : 'post',
      parameters: parametros,
      onComplete: js_retornaAtualizaAuto
    });

  }

  function js_retornaAtualizaAuto() {
    js_removeObj("msgBox");
  }

  function js_validaTipoTurma() {

    var iTipoTurma = $F('ed57_i_tipoturma');

    js_verificaTurnoAdicional();

    if ($('aprovaAutomatico')) {
      $('aprovaAutomatico').style.display = '';
    }
    $('codigoInep').style.display       = '';
    $('tipoAtendimento').style.display  = '';

    $('regime_matricula').style.display = 'table-row';
    if ($('etapaCenso')) {
      $('etapaCenso').style.display = 'table-row';
    }
    $('trMaisEducacao').style.display   = 'table-row';

    if (iTipoTurma == 6) {

      if ($('aprovaAutomatico')) {
        $('aprovaAutomatico').style.display = 'none';
      }
      $('codigoInep').style.display       = 'none';
      $('tipoAtendimento').style.display  = 'none';
      $('regime_matricula').style.display = 'none';
      $('turnoAdicional').style.display   = 'none';
      $('trMaisEducacao').style.display   = 'none';
      if ($('etapaCenso')) {
        $('etapaCenso').style.display  = 'none';
      }
    }

    if (iTipoTurma == 2) {

      $('trMaisEducacao').style.display         = 'none';
      $('ed57_censoprogramamaiseducacao').value = '';
    }
  }

  if($F('ed57_i_tipoturma') == 6 ) {
    parent.document.formaba.a5.disabled = true;
  }

  function js_verificaEnsinoIsInfantil() {

    var oParametros       = {};
    oParametros.iEnsino   = $F('ed29_i_ensino');
    oParametros.sExecucao = 'isInfantil';

    var oRequest          = {};
    oRequest.method       = 'post';
    oRequest.parameters   = 'json=' + Object.toJSON( oParametros );
    oRequest.asynchronous = false;
    oRequest.onComplete   = function (oAjax) {

      js_removeObj('msgBoxA');
      var oRetorno    = eval( '(' + oAjax.responseText + ')');
      lEnsinoInfantil = oRetorno.lEnsinoInfantil;
    }

    js_divCarregando("Aguarde, verificando tipo de ensino...", "msgBoxA");
    new Ajax.Request ('edu4_ensino.RPC.php', oRequest);
  }

  function js_verificaTurnoIntegral() {

    var oParametros    = {};
    oParametros.iTurno = $F('ed57_i_turno');
    oParametros.exec   = "isTurnoIntegral";

    var oRequest          = {};
    oRequest.method       = 'post';
    oRequest.parameters   = 'json=' + Object.toJSON( oParametros );
    oRequest.asynchronous = false;
    oRequest.onComplete   = function (oAjax) {

      js_removeObj('msgBoxB');
      var oRetorno    = eval( '(' + oAjax.responseText + ')');
      lTurnoIntegral  = oRetorno.lTurnoIntegral;
    }

    js_divCarregando("Aguarde, verificando turno...", "msgBoxB");
    new Ajax.Request ('edu4_turno.RPC.php', oRequest);
  }

  function js_adicionaLinhaVagasTurma() {

    if (lEnsinoInfantil && lTurnoIntegral) {

      js_insereLinhaVagasTurmaReferencia();
      $('ed246_i_turno').value  = '';
      $('ed15_c_nomeadd').value = '';
    } else {

      js_insereVagasTurmaNormal();
    }
  }


  function js_insereLinhaVagasTurmaReferencia() {

    js_limparLinhas();

    var iVagasSala      = $F("iVagasSala");
    var sIdLinha        = "";
    var aTurnoReferente = [];
    aTurnoReferente     = $F('ed336_turnoreferente').split(", ");

    aTurnoReferente.each( function (sTurno) {

      var sTurnoLower        = sTurno.toLowerCase();
      var sVagas             = "vagas" + sTurnoLower.removeAcento();
      var sMatriculados      = "matriculados" + sTurnoLower.removeAcento();
      var sDisponivel        = "disponivel" + sTurnoLower.removeAcento();
      var oLinha             = new Element("tr");
      var oColuna1           = new Element("td");
      var oColuna2           = new Element("td");
      var oLabelVagas        = new Element("label", {"class" : "bold", "for" : sVagas}).update("Vagas " + sTurnoLower + ": ");
      var oLabelMatriculados = new Element("label", {"class" : "bold", "for" : sMatriculados}).update(" Alunos matriculados: " );
      var oLabelDisponivel   = new Element("label", {"class" : "bold", "for" : sDisponivel}).update(" Vagas disponíveis: " );
      var oInputVagas        = new Element("input", {"type": "text", "name": sVagas, "size":8, "id" : sVagas, "value": iVagasSala,
        "referente" : sTurnoLower.removeAcento(), "class" : "vagasTurma"});
      var oInputMatriculados = new Element("input", {"type": "text", "disabled":"disabled", "name":sMatriculados, "size":8,
        "class":"readonly matriculadosTurno",
        "id":sMatriculados, "value": "0", "referente" : sTurnoLower.removeAcento()});
      var oInputDisponivel   = new Element("input", {"type": "text", "disabled":"disabled", "name": sDisponivel, "class":"readonly",
        "size":8, "id": sDisponivel, "value": iVagasSala, "referente" : sTurnoLower.removeAcento()});
      oInputVagas.onchange = function() {
        js_replicaVagas(sTurnoLower.removeAcento());
      }

      oColuna1.appendChild(oLabelVagas);
      oColuna2.appendChild(oInputVagas);
      oColuna2.appendChild(oLabelMatriculados);
      oColuna2.appendChild(oInputMatriculados);
      oColuna2.appendChild(oLabelDisponivel);
      oColuna2.appendChild(oInputDisponivel);
      oLinha.appendChild(oColuna1);
      oLinha.appendChild(oColuna2);

      var oNodeIrmao = $('linhaFrequencia').nextSibling;
      if (sIdLinha != '') {
        oNodeIrmao = $(sIdLinha);
      }

      sIdLinha = "linhaTurno" + sTurnoLower;
      oLinha.setAttribute("id", sIdLinha);
      aLinhasCriadas.push(oLinha);
      $('linhaFrequencia').parentNode.insertBefore(oLinha, oNodeIrmao.nextSibling );

    });
  }

  function js_insereVagasTurmaNormal() {

    js_limparLinhas();

    var iVagasSala      = $F("iVagasSala");
    var sIdLinha        = "";
    var sIdLinha        = "";
    var sTurnoReferente = $F('ed336_turnoreferente').toLowerCase();

    var sVagas             = "vagasTurma";
    var sMatriculados      = "alunosMatriculados";
    var sDisponivel        = "vagasDisponiveis";
    var oLinha             = new Element("tr");
    var oColuna1           = new Element("td");
    var oColuna2           = new Element("td");
    var oLabelVagas        = new Element("label", {"class" : "bold", "for" : sVagas}).update("Vagas Turma: ");
    var oLabelMatriculados = new Element("label", {"class" : "bold", "for" : sMatriculados}).update(" Alunos matriculados: " );
    var oLabelDisponivel   = new Element("label", {"class" : "bold", "for" : sDisponivel}).update(" Vagas disponíveis: " );
    var oInputVagas        = new Element("input", {"type": "text", "name": sVagas, "size":8, "id" : sVagas, "value": iVagasSala,
      "class" : "vagasTurma"});
    var oInputMatriculados = new Element("input", {"type": "text", "disabled":"disabled", "name":sMatriculados, "size":8, "class":"readonly",
      "id":sMatriculados, "value": "0"});
    var oInputDisponivel   = new Element("input", {"type": "text", "disabled":"disabled", "name": sDisponivel, "class":"readonly",
      "size":8, "id": sDisponivel, "value": iVagasSala});

    oInputVagas.onchange = function (){
      js_calclulaVagasNormal();
    };

    oColuna1.appendChild(oLabelVagas);
    oColuna2.appendChild(oInputVagas);
    oColuna2.appendChild(oLabelMatriculados);
    oColuna2.appendChild(oInputMatriculados);
    oColuna2.appendChild(oLabelDisponivel);
    oColuna2.appendChild(oInputDisponivel);
    oLinha.appendChild(oColuna1);
    oLinha.appendChild(oColuna2);

    sIdLinha = "linhaTurno" + sTurnoReferente;
    oLinha.setAttribute("id", sIdLinha);
    aLinhasCriadas.push(oLinha);
    $('linhaFrequencia').parentNode.insertBefore(oLinha, $('linhaFrequencia').nextSibling );
  }

  function js_limparLinhas() {

    aLinhasCriadas.each( function (oElement) {

      var oPai = oElement.parentNode;
      oPai.removeChild(oElement);
    });

    aLinhasCriadas = [];
  }

  /**
   * Replica as vagas da turma para todos os períodos
   * @param  {[type]} sTurnoReferente [description]
   * @return {[type]}                 [description]
   */
  function js_replicaVagas (sTurnoReferente) {

    var iIdVagas         = "vagas" + sTurnoReferente;
    var iValorVagasAtual = 0;

    $$(".vagasTurma").each( function (oElement){

      if (oElement.getAttribute("referente") == sTurnoReferente) {

        js_calclulaVagas(oElement);
        iValorVagasAtual = oElement.value;
      }
    });

    $$(".vagasTurma").each( function (oElement){

      if (oElement.getAttribute("referente") != sTurnoReferente) {

        oElement.value = iValorVagasAtual;
        js_calclulaVagas(oElement);
      }
    });

  }

  /**
   * Calcula as vagas para o periodo informado
   * @param  {[type]} oElement [description]
   * @return {[type]}          [description]
   */
  function js_calclulaVagas(oElement) {

    var sTurnoReferente = oElement.getAttribute("referente");

    var iIdVagas       = "vagas" + sTurnoReferente;
    var iIdMatriculado = "matriculados" + sTurnoReferente;
    var iIdDisponivel  = "disponivel" + sTurnoReferente;

    var iTotalVagas        = new Number($F(iIdVagas));
    var iTotalMatriculados = new Number($F(iIdMatriculado));
    /**
     * Busca o maior número de alunos matriculados em todos os turnos referentes para calcular as vagas disponíveis e
     * substituir o total de vagas por turno referente
     */
    $$(".matriculadosTurno").each( function (oTurnoReferente) {
      iTotalMatriculados = iTotalMatriculados < oTurnoReferente.value ? oTurnoReferente.value : iTotalMatriculados;
    });

    if ( iTotalVagas < iTotalMatriculados ) {

      alert("Número de vagas não pode ser menor que o número de alunos matriculados.");

      $(iIdVagas).value      = iTotalMatriculados;
      $(iIdDisponivel).value = iTotalMatriculados - $F(iIdMatriculado);
    } else {
      $(iIdDisponivel).value = $F(iIdVagas) - $F(iIdMatriculado);
    }

  }

  function js_calclulaVagasNormal() {

    var iIdVagas       = "vagasTurma";
    var iIdMatriculado = "alunosMatriculados";
    var iIdDisponivel  = "vagasDisponiveis";

    var iTotalVagas        = new Number($F(iIdVagas));
    var iTotalMatriculados = new Number($F(iIdMatriculado));

    if ( iTotalVagas < iTotalMatriculados ) {

      alert("Número de vagas não pode ser menor que o número de alunos matriculados.");
      $(iIdVagas).value      = $F(iIdMatriculado);
      $(iIdDisponivel).value = 0;
    } else {
      $(iIdDisponivel).value = $F(iIdVagas) - $F(iIdMatriculado)
    }
  }

  /**
   * Busca as informações referentes as vagas da turma
   */
  function buscaVagasTurma() {

    var oParametro        = new Object();
    oParametro.exec   = 'buscaVagasPorTurno';
    oParametro.iTurma = $F('ed57_i_codigo');

    var oDadosRequisicao              = new Object();
    oDadosRequisicao.method       = 'post';
    oDadosRequisicao.parameters   = 'json=' + Object.toJSON( oParametro );
    oDadosRequisicao.asynchronous = false;
    oDadosRequisicao.onComplete   = retornoBuscaVagasTurma;

    js_divCarregando( "Aguarde, buscando as vagas da turma.", "msgBox" );
    new Ajax.Request( 'edu4_turmas.RPC.php', oDadosRequisicao );
  }

  /**
   * Retorno das informações das vagas
   * @param oResposta
   */
  function retornoBuscaVagasTurma( oResposta ) {

    js_removeObj( "msgBox" );
    var oRetorno = eval( '(' + oResposta.responseText + ')' );

    /**
     * Percorre os registros retornados das vagas por turno
     */
    for( var iTurnoReferente in oRetorno.aVagasTurma ) {

      /**
       * Caso seja uma turma de ensino infantil com turno integral, monta os dados de acordo com o turno referente
       * Senão, seta os valores diretamente nos campos com ID's fixos
       */
      if ( lEnsinoInfantil && lTurnoIntegral ) {

        var oElementoVagas       = $( 'vagas' + oRetorno.aVagasTurma[iTurnoReferente].sTurno.urlDecode() );
        oElementoVagas.value = oRetorno.aVagasTurma[iTurnoReferente].iVagas;

        var oElementoMatriculados       = $( 'matriculados' + oRetorno.aVagasTurma[iTurnoReferente].sTurno.urlDecode() );
        oElementoMatriculados.value = oRetorno.aVagasTurma[iTurnoReferente].iVagasOcupadas;

        var oElementoDisponivel       = $( 'disponivel' + oRetorno.aVagasTurma[iTurnoReferente].sTurno.urlDecode() );
        oElementoDisponivel.value = oRetorno.aVagasTurma[iTurnoReferente].iVagasDisponiveis;

        $('ed246_i_turno').value  = '';
        $('ed15_c_nomeadd').value = '';

      } else {

        $('vagasTurma').value         = oRetorno.aVagasTurma[iTurnoReferente].iVagas;
        $('alunosMatriculados').value = oRetorno.aVagasTurma[iTurnoReferente].iVagasOcupadas;
        $('vagasDisponiveis').value   = oRetorno.aVagasTurma[iTurnoReferente].iVagasDisponiveis;
      }
    }
  }

  function js_verificaTurnoAdicional() {

    if (lTurnoIntegral && lEnsinoInfantil) {
      $('turnoAdicional').style.display  = 'none';
    } else {
      $('turnoAdicional').style.display   = 'table-row';
    }
  }

  /**
   * Verifica se a base curricular possui curso profissionalizante
   */
  function verificaCensoCursoProfissionalizante() {

    var iCensoCursoProfissionalizante = $F('ed10_censocursoprofiss');

    if (iCensoCursoProfissionalizante != '') {

      js_OpenJanelaIframe('','db_iframe_censocursoprofiss','func_censocursoprofiss.php?pesquisa_chave=' + iCensoCursoProfissionalizante +
        '&funcao_js=parent.js_mostracensocursoprofiss1','Pesquisa', false);
    }
  }

</script>