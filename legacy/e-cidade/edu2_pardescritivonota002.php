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

require_once(modification("fpdf151/FpdfMultiCellBorder.php"));
require_once(modification("libs/db_stdlibwebseller.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_libdocumento.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("std/DBDate.php"));

$cldiarioavaliacao   = new cl_diarioavaliacao;
$clprocavaliacao     = new cl_procavaliacao;
$clprocresultado     = new cl_procresultado;
$clregenciaperiodo   = new cl_regenciaperiodo;
$clregencia          = new cl_regencia;
$clmatricula         = new cl_matricula;
$claluno             = new cl_aluno;
$clturma             = new cl_turma;
$clpareceraval       = new cl_pareceraval;
$clparecerresult     = new cl_parecerresult;
$clDBConfig          = new cl_db_config();
$clEscola            = new cl_escola();
$clregenteconselho   = new cl_regenteconselho;

$resultedu           = eduparametros(db_getsession("DB_coddepto"));
$permitenotaembranco = VerParametroNota(db_getsession("DB_coddepto"));
$escola              = db_getsession("DB_coddepto");

$oRegencia    = RegenciaRepository::getRegenciaByCodigo($disciplinas);
$aDisciplinas = explode(",", $disciplinas);


$obs1       = base64_decode($obs1);
$descr_disc = $oRegencia->getDisciplina()->getNomeDisciplina();

if ( strstr($disciplinas,",") ) {
  $descr_disc = "TODAS";
} else if ($punico == "yes") {
  $descr_disc = "PARECER ÚNICO";
}

$periodo = explode("|",$periodo);
if ($periodo[0] == "A") {

  $tp_per = "A";
  $sSqlProcAvaliacao = $clprocavaliacao->sql_query("","ed09_c_descr as periodoselecionado","",
                                                   " ed41_i_codigo = $periodo[1]");
  $rsResultProcAvaliacao = $clprocavaliacao->sql_record($sSqlProcAvaliacao);
  db_fieldsmemory($rsResultProcAvaliacao,0);
  $campos  = "ed95_i_regencia,ed232_i_codigo,ed232_c_descr,ed72_t_obs,ed72_i_codigo as codaval,ed72_t_parecer as parecer,";
  $campos .= " ed72_c_amparo as amparoum,ed81_c_todoperiodo as amparo,ed06_c_descr as justificativa, ";
  $campos .= " ed81_i_justificativa,ed81_i_convencaoamp,ed250_c_abrev";
  $tabela  = "diarioavaliacao";
  $where   = "ed72_i_procavaliacao";
  $join    = "ed72_i_diario";

} else {

  $tp_per = "R";
  $sSqlProcResultado = $clprocresultado->sql_query("","ed42_c_descr as periodoselecionado","",
                                                   " ed43_i_codigo = $periodo[1]");
  $rsResultProcResultado = $clprocresultado->sql_record($sSqlProcResultado);
  db_fieldsmemory($rsResultProcResultado,0);
  $campos     = "ed95_i_regencia,ed59_i_ordenacao,ed232_i_codigo,ed232_c_descr,ed73_i_codigo as codaval,ed73_t_parecer as parecer,";
  $campos    .= " ed73_c_amparo as amparoum,ed81_c_todoperiodo as amparo,ed06_c_descr as justificativa, ";
  $campos    .= " ed81_i_justificativa,ed81_i_convencaoamp,ed250_c_abrev";
  $tabela     = "diarioresultado";
  $where      = "ed73_i_procresultado";
  $join       = "ed73_i_diario";
  $ed72_t_obs = "";

}

$sSqlMatricula     = $clmatricula->sql_query("","ed47_i_codigo","to_ascii(ed47_v_nome)"," ed60_i_codigo in ($alunos)");
$rsResultMatricula = $clmatricula->sql_record($sSqlMatricula);
$linhas1           = $clmatricula->numrows;
if ($clmatricula->numrows == 0) {
  db_redireciona("db_erros.php?fechar=true&db_erro=Nenhuma registro encontrado.");
}

$sSqlTurma     = $clturma->sql_query_turmaserie("","ed57_i_codigo as turma, ed11_i_codigo",""," ed220_i_codigo = $turma");
$rsResultTurma = $clturma->sql_record($sSqlTurma);
db_fieldsmemory($rsResultTurma,0);

$oTurma = TurmaRepository::getTurmaByCodigo($turma);
$oEtapa = EtapaRepository::getEtapaByCodigo($ed11_i_codigo);

//1 - período, 2 - aulas dadas
$sLabelTipoAula = "Dias Letivos:";
if ($oTurma->getFormaCalculoCargaHoraria() == 1) {
  $sLabelTipoAula = "Aulas Dadas:";
}


$oProcedimentoAvalicao = $oTurma->getProcedimentoDeAvaliacaoDaEtapa($oEtapa);
$iOrdemSequencia       = null;


if ($periodo[0] == 'R') {

  $oElementoAvaliacao = ResultadoAvaliacaoRepository::getResultadoAvaliacaoByCodigo($periodo[1]);

  /**
   * Procurar todas as avaliacoes em que a ordem da avaliacao é menor que o resultado
  */
  $iOrdemSequencia = $oElementoAvaliacao->getOrdemSequencia();

  foreach ($oRegencia->getProcedimentoAvaliacao()->getAvaliacoes() as $oAvaliacao) {

    if ($oAvaliacao->getOrdemSequencia() < $iOrdemSequencia) {
      $aAvaliacoes[] = $oAvaliacao;
    }
  }
} else {

  $oElementoTurma     = AvaliacaoPeriodicaRepository::getAvaliacaoPeriodicaByCodigo($periodo[1]);
  $iOrdemSequencia    = $oElementoTurma->getOrdemSequencia();
  $aAvaliacoes        = array($oRegencia->getProcedimentoAvaliacao()->getElementoAvaliacaoByOrdem($iOrdemSequencia));
}

$oElementoAvaliacao = $oRegencia->getProcedimentoAvaliacao()->getElementoAvaliacaoByOrdem($iOrdemSequencia);

$pdf = new FpdfMultiCellBorder();
$pdf->Open();
$pdf->SetAutoPageBreak(true, 15);
$pdf->AliasNbPages();
$pdf->setMulticellBreakPageFunction('desenharQuadroBoletim');
$pdf->setfillcolor(223);
$m0         = 9;
$m1         = 14;
$m2         = 18;
$m3         = 22;
$m4         = 26;
$m5         = 30;
$m6         = 33;
$m7         = 3;
$m8         = 5;
$a          = 5;
$f          = 190;
$r          = 33;
$alturahead = $pdf->setY(3);

for ($x = 0; $x < $linhas1; $x++) {

  db_fieldsmemory($rsResultMatricula,$x);

  $sSqlMatricula = $clmatricula->sql_query("","*",""," ed60_i_turma = $turma
                                          AND ed60_i_aluno = $ed47_i_codigo AND ed60_c_ativa = 'S'");
  $rsMatricula = $clmatricula->sql_record($sSqlMatricula);
  db_fieldsmemory($rsMatricula,0);
  $rfatual = ResultadoFinal($ed60_i_codigo,$ed60_i_aluno,$ed60_i_turma,trim($ed60_c_situacao),trim($ed60_c_concluida), $ed29_i_ensino);
  db_inicio_transacao();
  $oMatricula = MatriculaRepository::getMatriculaByCodigo($ed60_i_codigo);
  $oDiario = $oMatricula->getDiarioDeClasse();
  db_fim_transacao(false);

  $iTotalFaltas = 0;
  $iTotalAulas  = 0;

  if ($punico == "yes") {

    /**
     * Se a primeira discilina da turma não tivér parecer, percorremos as disciplinas da turma até encontrar
     * uma disciplina com parecer lançado
     */
    $oParecer = LancamentoAvaliacaoAluno::getParecer($oMatricula, $oRegencia, $oElementoAvaliacao->getOrdemSequencia());

    if (trim($oParecer->sParecerPadronizado) == '' && trim($oParecer->sParecer) == '')  {

      foreach ($oTurma->getDisciplinas() as $oRegenciaAux) {

        $oElementoAvaliacaoAux = $oRegencia->getProcedimentoAvaliacao()->getElementoAvaliacaoByOrdem($iOrdemSequencia);
        $oParecerAux = LancamentoAvaliacaoAluno::getParecer($oMatricula, $oRegenciaAux, $oElementoAvaliacao->getOrdemSequencia());

        if (trim($oParecerAux->sParecerPadronizado) != '' || trim($oParecerAux->sParecer) != '') {

          $oElementoAvaliacao = $oElementoAvaliacaoAux;
          $disciplinas        = $oRegenciaAux->getCodigo();
          $oRegencia          = $oRegenciaAux;
          continue;
        }
      }
    }

    /**
     * Conta o total de aulas e faltas por período de avaliação
     */
    foreach ($aAvaliacoes as $oAvaliacao) {

      $oPeriodoAvaliacao = $oAvaliacao->getPeriodoAvaliacao();

      foreach ($oDiario->getDisciplinas() as $oDiarioDisciplina) {

        $iTotalFaltas += $oDiarioDisciplina->getTotalFaltasPorPeriodo($oPeriodoAvaliacao);
        $iTotalAulas  += $oDiarioDisciplina->getRegencia()->getTotalDeAulasNoPeriodo($oPeriodoAvaliacao);
      }
    }
  } else {

    if ($oElementoAvaliacao instanceof ResultadoAvaliacao) {

      $iTotalFaltas = $oDiario->getDisciplinasPorRegencia($oRegencia)->getTotalFaltas();
      $iTotalAulas  = $oRegencia->getTotalDeAulas();
    } else {

      $iTotalFaltas = $oDiario->getDisciplinasPorRegencia($oRegencia)
                              ->getTotalFaltasPorPeriodo($oElementoAvaliacao->getPeriodoAvaliacao());
      $iTotalAulas = $oRegencia->getTotalDeAulasNoPeriodo($oElementoAvaliacao->getPeriodoAvaliacao());
    }
  }

  /**
   * Dados Instituição
   */
  $sCamposInstit   = "nomeinst as nome,ender,munic,uf,telef,email,url,logo";
  $sSqlDadosInstit = $clDBConfig->sql_query_file(db_getsession('DB_instit'),$sCamposInstit);
  $rsDadosInstit   = db_query($sSqlDadosInstit);
  $oDadosInstit    = db_utils::fieldsMemory($rsDadosInstit,0);
  $url             = $oDadosInstit->url;
  $nome            = $oDadosInstit->nome;
  $sLogoInstit     = $oDadosInstit->logo;
  $munic           = $oDadosInstit->munic;

  /**
   * Dados Escola
   */
  $sCamposEscola      = "ed18_i_codigo,ed18_c_nome,j14_nome,ed18_i_numero,j13_descr,ed261_c_nome,ed260_c_sigla, ";
  $sCamposEscola     .= "ed18_c_email,ed18_c_logo,ed26_i_numero,ed26_i_ddd";
  $sSqlDadosEscola    = $clEscola->sql_query_dados(db_getsession("DB_coddepto"),$sCamposEscola);
  $rsDadosEscola      = db_query($sSqlDadosEscola);
  $oDadosEscola       = db_utils::fieldsMemory($rsDadosEscola,0);
  $sNomeEscola        = $oDadosEscola->ed18_c_nome;
  $sLogoEscola        = $oDadosEscola->ed18_c_logo;
  $iCodigoEscola      = $oDadosEscola->ed18_i_codigo;
  $ruaescola          = $oDadosEscola->j14_nome;
  $numescola          = $oDadosEscola->ed18_i_numero;
  $bairroescola       = $oDadosEscola->j13_descr;
  $cidadeescola       = $oDadosEscola->ed261_c_nome;
  $estadoescola       = $oDadosEscola->ed260_c_sigla;
  $emailescola        = $oDadosEscola->ed18_c_email;
  $iTelefoneEscola    = $oDadosEscola->ed26_i_numero;
  if ($oDadosEscola->ed26_i_ddd != "") {
    $iDTelefone         = "($oDadosEscola->ed26_i_ddd)";
  } else {
    $iDTelefone         = "";
  }

  $DadosCabecalho          = $sNomeEscola." ".$iDTelefone." ".$iTelefoneEscola;
  $sCamposRegenteConselho  = " case when cgmrh.z01_nome is null then cgmcgm.z01_nome ";
  $sCamposRegenteConselho .= "      else cgmrh.z01_nome end as conselheiro";
  $sSqlRegenteConselho     = $clregenteconselho->sql_query("",$sCamposRegenteConselho,"",
                                                       " ed235_i_turma = $ed57_i_codigo");
  $rsRegenteConselho       = $clregenteconselho->sql_record($sSqlRegenteConselho);
  if ($clregenteconselho->numrows > 0) {
    db_fieldsmemory($rsRegenteConselho,0);
  } else {
    $conselheiro = "";
  }

  $head1 = "BOLETIM POR PARECER DESCRITIVO $periodoselecionado";
  $head2 = "Aluno: $ed47_v_nome - $rfatual";
  $head3 = "Curso: $ed29_i_codigo - $ed29_c_descr";
  $head4 = "Calendário: $ed52_c_descr";
  $head5 = "Etapa: $ed11_c_descr          " . "Turma: $ed57_c_descr"   ;
  $head6 = "Matrícula: $ed60_i_codigo";
  $head7 = "Disciplina: $descr_disc";

  if (strlen($nome) > 42 || strlen($sNomeEscola) > 42) {
    $TamFonteNome = 8;
  } else {
    $TamFonteNome = 9;
  }

  $iPosXLogoEscola = 180;
  $pdf->AddPage('P');

  $m0         = 9;
  $m1         = 14;
  $m2         = 18;
  $m3         = 22;
  $m4         = 26;
  $m5         = 30;
  $m6         = 33;
  $m7         = 3;
  $m8         = 5;
  $a          = 5;
  $f          = 190;
  $r          = 33;
  $alturahead = $pdf->setY(6);
  $pdf->setfillcolor(225);
  $pdf->SetFont('arial','b',7);
  $margemesquerda  = $pdf->lMargin;

  $oLibDocumento = new libdocumento(5001,null);

  if ($oLibDocumento->lErro) {
    db_redireciona("db_erros.php?fechar=true&db_erro={$oLibDocumento->sMsgErro}");
  }

  $aParagrafo = $oLibDocumento->getDocParagrafos();

  foreach ($aParagrafo as $oParagrafo ) {
    eval($oParagrafo->oParag->db02_texto);
  }



  $pdf->Rect(10, $pdf->getY()+8, $pdf->w - 20, $pdf->h - ($pdf->getY() + 20), '');
  $pdf->cell(170,4,"Resultado final em $ed11_c_descr : $rfatual",0,0,"L",0);
  $pdf->cell(10,4,"","",1,"L",0);
  $sql  = " SELECT $campos ";
  $sql .= "       FROM $tabela ";
  $sql .= "        inner join diario on ed95_i_codigo = $join ";
  $sql .= "        inner join regencia on ed59_i_codigo = ed95_i_regencia ";
  $sql .= "        inner join disciplina on ed12_i_codigo = ed59_i_disciplina ";
  $sql .= "        inner join caddisciplina on ed232_i_codigo = ed12_i_caddisciplina ";
  $sql .= "        left join amparo on ed81_i_diario = ed95_i_codigo ";
  $sql .= "        left join justificativa on ed06_i_codigo = ed81_i_justificativa ";
  $sql .= "        left join convencaoamp on ed250_i_codigo = ed81_i_convencaoamp ";
  $sql .= "       WHERE ed95_i_aluno = $ed60_i_aluno ";
  $sql .= "       AND ed95_i_regencia in($disciplinas) ";
  $sql .= "       AND $where = {$oElementoAvaliacao->getCodigo()} ";
  $sql .= "       AND ed59_c_condicao = 'OB'";
  $sql .= "       ORDER BY ed59_i_ordenacao ";


  $result  = db_query($sql);

  // db_criatabela($result);
  $linhas0 = pg_num_rows($result);
  for ($r = 0; $r < $linhas0; $r++) {

    db_fieldsmemory($result,$r);
    $pdf->setfont('arial','b',7);

    if (($amparo == "N" || $amparo == "") && ($amparoum == "N" || $amparoum == "")) {

      if ($pdf->getY() > $pdf->h - 55) {
        $pdf->addpage("P");
        $m0         = 9;
        $m1         = 14;
        $m2         = 18;
        $m3         = 22;
        $m4         = 26;
        $m5         = 30;
        $m6         = 33;
        $m7         = 3;
        $m8         = 5;
        $a          = 5;
        $f          = 190;
        $r          = 33;
        $alturahead = $pdf->setY(6);
        $pdf->setfillcolor(225);
        $pdf->SetFont('arial','b',10);
        $margemesquerda  = $pdf->lMargin;
        $oLibDocumento = new libdocumento(5001,null);

        if ($oLibDocumento->lErro) {
          db_redireciona("db_erros.php?fechar=true&db_erro={$oLibDocumento->sMsgErro}");
        }
        $aParagrafo = $oLibDocumento->getDocParagrafos();

        foreach ($aParagrafo as $oParagrafo ) {
          eval($oParagrafo->oParag->db02_texto);
        }
     	$pdf->Rect(10, $pdf->getY()+8,$pdf->getX()+180,$pdf->getY()+200,'');
     	$pdf->cell(170,5,"Resultado final em $ed11_c_descr : $rfatual",0,0,"L",0);
        $pdf->cell(10,5,"","",1,"L",0);
      }
      $pdf->cell(190,9,"","",1,"L",0);
      $pdf->cell(10,5,"","",0,"L",0);
      $pdf->cell(170,5,"{$sLabelTipoAula} {$iTotalAulas}",0,0,"L",0);
      $pdf->cell(10,5,"","",1,"L",0);
      $pdf->cell(10,5,"","",0,"L",0);
      $pdf->cell(170,5,"Total de Faltas:{$iTotalFaltas}", 0,0,"L",0);
      $pdf->cell(10,1,"","",1,"L",0);

      if ($periodo[0] == "A") {

        $sSqlParecerAval = $clpareceraval->sql_query("","ed93_t_parecer as parecerconcat",
                                                     "ed93_i_codigo"," ed93_i_diarioavaliacao = $codaval");
        $rsParecerResult = $clpareceraval->sql_record($sSqlParecerAval);
        $linhas_par = $clpareceraval->numrows;
      } else {

        $sSqlParecerResult = $clparecerresult->sql_query("","ed63_t_parecer as parecerconcat",
                                                         "ed63_i_codigo"," ed63_i_diarioresultado = $codaval");
        $rsParecerResult = $clparecerresult->sql_record($sSqlParecerResult);
        $linhas_par = $clparecerresult->numrows;
      }

      $pdf->cell(190,5,"","",1,"L",0);
      $pdf->cell(5,5,"","",0,"L",0);
      $pdf->cell(180,5,$punico=="yes"?"PARECER ÚNICO":$ed232_c_descr,1,0,"L",1);
      $pdf->cell(5,5,"","",1,"L",0);
      if ($linhas_par > 0) {
        $pdf->setfillcolor(240);
        $pdf->cell(5,5,"","",0,"L",0);
        $pdf->cell(180,5,"Parecer Padronizado:",1,0,"L",1);
        $pdf->cell(5,5,"","",1,"L",0);
        $pdf->setfillcolor(223);
        if ($padraotipo == "L") {
          $pdf->cell(5,5,"","",0,"L",0);
          $pdf->cell(180,5,"Seq - Parecer => Legenda",1,0,"L",0);
          $pdf->cell(5,5,"","",1,"L",0);
        }
        $linha     = $pdf->getY();
        $cor1      = 0;
        $cor2      = 1;
        $cor       = "";
        $parpadrao = "";
        $seppadrao = "";

        for ($z = 0; $z < $linhas_par; $z++) {
          db_fieldsmemory($rsParecerResult,$z);
          if ($cor == $cor1) {
            $cor = $cor2;
          } else {
            $cor = $cor1;
          }
          $pdf->setfont('arial','',10);
          $parpadrao .= $seppadrao.$parecerconcat;
          $seppadrao = "    ";
          if ($padraotipo == "L") {

            $explode_parecer = explode("**",$parecerconcat);
            for ($b=0; $b<count($explode_parecer); $b++) {

              $pdf->cell(5,5,"","",0,"L",0);
              $pdf->cell(180,5,trim($explode_parecer[$b]),1,0,"L",0);
              $pdf->cell(5,5,"","",1,"L",0);
            }
          }
        }

        if ($padraotipo == "C") {
          $pdf->cell(5,5,"","LR",0,"",0);
          $pdf->multicell(180,5,$parpadrao, 1,"L",0,0); // observação
        }
        $pdf->line(15,$pdf->getY(),195,$pdf->getY());
        $pdf->line(15,$linha,195,$linha);
      }

      $pdf->setfillcolor(240);
      $pdf->setfont('arial','b',10);
      $pdf->cell(5,5,"","",0,"L",0);
      $pdf->cell(180,5,"Parecer Descritivo:",1,0,"L",1);
      $pdf->cell(5,5,"","",1,"L",0);
      $pdf->setfillcolor(223);

      $iPaginaAntesImpressaoObservacao = $pdf->PageNo();
      if (trim($parecer) != "" || $descr_disc == "TODAS") {

        $pdf->setfont('arial','',10);
        $pdf->cell(5,5,"","",0,"L",0);
        $pdf->multicell(180,5,"  ".trim($parecer),"LRBT","L",0,0);
      } else {

          $iYParecerVazio = $pdf->GetY();
          $sTexto = (@$ed72_t_obs!=""?@$ed72_t_obs."\n":"").mb_strtoupper(@$obs1);
          $iTotalLinhasObservacao = ($pdf->NbLines(180, $sTexto));
          $iTotalLinhasEmBranco   = 35 - $iTotalLinhasObservacao;
          for ($iContador = 0; $iContador < $iTotalLinhasEmBranco; $iContador++) {

            $pdf->SetX(14);
            $pdf->SetFont('arial', '', 10);
            $sLinhas = "";
            $pdf->SetX(15);
            $pdf->cell(180, 5, str_pad($sLinhas, 90, "_"), 0, 1);
          }

          $pdf->Rect(15, $iYParecerVazio, 180, $pdf->GetY() - $iYParecerVazio);
          $pdf->ln(3);
        }
    } else {
      $pdf->cell(190,5,"","",1,"L",0);
      $pdf->cell(10,5,"","",0,"L",0);
      $pdf->cell(170,5,"Aluno possui amparo para esta discilpina neste período",0,0,"L",0);
      $pdf->cell(10,5,"","",1,"L",0);
      $pdf->cell(10,5,"","",0,"L",0);
      if ($ed81_i_justificativa != "") {
        $pdf->cell(170,5,"Justificativa Legal: ".pg_result($result,0,'justificativa'),0,0,"L",0);
      } else {
        $pdf->cell(170,5,pg_result($result,0,'ed250_c_abrev'),0,0,"L",0);
      }
      $pdf->cell(10,5,"","",1,"L",0);
    }
  }
  if ((isset($ed72_t_obs) && @$ed72_t_obs != "") || @$obs1 != "") {

    $sTexto = (@$ed72_t_obs!=""?@$ed72_t_obs."\n":"").mb_strtoupper(@$obs1);
    quebrarLinha($pdf, ($pdf->NbLines(180, $sTexto) + 5) * 5);
    $pdf->setfont('arial','b',10);
    $pdf->setx(15);
    $pdf->cell(180,5,"Observações:",1, 1,"L",1);
    $pdf->setfont('arial','',10);
    $pdf->setx(15);
    $pdf->multicell(180,5,$sTexto,1,"L",0,0);
    $impresso = true;
  }

  quebrarLinha($pdf, 15);
  $pdf->cell(190,5,"","",1,"L",0);
  if ($assinaturaregente == "S") {

    $pdf->SetY($pdf->h - 25);
    $pdf->setfont('arial','',10);
    $pdf->cell(190,5,"__________________________________________________","",1,"C",0);
    $pdf->cell(190,5,"Professor ".$conselheiro,"",1,"C",0);
  }

  if ( !isset($iPaginaAntesImpressaoObservacao) ) {
    $iPaginaAntesImpressaoObservacao = 1;
  }
  if ($pdf->PageNo() > $iPaginaAntesImpressaoObservacao) {
    $pdf->Rect(10, 8, 190, 280);
  }
}


function quebrarLinha(FpdfMultiCellBorder $oPdf, $iTotalLinhas) {

  if ($oPdf->GetY() + $iTotalLinhas > $oPdf->h - 15) {
    $oPdf->AddPage();
  }
}

function desenharQuadroBoletim() {

  global $pdf;
  $pdf->Rect(10, 8, 190, 280);
}
$pdf->Output();
?>