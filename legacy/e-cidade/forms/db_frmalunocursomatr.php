<?
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
$clrotulo = new rotulocampo;
$clrotulo->label("ed56_i_aluno");
$clrotulo->label("ed56_i_escola");
$clrotulo->label("ed60_i_turma");
$clrotulo->label("ed31_i_curso");
$clrotulo->label("ed57_i_base");
$clrotulo->label("ed57_i_calendario");
$clrotulo->label("ed223_i_serie");
$clrotulo->label("ed57_i_turno");
$clrotulo->label("ed60_d_datamatricula");

$clrotulo->label("ed334_tipo");

$ed60_d_datamatricula_dia = date("d",db_getsession("DB_datausu"));
$ed60_d_datamatricula_mes = date("m",db_getsession("DB_datausu"));
$ed60_d_datamatricula_ano = date("Y",db_getsession("DB_datausu"));
$ed60_d_datamatricula = $ed60_d_datamatricula_dia."/".$ed60_d_datamatricula_mes."/".$ed60_d_datamatricula_ano;
?>

<form name="form2" method="post" action="">
    <table class="form-container" id="alunomatricula" style="display: none;">
      <tr>
        <td nowrap title="<?=@$Ted56_i_escola?>" width="15%">
          <?db_ancora(@$Led56_i_escola, "", 3);?>
        </td>
        <td>
          <?db_input('ed56_i_escola', 15, $Ied56_i_escola, true, 'text', 3, "")?>
          <?db_input('ed18_c_nome', 50, @$Ied18_c_nome, true, 'text', 3, '')?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Ted56_i_aluno?>">
          <?db_ancora(@$Led56_i_aluno, "", 3);?>
        </td>
        <td>
          <?db_input('ed56_i_aluno', 15, $Ied56_i_aluno, true, 'text', 3, "")?>
          <?db_input('ed47_v_nome', 50, @$Ied47_v_nome, true, 'text', 3, '')?>
        </td>
      </tr>
      <tr id ='linhaTurma'>
        <td nowrap title="<?=@$Ted60_i_turma?>">
          <?db_ancora(@$Led60_i_turma, "js_pesquisaed60_i_turma();", $db_opcao);?>
        </td>
        <td>
          <?db_input('ed60_i_turma', 15, $Ied60_i_turma, true, 'text', 3, '')?>
          <?db_input('ed57_c_descr', 20, @$Ied57_c_descr, true, 'text', 3, '')?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Ted31_i_curso?>">
          <?=@$Led31_i_curso?>
        </td>
        <td>
          <?db_input('ed31_i_curso', 15, @$Ied31_i_curso, true, 'text', 3, '')?>
          <?db_input('ed29_c_descr', 40, @$Ied29_c_descr, true, 'text', 3, '')?>
        </td>
      </tr>
      <tr>
        <td>
          <?=@$Led57_i_base?>
        </td>
        <td>
          <?db_input('ed57_i_base', 15, @$Ied57_i_base, true, 'text', 3, '')?>
          <?db_input('ed31_c_descr', 40, @$Ied31_c_descr, true, 'text', 3, '')?>
        </td>
      </tr>
      <tr>
        <td>
          <?=@$Led57_i_calendario?>
        </td>
        <td>
          <?db_input('ed57_i_calendario', 15, @$Ied57_i_calendario, true, 'text', 3, '')?>
          <?db_input('ed52_c_descr', 40, @$Ied52_c_descr, true, 'text', 3, '')?>
          <?db_input('ed52_i_ano', 5, @$Ied52_i_ano, true, 'text', 3, '')?>
          <?db_input('ed52_d_inicio', 10, @$Ied52_d_inicio, true, 'hidden', 3, '')?>
          <?db_input('ed52_d_fim', 10, @$Ied52_d_fim, true, 'hidden', 3, '')?>
        </td>
      </tr>
      <tr>
        <td>
          <?=@$Led223_i_serie?>
        </td>
        <td>
          <?
            if (isset($ed60_i_turma)) {

              $sSqlPossib    = $oDaoAlunoPossib->sql_query("", "ed79_i_serie as seriepossib", "", " ed56_i_aluno = $ed56_i_aluno");
              $rsPossib      = $oDaoAlunoPossib->sql_record($sSqlPossib);
              if ($oDaoAlunoPossib->numrows > 0) {

                $oDadosPossib  = db_utils::fieldsmemory($rsPossib, 0);
                $iSeriePossib  = $oDadosPossib->seriepossib;
              }

              $sCamposEtapa  = " ed223_i_serie,ed11_c_descr as descretapa ";
              $sOrderByEtapa = " ed223_i_ordenacao ";
              $sWhereEtapa   = " ed220_i_turma = $ed60_i_turma ";
              $sSqlEtapa     = $oDaoTurmaSerieRegimeMat->sql_query("", $sCamposEtapa, $sOrderByEtapa, $sWhereEtapa);
              $rsEtapa       = $oDaoTurmaSerieRegimeMat->sql_record($sSqlEtapa);
              $iLinhas       = $oDaoTurmaSerieRegimeMat->numrows;

              if ($oDaoTurmaSerieRegimeMat->numrows > 1) {

                ?>
                <select name="codetapaturma" id="codetapaturma" onchange="parent.formaba.codigo_etapa_multi.value=this.value">
                  <option value=""></option>
                  <?php
                  $oSituacaoAluno = new SituacaoAluno( AlunoRepository::getAlunoByCodigo( $ed56_i_aluno ) );

                  /* Obtenho as todas as etapas equivalentes a etapa em que o aluno estava matriculado */
                  if ($oDaoAlunoPossib->numrows > 0) {
                    $rsEquiv = $oDaoSerieEquiv->sql_record($oDaoSerieEquiv->sql_query("","ed234_i_serieequiv",""," ed234_i_serie = $iSeriePossib"));
                  }

                  // Percorro as etapas da turma e verifico se alguma delas equivale a etapa da turma de origem
                  for ($iCont = 0; $iCont < $iLinhas; $iCont++) {

                    $oDadosEtapa = db_utils::fieldsmemory($rsEtapa, $iCont);
                    $selected    = "";
                    if (!empty($codigo_etapa_multi) && $codigo_etapa_multi == $oDadosEtapa->ed223_i_serie) {
                      $selected    = "selected";
                    } else if (empty($codigo_etapa_multi)) {
                      $selected    = "selected";
                    }
                    $disabled    = "";

                    if(    $oSituacaoAluno->getCodigoAlunoCurso() != ""
                        && $oSituacaoAluno->getSitucaoAlunoCurso() != "MATRICULADO"
                        && $oSituacaoAluno->getEtapa()->getCodigo() != $oDadosEtapa->ed223_i_serie
                        && $ed233_c_consistirmat == 'S'
                      ) {

                      $selected = "";
                      $disabled = "disabled";
                    }

                    ?>
                    <option value="<?=$oDadosEtapa->ed223_i_serie?>" <?=$selected?> <?=$disabled?>><?=$oDadosEtapa->descretapa?></option>
                    <?

                  }
                  ?>
                </select>
                <?db_input('ed11_c_descr', 40, @$Ied11_c_descr, true, 'text', 3, '')?>
                <?db_input('ed11_i_sequencia', 10, @$Ied11_i_sequencia, true, 'hidden', 3, '')?>
              <?
              } else {
              ?>
                <?db_input('codetapaturma', 15, @$Icodetapaturma, true, 'text', 3, '')?>
                <?db_input('ed11_c_descr', 40, @$Ied11_c_descr, true, 'text', 3, '')?>
                <?db_input('ed11_i_sequencia', 10, @$Ied11_i_sequencia, true, 'hidden', 3, '')?>
              <?
              }

            } else {

              db_input('codetapaturma', 15, @$Icodetapaturma, true, 'text', 3, '');
              db_input('ed11_c_descr', 40, @$Ied11_c_descr, true, 'text', 3, '');
              db_input('ed11_i_sequencia', 10, @$Ied11_i_sequencia, true, 'hidden', 3, '');
            }
          ?>
        </td>
      </tr>
      <tr>
        <td>
          <label class="bold">Tipo de Ingresso:</label>
        </td>
        <td>
          <?php
            $aTipoIngresso = array( 1 => "Normal", 2 => "Classificado", 3 => "Reclassificado", 4 => "Avanço" );
            db_select( 'ed334_tipo', $aTipoIngresso, true, 1, "class = 'field-size3';");
          ?>
        </td>
      </tr>
      <tr id="linhaTurnoTurma">
        <td>
          <?=@$Led57_i_turno?>
        </td>
        <td>
          <?db_input('ed57_i_turno', 15, @$Ied57_i_turno, true, 'text', 3, '')?>
          <?db_input('ed15_c_nome', 20, @$Ied15_c_nome, true, 'text', 3, '')?>
        </td>
      </tr>
      <tr id='linhaDataMatricula'>
        <td nowrap title="<?=@$Ted60_d_datamatricula?>">
          <?=@$Led60_d_datamatricula?>
        </td>
        <td>
          <?db_inputdata('ed60_d_datamatricula', @$ed60_d_datamatricula_dia, @$ed60_d_datamatricula_mes,
                         @$ed60_d_datamatricula_ano, true, 'text', $db_opcao," onchange=\"js_data();\"",
                         "", "", "parent.js_data();", "js_data();")?>
        </td>
      </tr>
        <?
          if (isset($ed60_i_turma)) {

            $sCampos  = "ed18_c_nome as ed18_c_nomeorigem, matricula.ed60_i_codigo as matricula, ";
            $sCampos .= "turma.ed57_i_codigo as turmaorigem, turma.ed57_c_descr as ed57_c_descrorigem, ";
            $sCampos .= "fc_nomeetapaturma(ed57_i_codigo) as ed11_c_descrorigem ";
            $sql_imp = "SELECT $sCampos FROM transfescolafora
                          inner join escola  on  escola.ed18_i_codigo = transfescolafora.ed104_i_escolaorigem
                          inner join aluno  on  aluno.ed47_i_codigo = transfescolafora.ed104_i_aluno
                          inner join escolaproc  on  escolaproc.ed82_i_codigo = transfescolafora.ed104_i_escoladestino
                          inner join matricula on matricula.ed60_i_codigo = transfescolafora.ed104_i_matricula
                          inner join turma on turma.ed57_i_codigo = matricula.ed60_i_turma
                          inner join calendario on calendario.ed52_i_codigo = turma.ed57_i_calendario
                        WHERE matricula.ed60_c_situacao = 'TRANSFERIDO FORA'
                          AND ed104_i_aluno = $ed56_i_aluno
                          AND ed52_i_ano = $ed52_i_ano
                        ORDER BY ed104_d_data DESC
                        LIMIT 1";
            $result_imp = db_query($sql_imp);
            $linhas_imp = pg_num_rows($result_imp);

            if ($linhas_imp > 0) {
        ?>
      <tr>
        <td colspan="2">
          Este aluno foi transferido para fora da Rede Municipal neste ano.<br>
          Caso queira importar o aproveitamento deste aluno na turma abaixo relacionada, informe no campo abaixo:
        </td>
      </tr>
      <tr>
        <td>
          <b>Importar aproveitamento:</b>
        </td>
        <td>
          <select name="importaaprov">
            <option value="S" selected>SIM</option>
            <option value="N">NÃO</option>
          </select>
        </td>
      </tr>
        <?
          for ($iCont = 0; $iCont < $linhas_imp; $iCont++) {

            db_fieldsmemory($result_imp, $iCont);
            $checked = $iCont == 0 ? "checked" : "";
        ?>
      <tr>
        <td style="text-decoration:underline;" onmouseover="document.getElementById('aprov<?=$turmaorigem?>').style.visibility = 'visible'"
            onmouseout="document.getElementById('aprov<?=$turmaorigem?>').style.visibility = 'hidden'">
          <?db_input('turmaorigem', 15, @$Iturmaorigem, true, 'radio', 3, $checked)?>
          Turma Anterior:
        </td>
        <td>
          <?db_input('ed57_c_descrorigem', 10, @$Ied57_c_descrorigem, true, 'text', 3, '')?>
          <?db_input('ed11_c_descrorigem', 20, @$Ied11_c_descrorigem, true, 'text', 3, '')?>
          <?db_input('ed18_c_nomeorigem', 50, @$Ied18_c_nomeorigem, true, 'text', 3, '')?>
          Matrícula:
          <?db_input('matricula', 10, @$Imatricula, true, 'text', 3, '')?><br>
          <table border="1" cellspacing="0" cellpadding="0" id="aprov<?=$turmaorigem?>"
                 style="position:absolute;visibility:hidden;">
            <?
              $veraprovnulo  = "";
              $primeira      = "";
              $sCampos       = " ed59_i_codigo as regenciaorigem,ed232_c_descr,ed232_c_abrev,ed09_c_abrev, ";
              $sCampos      .= " ed72_i_valornota,ed72_c_valorconceito,ed72_t_parecer,d37_c_tipo,ed59_i_ordenacao ";
              $sOrderBy      = " ed59_i_ordenacao,ed41_i_sequencia ASC ";
              $sWhere        = " ed95_i_aluno = $ed56_i_aluno AND ed59_i_turma = $turmaorigem AND ed09_c_somach = 'S'";
              $sSqlDiario    = $oDaoDiarioAvaliacao->sql_query("", $sCampos, $sOrderBy, $sWhere);
              $rsDiario = $oDaoDiarioAvaliacao->sql_record($sSqlDiario);

              if ($oDaoDiarioAvaliacao->numrows == 0) {

                echo "<tr><td width='160px' style='background:#f3f3f3;'>Nenhum registro de aproveitamento.</td></tr>";

              } else {

                for($iCont = 0; $iCont < $oDaoDiarioAvaliacao->numrows; $iCont++) {

                  $oDados = db_utils::fieldsmemory($rsDiario, $iCont);
                  if ($primeira != $oDados->regenciaorigem) {

                    echo "</tr><tr><td style='background:#444444;color:#DEB887'><b>$oDados->ed232_c_descr</b></td>";
                    $primeira = $oDados->regenciaorigem;

                  }

                  if(trim($ed37_c_tipo) == "NOTA") {

                    if($resultedu == 'S') {
                      $aproveitamento = $oDados->ed72_i_valornota != "" ?
                                        number_format($oDados->ed72_i_valornota, 2, ",", ".") : "";
                    } else {
                      $aproveitamento = $oDados->ed72_i_valornota != "" ?
                                        number_format($oDados->ed72_i_valornota, 0) : "";
                    }

                  } elseif (trim($ed37_c_tipo) == "NIVEL") {
                    $aproveitamento = $oDados->ed72_c_valorconceito;
                  } else {
                    $aproveitamento = "";
                  }

                  $veraprovnulo .= $aproveitamento;
                  echo "<td style='background:#f3f3f3;'><b>$$oDados->ed09_c_abrev:</b></td>
                        <td width='50px' style='background:#f3f3f3;' align='center'>".
                        ($aproveitamento == "" ? "&nbsp;" : $aproveitamento)."</td>";
                }
              }
            ?>
            </tr>
          </table>
        </td>
      </tr>
      <?
     }
   }
 }
 ?>
      <tr>
         <td colspan="2" align="center">
         <center>
         <input id='incluirMatricula' name="incluirmatricula" type="submit" value="Incluir" disabled
                onclick="return js_validaturma();">
          </center>
        </td>
      </tr>
    </table>
  </form>
<script>

var oTurmaTurno;
var aNomeTurnoReferencia = {1:'Manhã', 2:'Tarde', 3:'Noite'};

function js_pesquisaed60_i_turma() {

  js_OpenJanelaIframe('', 'db_iframe_turma', 'func_alunocursoturma.php?aluno='+document.form2.ed56_i_aluno.value+
                      '&funcao_js=parent.js_mostraturma1|ed57_i_codigo|ed57_c_descr|ed11_c_descr|ed52_c_descr|'+
                      'ed29_c_descr|ed31_c_descr|ed15_c_nome|ed11_i_codigo|ed52_i_codigo|ed29_i_codigo|'+
                      'ed31_i_codigo|ed15_i_codigo|ed11_i_sequencia|ed52_i_ano|'+
                      'ed52_d_inicio|ed52_d_fim&lEliminarSeriesAnteriores=true&turmasprogressao=f',
                       'Pesquisa de Turmas', true, 0, 0
                     );

}

function js_mostraturma1(chave1,chave2,chave3,chave4,chave5,chave6,chave7,chave8,chave9,chave10,
                         chave11,chave12,chave13,chave14,chave15,chave16) {

  document.form2.ed60_i_turma.value      = chave1;
  document.form2.ed57_c_descr.value      = chave2;
  document.form2.ed11_c_descr.value      = chave3;
  document.form2.ed52_c_descr.value      = chave4;
  document.form2.ed29_c_descr.value      = chave5;
  document.form2.ed31_c_descr.value      = chave6;
  document.form2.ed15_c_nome.value       = chave7;
  document.form2.codetapaturma.value     = chave8;
  document.form2.ed57_i_calendario.value = chave9;
  document.form2.ed31_i_curso.value      = chave10;
  document.form2.ed57_i_base.value       = chave11;
  document.form2.ed57_i_turno.value      = chave12;
  document.form2.ed11_i_sequencia.value  = chave13;
  document.form2.ed52_i_ano.value        = chave14;
  document.form2.ed52_d_inicio.value     = chave15;
  document.form2.ed52_d_fim.value        = chave16;

  parent.document.formaba.int_ed57_i_codigo.value         = chave1;
  parent.document.formaba.date_ed60_d_datamatricula.value = document.form2.ed60_d_datamatricula.value;
  parent.document.formaba.ed52_i_ano.value                = chave14;
  db_iframe_turma.hide();

  if ( oTurmaTurno instanceof DBViewFormularioEducacao.TurmaTurnoReferente) {
    oTurmaTurno.limpaLinhasCriadas();
  }

  js_mostraTurnosTurma();

  document.form2.submit();
}

function js_mostraTurnosTurma() {

  oTurmaTurno = new DBViewFormularioEducacao.TurmaTurnoReferente($('linhaTurnoTurma'), $F('ed60_i_turma'));
  oTurmaTurno.show();
  $('incluirMatricula').removeAttribute("disabled");
}

function js_mostraturma2(chave1,chave2,chave3,chave4,chave5,chave6,chave7,chave8,chave9,chave10,
                         chave11,chave12,chave13,chave14,chave15,chave16) {

  document.form2.ed60_i_turma.value      = chave1;
  document.form2.ed57_c_descr.value      = chave2;
  document.form2.ed11_c_descr.value      = chave3;
  document.form2.ed52_c_descr.value      = chave4;
  document.form2.ed29_c_descr.value      = chave5;
  document.form2.ed31_c_descr.value      = chave6;
  document.form2.ed15_c_nome.value       = chave7;
  document.form2.codetapaturma.value     = chave8;
  document.form2.ed57_i_calendario.value = chave9;
  document.form2.ed31_i_curso.value      = chave10;
  document.form2.ed57_i_base.value       = chave11;
  document.form2.ed57_i_turno.value      = chave12;
  document.form2.ed11_i_sequencia.value  = chave13;
  document.form2.ed52_i_ano.value        = chave14;
  document.form2.ed52_d_inicio.value     = chave15;
  document.form2.ed52_d_fim.value        = chave16;

  parent.document.formaba.int_ed57_i_codigo.value         = chave1;
  parent.document.formaba.date_ed60_d_datamatricula.value = document.form2.ed60_d_datamatricula.value;
  parent.document.formaba.ed52_i_ano.value                = chave14;

  db_iframe_turma.hide();

  js_mostraTurnosTurma();
}

/**
 * Verifica qual tipo de validação deve ser feita
 */
function js_validaturma() {

  var lValidacoesTurma = false;

  if (oTurmaTurno.lEnsinoInfantil && oTurmaTurno.lTurnoIntegral) {
    lValidacoesTurma = js_validaTurmaInfantilIntegral();
  } else {
    lValidacoesTurma = js_validaTurmaNormal();
  }

  if (lValidacoesTurma) {
    lValidacoesTurma = js_validacoesGerais();
  }

  return lValidacoesTurma;
}

/**
 * Validações aplicadas caso a turma seja de ensino infantil e o turno seja integral
 */
function js_validaTurmaInfantilIntegral() {

  var aTurnoReferencia = new Array();

  // Verifica quais referências estão selecionadas e adicionas elas ao array aTurnoReferencia
  for (var i = 1; i < 4; i++) {

    if ( $("check_turno"+i) && $("check_turno"+i).checked ) {
      aTurnoReferencia.push( $F("check_turno"+i) );
    }
  }

  // Verifica se ao menos 1 checkbox esta selecionado
  if (aTurnoReferencia.length == 0) {

    alert(_M('educacao.escola.db_frmalunocursomatr.selecione_turno'));
    //alert( _M('educacao.escola.db_frmalunocursomatr.selecione_turno') );
    return false;
  }

  // Verifica se existe vagas disponíveis nos turnos referentes
  var lTemVagas = true;
  var sMsg      = "Turma não possui vaga no(s) turno(s):";
  for (var index = 0; index < aTurnoReferencia.length; index++) {

    var aVagasTurno = new Array();
    aVagasTurno     = oTurmaTurno.getVagasDisponiveis(aTurnoReferencia[index]);

    if (aVagasTurno.length == 0) {
      lTemVagas = false;
      sMsg += "\n - " + aNomeTurnoReferencia[aTurnoReferencia[index]];
    }
  }

  if (!lTemVagas) {
    alert(sMsg);
  }
  return lTemVagas;

}

/**
 * Valida vagas quando a turma é diferente de infantil ou seu turno é diferente de integral
 */
function js_validaTurmaNormal() {

  // Verifica se existe vagas disponíveis na turma
  if( !oTurmaTurno.temVagasDisponiveis() ) {
    alert(_M('educacao.escola.db_frmalunocursomatr.turno_sem_vagas'));
    return false;
  }
  return true;
}

/**
 * Validações que abrangem tanto turmas normais quanto as de ensino infantil com turno integral
 */
function js_validacoesGerais() {

  if (document.form2.ed60_d_datamatricula.value == "") {

    alert(_M('educacao.escola.db_frmalunocursomatr.informe_data_matricular'));
    document.form2.ed60_d_datamatricula.focus();
    document.form2.ed60_d_datamatricula.style.backgroundColor='#99A9AE';
    return false;

  } else {

    datamat = document.form2.ed60_d_datamatricula_ano.value+"-"+
              document.form2.ed60_d_datamatricula_mes.value+"-"+
              document.form2.ed60_d_datamatricula_dia.value;
    dataini = document.form2.ed52_d_inicio.value;
    datafim = document.form2.ed52_d_fim.value;
    check = js_validata(datamat, dataini, datafim);

    if (check == false) {

      data_ini = dataini.substr(8,2)+"/"+dataini.substr(5,2)+"/"+dataini.substr(0,4);
      data_fim = datafim.substr(8,2)+"/"+datafim.substr(5,2)+"/"+datafim.substr(0,4);

      alert(_M('educacao.escola.db_frmalunocursomatr.data_matricula_fora_periodo_calendario', {"iDataInicial" : data_ini, "IDataFinal" : data_fim}));

      document.form2.ed60_d_datamatricula.focus();
      document.form2.ed60_d_datamatricula.style.backgroundColor='#99A9AE';

      return false;

    }

  }

  if (document.form2.codetapaturma.value == "") {

    alert(_M('educacao.escola.db_frmalunocursomatr.informe_etapa_origem_aluno'));
    return false;

  }

  return true;

}

function js_data() {

  parent.document.formaba.date_ed60_d_datamatricula.value = document.form2.ed60_d_datamatricula.value;

}

if (parent.document.formaba.int_ed57_i_codigo.value != "") {

  datamarcada = parent.document.formaba.date_ed60_d_datamatricula.value.split("/");

  document.form2.ed60_d_datamatricula_dia.value = datamarcada[0];
  document.form2.ed60_d_datamatricula_mes.value = datamarcada[1];
  document.form2.ed60_d_datamatricula_ano.value = datamarcada[2];
  document.form2.ed60_d_datamatricula.value     = parent.document.formaba.date_ed60_d_datamatricula.value;

  js_OpenJanelaIframe('','db_iframe_turma',
                      'func_alunocursoturma.php?pesquisa_chave='+parent.document.formaba.int_ed57_i_codigo.value+
                      '&aluno='+document.form2.ed56_i_aluno.value+
                      '&funcao_js=parent.js_mostraturma2',
                      'Pesquisa de Turmas',false,0,0);

}

$("codetapaturma").setAttribute( "rel", "ignore-css" );
$("ed56_i_escola").addClassName("field-size2");
$("ed18_c_nome").addClassName("field-size7");
$("ed56_i_aluno").addClassName("field-size2");
$("ed47_v_nome").addClassName("field-size7");
$("ed60_i_turma").addClassName("field-size2");
$("ed57_c_descr").addClassName("field-size7");
$("ed31_i_curso").addClassName("field-size2");
$("ed29_c_descr").addClassName("field-size7");
$("ed57_i_base").addClassName("field-size2");
$("ed31_c_descr").addClassName("field-size7");
$("ed57_i_calendario").addClassName("field-size2");
$("ed31_c_descr").addClassName("field-size7");
$("ed52_i_ano").addClassName("field-size1");
$("codetapaturma").addClassName("field-size2");
$("ed11_c_descr").addClassName("field-size7");
$("ed52_c_descr").addClassName("field-size6");
$("ed57_i_turno").addClassName("field-size2");
$("ed15_c_nome").addClassName("field-size7");
$("ed60_d_datamatricula").addClassName("field-size2");
$("ed334_tipo").setAttribute("rel","ignore-css");
$("ed334_tipo").addClassName("field-size9");

</script>