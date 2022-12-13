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

require_once(modification("libs/db_stdlibwebseller.php"));
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));

parse_str( $_SERVER["QUERY_STRING"]);
db_postmemory( $_POST );

$clregencia          = new cl_regencia;
$clregenteconselho   = new cl_regenteconselho;
$clregenciahorario   = new cl_regenciahorario;
$clrechumanohoradisp = new cl_rechumanohoradisp;
$clrechumano         = new cl_rechumano;
$clperiodoescola     = new cl_periodoescola;
$cldiasemana         = new cl_diasemana;
$clcalendario        = new cl_calendario;
$clturmaachorario    = new cl_turmaachorario;
$clrechumanoativ     = new cl_rechumanoativ;
$oDaoTurma           = new cl_turma();
$escola              = db_getsession("DB_coddepto");
$deixamarcar         = true;

$sSqlRecHumano = $clrechumano->sql_query("",
                                         "cgmcgm.z01_numcgm as rechumanocgm,
                                          cgmrh.z01_numcgm as rhpessoalcgm",
                                         "",
                                         " (ed20_i_codigo = $rechumano or ed284_i_rechumano = $rechumano)"
                                        );


$result_cgm = $clrechumano->sql_record( $sSqlRecHumano );
$sTabela    = '';

if ($clrechumano->numrows > 0) {

  db_fieldsmemory( $result_cgm, 0 );
  if (empty($rechumanocgm)) {

    $cgmprof = $rhpessoalcgm;
    $sTabela = 'cgmrh';
  } else {

    $cgmprof = $rechumanocgm;
    $sTabela = 'cgmcgm';
  }
} else {
  $cgmprof = 0;
}
?>
<script>
function ordenarLista(select) {

  arrTextos       = new Array(); // text de cada option
  arrValues       = new Array(); // value de cada option
  arrGuardaTextos = new Array(); // text de cada option de novo
  arrTextos[0]    = arrValues[0] = arrGuardaTextos[0] = "";
  var total       = select.length;

  for (i = 1; i < total; i++) {

    arrTextos[i]       = select.options[i].text;
    arrValues[i]       = select.options[i].value;
    arrGuardaTextos[i] = select.options[i].text;
  }

  arrTextos.sort();
  for (i = 1; i < total; i++) {

    select.options[i].text = arrTextos[i];
    for (j = 1; j < total; j++) {

      if (arrTextos[i] == arrGuardaTextos[j]) {

        select.options[i].value = arrValues[j];
        j = select.length;
      }
    }
  }
}
</script>
<?php
if (isset($chavepesquisa)) {


  $sCampos  = " case when ed20_i_tiposervidor = 1 then cgmrh.z01_numcgm else cgmcgm.z01_numcgm end as cgmprof,";
  $sCampos .= " ed33_i_codigo,ed17_h_inicio as horainicio,ed17_h_fim as horafim,";
  $sCampos .= " ed15_c_nome as descrturno,ed08_c_descr as descrperiodo";
  $sWhere   = " ed33_i_diasemana = {$diasemana} AND ed33_i_periodo = {$periodo}  and ed33_ativo is true";
  $sWhere  .= " AND ed75_i_rechumano = {$rechumano} AND ed17_i_escola = {$escola} and ed33_horaatividade is false";

  $sSqlHorariosDisponiveis = $clrechumanohoradisp->sql_query_disponibilidade("", $sCampos, "", $sWhere);
  $result                  = $clrechumanohoradisp->sql_record( $sSqlHorariosDisponiveis );

  if ($clrechumanohoradisp->numrows == 0) {
    db_msgbox("Regente não tem este horário disponível na escola!\\n(Veja em Cadastros/Recursos Humanos/Aba Horários da Regência)");
  } else {

    $sCamposAtividades = "ed75_c_simultaneo as simultaneo, ed75_i_codigo";
    $sWhereAtividades  = "ed20_i_codigo = {$rechumano} and ed75_c_simultaneo = 'S'";
    $sSqlAtividades    = $clrechumanoativ->sql_query( "", $sCamposAtividades , "", $sWhereAtividades );
 	  $result_atividade  = $clrechumanoativ->sql_record( $sSqlAtividades );

    if ( $clrechumanoativ->numrows != 0 ) {
      db_fieldsmemory($result_atividade,0);
    }

    $sSqlCalendario = $clcalendario->sql_query_file( "", "ed52_i_ano as anocalendario", "", "ed52_i_codigo = {$codcalendario}" );
    $result_anocal  = $clcalendario->sql_record( $sSqlCalendario );

    db_fieldsmemory($result_anocal,0);
    db_fieldsmemory($result,0);

    $restrict  = " AND (((ed17_h_inicio > '{$horainicio}' AND ed17_h_inicio < '{$horafim}') ";
    $restrict .= "         OR (ed17_h_fim  > '{$horainicio}' AND ed17_h_fim < '{$horafim}'))  ";
    $restrict .= "        OR (ed17_h_inicio <= '{$horainicio}' AND ed17_h_fim >= '{$horafim}') ";
    $restrict .= "        OR (ed17_h_inicio >= '{$horainicio}' AND ed17_h_fim <= '{$horafim}') ";
    $restrict .= "        OR (ed17_h_inicio = '{$horainicio}' AND ed17_h_fim = '{$horafim}'))";

    if ($maisturmas != "") {

      $oDaoRegencia   = new cl_regencia();
      $sWhereRegencia = "ed59_i_turma in({$maisturmas}) and ( ed58_i_rechumano = {$rechumano} )";
      $sSqlRegencia   = $oDaoRegencia->sql_query_regenciahorario( null, 'distinct ed59_i_turma', null, $sWhereRegencia );
      $rsRegencia     = db_query( $sSqlRegencia );
      $iTotalRegencia = pg_num_rows( $rsRegencia );

      $aTurmas = array();
      for( $iContador = 0; $iContador < $iTotalRegencia; $iContador++ ) {
        $aTurmas[] = db_utils::fieldsMemory( $rsRegencia, $iContador )->ed59_i_turma;
      }

      if ( count($aTurmas) > 0 ) {
        $condicao = " AND ed59_i_turma in(" . implode( ", ", $aTurmas ) . ")";
      }
//      $condicao = " AND ed59_i_turma not in ({$maisturmas})";
    } else {
      $condicao = "";
    }

    $sCampos     = "ed08_c_descr, ed17_h_inicio, ed17_h_fim, ed17_i_escola, ed18_c_nome as escolas";
    $sCampos    .= ", ed20_i_codigo as codmatricula, ed18_c_nome, {$sTabela}.z01_nome as professor";
    $sCampos    .= ", ed57_c_descr as tiurma, ed15_c_nome as tiurno, ed52_c_descr as calendariu";
    $sWhere      = " ed58_i_diasemana = {$diasemana} AND {$sTabela}.z01_numcgm = {$cgmprof} ";
    $sWhere     .= " and ed58_ativo is true and ed59_c_encerrada = 'N' AND ed52_i_ano = {$anocalendario} {$restrict} {$condicao}";

    $sSqlRegenciaHorario = $clregenciahorario->sql_query( "", $sCampos, "", $sWhere );
    $result              = $clregenciahorario->sql_record( $sSqlRegenciaHorario );

    $sCampos       = "ed08_c_descr, ed17_h_inicio, ed17_h_fim, ed17_i_escola, ed18_c_nome as escolas";
    $sCampos      .= ", ed20_i_codigo as codmatricula";
    $sCampos      .= ", escola.ed18_c_nome, ed17_i_turno as tiurno, ed268_c_descr as tiurma";
    $sCampos      .= ", ed270_i_rechumano, ed52_c_descr as calendariu";
    $sWhere        = "ed270_i_diasemana = {$diasemana} AND {$sTabela}.z01_numcgm = {$cgmprof} ";
    $sWhere       .= "AND ed52_i_ano = {$anocalendario} {$restrict}";
    $sSqlTurmaAC   = $clturmaachorario->sql_query( "", $sCampos, "", $sWhere );
    $result_sala   = db_query( $sSqlTurmaAC );
    $iTotalTurmaAC = pg_num_rows( $result_sala );

    $aTurmasSimultaneas = array();
    $lTemConflito       = false;
    $deixamarcar        = true;
    $msg_erro           = "{$descrturno} {$descrperiodo} Período ({$horainicio} às {$horafim}) está em conflito";
    $msg_erro          .= " com o(s) período(s):\\n\\n";
    $msgsimultaneo      = '';

    if( $clregenciahorario->numrows > 0 ) {

      $lTemConflito  = true;
      $deixamarcar   = $simultaneo == 'S';
      $msg_erro     .= '* TURMA(S)\\n';

      for ($q = 0; $q < $clregenciahorario->numrows; $q++) {

        db_fieldsmemory( $result, $q );
        $msg_erro .= "   -> {$ed08_c_descr} ({$ed17_h_inicio} às {$ed17_h_fim}) já marcado na turma {$tiurma},";
        $msg_erro .= " calendário {$calendariu} da Escola {$ed17_i_escola} - {$ed18_c_nome} (Matrícula: {$codmatricula})\\n";
      }
    }

    if( $iTotalTurmaAC > 0 ) {

      $lTemConflito  = true;
      $msg_erro     .= '* ATIVIDADE COMPLEMENTAR / AEE\\n';

      for ($u = 0; $u < $iTotalTurmaAC; $u++) {

        db_fieldsmemory( $result_sala, $u );
        $msg_erro .= "   -> {$ed08_c_descr} ({$ed17_h_inicio} às {$ed17_h_fim}) já marcado na turma {$tiurma},";
        $msg_erro .= " calendário {$calendariu} da Escola {$ed17_i_escola} - {$ed18_c_nome} (Matrícula: {$codmatricula})\\n";
      }
    }

    if( $simultaneo == 'S' ) {

      $msg_erro .= '\\nCertifique-se que a disciplina é ministrada por este regente no mesmo horário para ambas';
      $msg_erro .= ' as turmas e confirme.\\n';
    }

    if( $lTemConflito ) {
      db_msgbox($msg_erro);
    }

    if( $deixamarcar == true ) {

      $sSqlRegencia = $clregencia->sql_query( "", "ed59_i_qtdperiodo,ed232_c_descr", "", "ed59_i_codigo = $chavepesquisa" );
      $result       = $clregencia->sql_record( $sSqlRegencia );
      $qtdperiodo   = pg_result( $result, 0, 0 );
      $descr        = trim( pg_result( $result, 0, 1 ) );
    ?>
      <script>
       contador = 0;
       for (i = 0; i < parent.document.getElementById("contp").value; i++) {

         for (x = 0; x < parent.document.getElementById("contd").value; x++) {

           val    = parent.document.getElementById("valorQ"+i+x).value;
           separa = val.split("|");
           if (separa[0] == parent.document.form1.ed58_i_regencia.value) {
             contador++;
           }
         }
       }

       if (contador >= <?=$qtdperiodo?>) {
         alert("Disciplina <?=$descr?> tem somente <?=$qtdperiodo?> período(s) por semana!");
       } else {

         parent.document.getElementById("text<?=$quadro?>").value      = parent.document.form1.ed232_c_abrev.value;
         parent.document.getElementById("valor<?=$quadro?>").value     = "<?=$chavepesquisa."|".$diasemana."|".$periodo."|".$rechumano?>";
         parent.document.getElementById("disc<?=$quadro?>").innerHTML  = parent.document.form1.ed232_c_descr.value;
         parent.document.getElementById("rh<?=$quadro?>").innerHTML    = "<font color='#FF0000'>"+parent.document.form1.z01_nome.value+"</font>";
         parent.document.getElementById("codrh<?=$quadro?>").innerHTML = <?=$rechumano?>;
         jatem                                                         = false;
         tam                                                           = parent.document.form1.conselheiro.length;

         for (i = 0; i < tam; i++) {

           if (parent.document.form1.conselheiro.options[i].value == <?=$rechumano?>) {
             jatem = true;
           }
         }

         if (jatem == false) {
           parent.document.form1.conselheiro.options[parent.document.form1.conselheiro.length] = new Option(parent.document.form1.z01_nome.value,parent.document.form1.ed58_i_rechumano.value);
         }

         ordenarLista(parent.document.form1.conselheiro);
         parent.document.form1.conselheiro.value = parent.document.form1.cons_selected.value;
         parent.document.getElementById("text<?=$quadro?>").style.background = "#CCCCCC";
       }
      </script>
     <?php
    }
  }
}

if (isset($disponibilidade)) {

  if (isset($excluir) && $excluir != "") {

    $clregenciahorario->ed58_ativo    = 'false';
    $clregenciahorario->ed58_i_codigo = $excluir;
    $clregenciahorario->alterar($excluir);
  }

  $sSqlCalendario = $clcalendario->sql_query_file( "", "ed52_i_ano as anocalendario", "", "ed52_i_codigo = {$codcalendario}" );
  $result_anocal  = $clcalendario->sql_record( $sSqlCalendario );

  db_fieldsmemory( $result_anocal, 0 );

  $sCampos = "ed17_i_codigo, ed17_h_inicio as horainicio, ed17_h_fim as horafim";
  $sOrder  = "ed15_i_sequencia, ed08_i_sequencia";
  $sWhere  = " ed17_i_escola = {$escola} AND ed17_i_turno in ({$ed57_i_turno})";

  $sSqlPeriodoEscola = $clperiodoescola->sql_query( "", $sCampos, $sOrder, $sWhere );
  $result1           = $clperiodoescola->sql_record( $sSqlPeriodoEscola );

  $sWhereDiaSemana = " ed04_c_letivo = 'S' AND ed04_i_escola = {$escola}";
  $sSqlDiaSemana   = $cldiasemana->sql_query_rh( "", "ed32_i_codigo", "ed32_i_codigo", $sWhereDiaSemana );

  $result          = $cldiasemana->sql_record( $sSqlDiaSemana );

  for ($x = 0; $x < $cldiasemana->numrows; $x++) {

    db_fieldsmemory( $result, $x );

    for( $z = 0;$z < $clperiodoescola->numrows; $z++ ) {

      db_fieldsmemory( $result1, $z );

      $quadro  = "Q".$z.$x;
      $sWhere  = " ed33_i_diasemana = {$ed32_i_codigo} AND ed33_i_periodo = {$ed17_i_codigo} and ed33_horaatividade is false ";
      $sWhere .= " AND ed75_i_rechumano = {$rechumano} AND ed17_i_escola = {$escola} AND ed33_ativo is true";

      $sSqlRecHumanoHoraDisp = $clrechumanohoradisp->sql_query( "", "ed33_i_codigo", "", $sWhere );
      $result2               = $clrechumanohoradisp->sql_record( $sSqlRecHumanoHoraDisp );

      $restrict  = " AND ( ";
      $restrict .= "   ( (ed17_h_inicio > '{$horainicio}' AND ed17_h_inicio < '{$horafim}') ";
      $restrict .= "     OR (ed17_h_fim  > '{$horainicio}' AND ed17_h_fim < '{$horafim}') ";
      $restrict .= "   ) ";
      $restrict .= "   OR (ed17_h_inicio <= '{$horainicio}' AND ed17_h_fim >= '{$horafim}') ";
      $restrict .= "   OR (ed17_h_inicio >= '{$horainicio}' AND ed17_h_fim <= '{$horafim}') ";
      $restrict .= "   OR (ed17_h_inicio = '{$horainicio}' AND ed17_h_fim = '{$horafim}') ";
      $restrict .= "  )";

      $sWhere    = " ed58_i_diasemana = {$ed32_i_codigo} AND ed58_i_rechumano = {$rechumano} and ed58_ativo is true";
      $sWhere   .= " AND ed59_c_encerrada = 'N' AND ed52_i_ano = {$anocalendario} {$restrict} ";

      $sCamposRegenciaHorario = "ed58_i_codigo, ed57_c_descr as nometurma, ed232_c_abrev as abrevdisc";
      $sSqlRegenciaHorario    = $clregenciahorario->sql_query( "", $sCamposRegenciaHorario, "", $sWhere );
      $result3                = $clregenciahorario->sql_record( $sSqlRegenciaHorario );

      $sCampos  = " ed08_c_descr, ed17_h_inicio, ed17_h_fim, ed17_i_escola, ed20_i_codigo as codmatricula";
      $sCampos .= ", escola.ed18_c_nome, ed57_c_descr as tiurma, ed57_c_descr as nometurma, ";
      $sCampos .= " '' as abrevdisc, ed17_i_turno as tiurno, ed20_i_codigo, ed52_c_descr as calendariu";
      $sWhere   = " ed58_i_diasemana = {$ed32_i_codigo} AND ed20_i_codigo = {$rechumano} and ed58_ativo is true ";
      $sWhere  .= " AND ed52_i_ano = {$anocalendario} {$restrict}";
      $sWhere  .= " and ed18_i_codigo =  {$escola} ";
      $sSqlTurma   = $oDaoTurma->sql_query_rechumano_hora_disponivel( "", $sCampos, "", $sWhere );
      $result_sala = db_query( $sSqlTurma );

      $iTotalTurma = pg_num_rows( $result_sala );

      $sWhereRecHumanoAtiv = "ed20_i_codigo = {$rechumano} and ed75_i_escola = {$escola} AND ed75_i_saidaescola is null";
      $sSqlRecHumanoAtiv   = $clrechumanoativ->sql_query( "", "ed75_c_simultaneo", "", $sWhereRecHumanoAtiv );
      $result_atividade    = $clrechumanoativ->sql_record( $sSqlRecHumanoAtiv );

      if ($clrechumanoativ->numrows > 0) {
        db_fieldsmemory( $result_atividade, 0 );
      } else {
        $ed75_c_simultaneo = 'N';
      }

      if ($clrechumanohoradisp->numrows == 0) {

        ?>
        <script>
        if (parent.document.getElementById("text<?=$quadro?>").value == "") {

          <?
             if ($rechumano != 0) {?>

               parent.document.getElementById("text<?=$quadro?>").style.background = "#FF9900"; //laranja
               parent.document.getElementById("disc<?=$quadro?>").innerHTML        = '';
               parent.document.getElementById("rh<?=$quadro?>").innerHTML          = 'HORÁRIO NÃO DISPONÍVEL NESTA ESCOLA';
           <?} else {?>

               parent.document.getElementById("text<?=$quadro?>").style.background = "#CCCCCC"; //cinza (NENHUM REGENTE SELECIONADO)
               parent.document.getElementById("disc<?=$quadro?>").innerHTML        = '';
               parent.document.getElementById("rh<?=$quadro?>").innerHTML          = '';
           <?}?>

        } else {
          parent.document.getElementById("text<?=$quadro?>").style.background = "#CCCCCC"; // Cinza
        }
        </script>
    <?} else {?>
        <script>
         if (parent.document.getElementById("text<?=$quadro?>").value == "") {

           <?
           if ($clregenciahorario->numrows > 0 && $iTotalTurma > 0) { // Ele já tem horário marcado, mas em outra turma, pois o quadro está em branco

             if ($clregenciahorario->numrows > 0) {
               db_fieldsmemory( $result3, 0 );
             } else {
               db_fieldsmemory( $result_sala, 0 );
             }

             $sSimult = $nometurma;
             if (!empty($abrevdisc)) {
               $sSimult .= ' / '.$abrevdisc;
             }

             if ($ed75_c_simultaneo == 'S') {

              echo 'parent.document.getElementById("text'.$quadro.'").style.background = "#6495ed";'; // Azul (pode marcar simultâneo)
              echo "parent.document.getElementById('disc$quadro').innerHTML            = '';";
              echo "parent.document.getElementById('rh$quadro').innerHTML              = 'MARCAR EM SIMULTÂNEO ($sSimult)';";
             } else { // Vermelho (não pode marcar simultâneo)

              echo 'parent.document.getElementById("text'.$quadro.'").style.background = "#FF0000";'; // Vermelho
              echo "parent.document.getElementById('disc$quadro').innerHTML            = '';";
              echo "parent.document.getElementById('rh$quadro').innerHTML              = 'REGENTE OCUPADO ($sSimult)';";
             }
           } else {

             if ($ed75_c_simultaneo == 'S') {

               echo 'parent.document.getElementById("text'.$quadro.'").style.background = "#6495ed";'; // Azul (pode marcar simultâneo)
               echo "parent.document.getElementById('disc$quadro').innerHTML            = '';";
               echo "parent.document.getElementById('rh$quadro').innerHTML              = 'HORÁRIO LIVRE';";
             } else { // Verdinho (não pode marcar simultâneo)

               echo 'parent.document.getElementById("text'.$quadro.'").style.background = "#CCFFCC";'; // Verde
               echo "parent.document.getElementById('disc$quadro').innerHTML            = '';";
               echo "parent.document.getElementById('rh$quadro').innerHTML              = 'HORÁRIO LIVRE';";
             }
           }
           ?>
         } else {
           parent.document.getElementById("text<?=$quadro?>").style.background = "#CCCCCC"; // Cinza
         }
        </script>
    <?}
    }
  }
}
?>