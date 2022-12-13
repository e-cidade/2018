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

require_once(modification("fpdf151/pdfwebseller.php"));
require_once(modification("libs/db_utils.php"));

$oDaoEduParametros = new cl_edu_parametros();
$oDaoCalendario    = new cl_calendario();
$oDaoTurma         = new cl_turma();
$oDaoMatricula     = new cl_matricula();

$sCampos          = "ed52_i_ano as ano_calendario, ed52_c_descr as descr_calendario";
$sSql             = $oDaoCalendario->sql_query_file("", $sCampos, "", " ed52_i_codigo = $iCalendario" );
$rsAno            = $oDaoCalendario->sql_record( $sSql );
$oDadosCalendario = db_utils::fieldsmemory($rsAno, 0);

$iDiaLimite = DBDate::getQuantidadeDiasMes($iMes, $oDadosCalendario->ano_calendario);

$iCondicaoEnsino = "";
if ($sEnsino != "") {
  $iCondicaoEnsino = " AND ed11_i_ensino in ($sEnsino)";
}

/*
 * Explode a data
 */
$sCampos = " ed233_c_limitemov ";
$sWhere  = " ed233_i_escola = $iEscola ";
$sSql    =  $oDaoEduParametros->sql_query("", $sCampos, "", $sWhere);
$rs      = $oDaoEduParametros->sql_record($sSql);

if ($oDaoEduParametros->numrows > 0) {

  $oDadosParametros = db_utils::fieldsmemory($rs, 0);
  if (!strstr($oDadosParametros->ed233_c_limitemov,"/")) {

    ?>
    <table width='100%'>
     <tr>
      <td align='center'>
       <font color='#FF0000' face='arial'>
        <b>Parâmetro Dia/Mês Limite da Movimentação (Procedimentos->Parâmetros)<br>
           deve estar no formato dd/mm ou d/m (Exemplo: 02/02 ou 2/2)<br><br>
           Valor atual do parâmetro Dia/Mês Limite da Movimentação:
           <?= trim($oDadosParametros->ed233_c_limitemov) == "" ? "Não informado"
             :$oDadosParametros->ed233_c_limitemov
           ?><br>
        <input type='button' value='Fechar' onclick='window.close()'>
       </font>
      </td>
     </tr>
    </table>
    <?php
    exit;
  }

  $aLimiteMov     = explode("/", $oDadosParametros->ed233_c_limitemov);
  $iDiaLimiteMov  = $aLimiteMov[0];
  $iMesLimiteMov  = $aLimiteMov[1];

  if (@!checkdate($iMesLimiteMov, $iDiaLimiteMov, $oDadosCalendario->ano_calendario)) {
    ?>
    <table width='100%'>
     <tr>
      <td align='center'>
       <font color='#FF0000' face='arial'>
        <b>Parâmetro Dia/Mês Limite da Movimentação (Procedimentos->Parâmetros)<br>
           deve estar no formato dd/mm ou d/m (Exemplo: 02/02 ou 2/2) e deve ser uma data válida.<br><br>
           Valor atual do parâmetro Dia/Mês Limite da Movimentação:
           <?= trim($oDadosParametros->ed233_c_limitemov) == "" ? "Não informado"
             :$oDadosParametros->ed233_c_limitemov
           ?><br>
           Data Limite da Movimentação: <?= $iDiaLimiteMov."/".$iMesLimiteMov."/".$oDadosCalendario->ano_calendario
                                        ?> (Data Inválida)<br></b>
        <input type='button' value='Fechar' onclick='window.close()'>
       </font>
      </td>
     </tr>
    </table>
    <?php
    exit;
  }

  $dDataLimiteMov  = $oDadosCalendario->ano_calendario."-".(strlen($iMesLimiteMov) == 1 ? "0".
                                                            $iMesLimiteMov:$iMesLimiteMov
                                                           );
  $dDataLimiteMov .= "-".(strlen($iDiaLimiteMov) == 1 ? "0".$iDiaLimiteMov:$iDiaLimiteMov);
} else {
  $dDataLimiteMov = $oDadosCalendario->ano_calendario."-01-01";
}

$dDataInicial = $oDadosCalendario->ano_calendario."-".(strlen($iMes) == 1 ? "0".$iMes:$iMes)."-01";
$dDataLimite  = $oDadosCalendario->ano_calendario."-".(strlen($iMes) == 1 ? "0".$iMes:$iMes)."-".$iDiaLimite;

/*
 * QUANTIDADE DE TURMAS.
 */
$sCamposQtdTurmas  = " count(ed57_i_codigo) as qtdturmas, ed11_c_descr,  ed11_c_abrev, ed11_i_codigo,";
$sCamposQtdTurmas .= " ed11_i_ensino, ed10_c_descr, ed15_i_codigo, ed15_c_nome, ed15_i_sequencia ";
$sWhereQtdTurmas   = " ed57_i_escola = $iEscola AND ed52_i_codigo = $iCalendario $iCondicaoEnsino ";
$sWhereQtdTurmas  .= " AND exists(select * from matricula where ed60_i_turma = ed57_i_codigo ";
$sWhereQtdTurmas  .= " AND ed60_d_datamatricula <= '$dDataLimite') ";
$sGroupQtdTurmas   = " GROUP BY ed11_c_descr, ed11_i_codigo, ed11_i_sequencia, ed11_i_ensino, ed10_c_descr, ed15_i_codigo,";
$sGroupQtdTurmas  .= " ed15_c_nome, ed15_i_sequencia, ed11_c_abrev ";
$sOrderQtdTurmas   = " ed15_i_sequencia, ed11_i_ensino, ed11_i_sequencia ";
$sSqlQtdTurmas     = $oDaoTurma->sql_query_boletimestat("", $sCamposQtdTurmas, $sOrderQtdTurmas,
                                                        $sWhereQtdTurmas.$sGroupQtdTurmas);
$rsQtdTurmas       = $oDaoTurma->sql_record($sSqlQtdTurmas);
$iLinhasQtdTurmas  = $oDaoTurma->numrows;
if ($iLinhasQtdTurmas == 0) {?>

  <table width='100%'>
   <tr>
    <td align='center'>
     <font color='#FF0000' face='arial'>
      <b>Nenhum registro encontrado.<br>
      <input type='button' value='Fechar' onclick='window.close()'></b>
     </font>
    </td>
   </tr>
  </table>
  <?php
  exit;
}

$oDadosTurmas  = db_utils::fieldsmemory($rsQtdTurmas, 0, 'ed10_c_descr');
$sTituloEnsino = "TODOS";
if ($sEnsino != "") {
  $sTituloEnsino = $oDadosTurmas->ed10_c_descr;
}

$oEscola = EscolaRepository::getEscolaByCodigo( $iEscola );
$oPdf    = new PDF();
$oPdf->Open();
$oPdf->AliasNbPages();
$head1 = "RELATÓRIO BOLETIM ESTATÍSTICO";
$head2 = "Escola: {$oEscola->getNome()}";
$head3 = "Mês: ".db_mes($iMes, 1);
$head4 = "Calendário: ".$oDadosCalendario->descr_calendario;
$head5 = "Ensino: ".$sTituloEnsino;
$oPdf->ln(5);
$lTroca = 1;

/*
 * Váriáveis Abreviadas:
 * M     = Alunos do Sexo Masculino;
 * F     = Aluno do Sexo Feminino;
 * T     = Total;
 * Tot   = Total;
 * Trans = Alunos Transferidos;
 * Evad  = Alunos Evadidos;
 * Prog  = Alunos Progredidos;
 * Nov   = Alunos Novos;
 * Efe   = Alunos com Matriculas Efetivas;
 */
$iSomaMTotal = 0;
$iSomaFTotal = 0;
$iSomaTot    = 0;
$iSomaMTrans = 0;
$iSomaFTrans = 0;
$iSomaTrans  = 0;
$iSomaMEvad  = 0;
$iSomaFEvad  = 0;
$iSomaTEvad  = 0;
$iSomaMProg  = 0;
$iSomaFProg  = 0;
$iSomaTProg  = 0;
$iSomaMNov   = 0;
$iSomaFNov   = 0;
$iSomaTNov   = 0;
$iSomaTurma  = 0;
$iSomaMEfet  = 0;
$iSomaFEfet  = 0;
$iSomaTEfet  = 0;
$iPrimeiro   = "";
$iPriTurno   = "";

for ($iContTurmas = 0; $iContTurmas < $iLinhasQtdTurmas; $iContTurmas++) {

  $oDadosTurmas = db_utils::fieldsmemory($rsQtdTurmas, $iContTurmas);
  $oDadosTurmas->ed15_c_nome;
  if ($oPdf->gety() > $oPdf->h - 30 || $lTroca != 0 ) {

    $oPdf->addpage('P');
    $oPdf->setfillcolor(215);
    $oPdf->setfont('arial','b',8);
    $iPosY = $oPdf->getY();
    $iPosX = $oPdf->getX();
    $oPdf->cell(15, 12, "Etapa", 1, 0, "C", 1);
    $oPdf->cell(15, 12, "N° Turmas", 1, 0, "C", 1);
    $oPdf->setY($iPosY);
    $oPdf->setX($iPosX+30);
    $oPdf->cell(27, 8, "Matrícula Total", 1, 2, "C", 1);
    $oPdf->cell(9, 4, "M", 1, 0, "C", 1);
    $oPdf->cell(9, 4, "F", 1, 0, "C", 1);
    $oPdf->cell(9, 4, "T", 1, 0, "C", 1);
    $oPdf->setY($iPosY);
    $oPdf->setX($iPosX+57);
    $oPdf->cell(81, 4, "Eliminados", 1, 2, "C", 1);
    $oPdf->cell(27, 4, "Transferidos", 1, 0, "C", 1);
    $oPdf->cell(27, 4, "Evad/Canc/Falec", 1, 0, "C", 1);
    $oPdf->cell(27, 4, "Progredidos", 1, 0, "C", 1);
    $oPdf->setY($iPosY+8);
    $oPdf->setX($iPosX+57);
    $oPdf->cell(9, 4, "M", 1, 0, "C", 1);
    $oPdf->cell(9, 4, "F", 1, 0, "C", 1);
    $oPdf->cell(9, 4, "T", 1, 0, "C", 1);
    $oPdf->cell(9, 4, "M", 1, 0, "C", 1);
    $oPdf->cell(9, 4, "F", 1, 0, "C", 1);
    $oPdf->cell(9, 4, "T", 1, 0, "C", 1);
    $oPdf->cell(9, 4, "M", 1, 0, "C", 1);
    $oPdf->cell(9, 4, "F", 1, 0, "C", 1);
    $oPdf->cell(9, 4, "T", 1, 0, "C", 1);
    $oPdf->setY($iPosY);
    $oPdf->setX($iPosX+138);
    $oPdf->cell(27, 8, "Novos", 1, 2, "C", 1);
    $oPdf->cell(9, 4, "M", 1, 0, "C", 1);
    $oPdf->cell(9, 4, "F", 1, 0, "C", 1);
    $oPdf->cell(9, 4, "T", 1, 0, "C", 1);
    $oPdf->setY($iPosY);
    $oPdf->setX($iPosX+165);
    $oPdf->cell(27, 8, "Matrícula Efetiva", 1, 2, "C", 1);
    $oPdf->cell(9, 4, "M", 1, 0, "C", 1);
    $oPdf->cell(9, 4, "F", 1, 0, "C", 1);
    $oPdf->cell(9, 4, "T", 1, 1, "C", 1);
    $lTroca = 0;
  }

  $oPdf->setfont('arial', 'b', 8);
  $oPdf->setfillcolor(215);

  if ($iPriTurno != $oDadosTurmas->ed15_i_codigo) {

    $oPdf->cell(192, 4, "Turno: ".$oDadosTurmas->ed15_c_nome, 1, 1, "L", 1);
    $iPriTurno = $oDadosTurmas->ed15_i_codigo;
  }

  $oPdf->setfillcolor(240);

  if ($iPrimeiro != $oDadosTurmas->ed11_i_ensino) {

    $oPdf->cell(192, 4,$oDadosTurmas->ed10_c_descr, 1, 1, "L", 1);
    $iPrimeiro = $oDadosTurmas->ed11_i_ensino;
  }

  $oPdf->setfont('arial', '', 8);
  $oPdf->cell(15, 6, $oDadosTurmas->ed11_c_abrev, 1, 0, "C", 0);
  $oPdf->cell(15, 6, $oDadosTurmas->qtdturmas, 1, 0, "C", 0);
  $iSomaTurma += $oDadosTurmas->qtdturmas;

  /*
   * MATRÍCULA
   */
  $dtInicioMes = "{$oDadosCalendario->ano_calendario}-$iMes-01";

  $sCamposMatricula  = "ed47_v_sexo,";
  $sCamposMatricula .= "case";
  $sCamposMatricula .= "     when ed60_d_datamatricula between '{$dtInicioMes}' and '{$dDataLimite}'";
  $sCamposMatricula .= "          then 1";
  $sCamposMatricula .= "          else 0";
  $sCamposMatricula .= " end as novo,";
  $sCamposMatricula .= "case";
  $sCamposMatricula .= "     when ed60_c_situacao = 'MATRICULADO' and ed60_d_datasaida is null";
  $sCamposMatricula .= "          then 'M1'";
  $sCamposMatricula .= "     when (ed60_c_situacao = 'TRANSFERIDO REDE' or ed60_c_situacao = 'TRANSFERIDO FORA')";
  $sCamposMatricula .= "      and ed60_d_datasaida > '{$dDataLimite}'";
  $sCamposMatricula .= "      and ed60_d_datasaida > '{$dDataLimiteMov}'";
  $sCamposMatricula .= "          then 'M1'";
  $sCamposMatricula .= "     when (ed60_c_situacao = 'TRANSFERIDO REDE' or ed60_c_situacao = 'TRANSFERIDO FORA')";
  $sCamposMatricula .= "      and ed60_d_datasaida between '{$dDataLimiteMov}' and '{$dDataLimite}'";
  $sCamposMatricula .= "          then 'M2'";
  $sCamposMatricula .= "     when (ed60_c_situacao in('EVADIDO', 'CANCELADO', 'FALECIDO', 'MATRICULA TRANCADA', 'MATRICULA INDEFERIDA'))";
  $sCamposMatricula .= "      and ed60_d_datasaida > '{$dDataLimite}'";
  $sCamposMatricula .= "      and ed60_d_datasaida > '{$dDataLimiteMov}'";
  $sCamposMatricula .= "          then 'M1'";
  $sCamposMatricula .= "    when (ed60_c_situacao in('EVADIDO', 'CANCELADO', 'FALECIDO', 'MATRICULA TRANCADA', 'MATRICULA INDEFERIDA'))";
  $sCamposMatricula .= "     and ed60_d_datasaida between '{$dDataLimiteMov}' and '{$dDataLimite}'";
  $sCamposMatricula .= "         then 'M3'";
  $sCamposMatricula .= "    when (ed60_c_situacao = 'AVANÇADO' or ed60_c_situacao = 'CLASSIFICADO')";
  $sCamposMatricula .= "     and ed60_d_datasaida > '{$dDataLimite}'";
  $sCamposMatricula .= "     and ed60_d_datasaida > '{$dDataLimiteMov}'";
  $sCamposMatricula .= "         then 'M1'";
  $sCamposMatricula .= "    when (ed60_c_situacao = 'AVANÇADO' or ed60_c_situacao = 'CLASSIFICADO')";
  $sCamposMatricula .= "     and ed60_d_datasaida between '{$dDataLimiteMov}' and '{$dDataLimite}'";
  $sCamposMatricula .= "         then 'M4'";
  $sCamposMatricula .= "   when (ed60_c_situacao = 'TROCA DE MODALIDADE' and ed60_d_datasaida >= '{$dDataLimite}')";
  $sCamposMatricula .= "        then 'M1'";
  $sCamposMatricula .= " end as situacao";

  $sWhereMatricula   = " ed57_i_escola = $iEscola AND ed221_i_serie = $oDadosTurmas->ed11_i_codigo";
  $sWhereMatricula  .= " AND ed52_i_codigo = $iCalendario AND ed221_c_origem = 'S' AND ed15_i_codigo = $iPriTurno";
  $sWhereMatricula  .= " AND ed60_d_datamatricula <= '$dDataLimite' $iCondicaoEnsino ";
  $sOrderMatricula   = " ed11_c_descr,ed15_i_sequencia,ed57_c_descr,ed47_v_nome ";
  $sSqlMatricula     = $oDaoMatricula->sql_query_boletimestat("", $sCamposMatricula, $sOrderMatricula, $sWhereMatricula);

  $rsMatricula       = $oDaoMatricula->sql_record($sSqlMatricula);
  $iLinhasMatricula  = $oDaoMatricula->numrows;

  $iMTot   = 0;
  $iFTot   = 0;
  $iTot    = 0;
  $iMTrans = 0;
  $iFTrans = 0;
  $iTrans  = 0;
  $iMEvad  = 0;
  $iFEvad  = 0;
  $iTEvad  = 0;
  $iMProg  = 0;
  $iFProg  = 0;
  $iTProg  = 0;
  $iMNov   = 0;
  $iFNov   = 0;
  $iTNov   = 0;
  $iMEfet  = 0;
  $iFEfet  = 0;
  $iTEfet  = 0;

  for ($iContMatricula = 0; $iContMatricula < $iLinhasMatricula; $iContMatricula++) {

    $oDadosMatricula = db_utils::fieldsmemory($rsMatricula, $iContMatricula);
    if ($oDadosMatricula->ed47_v_sexo == "M" && $oDadosMatricula->situacao != "") {

      $iMTot++;
      $iSomaMTotal++;
    }

    if ($oDadosMatricula->ed47_v_sexo == "F" && $oDadosMatricula->situacao != "") {

      $iFTot++;
      $iSomaFTotal++;
    }

    if ($oDadosMatricula->ed47_v_sexo == "M" && $oDadosMatricula->situacao == "M2") {

      $iMTrans++;
      $iSomaMTrans++;
    }

    if ($oDadosMatricula->ed47_v_sexo == "F" && $oDadosMatricula->situacao == "M2") {

      $iFTrans++;
      $iSomaFTrans++;
    }

    if ($oDadosMatricula->ed47_v_sexo == "M" && $oDadosMatricula->situacao == "M3") {

      $iMEvad++;
      $iSomaMEvad++;
    }

    if ($oDadosMatricula->ed47_v_sexo == "F" && $oDadosMatricula->situacao == "M3") {

      $iFEvad++;
      $iSomaFEvad++;
    }

    if ($oDadosMatricula->ed47_v_sexo == "M" && $oDadosMatricula->situacao == "M4") {

      $iMProg++;
      $iSomaMProg++;
    }

    if ($oDadosMatricula->ed47_v_sexo == "F" && $oDadosMatricula->situacao == "M4") {

      $iFProg++;
      $iSomaFProg++;
    }

    if ($oDadosMatricula->ed47_v_sexo == "M" && $oDadosMatricula->novo == 1 && $oDadosMatricula->situacao != "") {

      $iMNov++;
      $iSomaMNov++;
    }

    if ($oDadosMatricula->ed47_v_sexo == "F" && $oDadosMatricula->novo == 1 && $oDadosMatricula->situacao != "") {

      $iFNov++;
      $iSomaFNov++;
    }
  }

  $oPdf->cell(9, 6, $iMTot == 0 ? "-" : $iMTot, 1, 0, "C", 0);
  $oPdf->cell(9, 6, $iFTot == 0 ? "-" : $iFTot, 1, 0, "C", 0);
  $iTot      = $iMTot+$iFTot;
  $iSomaTot += $iTot;
  $oPdf->cell(9, 6, $iTot == 0 ? "-" : $iTot, 1, 0, "C", 0);

  $oPdf->cell(9, 6, $iMTrans == 0 ? "-" : $iMTrans, 1, 0, "C", 0);
  $oPdf->cell(9, 6, $iFTrans == 0 ? "-" : $iFTrans, 1, 0, "C", 0);
  $iTrans      = $iMTrans+$iFTrans;
  $iSomaTrans += $iTrans;
  $oPdf->cell(9, 6, $iTrans == 0 ? "-" : $iTrans, 1, 0, "C", 0);

  $oPdf->cell(9, 6, $iMEvad == 0 ? "-" : $iMEvad, 1, 0, "C", 0);
  $oPdf->cell(9, 6, $iFEvad == 0 ? "-" : $iFEvad, 1, 0, "C", 0);
  $iTEvad      = $iMEvad+$iFEvad;
  $iSomaTEvad += $iTEvad;
  $oPdf->cell(9, 6, $iTEvad == 0 ? "-" : $iTEvad, 1, 0, "C", 0);

  $oPdf->cell(9,6,$iMProg == 0 ? "-" : $iMProg, 1, 0, "C", 0);
  $oPdf->cell(9,6,$iFProg == 0 ? "-" : $iFProg, 1, 0, "C", 0);
  $iTProg      = $iMProg+$iFProg;
  $iSomaTProg += $iTProg;
  $oPdf->cell(9, 6, $iTProg == 0 ? "-" : $iTProg, 1, 0, "C", 0);

  $oPdf->cell(9, 6, $iMNov == 0 ? "-" : $iMNov, 1, 0, "C", 0);
  $oPdf->cell(9, 6, $iFNov == 0 ? "-" : $iFNov, 1, 0, "C", 0);
  $iTNov      = $iMNov+$iFNov;
  $iSomaTNov += $iTNov;
  $oPdf->cell(9, 6, $iTNov == 0 ? "-" : $iTNov, 1, 0, "C", 0);

  /*
   * Matricula efetiva
   */
  $iMEfet      = $iMTot-($iMTrans+$iMEvad+$iMProg);
  $iFEfet      = $iFTot-($iFTrans+$iFEvad+$iFProg);
  $iSomaMEfet += $iMEfet;
  $iSomaFEfet += $iFEfet;

  $oPdf->cell(9, 6, $iMEfet == 0 ? "-" : $iMEfet, 1, 0, "C", 0);
  $oPdf->cell(9, 6, $iFEfet == 0 ? "-" : $iFEfet, 1, 0, "C", 0);
  $iTEfet      = $iMEfet+$iFEfet;
  $iSomaTEfet += $iTEfet;
  $oPdf->cell(9, 6, $iTEfet == 0 ? "-" : $iTEfet, 1, 1, "C", 0);
}

$oPdf->setfillcolor(215);
$oPdf->setfont('arial', 'b', 8);
$oPdf->cell(15, 6, "TOTAL", 1, 0, "C", 1);
$oPdf->cell(15, 6, $iSomaTurma, 1, 0, "C", 1);

/*
 * Totais
 * Matricula Total
 */
$oPdf->cell(9, 6, $iSomaMTotal == 0 ? "-" : $iSomaMTotal, 1, 0, "C", 1);
$oPdf->cell(9, 6, $iSomaFTotal == 0 ? "-" : $iSomaFTotal, 1, 0, "C", 1);
$oPdf->cell(9, 6, $iSomaTot == 0 ? "-" : $iSomaTot, 1, 0, "C", 1);

/*
 * Transferidos
 */
$oPdf->cell(9, 6, $iSomaMTrans == 0 ? "-" : $iSomaMTrans, 1, 0, "C", 1);
$oPdf->cell(9, 6, $iSomaFTrans == 0 ? "-" : $iSomaFTrans, 1, 0, "C", 1);
$oPdf->cell(9, 6, $iSomaTrans == 0 ? "-"  : $iSomaTrans,  1, 0, "C", 1);

/*
 * Evadidos/Cancelados
 */
$oPdf->cell(9, 6, $iSomaMEvad == 0 ? "-" : $iSomaMEvad, 1, 0, "C", 1);
$oPdf->cell(9, 6, $iSomaFEvad == 0 ? "-" : $iSomaFEvad, 1, 0, "C", 1);
$oPdf->cell(9, 6, $iSomaTEvad == 0 ? "-" : $iSomaTEvad, 1, 0, "C", 1);

/*
 * Progredidos
 */
$oPdf->cell(9, 6, $iSomaMProg == 0 ? "-" : $iSomaMProg, 1, 0, "C", 1);
$oPdf->cell(9, 6, $iSomaFProg == 0 ? "-" : $iSomaFProg, 1, 0, "C", 1);
$oPdf->cell(9, 6, $iSomaTProg == 0 ? "-" : $iSomaTProg, 1, 0, "C", 1);

/*
 * Novos
 */
$oPdf->cell(9, 6, $iSomaMNov == 0 ? "-" : $iSomaMNov, 1, 0, "C", 1);
$oPdf->cell(9, 6, $iSomaFNov == 0 ? "-" : $iSomaFNov, 1, 0, "C", 1);
$oPdf->cell(9, 6, $iSomaTNov == 0 ? "-" : $iSomaTNov, 1, 0, "C", 1);

/*
 * Matricula Efetiva
 */
$oPdf->cell(9, 6, $iSomaMEfet == 0 ? "-" : $iSomaMEfet, 1, 0, "C", 1);
$oPdf->cell(9, 6, $iSomaFEfet == 0 ? "-" : $iSomaFEfet, 1, 0, "C", 1);
$oPdf->cell(9, 6, $iSomaTEfet == 0 ? "-" : $iSomaTEfet, 1, 0, "C", 1);

/*
 * Listagem dos alunos
 */
if ($sImprimeLista == "yes") {

  $oPdf->setfillcolor(223);

  $sCamposLista  = "ed47_i_codigo, ed47_v_nome, ed47_v_sexo, ed11_c_descr, ed57_c_descr, ed15_c_nome, ed60_d_datamatricula,";
  $sCamposLista .= "case";
  $sCamposLista .= "     when ed60_d_datamatricula between '{$dDataInicial}' and '{$dDataLimite}'";
  $sCamposLista .= "          then 1";
  $sCamposLista .= "          else 0";
  $sCamposLista .= " end as novo,";
  $sCamposLista .= "case";
  $sCamposLista .= "     when ed60_c_situacao = 'MATRICULADO'";
  $sCamposLista .= "      and ed60_d_datasaida is null";
  $sCamposLista .= "          then 'M1'";
  $sCamposLista .= "     when (ed60_c_situacao = 'TRANSFERIDO REDE' or ed60_c_situacao = 'TRANSFERIDO FORA')";
  $sCamposLista .= "      and ed60_d_datasaida > '{$dDataLimite}'";
  $sCamposLista .= "      and ed60_d_datasaida > '{$dDataLimiteMov}'";
  $sCamposLista .= "          then 'M1'";
  $sCamposLista .= "     when (ed60_c_situacao = 'TRANSFERIDO REDE' or ed60_c_situacao = 'TRANSFERIDO FORA')";
  $sCamposLista .= "      and ed60_d_datasaida between '{$dDataInicial}' and '{$dDataLimite}'";
  $sCamposLista .= "      and ed60_d_datasaida > '{$dDataLimiteMov}'";
  $sCamposLista .= "          then 'M2'";
  $sCamposLista .= "     when (ed60_c_situacao = 'EVADIDO' or ed60_c_situacao = 'CANCELADO' or ed60_c_situacao = 'FALECIDO')";
  $sCamposLista .= "      and ed60_d_datasaida > '{$dDataLimite}'";
  $sCamposLista .= "      and ed60_d_datasaida > '{$dDataLimiteMov}'";
  $sCamposLista .= "          then 'M1'";
  $sCamposLista .= "     when (ed60_c_situacao = 'EVADIDO' or ed60_c_situacao = 'CANCELADO' or ed60_c_situacao = 'FALECIDO')";
  $sCamposLista .= "      and ed60_d_datasaida between '{$dDataInicial}' and '{$dDataLimite}'";
  $sCamposLista .= "      and ed60_d_datasaida > '{$dDataLimiteMov}'";
  $sCamposLista .= "          then 'M3'";
  $sCamposLista .= "     when (ed60_c_situacao = 'AVANÇADO' or ed60_c_situacao = 'CLASSIFICADO')";
  $sCamposLista .= "      and ed60_d_datasaida > '{$dDataLimite}'";
  $sCamposLista .= "      and ed60_d_datasaida > '{$dDataLimiteMov}'";
  $sCamposLista .= "          then 'M1'";
  $sCamposLista .= "     when (ed60_c_situacao = 'AVANÇADO' or ed60_c_situacao = 'CLASSIFICADO')";
  $sCamposLista .= "      and ed60_d_datasaida between '{$dDataInicial}' and '{$dDataLimite}'";
  $sCamposLista .= "      and ed60_d_datasaida > '{$dDataLimiteMov}'";
  $sCamposLista .= "          then 'M4'";
  $sCamposLista .= " end as situacao";

  $sWhereLista   = "     ed57_i_escola  = {$iEscola} ";
  $sWhereLista  .= " AND ed52_i_codigo  = {$iCalendario} ";
  $sWhereLista  .= " AND ed221_c_origem = 'S' ";
  $sWhereLista  .= " AND ed60_d_datamatricula <= '{$dDataLimite}' ";
  $sWhereLista  .= " {$iCondicaoEnsino} ";
  $sOrderLista   = " ed11_c_descr, ed15_i_sequencia, ed57_c_descr, ed47_v_nome ";
  $sSqlLista     = $oDaoMatricula->sql_query_boletimestat("", $sCamposLista, $sOrderLista, $sWhereLista);
  $rsLista       = $oDaoMatricula->sql_record($sSqlLista);
  $iLinhasLista  = $oDaoMatricula->numrows;
  $lTroca        = 1;
  $iConta        = 0;

  for($iContLista = 0; $iContLista < $iLinhasLista; $iContLista++) {

    $oDadosMatricula = db_utils::fieldsmemory($rsLista, $iContLista);
    if ($oPdf->gety() > $oPdf->h - 30 || $lTroca != 0 ) {

      $oPdf->Addpage();
      $oPdf->setfont('arial', 'b', 7);
      $oPdf->cell(10, 4, "Seq", "B", 0, "C", 0);
      $oPdf->cell(10, 4, "Código", "B", 0, "C", 0);
      $oPdf->cell(60, 4, "Nome", "B", 0, "L", 0);
      $oPdf->cell(10, 4, "Sexo", "B", 0, "L", 0);
      $oPdf->cell(30, 4, "Turma", "B", 0, "C", 0);
      $oPdf->cell(30, 4, "Etapa", "B", 0, "C", 0);
      $oPdf->cell(20, 4, "Turno", "B", 0, "L", 0);
      $oPdf->cell(20, 4, "Data Matrícula", "B", 1, "C", 0);
      $lTroca = 0;
    }

    if ($oDadosMatricula->situacao == "M1") {

      $oPdf->setfont('arial', '', 7);
      $oPdf->cell(10, 4, ($iConta+1), 0, 0, "C", 0);
      $oPdf->cell(10, 4, $oDadosMatricula->ed47_i_codigo, 0, 0, "C", 0);
      $oPdf->cell(60, 4, $oDadosMatricula->ed47_v_nome, 0, 0, "L", 0);
      $oPdf->cell(10, 4, $oDadosMatricula->ed47_v_sexo, 0, 0, "C", 0);
      $oPdf->cell(30, 4, substr($oDadosMatricula->ed57_c_descr, 0, 15), 0, 0, "C", 0);
      $oPdf->cell(30, 4, $oDadosMatricula->ed11_c_descr, 0, 0, "C", 0);
      $oPdf->cell(20, 4, $oDadosMatricula->ed15_c_nome, 0, 0, "C", 0);
      $oPdf->cell(20, 4, db_formatar($oDadosMatricula->ed60_d_datamatricula, 'd'), 0, 1, "C", 0);
      $iConta++;
    }
  }

  $oPdf->setfont('arial', 'b', 9);
  $oPdf->cell(190, 8, "", 0, 1, "L", 0);
  $oPdf->cell(190, 4, "Total de alunos: $iConta", 0, 1, "L", 0);
}

$oPdf->Output();