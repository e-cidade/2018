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

require_once ('libs/db_stdlib.php');
require_once ('libs/db_conecta.php');
require_once ('libs/db_sessoes.php');
require_once ('libs/db_usuariosonline.php');
require_once ('libs/db_utils.php');
require_once ('dbforms/db_funcoes.php');

db_postmemory($HTTP_POST_VARS);

$oDaoRegenciaHorario   = new cl_regenciahorario();
$oDaoTurmaACHorario    = new cl_turmaachorario();
$oDaoRecHumanoHoraDisp = new cl_rechumanohoradisp();
$oDaoRechumano         = new cl_rechumano();
$oDaoPeriodoEscola     = new cl_periodoescola();
$oDaoDiaSemana         = new cl_diasemana();
$oDaoCalendario        = new cl_calendario();
$oDaoRechumanoAtiv     = new cl_rechumanoativ();
$iEscola               = db_getsession('DB_coddepto');

?>
<script>
function js_horario(sQuadro, lRegenteSelecionado, lHorarioDisponivel, lHorarioMarcado, 
                    lAtendSimultaneo, sNomeTurma, sAbrevDisc) {

  if (parent.document.getElementById('text'+sQuadro).value == '' && lRegenteSelecionado) {

    // Se o regente não tem o horário disponível na escola e não está marcado em outra turma
    if (!lHorarioDisponivel && !lHorarioMarcado) { 
  
      parent.document.getElementById('text'+sQuadro).style.background = "#FF9900"; // Laranja
      parent.document.getElementById('rh'+sQuadro).innerHTML          = 'HORÁRIO NÃO DISPONÍVEL NESTA ESCOLA';
    } else if (lHorarioMarcado) { // Já possui horário marcado em outra turma
       
      var sSimult = sNomeTurma;

      if (sAbrevDisc != '') {
        sSimult += ' / '+sAbrevdisc;
      }

      if (lAtendeSimultaneo) {

        parent.document.getElementById('text'+sQuadro).style.background = '#6495ed'; // Azul (pode marcar simultâneo)
        parent.document.getElementById('rh'+sQuadro).innerHTML          = 'MARCAR EM SIMULTÂNEO ('+sSimult+')';
      } else { // Vermelho (não pode marcar simultâneo)

        parent.document.getElementById('text'+sQuadro).style.background = '#FF0000'; // Vermelho              
        parent.document.getElementById('rh$sQuadro').innerHTML          = 'REGENTE OCUPADO ('+sSimult+')';
      }
    } else { // Horário livre

      if (lAtendSimultaneo) {

        parent.document.getElementById('text'+sQuadro).style.background = '#6495ed'; // Azul (pode marcar simultâneo)
        parent.document.getElementById('rh'+sQuadro).innerHTML          = 'HORÁRIO LIVRE';
      } else { // Verde (não pode marcar simultâneo)

        parent.document.getElementById('text'+sQuadro).style.background = '#CCFFCC'; // Verde               
        parent.document.getElementById('rh'+sQuadro).innerHTML          = 'HORÁRIO LIVRE';
      }
    }
  } else {
    parent.document.getElementById('text'+sQuadro).style.background = "#CCCCCC"; // Cinza
  }
}
</script>

<?
if (isset($chavepesquisa)) {
  
  try {

    /* Verifico se o regente possui o horário disponível na escola */
    $sCampos  = 'case when ed20_i_tiposervidor = 1 then cgmrh.z01_numcgm else cgmcgm.z01_numcgm end as cgmprof, ';
    $sCampos .= 'ed33_i_codigo, ed17_h_inicio as horainicio, ed17_h_fim as horafim, ed15_c_nome as descrturno, ';
    $sCampos .= 'ed08_c_descr as descrperiodo';
    $sWhere   = " ed33_i_diasemana = $diasemana and ed33_i_periodo = $periodo ";
    $sWhere  .= " and ed75_i_rechumano = $rechumano and ed17_i_escola = $iEscola and ed33_ativo is true";
    $sSql     = $oDaoRecHumanoHoraDisp->sql_query_disponibilidade(null, $sCampos, '', $sWhere);
    $rs       = $oDaoRecHumanoHoraDisp->sql_record($sSql);
    if ($oDaoRecHumanoHoraDisp->numrows <= 0) {

      $sMensagem  = 'Regente não tem este horário disponível na escola!\n';
      $sMensagem .= '(Veja em Cadastros/Recursos Humanos/Aba Horários da Regência)';
      throw new Exception( $sMensagem );
    }

    $oDadosPeriodo = db_utils::fieldsmemory( $rs, 0 );
  
    /* Obtenho o CGM do regente */
    $sSql = $oDaoRechumano->sql_query_rechumano(
                                                 null,
                                                 'reccgm.z01_numcgm as cgmprof',
                                                 '',
                                                 "ed20_i_codigo = {$rechumano}"
                                               );
    $rs   = $oDaoRechumano->sql_record($sSql);

    if ($oDaoRechumano->numrows > 0) {
      $iNumCgmRecHumano = db_utils::fieldsmemory($rs, 0)->cgmprof;
    } else {
      $iNumCgmRecHumano = 0;
    }
  
    /* Obtenho o ano do calendário */
    $sSql = $oDaoCalendario->sql_query_file(
                                             null,
                                             'ed52_i_ano as anocalendario',
                                             '',
                                             "ed52_i_codigo = {$codcalendario}"
                                           );
    $rs   = $oDaoCalendario->sql_record($sSql);

    if ($oDaoCalendario->numrows <= 0) {

      $sMensagem = 'Erro ao obter o ano do calendário. Erro da classe: '. $oDaoCalendario->erro_msg;
      throw new Exception( $sMensagem );
    }

    $iAnoCalendario = db_utils::fieldsmemory($rs, 0)->anocalendario;
    
    if (!empty($maisturmas)) {
      $sCondTurmas = " and ed59_i_turma not in ($maisturmas)";
    } else {
      $sCondTurmas = '';
    }
  
    $sCampos     = 'ed08_c_descr, ed17_h_inicio, ed17_h_fim, ed17_i_escola, ed20_i_codigo as codmatricula, ';
    $sCampos    .= 'ed18_c_nome, reccgm.z01_nome as professor, ed57_c_descr as turma, ';
    $sCampos    .= 'ed15_c_nome as turno,ed52_c_descr as calendario';
    $sWhere      = " ed58_i_diasemana = $diasemana and reccgm.z01_numcgm = $iNumCgmRecHumano ";
    $sWhere     .= " and ed52_i_ano = $iAnoCalendario and ed58_ativo is true  ";
  
    $sIntervalo  = " and (((periodoescola.ed17_h_inicio > '".$oDadosPeriodo->horafim."' "; // inicio
    $sIntervalo .= "           and periodoescola.ed17_h_inicio < '".$oDadosPeriodo->horafim."') ";
    $sIntervalo .= "       or (periodoescola.ed17_h_fim  > '".$oDadosPeriodo->horainicio."' ";
    $sIntervalo .= "           and periodoescola.ed17_h_fim < '".$oDadosPeriodo->horafim."')) ";
    $sIntervalo .= "      or (periodoescola.ed17_h_inicio <= '".$oDadosPeriodo->horainicio."' ";
    $sIntervalo .= "          and periodoescola.ed17_h_fim >= '".$oDadosPeriodo->horafim."') ";
    $sIntervalo .= "      or (periodoescola.ed17_h_inicio >= '".$oDadosPeriodo->horainicio."' ";
    $sIntervalo .= "          and periodoescola.ed17_h_fim <= '".$oDadosPeriodo->horafim."') ";
    $sIntervalo .= "      or (periodoescola.ed17_h_inicio = '".$oDadosPeriodo->horainicio."' ";
    $sIntervalo .= "          and periodoescola.ed17_h_fim = '".$oDadosPeriodo->horafim."'))";
    $sSql        = $oDaoRegenciaHorario->sql_query_rechumano(
                                                              null,
                                                              $sCampos,
                                                              '',
                                                              $sWhere.$sIntervalo.$sCondTurmas
                                                            );
    $rs          = $oDaoRegenciaHorario->sql_record($rs);
    $sMsgErro    = '';

    if ($oDaoRegenciaHorario->numrows > 0) {
  
      $sMsgErro       = $oDadosPeriodo->descrturno.' '.$oDadosPeriodo->descrperiodo;
      $sMsgErro      .= ' Período ('.$oDadosPeriodo->horafim.' às '.$oDadosPeriodo->horafim.') ';
      $sMsgErro      .= 'está em conflito com período(s):\n\n';

      for ($iCont = 0; $iCont < $oDaoRegenciaHorario->numrows; $iCont++) {
  
        $oDadosConflito = db_utils::fieldsmemory($rs, $iCont);
        $sMsgErro .= ' -> '.$oDadosConflito->ed08_c_descr;
        $sMsgErro .= ' ('.$oDadosConflito->ed17_h_inicio.' às '.$oDadosConflito->ed17_h_fim.')';
        $sMsgErro .= ' já marcado na turma '.$oDadosConflito->turma.', calendário '.$oDadosConflito->calendario;
        $sMsgErro .= ' da Escola '.$oDadosConflito->ed17_i_escola.' - '.$oDadosConflito->ed18_c_nome;
        $sMsgErro .= ' (Matrícula: '.$oDadosConflito->codmatricula.')\n';
      }
    }
  
    if (empty($sMsgErro)) {
  
      /* Verifico caso o regente já possua horário em turmaac */
      $sWhere           = " turmaachorario.ed270_i_diasemana = $diasemana ";
      $sWhere          .= " and reccgm.z01_numcgm = $iNumCgmRecHumano ";
      $sWhere          .= " and ed52_i_ano = $iAnoCalendario $sIntervalo ";
      $sSql             = $oDaoTurmaACHorario->sql_query_rechumano(null, $sCampos, '', $sWhere);
      $rs               = $oDaoTurmaACHorario->sql_record($sSql); // $result_sala

      if ($oDaoTurmaACHorario->numrows > 0) {
  
        $sMsgErro       = $oDadosPeriodo->descrturno.' '.$oDadosPeriodo->descrperiodo;
        $sMsgErro      .= ' Período ('.$oDadosPeriodo->horafim.' às '.$oDadosPeriodo->horafim.') ';
        $sMsgErro      .= 'está em conflito com período(s):\n\n';

        for ($iCont = 0; $iCont < $oDaoTurmaACHorario->numrows; $iCont++) {
        
          $oDadosConflito = db_utils::fieldsmemory($rs, $iCont);
          $sMsgErro .= ' -> '.$oDadosConflito->ed08_c_descr;
          $sMsgErro .= ' ('.$oDadosConflito->ed17_h_inicio.' às '.$oDadosConflito->ed17_h_fim.')';
          $sMsgErro .= ' já marcado na turma '.$oDadosConflito->turma.', calendário '.$oDadosConflito->calendario;
          $sMsgErro .= ' da Escola '.$oDadosConflito->ed17_i_escola.' - '.$oDadosConflito->ed18_c_nome;
          $sMsgErro .= ' (Matrícula: '.$oDadosConflito->codmatricula.')\n';
        }
      }
    }
  
    if (!empty($sMsgErro)) {
      throw new Exception($sMsgErro);
    } else {
    ?>
      <script>
       contador = 0;
       for( i = 0; i < parent.document.getElementById("contp").value; i++ ) {

         for( x = 0; x < parent.document.getElementById("contd").value; x++ ) {

           val    = parent.document.getElementById("valorQ"+i+x).value;
           separa = val.split("|");

           if (separa[0] == parent.document.form1.ed270_i_rechumano.value) {
             contador++;
           }
         }
       }
       parent.document.getElementById("text<?=$quadro?>").value = parent.document.form1.identificacao.value;
       parent.document.getElementById("valor<?=$quadro?>").value = "<?=$chavepesquisa."|".$diasemana."|".$periodo."|".$rechumano?>";
       parent.document.getElementById("rh<?=$quadro?>").innerHTML = "<font color='#FF0000'>"+parent.document.form1.z01_nome.value+"</font>";
       parent.document.getElementById("codrh<?=$quadro?>").innerHTML = <?=$rechumano?>;
      </script>
      <?
    } // Fecha else não tem mensagem de erro
  } catch (Exception $oExcecao) {
    echo "<script>alert('".str_replace("'", "\'", $oExcecao->getMessage())."');</script>";
  }
}

if (isset($disponibilidade)) {

  try {
	
    if (isset($excluir) && !empty($excluir)) {
  
      $oDaoTurmaACHorario->excluir($excluir);
      if ($oDaoTurmaACHorario->erro_status == '0') {

        $sMensagem = 'Não foi possível excluir o horário. Erro da classe: '.$oDaoTurmaACHorario->erro_msg;
        throw new Exception( $sMensagem );
      }
    }
    
    if ($rechumano != 0) {
  
      /* Obtenho o CGM do rechumano */
      $sSql    = $oDaoRechumano->sql_query_rechumano($rechumano, 'reccgm.z01_numcgm');
      $rs      = $oDaoRechumano->sql_record($sSql);
      $sTabela = '';
    
      if ($oDaoRechumano->numrows <= 0) {

        $sMensagem = 'Não foi possível obter o CGM do regente. Erro da classe: ' . $oDaoCalendario->erro_msg;
        throw new Exception( $sMensagem );
      }

      $iNumCgmRecHumano = db_utils::fieldsmemory($rs, 0)->z01_numcgm;
  
      /* Obtenho o ano do calendário */
      $sSql =  $oDaoCalendario->sql_query_file(
                                                null,
                                                'ed52_i_ano as anocalendario',
                                                '',
                                                "ed52_i_codigo = {$codcalendario}"
                                              );
      $rs   = $oDaoCalendario->sql_record($sSql);

      if ($oDaoCalendario->numrows <= 0) {

        $sMensagem  = 'Não foi possível obter o ano do calendário para verificar a disponibilidade do regente.';
        $sMensagem .= ' Erro da classe: ' . $oDaoCalendario->erro_msg;
        throw new Exception( $sMensagem );
      }

      $iAnoCalendario = db_utils::fieldsmemory($rs, 0)->anocalendario;
  
      /* Obtenho os períodos de aula da escola de acordo com o turno */
      $sCampos   = 'periodoescola.ed17_i_codigo, periodoescola.ed17_h_inicio as horainicio, ';
      $sCampos  .= 'periodoescola.ed17_h_fim as horafim';
      $sOrder    = 'turno.ed15_i_sequencia, periodoaula.ed08_i_sequencia';
      $sWhere    = " ed17_i_escola = $iEscola and ed17_i_turno in ($ed17_i_turno)";
      $sSql      = $oDaoPeriodoEscola->sql_query(null, $sCampos, $sOrder, $sWhere);
      $rsPeriodo = $oDaoPeriodoEscola->sql_record($sSql);
    
      // Percorro todos os períodos de aula em todos os dias letivos
      for ($iCont = 0;$iCont < $oDaoPeriodoEscola->numrows; $iCont++) {
    
        $oDadosPeriodo = db_utils::fieldsmemory($rsPeriodo, $iCont);
        
        /* Obtenho os dias da semana que são letivos na escola */
        $sSql = $oDaoDiaSemana->sql_query_rh(
                                              null,
                                              'diasemana.ed32_i_codigo',
                                              'ed32_i_codigo',
                                              "ed04_c_letivo = 'S' and ed04_i_escola = {$iEscola}"
                                            );
        $rsDiasLetivos = $oDaoDiaSemana->sql_record($sSql);

        for ($iCont2 = 0; $iCont2 < $oDaoDiaSemana->numrows; $iCont2++) {
  
          $iCodDia = db_utils::fieldsmemory($rsDiasLetivos, $iCont2)->ed32_i_codigo;
          $sQuadro = 'Q'.$iCont.$iCont2;
  
          /* Verifico se o regente possui algum horário disponível na escola */
          $sWhere             = " rechumanohoradisp.ed33_i_diasemana = $iCodDia ";
          $sWhere            .= ' and rechumanohoradisp.ed33_i_periodo = '.$oDadosPeriodo->ed17_i_codigo;
          $sWhere            .= ' and rechumanohoradisp.ed33_ativo is true ';
          $sWhere            .= " and rechumanoescola.ed75_i_rechumano = $rechumano ";
          $sWhere            .= " and rechumanoescola.ed75_i_escola = $iEscola ";
          $sWhere            .= " and periodoescola.ed17_i_escola = $iEscola";
          $sSql               = $oDaoRecHumanoHoraDisp->sql_query_disponibilidade(null, 'ed17_i_codigo', '', $sWhere);
          $rs                 = $oDaoRecHumanoHoraDisp->sql_record($sSql);
          $lPossuiHorarioDisp = $oDaoRecHumanoHoraDisp->numrows <= 0 ? false : true;
  
          if (!empty($maisturmas)) {
            $sCondTurmas = " and ed59_i_turma not in ($maisturmas)";
          } else {
            $sCondTurmas = '';
          }
          
          /* Obtenho os dados da turma e disciplina onde o regente já possua horário em conflito com o horário atual */
          $sCampos     = ' ed57_c_descr as nometurma, ed232_c_abrev as abrevdisc ';
          $sWhere      = " ed58_i_diasemana = $iCodDia and reccgm.z01_numcgm = $iNumCgmRecHumano ";
          $sWhere     .= " and ed52_i_ano = $iAnoCalendario ";
          $sIntervalo  = " and (((periodoescola.ed17_h_inicio > '".$oDadosPeriodo->horainicio."' ";
          $sIntervalo .= "           and periodoescola.ed17_h_inicio < '".$oDadosPeriodo->horafim."') ";
          $sIntervalo .= "       or (periodoescola.ed17_h_fim  > '".$oDadosPeriodo->horainicio."' ";
          $sIntervalo .= "           and periodoescola.ed17_h_fim < '".$oDadosPeriodo->horafim."')) ";
          $sIntervalo .= "      or (periodoescola.ed17_h_inicio <= '".$oDadosPeriodo->horainicio."' ";
          $sIntervalo .= "          and periodoescola.ed17_h_fim >= '".$oDadosPeriodo->horafim."') ";
          $sIntervalo .= "      or (periodoescola.ed17_h_inicio >= '".$oDadosPeriodo->horainicio."' ";
          $sIntervalo .= "          and periodoescola.ed17_h_fim <= '".$oDadosPeriodo->horafim."') ";
          $sIntervalo .= "      or (periodoescola.ed17_h_inicio = '".$oDadosPeriodo->horainicio."' ";
          $sIntervalo .= "          and periodoescola.ed17_h_fim = '".$oDadosPeriodo->horafim."'))";
          $sSql        = $oDaoRegenciaHorario->sql_query_rechumano(
                                                                    null,
                                                                    $sCampos,
                                                                    '',
                                                                    $sWhere.$sIntervalo.$sCondTurmas
                                                                  );
          $rs          = $oDaoRegenciaHorario->sql_record($rs);
          $sNomeTurma  = '';
          $sAbrevDisc  = '';

          if ($oDaoRegenciaHorario->numrows > 0) {
    
            $sNomeTurma = db_utils::fieldsmemory($rs, 0)->nometurma;
            $sAbrevDisc = db_utils::fieldsmemory($rs, 0)->abrevdisc;
          }
         
          /* Verifico caso o regente já possua horário em turmaac */
          $sCampos          = " turmaac.ed268_c_descr as nometurma ";
          $sWhere           = " turmaachorario.ed270_i_diasemana = $iCodDia ";
          $sWhere          .= " and reccgm.z01_numcgm = $iNumCgmRecHumano ";
          $sWhere          .= " and ed52_i_ano = $iAnoCalendario $sIntervalo ";
          $sSql             = $oDaoTurmaACHorario->sql_query_rechumano(null, $sCampos, '', $sWhere);
          $rs               = $oDaoTurmaACHorario->sql_record($sSql); // $result_sala
          if ($oDaoTurmaACHorario->numrows > 0) {
            $sNomeTurma = db_utils::fieldsmemory($rs, 0)->nometurma;
          }
         
          /* Verifico se o regente faz atendimento simultâneo */
          $sWhereRecHumanoAtiv = "rechumano.ed20_i_codigo = {$rechumano} and rechumanoescola.ed75_i_escola = {$iEscola}";
          $sSql                = $oDaoRechumanoAtiv->sql_query(
                                                                null,
                                                                'ed75_c_simultaneo',
                                                                '',
                                                                $sWhereRecHumanoAtiv
                                                              );
          $rs                     = $oDaoRechumanoAtiv->sql_record($sSql); // $result_atividade
          $sAtendimentoSimultaneo = 'false';

          if ($oDaoRechumanoAtiv->numrows > 0) {
            $sAtendimentoSimultaneo = db_utils::fieldsmemory($rs, 0)->ed75_c_simultaneo == 'S' ? 'true' : 'false';	
          }
    
          echo '<script>';
          if ($lPossuiHorarioDisp) { // Não possui horário disponível ou nenhum regente informado
  
            if ($rechumano != 0) {
              $sRec = 'true';
            } else {
              $sRec = 'false';
            }

            echo "js_horario('$sQuadro', $sRec, true, false, $sAtendimentoSimultaneo, '', '');";
          } else {
            echo "js_horario('$sQuadro', true, false, false, $sAtendimentoSimultaneo, '$sNomeTurma', '$sAbrevDisc');";
          }

          echo '</script>';
        } // Fim for percorre dias da semana letivos
      } // Fim for percorre períodos de aula escola
    } // Fim if $rechumano != 0
  } catch (Exception $oExcecao) {
    echo "<script>alert('".str_replace("'", "\'", $oExcecao->getMessage())."');</script>";
  }

} // Fim if existe disponibilidade
?>