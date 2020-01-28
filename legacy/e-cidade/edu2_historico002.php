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
require_once(modification("fpdf151/scpdf.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_libdocumento.php"));
require_once(modification("libs/db_libparagrafo.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("model/educacao/ArredondamentoNota.model.php"));
require_once(modification("std/DBDate.php"));

if (isset($alunos)) {
  $sAlunos = $alunos;
}

$sParamEdu              = eduparametros(db_getsession("DB_coddepto"));
$oDaoEduParametros      = new cl_edu_parametros;
$oDaoHistorico          = new cl_historico;
$oDaoAluno              = new cl_aluno;
$oDaoEscola             = new cl_escola;
$oDaoTelefoneEscola     = new cl_telefoneescola;
$oDaoAprovConselho      = new cl_aprovconselho;
$oDaoTrocaSerie         = new cl_trocaserie;
$oDaoEduRelatModel      = new cl_edu_relatmodel;
$oDaoCursoAto           = new cl_cursoato;
$iPosAssinatura         = 35;
$lExibirReclassificacao = $sExibirReclassificacao == 't' ? true : false;

/**
 * Variáveis para controle se devem ser exibidas etapas de reclassificação, de acordo com o selecionado no filtro do
 * formulário
 *
 * $sExibirReclassificacao: 't' => Exibe etapas reclassificadas
 *                          'f' => Não exibe etapas reclassificadas
 */
$sWhereExibeReclassificacaoRede = "";
$sWhereExibeReclassificacaoFora = "";

if ( !$lExibirReclassificacao ) {

  $sWhereExibeReclassificacaoRede = " AND trim(ed62_c_situacao) <> 'RECLASSIFICADO'";
  $sWhereExibeReclassificacaoFora = " AND trim(ed99_c_situacao) <> 'RECLASSIFICADO'";
}

/**
 * Função que recebe a data do banco e retorna no padrão brasileiro.
 *
 * @param date $dData
 * @return date $dData
 */
function getData($dData) {
  return substr($dData, 8, 2)."/".substr($dData, 5, 2)."/".substr($dData, 0, 4);
}

/**
 * Validação para buscar ou não etapas de cursos que ainda não foram concluídos, para o caso da impressão do Certificado
 * @return string
 */
function getWhereCursoConcluido() {

  $sWhereCursoConcluido = '';

  if ( isset($_GET['lExibirSomenteCursosConcluidos']) && $_GET['lExibirSomenteCursosConcluidos'] ) {

    $sWhereCursoConcluido .= "AND (ed61_i_anoconc IS NOT NULL ";
    $sWhereCursoConcluido .= "      OR ";
    $sWhereCursoConcluido .= "      EXISTS( SELECT 1";
    $sWhereCursoConcluido .= "                FROM cursoequivalencia";
    $sWhereCursoConcluido .= "                INNER JOIN historico AS cursobase ON cursobase.ed61_i_curso = cursoequivalencia.ed140_cursoedu";
    $sWhereCursoConcluido .= "                INNER JOIN historico AS cursoequivalente ON cursoequivalente.ed61_i_curso = cursoequivalencia.ed140_cursoequivalente";
    $sWhereCursoConcluido .= "                WHERE cursobase.ed61_i_aluno = cursoequivalente.ed61_i_aluno";
    $sWhereCursoConcluido .= "                  AND cursoequivalente.ed61_i_curso = historico.ed61_i_curso";
    $sWhereCursoConcluido .= "                  AND cursobase.ed61_i_anoconc IS NOT NULL)";
    $sWhereCursoConcluido .= "    )";
  }

  return $sWhereCursoConcluido;
}

/**
 * Validação para buscar as etapas do curso informado por parâmetro e suas equivalências,
 * para o casa da impressão do Certificado.
 * @return string
 */
function getWhereCurso() {

  $sWhereCurso = '';

  if ( !empty($_GET['iCurso']) ) {

    $iCurso = $_GET['iCurso'];

    $sWhereCurso .= "AND (historico.ed61_i_curso = {$iCurso} ";
    $sWhereCurso .= "      OR ";
    $sWhereCurso .= "      EXISTS( SELECT 1";
    $sWhereCurso .= "                FROM cursoequivalencia";
    $sWhereCurso .= "                INNER JOIN historico AS cursobase ON cursobase.ed61_i_curso = cursoequivalencia.ed140_cursoedu";
    $sWhereCurso .= "                INNER JOIN historico AS cursoequivalente ON cursoequivalente.ed61_i_curso = cursoequivalencia.ed140_cursoequivalente";
    $sWhereCurso .= "                WHERE cursobase.ed61_i_aluno = cursoequivalente.ed61_i_aluno";
    $sWhereCurso .= "                  AND cursoequivalente.ed61_i_curso = historico.ed61_i_curso";
    $sWhereCurso .= "                  AND cursobase.ed61_i_curso = {$iCurso})";
    $sWhereCurso .= "    )";
  }

  return $sWhereCurso;
}


/**
 * Função responsável por montar o cabeçalho do Histórico Escolar.
 * Esta função executa automaticamente as funcções montaTopoDisciplinas e montaRodape
 *
 * @author Thiago A. de Lima - thiago.lima@dbseller.com.br
 *
 * @param object $oPdf
 * @param object $oDadosRelatModel
 * @param object $oDadosEscola
 * @param object $oDadosAluno
 * @param object $oDadosHist
 * @param integer $iEscola
 * @param integer $iCodigoCurso
 * @param integer $sTelefoneEscola
 * @param integer $iCodigoHist
 * @param string $sCondicaoHistMps
 * @param string $sCondicaoHistMpsFora
 */
function montaCabecalho($oPdf, $oDadosRelatModel, $oDadosEscola, $oDadosAluno, $oDadosHist, $iEscola,
                        $iCodigoCurso, $sTelefoneEscola, $iCodigoHist, $sCondicaoHistMps, $sCondicaoHistMpsFora,
                        $lExibirReclassificacao) {

  $iInstituicao = db_getsession( "DB_instit" );
  $sImagem      = RelatorioHistoricoEscolar::getBrasao( $oDadosRelatModel->ed217_brasao, new Instituicao( $iInstituicao ) );

  $oPdf->SetFillColor(223);
  $oPdf->AddPage('L');
  $oPdf->Image($sImagem, 10, 10, 25, 25);
  $oPdf->SetFont('Arial', 'b', 8);
  $oPdf->SetX(35);
  $oPdf->MultiCell(140, 4, $oDadosRelatModel->ed217_t_cabecalho, 0, "C", 0, 0);
  $oPdf->SetXY(175, 10);

  /* Busca o nome da Instituição que está logada */
  $oDaoInstituicao = new cl_db_config();
  $sSqlInstituicao = $oDaoInstituicao->sql_query("", " nomeinst ", "", " codigo = ".db_getsession("DB_instit"));
  $rsInstituicao   = $oDaoInstituicao->sql_record($sSqlInstituicao);
  $sInstituicao    = db_utils::fieldsmemory($rsInstituicao, 0)->nomeinst;

  /* Busca o Ato Legal do Curso */

  $sAtoLegal = retornaStringAtosLegais(AlunoRepository::getAlunoByCodigo($oDadosAluno->ed47_i_codigo),
                                       EscolaRepository::getEscolaByCodigo($iEscola));

  /**
   * Verifica se escola possui código referênte e o adiciona na frente do nome.
   */
  $sNomeEscola = $oDadosEscola->nome_escola;
  if (  $oDadosEscola->ed18_codigoreferencia != null ) {
    $sNomeEscola = "{$oDadosEscola->ed18_codigoreferencia} - {$sNomeEscola}";
  }

  $sCabecalhoEscola  = $sNomeEscola." \n";
  $sCabecalhoEscola .= "Mantenedora: ".$sInstituicao." \n";
  $sCabecalhoEscola .= "Endereço: ".$oDadosEscola->rua_escola.", ".$oDadosEscola->num_escola." \n";
  $sCabecalhoEscola .= "CEP: ".$oDadosEscola->cep_escola." - ".$oDadosEscola->mun_escola." / ";
  $sCabecalhoEscola .= $oDadosEscola->uf_escola." ".$sTelefoneEscola;

  $oPdf->MultiCell(110, 3, $sCabecalhoEscola, 0, "L", 0, 0);
  $oPdf->SetX(175);
  $oPdf->MultiCell(110, 2, "", "", "L", 0, 0);
  $oPdf->SetX(175);
  $oPdf->SetFont('Arial', 'b', 6);
  $oPdf->MultiCell(110, 2, $sAtoLegal, "", "L", 0, 0);
  $oPdf->SetY(36);
  $oPdf->SetFont('Arial', 'b', 7);
  $oPdf->Cell(10, 4, "Nome: ", 0, 0, "L", 0);
  $oPdf->SetFont('Arial', 'b', 9);
  $oPdf->Cell(155, 4, $oDadosAluno->ed47_v_nome, 0, 0, "L", 0);
  $oPdf->SetFont('Arial', 'b', 7);

  $aNacionalidade = array(
                          "1" => "BRASILEIRO",
                          "2" => "BRASILEIRO NASCIDO NO EXTERIOR OU NATURALIZADO",
                          "3" => "ESTRANGEIRO"
                         );

  $oPdf->Cell(20, 4, "Nacionalidade: ", 0, 0, "L", 0);
  $oPdf->Cell(111, 4, $aNacionalidade[$oDadosAluno->ed47_i_nacion], 0, 1, "L", 0);
  $oPdf->Cell(15, 4, "Filho(a) de: ", 0, 0, "L", 0);

  if (trim($oDadosAluno->ed47_v_pai) == "" && trim($oDadosAluno->ed47_v_mae) == "") {
    $sFiliacao = "";
  } elseif (trim($oDadosAluno->ed47_v_pai) == "" && trim($oDadosAluno->ed47_v_mae) != "") {
    $sFiliacao = $oDadosAluno->ed47_v_mae;
  } elseif (trim($oDadosAluno->ed47_v_pai) != "" && trim($oDadosAluno->ed47_v_mae) == "") {
    $sFiliacao = $oDadosAluno->ed47_v_pai;
  } elseif (trim($oDadosAluno->ed47_v_pai) !== "" && trim($oDadosAluno->ed47_v_mae) != "") {
    $sFiliacao = "$oDadosAluno->ed47_v_pai e de $oDadosAluno->ed47_v_mae";
  }

  $sLocalNascimento = "";

  if ( $oDadosAluno->ed47_i_nacion == 1 ) {
    $sLocalNascimento .= " em " . $oDadosAluno->municnat." / ".$oDadosAluno->ufnat;
  }

  $oPdf->Cell(150, 4, $sFiliacao, 0, 0, "L", 0);
  $oPdf->Cell(20, 4, "Nascido(a) em: ", 0, 0, "L", 0);
  $oPdf->Cell(111, 4, db_formatar($oDadosAluno->ed47_d_nasc, 'd') . $sLocalNascimento, 0, 1, "L", 0);

  montaTopoDisciplinas($oPdf);
  montaRodape($oPdf, $oDadosRelatModel, $oDadosAluno, $oDadosHist, $iCodigoHist,
              $sCondicaoHistMps, $sCondicaoHistMpsFora, $oDadosEscola, $lExibirReclassificacao);
}

/**
 * Função para montar a topo das disciplinas, monta as 3 (três) colunas.
 * @param object $oPdf
 */
function montaTopoDisciplinas($oPdf) {

  $oPdf->SetY(44);
  $oPdf->SetFont('Arial', 'b', 7);

  for ($iCont = 0; $iCont < 3; $iCont++) {

    $oPdf->Cell(34, 4, "Disciplina", 1, 0, "C", 0);
    $oPdf->Cell(11, 4, "Etapa"     , 1, 0, "C", 0);
    $oPdf->Cell(10, 4, "Ap."       , 1, 0, "C", 0);
    $oPdf->Cell(10, 4, "CH/DL"     , 1, 0, "C", 0);
    $oPdf->Cell( 8, 4, "RF"        , 1, 0, "C", 0);
    $oPdf->Cell( 9, 4, "PE"        , 1, 0, "C", 0);
    $oPdf->Cell(10, 4, "ESC"       , 1, 0, "C", 0);
  }

  $oPdf->SetXY(10, 48);

  for ($iContMaster = 0; $iContMaster < 18; $iContMaster++) {

    for ($iContLinha = 0; $iContLinha < 3; $iContLinha++) {

      $oPdf->Cell(34, 4, "", "LR", 0, "C", 0);
      $oPdf->Cell(11, 4, "", "LR", 0, "C", 0);
      $oPdf->Cell(10, 4, "", "LR", 0, "C", 0);
      $oPdf->Cell(10, 4, "", "LR", 0, "C", 0);
      $oPdf->Cell( 8, 4, "", "LR", 0, "C", 0);
      $oPdf->Cell( 9, 4, "", "LR", 0, "C", 0);
      $oPdf->Cell(10, 4, "", "LR", ($iContLinha == 2 ? 1 : 0), "C", 0);
    }
  }

  $oPdf->line(10, 48, 10, 126);
  $oPdf->line(44, 48, 44, 126);
  $oPdf->line(55, 48, 55, 126);
  $oPdf->line(65, 48, 65, 126);
  $oPdf->line(75, 48, 75, 126);
  $oPdf->line(83, 48, 83, 126);
  $oPdf->line(92, 48, 92, 126);
  $oPdf->line(102, 48, 102, 126);

  $oPdf->line(136, 48, 136, 126);
  $oPdf->line(147, 48, 147, 126);
  $oPdf->line(157, 48, 157, 126);
  $oPdf->line(167, 48, 167, 126);
  $oPdf->line(175, 48, 175, 126);
  $oPdf->line(184, 48, 184, 126);
  $oPdf->line(194, 48, 194, 126);

  $oPdf->line(228, 48, 228, 126);
  $oPdf->line(239, 48, 239, 126);
  $oPdf->line(249, 48, 249, 126);
  $oPdf->line(259, 48, 259, 126);
  $oPdf->line(267, 48, 267, 126);
  $oPdf->line(276, 48, 276, 126);
  $oPdf->line(286, 48, 286, 126);
}

/**
 * Função para montar a parte inferior do Histórico Escola
 * @param PDF $oPdf
 * @param object $oDadosRelatModel
 * @param object $oDadosAluno
 * @param object $oDadosHist
 * @param integer $iCodigoHist
 * @param string $sCondicaoHistMps
 * @param string $sCondicaoHistMpsFora
 * @param object $oDadosEscola
 */
function montaRodape($oPdf, $oDadosRelatModel, $oDadosAluno, $oDadosHist, $iCodigoHist,
                     $sCondicaoHistMps, $sCondicaoHistMpsFora, $oDadosEscola, $lExibirReclassificacao) {

  $sConvencoes  = "";

  if ($_GET['sDiretor'] != "") {

    $aDiretor       = explode("-", $_GET['sDiretor']);
    $sNomeDiretor   = $aDiretor[1];
    $sFuncaoDiretor = $aDiretor[0].(trim($aDiretor[2]) != "" ? " ($aDiretor[2])" : "");
  } else {

    $sNomeDiretor   = "Diretor(a)";
    $sFuncaoDiretor = "";
  }

  if ($_GET['sSecretario'] != "") {

    $aSecretario       = explode("-", $_GET['sSecretario']);
    $sNomeSecretario   = $aSecretario[1];
    $sFuncaoSecretario = $aSecretario[0].(trim($aSecretario[2]) != "" ? " ($aSecretario[2])" : "");
  } else {

    $sNomeSecretario   = "Secretário(a)";
    $sFuncaoSecretario = "";
  }

  $iLarguraColunaFaltaTurma = 10;
  $iLarguraColunaEscola     = 105;
  $iLarguraColunaCidade     = 66;

  if ($oDadosRelatModel->ed217_exibeturma == 't') {
    $iLarguraColunaEscola -= 10;
  }

  if ($oDadosRelatModel->ed217_exibecargahoraria == 't') {
    $iLarguraColunaCidade -= 10;
  }

  $oPdf->SetY($oPdf->GetY() + 6);
  $oPdf->SetFont('Arial', '', 6);
  $oPdf->Cell( 20, 3, "Etapa"    , 1, 0, "C", 0);
  $oPdf->Cell( 15, 3, "Ano"      , 1, 0, "C", 0);
  $oPdf->Cell( 15, 3, "Dias"     , 1, 0, "C", 0);

  if ($oDadosRelatModel->ed217_exibeturma == 't') {
    $oPdf->Cell( $iLarguraColunaFaltaTurma, 3, "Turma"     , 1, 0, "C", 0);
  }
  $oPdf->Cell( 15, 3, "CH"       , 1, 0, "C", 0);
  if ($oDadosRelatModel->ed217_exibecargahoraria == 't') {
    $oPdf->Cell( $iLarguraColunaFaltaTurma, 3, "Freq."       , 1, 0, "C", 0);
  }
  $oPdf->Cell( 15,                   3, "Resultado", 1, 0, "C", 0);
  $oPdf->Cell( 20,                   3, "Mínimo"   , 1, 0, "C", 0);
  $oPdf->Cell($iLarguraColunaEscola, 3, "Escola"   , 1, 0, "C", 0);
  $oPdf->Cell($iLarguraColunaCidade, 3, "Cidade"   , 1, 0, "C", 0);
  $oPdf->Cell(  5,                   3, "UF"       , 1, 1, "C", 0);

  $sWhereCursoConcluido = getWhereCursoConcluido();
  $sWhereCurso          = getWhereCurso();

  $sSqlSerie  = " SELECT * FROM (";
  $sSqlSerie .= "                 SELECT ed11_i_sequencia, ed11_c_abrev, ed62_i_anoref, ed62_i_qtdch";
  $sSqlSerie .= "                       , ed62_i_diasletivos, ed62_c_resultadofinal, ed62_i_escola, ed62_i_turma";
  $sSqlSerie .= "                       , ed260_c_sigla, ed261_c_nome, ed18_i_codigo, ed18_c_nome, ed62_c_minimo";
  $sSqlSerie .= "                       , ed11_i_codigo, ed62_c_termofinal as termofinaletapa";
  $sSqlSerie .= "                       , ed62_lancamentoautomatico, ed62_c_situacao as situacao ";
  $sSqlSerie .= "                       , ed62_i_turma as turma, ed62_percentualfrequencia as percentual_frequencia ";
  $sSqlSerie .= "                  FROM historicomps ";
  $sSqlSerie .= "                       inner join serie      ON ed11_i_codigo             = ed62_i_serie ";
  $sSqlSerie .= "                       inner join escola     ON escola.ed18_i_codigo      = historicomps.ed62_i_escola";
  $sSqlSerie .= "                       inner join censouf    ON censouf.ed260_i_codigo    = escola.ed18_i_censouf ";
  $sSqlSerie .= "                       inner join censomunic ON censomunic.ed261_i_codigo = escola.ed18_i_censomunic ";
  $sSqlSerie .= "                       inner join historico  ON  historico.ed61_i_codigo  = historicomps.ed62_i_historico ";
  $sSqlSerie .= "                 WHERE ed62_i_historico IN ({$iCodigoHist}) {$sWhereCursoConcluido} {$sWhereCurso} {$sCondicaoHistMps}";
  $sSqlSerie .= "                 UNION";
  $sSqlSerie .= "                 SELECT ed11_i_sequencia, ed11_c_abrev, ed99_i_anoref as ed62_i_anoref";
  $sSqlSerie .= "                       , ed99_i_qtdch as ed62_i_qtdch, ed99_i_diasletivos as ed62_i_diasletivos";
  $sSqlSerie .= "                       , ed99_c_resultadofinal as ed62_c_resultadofinal";
  $sSqlSerie .= "                       , ed99_i_escolaproc as ed62_i_escola, ed99_c_turma as ed62_i_turma";
  $sSqlSerie .= "                       , ed260_c_sigla, ed261_c_nome, ed82_i_codigo,  ed82_c_nome, ed99_c_minimo";
  $sSqlSerie .= "                       , ed11_i_codigo, ed99_c_termofinal as termofinaletapa";
  $sSqlSerie .= "                       , 'f' as ed62_lancamentoautomatico, ed99_c_situacao as situacao";
  $sSqlSerie .= "                       , ed99_c_turma as turma, null as percentual_frequencia";
  $sSqlSerie .= "                  FROM historicompsfora ";
  $sSqlSerie .= "                       inner join serie      ON ed11_i_codigo              = ed99_i_serie ";
  $sSqlSerie .= "                       inner join escolaproc ON  ed82_i_codigo             = ed99_i_escolaproc ";
  $sSqlSerie .= "                       inner join historico  ON  historico.ed61_i_codigo   = historicompsfora.ed99_i_historico  ";
  $sSqlSerie .= "                       left join censouf     ON  censouf.ed260_i_codigo    = escolaproc.ed82_i_censouf ";
  $sSqlSerie .= "                       left join censomunic  ON  censomunic.ed261_i_codigo = escolaproc.ed82_i_censomunic ";
  $sSqlSerie .= "                 WHERE ed99_i_historico IN ({$iCodigoHist}) {$sWhereCursoConcluido} {$sCondicaoHistMpsFora}";
  $sSqlSerie .= "               ) AS X ";
  $sSqlSerie .= "    ORDER BY ed62_i_anoref, ed11_c_abrev ASC ";

  $rsSerie      = db_query($sSqlSerie);
  $iLinhasSerie = pg_num_rows($rsSerie);
  $iContSerie   = 0;

  $aSeries       = array();
  $aRetornoSerie = db_utils::getCollectionByRecord($rsSerie);

  foreach ( $aRetornoSerie as $oRetornoSerie ) {
    $aSeries[ $oRetornoSerie->ed11_i_sequencia ] = $oRetornoSerie->ed62_i_anoref;
  }

  $lPossuiAprovacaoComProgressao = false;
  $oAluno                        = new Aluno($oDadosAluno->ed47_i_codigo);
  $aMatriculas                   = MatriculaRepository::getTodasMatriculasAluno( $oAluno );
  $aProgressoesEncerradas        = array();

  for ($iContRodape = 0; $iContRodape < $iLinhasSerie; $iContRodape++) {

    $oDadosSerie = db_utils::fieldsmemory($rsSerie, $iContRodape);

    if (    (!$lExibirReclassificacao && trim($oDadosSerie->situacao) == 'RECLASSIFICADO')
         && array_key_exists( $oDadosSerie->ed11_i_sequencia, $aSeries )
         && $oDadosSerie->ed62_i_anoref != $aSeries[ $oDadosSerie->ed11_i_sequencia ] ) {
      continue;
    }

    switch (trim($oDadosSerie->ed62_c_resultadofinal)) {

      case 'A':

        $sSituacaoFinal = 'APR';
        break;

      case 'D':

        $lPossuiAprovacaoComProgressao = true;
        $sSituacaoFinal                = 'AP/DP';
        break;

      case 'R':

        $sSituacaoFinal = 'REP';
        break;

      default:

        $sSituacaoFinal = 'REP';
        break;
    }

    /**
     * Caso tenha sido informado um termo final, este substituira o resultado final
     */
    if (!empty($oDadosSerie->termofinaletapa)) {
      $sSituacaoFinal = $oDadosSerie->termofinaletapa;
    }

    /**
     * Caso o histórico tenha sido lancado como transferencia, o resultado recebe TR
     */
    if (!empty($oDadosSerie->situacao) && $oDadosSerie->situacao == 'TRANSFERIDO') {
      $sSituacaoFinal = 'TR';
    }

    /**
     * Situações que devem ser apresentadas no Resultado
     */
    $aSituacoes = array(
                         'AVANÇADO'  => 'AVAN',
                         'CANCELADO' => 'CANC',
                         'EVADIDO'   => 'EVAD',
                         'FALECIDO'  => 'FALEC',
                       );

    if( array_key_exists( $oDadosSerie->situacao, $aSituacoes ) ) {
      $sSituacaoFinal = $aSituacoes[ $oDadosSerie->situacao ];
    }

    $oPdf->SetFont('Arial', '', 6);
    $oPdf->Cell(20, 3, $oDadosSerie->ed11_c_abrev, "LR", 0, "C", 0);
    $oPdf->Cell(15, 3, $oDadosSerie->ed62_i_anoref, "LR", 0, "C", 0);
    $oPdf->Cell(15, 3, $oDadosSerie->ed62_i_diasletivos, "LR", 0, "C", 0);

    if ($oDadosRelatModel->ed217_exibeturma == 't') {
      $oPdf->Cell( $iLarguraColunaFaltaTurma, 3, substr($oDadosSerie->turma, 0, 8), 0, 0, "C", 0);
    }

    $nCargaHoraria = $oDadosSerie->ed62_i_qtdch;
    $nCargaHoraria = (int)trim($nCargaHoraria);

    $oPdf->Cell(15, 3, $nCargaHoraria, "LR", 0, "C", 0);

    if ($oDadosRelatModel->ed217_exibecargahoraria == 't') {

      /**
       * Quando a etapa que está sendo percorrida é igual há alguma etapa das matrículas do aluno:
       * Verifica qual é a forma de calculo da frequência (Por Disciplina/ Carga Horária Total) e se existe ao menos
       * alguma disciplina do diário que possui Reclassifacação por Baixa Frequência e substitui o percentual de
       * frequência por '--'.
       */
      if ( $oDadosSerie->ed62_lancamentoautomatico == 't') {

        foreach ( $aMatriculas as $oMatricula ) {

          if(    $oMatricula->getEtapaDeOrigem()->getCodigo() == $oDadosSerie->ed11_i_codigo
              && $oMatricula->getTurma()->getCalendario()->getAnoExecucao() == $oDadosSerie->ed62_i_anoref
            ) {

            db_inicio_transacao();
            $oDiarioClasse = $oMatricula->getDiarioDeClasse();
            db_fim_transacao();

            $iFormaCalculo                  = $oDiarioClasse->getProcedimentoDeAvaliacao()->getFormaCalculoFrequencia();
            $lReclassificadoBaixaFrequencia = $oDiarioClasse->reclassificadoPorBaixaFrequencia();

            if ( $iFormaCalculo == 2 && $lReclassificadoBaixaFrequencia ) {
              $oDadosSerie->percentual_frequencia = '--';
            }
          }
        }
      }

      $oPdf->Cell( $iLarguraColunaFaltaTurma, 3, $oDadosSerie->percentual_frequencia , 0, 0, "C", 0);
    }

    $oPdf->Cell(15, 3, $sSituacaoFinal, "LR", 0, "C", 0);
    $oPdf->Cell(20, 3, $oDadosSerie->ed62_c_minimo, "LR", 0, "C", 0);
    $oPdf->Cell($iLarguraColunaEscola, 3, $oDadosSerie->ed18_i_codigo." - ".$oDadosSerie->ed18_c_nome, "LR", 0, "L", 0);
    $oPdf->Cell($iLarguraColunaCidade, 3, $oDadosSerie->ed261_c_nome , "LR", 0, "L", 0);
    $oPdf->Cell(5, 3, $oDadosSerie->ed260_c_sigla, "LR", 1, "L", 0);

    $iContSerie++;
    $oEtapa                = EtapaRepository::getEtapaByCodigo($oDadosSerie->ed11_i_codigo);
    $aProgressoesAprovadas = array();
    $aProgressoesAprovadas = ProgressaoParcialAlunoRepository::getProgressoesAprovadasNaEtapa($oAluno, $oEtapa);

    $oDadosProgressao = new stdClass();

    foreach ($aProgressoesAprovadas as $oProgressao) {

      if ($oProgressao->getTipoConclusao() == 2) {
        $aProgressoesEncerradas[$oEtapa->getCodigo()][2][] = $oProgressao->getDisciplina()->getNomeDisciplina();
      } else {

        $oDadosProgressao->disciplinas[] = $oProgressao->getDisciplina()->getNomeDisciplina();
        $oDadosProgressao->nota[]        = $oProgressao->getResultadoFinal()->getNota();
        $aProgressoesEncerradas[$oEtapa->getCodigo()][1] = $oDadosProgressao;
      }
    }
  }

  $iEscola              = db_getsession("DB_coddepto");
  $oParametroProgressao = ProgressaoParcialParametroRepository::getProgressaoParcialParametroByCodigo($iEscola);

  $sTextoPadraoProgressaoAmparada = $oParametroProgressao->getJustificativa();
  $sTextoProgressaoAmparada       = '';

  foreach ($aProgressoesEncerradas as $iEtapa => $aProgressao) {

    $oEtapa = EtapaRepository::getEtapaByCodigo($iEtapa);
    if (key($aProgressao) == 2) {

      $sTextoProgressaoAmparada .= "\nA(s) Disciplina(s) ".implode(", ", $aProgressao[2]) ." na {$oEtapa->getNome()} ";
      $sTextoProgressaoAmparada .= "foram {$sTextoPadraoProgressaoAmparada}.";
    } else {

      $sTextoProgressaoAmparada .= "\nAluno Cursou dependência em ".implode(", ", $aProgressao[1]->disciplinas)." e ";
      $sTextoProgressaoAmparada .= "foi aprovado com a média ".implode(", ", $aProgressao[1]->nota). " no(a) ";
      $sTextoProgressaoAmparada .= "{$oEtapa->getNome()} de escolaridade.";
    }
  }

  $iLimiteObs = 1500 - ($iLinhasSerie * 100);
  $oPdf->SetFont('Arial', 'b', 6);
  $iPosY = $oPdf->GetY();
  $oPdf->Multicell(138, 3, substr($oDadosRelatModel->ed217_t_rodape, 0, $iLimiteObs).$sTextoProgressaoAmparada, "LR", "L", 0, 0);

  if ($lPossuiAprovacaoComProgressao) {
    $sConvencoes .= "AP/DP = Aprovado com progressão parcial / Dependência\n";
  }

  $sCamposAprovCons  = " cgmcgm.z01_nome, ed253_i_data, ed232_c_descrcompleta as disc_conselho, ed253_t_obs,";
  $sCamposAprovCons .= " ed47_v_nome,ed11_c_descr as serie_conselho, ed59_i_ordenacao, ed52_i_ano, ed253_aprovconselhotipo, ";
  $sCamposAprovCons .=" ed253_alterarnotafinal, ed253_avaliacaoconselho ";
  $sWhereAprovCons   = " ed95_i_aluno = ".$oDadosHist->ed61_i_aluno." AND ed31_i_curso = ".$oDadosHist->ed61_i_curso;
  $oDaoAprovConselho = new cl_aprovconselho();
  $sSqlAprovCons     = $oDaoAprovConselho->sql_query("", $sCamposAprovCons, "ed59_i_ordenacao", $sWhereAprovCons);
  $rsAprovConselho   = $oDaoAprovConselho->sql_record($sSqlAprovCons);

  $iLinhasAprovCons         = $oDaoAprovConselho->numrows;
  $aObservacaoConselho      = array();
  $aAprovadoBaixaFrequencia = array();

  if ($iLinhasAprovCons > 0) {

    for ($iContObs = 0; $iContObs < $iLinhasAprovCons; $iContObs++) {

      $oDadosAprovConselho = db_utils::fieldsmemory($rsAprovConselho, $iContObs);

      switch ($oDadosAprovConselho->ed253_aprovconselhotipo) {

        case 1:

          $oDocumento                = new libdocumento( 5013 );
          $oDocumento->disciplina    = $oDadosAprovConselho->disc_conselho;
          $oDocumento->etapa         = $oDadosAprovConselho->serie_conselho;
          $oDocumento->justificativa = $oDadosAprovConselho->ed253_t_obs;
          $oDocumento->nota          = ArredondamentoNota::arredondar($oDadosAprovConselho->ed253_avaliacaoconselho, $oDadosAprovConselho->ed52_i_ano);
          $oDocumento->anomatricula  = $oDadosAprovConselho->ed52_i_ano;

          $oObservacao              = new stdClass();
          $oObservacao->aParagrafos = $oDocumento->getDocParagrafos();

          if( trim( $oObservacao->aParagrafos[1]->oParag->db02_texto ) ) {
            $aObservacaoConselho[] = "- ".$oObservacao->aParagrafos[1]->oParag->db02_texto;
          }
          break;

        case 2:

          $sHashSerieAno = $oDadosAprovConselho->serie_conselho.$oDadosAprovConselho->ed52_i_ano;
          if (!isset($aAprovadoBaixaFrequencia[$sHashSerieAno])) {
            $aAprovadoBaixaFrequencia[$sHashSerieAno] = $oDadosAprovConselho;
          }
          continue;
        break;

        case 3:

          $sTipoAprovacao = " foi aprovado pelo regimento escolar. ";
          $sObsConselho   = "- Disciplina ".$oDadosAprovConselho->disc_conselho." na Etapa ";
          $sObsConselho  .= $oDadosAprovConselho->serie_conselho.$sTipoAprovacao;
          $sObsConselho  .= "Justificativa: ".$oDadosAprovConselho->ed253_t_obs;

          $aObservacaoConselho[] = $sObsConselho;
          break;
      }
    }
  }

  if (count($aAprovadoBaixaFrequencia) > 0) {

    $oDocumento             = new libdocumento(5005);

    foreach ($aAprovadoBaixaFrequencia as $oBaixaFrequencia) {

      $oDocumento->nome_aluno = $oDadosHist->ed47_v_nome;
      $oDocumento->ano        = $oBaixaFrequencia->ed52_i_ano;
      $oDocumento->nome_etapa = $oBaixaFrequencia->serie_conselho;
      $aParagrafos            = $oDocumento->getDocParagrafos();

      if (isset($aParagrafos[1])) {
        $aObservacaoConselho[] = $aParagrafos[1]->oParag->db02_texto;
      }
    }
  }

  $sObsConselho = implode("\n", $aObservacaoConselho);

  $sObsProg       = "";
  $sSepProg       = "";
  $sCamposProg    = " distinct trocaserie.ed101_i_codigo, serieorig.ed11_c_descr as ed11_c_origem, ";
  $sCamposProg   .= " seriedest.ed11_c_descr as ed11_c_destino, trocaserie.ed101_d_data,trocaserie.ed101_c_tipo ";
  $sWhereProg     = " ed101_i_aluno = ".$oDadosHist->ed61_i_aluno;
  $oDaoTrocaSerie = new cl_trocaserie();
  $sSqlProg       = $oDaoTrocaSerie->sql_query_certificado_conclusao("", $sCamposProg, "ed101_d_data", $sWhereProg);
  $rsProg         = $oDaoTrocaSerie->sql_record($sSqlProg);
  $iLinhasProg    = $oDaoTrocaSerie->numrows;

  if ($iLinhasProg > 0) {

    for ($iContProg = 0; $iContProg < $iLinhasProg; $iContProg++) {

      $oDadosProg = db_utils::fieldsmemory($rsProg, $iContProg);

      if ( !$lExibirReclassificacao && $oDadosProg->ed101_c_tipo == "R" ) {
        continue;
      }

      $sObsProg .= $sSepProg."- ".($oDadosProg->ed101_c_tipo == "A" ? "AVANÇADO" : "RECLASSIFICADO")."(A) DA ETAPA ";
      $sObsProg .= (trim($oDadosProg->ed11_c_origem))." PARA ETAPA ".(trim($oDadosProg->ed11_c_destino))." EM ";
      $sObsProg .= getData($oDadosProg->ed101_d_data).", CONFORME LEI FEDERAL Nº 9394/96 - ARTIGO 23, §1º, ";
      $sObsProg .= "PARECER CEED N° 740/99 E REGIMENTO ESCOLAR";

      $sSepProg  = "\n";
    }
  }

  $sObsHist      = "";
  $sSepHist      = "";
  $sCamposObs    = " ed61_t_obs ";
  $sWhereObs     = " ed61_i_aluno IN (".$oDadosAluno->ed47_i_codigo.") ";
  $oDaoHistorico = new cl_historico();
  $sSqlObs       = $oDaoHistorico->sql_query("", $sCamposObs, "", $sWhereObs);
  $rsObs         = $oDaoHistorico->sql_record($sSqlObs);
  $iLinhasObs    = $oDaoHistorico->numrows;

  if ($iLinhasObs > 0) {

    for ($iContHist = 0; $iContHist < $iLinhasObs; $iContHist++) {

      $oDadosObs = db_utils::fieldsmemory($rsObs, $iContHist);
      $sObsHist .= $sSepHist."".$oDadosObs->ed61_t_obs;
    }
  }

  $oPdf->SetXY(148, $iPosY);

  $sConteudoMulticell  = $sConvencoes.(!empty($sObsHist) ? $sObsHist."\n" : "");
  $sConteudoMulticell .= (!empty($oDadosRelatModel->ed217_t_obs) ? $oDadosRelatModel->ed217_t_obs."\n" : "");
  $sConteudoMulticell .= (!empty($sObsProg) ? $sObsProg."\n" : "");
  $sConteudoMulticell .= (!empty($sObsConselho) ? $sObsConselho."\n" : "");

  $oPdf->Multicell(138, 3, substr($sConteudoMulticell, 0, $iLimiteObs), "LR", "J", 0, 0);

  $oPdf->Rect(10, $iPosY, 138, (170 - $iPosY +10 ));
  $oPdf->Rect(148, $iPosY, 138, (170 - $iPosY + 10));
  $oPdf->SetXY(10, $oPdf->h - 20);
  $oPdf->SetFont('Arial', 'b', 6);

  $sTextCell  = $oDadosEscola->mun_escola.", ".date("d",db_getsession("DB_datausu"))." de ";
  $sTextCell .= db_mes(date("m", db_getsession("DB_datausu")), 1)." de ".date("Y",db_getsession("DB_datausu")).".";

  $oPdf->Cell( 72, 5, $sTextCell                                                              , 0, 0, "L", 0);
  $oPdf->Cell(102, 5, "______________________________________________________________________", 0, 0, "C", 0);
  $oPdf->Cell(102, 5, "______________________________________________________________________", 0, 1, "C", 0);
  $oPdf->Cell( 72, 5, "", 0, 0, "L", 0);

  $oPdf->Cell(102, 5, $sNomeSecretario." - ".$sFuncaoSecretario, 0, 0, "C", 0);
  $oPdf->Cell(102, 5, $sNomeDiretor." - ".$sFuncaoDiretor, 0, 0, "C", 0);

  $oPdf->Line(10, 48, 286, 48);
  $oPdf->setY(175);
}

$sCamposTelefone    = " ed26_i_ddd, ed26_i_numero, ed26_i_ramal";
$sSqlTelefoneEscola = $oDaoTelefoneEscola->sql_query("", $sCamposTelefone, "", "ed26_i_escola = $iEscola LIMIT 1");
$rsTelefoneEscola   = $oDaoTelefoneEscola->sql_record($sSqlTelefoneEscola);

if ($oDaoTelefoneEscola->numrows > 0) {

  db_fieldsmemory($rsTelefoneEscola, 0);
  $sTelefoneEscola = "- Fone: ($ed26_i_ddd) $ed26_i_numero ".($ed26_i_ramal!=""?" Ramal: $ed26_i_ramal":"");
} else {
  $sTelefoneEscola = "";
}

$sCamposEscola  = "ed18_c_nome as nome_escola, j14_nome as rua_escola, ed18_c_cep as cep_escola, ed18_codigoreferencia, ";
$sCamposEscola .= "ed18_i_numero as num_escola, ed261_c_nome as mun_escola, ed260_c_sigla as uf_escola";
$sSqlEscola     = $oDaoEscola->sql_query("", $sCamposEscola, "", "ed18_i_codigo = $iEscola");
$rsEscola       = $oDaoEscola->sql_record($sSqlEscola);
$oDadosEscola   = db_utils::fieldsmemory($rsEscola, 0);

$sCamposRelatModel  = "ed217_t_cabecalho, ed217_t_rodape, ed217_t_obs, ed217_exibeturma, ed217_exibecargahoraria";
$sCamposRelatModel .= ", ed217_brasao";
$sSqlEduRelatModel  = $oDaoEduRelatModel->sql_query("", $sCamposRelatModel, "", "ed217_i_codigo = $iTipoRelatorio");
$rsEduRelatModel    = $oDaoEduRelatModel->sql_record($sSqlEduRelatModel);

if ($oDaoEduRelatModel->numrows > 0) {
  $oDadosRelatModel = db_utils::fieldsmemory($rsEduRelatModel, 0);
}

$sCamposAluno  = " aluno.*, censoufident.ed260_c_sigla as ufident, censoufnat.ed260_c_sigla as ufnat,  ";
$sCamposAluno .= " censoufcert.ed260_c_sigla as ufcert, censoufend.ed260_c_sigla as ufend,  ";
$sCamposAluno .= " censomunicnat.ed261_c_nome as municnat, censomuniccert.ed261_c_nome as municcert,  ";
$sCamposAluno .= " censomunicend.ed261_c_nome as municend,  censoorgemissrg.ed132_c_descr as orgemissrg ";
$sSqlAluno     = $oDaoAluno->sql_query("", "$sCamposAluno", "ed47_v_nome", " ed47_i_codigo IN ($sAlunos)");
$rsAluno       = $oDaoAluno->sql_record($sSqlAluno);
$iLinhasAluno  = $oDaoAluno->numrows;

if ($iLinhasAluno == 0) {

  echo " <table width='100%'> ";
  echo "   <tr> ";
  echo "     <td align='center'> ";
  echo "       <font color='#FF0000' face='arial'> ";
  echo "         <b>Nenhum histórico para o(s) aluno(s) selecionados<br> ";
  echo "         <input type='button' value='Fechar' onclick='window.close()'></b> ";
  echo "       </font> ";
  echo "     </td> ";
  echo "   </tr> ";
  echo " </table> ";
  exit;
}

$oPdf = new FPDF();
$oPdf->Open();
$oPdf->AliasNbPages();
$oPdf->SetAutoPageBreak(false);

for ($iContPrincipal = 0; $iContPrincipal < $iLinhasAluno; $iContPrincipal++) {

  $oDadosAluno      = db_utils::fieldsmemory($rsAluno, $iContPrincipal);

  $sWhereHistorico  = " ed61_i_aluno in (".$oDadosAluno->ed47_i_codigo.") ";
  $sSqlHistorico    = $oDaoHistorico->sql_query("", "*", " ed47_v_nome ", $sWhereHistorico);
  $rsHistorico      = $oDaoHistorico->sql_record($sSqlHistorico);
  $iLinhasHistorico = $oDaoHistorico->numrows;

  $iCodigoHist      = "";
  $iCodigoCurso     = "";
  $sSeparador       = "";

  for ($iContHist = 0; $iContHist < $iLinhasHistorico; $iContHist++) {

    $oDadosHist    = db_utils::fieldsmemory($rsHistorico, $iContHist);

    $iCodigoHist  .= $sSeparador.$oDadosHist->ed61_i_codigo;
    $iCodigoCurso .= $sSeparador.$oDadosHist->ed61_i_curso;

    $sSeparador    = ", ";
  }

  $sCamposAno  = "max(ed62_i_anoref) as ultimoanorede,  max(ed99_i_anoref) as ultimoanofora";
  $sSqlAnoHist = $oDaoHistorico->sql_query_historico("",  $sCamposAno,  "",  " ed61_i_codigo in ($iCodigoHist)");
  $rsAnoHist   = $oDaoHistorico->sql_record($sSqlAnoHist);
  $iLinhasAno  = $oDaoHistorico->numrows;

  if ($iLinhasAno > 0) {

    $oDadosAnoHist = db_utils::fieldsmemory($rsAnoHist, 0);

    if (trim($oDadosAnoHist->ultimoanorede) == "" && trim($oDadosAnoHist->ultimoanofora) == "") {
      $iUltimoAno = date("Y",  db_getsession("DB_datausu"));
    } else if (trim($oDadosAnoHist->ultimoanorede) == "" && trim($oDadosAnoHist->ultimoanofora) != "") {
      $iUltimoAno = $oDadosAnoHist->ultimoanofora;
    } else if (trim($oDadosAnoHist->ultimoanorede) != "" && trim($oDadosAnoHist->ultimoanofora) == "") {
      $iUltimoAno = $oDadosAnoHist->ultimoanorede;
    } else if (trim($oDadosAnoHist->ultimoanorede) != "" && trim($oDadosAnoHist->ultimoanofora) != "") {

      if (trim($oDadosAnoHist->ultimoanorede) > trim($oDadosAnoHist->ultimoanofora)) {
        $iUltimoAno = $oDadosAnoHist->ultimoanorede;
      } else {
        $iUltimoAno = $oDadosAnoHist->ultimoanofora;
      }
    }
  } else {
    $iUltimoAno = date("Y",  db_getsession("DB_datausu"));;
  }

  /* Carga Horária */
  $sWhereCarga  = "     ed61_i_codigo IN ({$iCodigoHist}) AND ed62_c_resultadofinal in ('D', 'A')";
  $sWhereCarga .= " AND ed62_i_anoref <= {$iUltimoAno} {$sWhereExibeReclassificacaoRede}";
  $sCamposCR    = "sum(ed62_i_qtdch) as chtotalrede";
  $sSqlCarga    = $oDaoHistorico->sql_query_historicomps("", $sCamposCR, "", $sWhereCarga);
  $rsCarga      = $oDaoHistorico->sql_record($sSqlCarga);
  $iLinhasCarga = $oDaoHistorico->numrows;
  $oDadosCarga  = db_utils::fieldsmemory($rsCarga, 0);

  /* Carga Horária Fora */
  $sWhereCargaFora  = "     ed61_i_codigo IN ({$iCodigoHist}) AND ed99_c_resultadofinal in ('D', 'A')";
  $sWhereCargaFora .= " AND ed99_i_anoref <= {$iUltimoAno} {$sWhereExibeReclassificacaoFora}";
  $sCamposCF        = " sum(ed99_i_qtdch) as chtotalfora ";
  $sSqlCargaFora    = $oDaoHistorico->sql_query_historicompsfora("", $sCamposCF, "", $sWhereCargaFora);
  $rsCargaFora      = $oDaoHistorico->sql_record($sSqlCargaFora);
  $iLinhasCargaFora = $oDaoHistorico->numrows;
  $oDadosCargaFora  = db_utils::fieldsmemory($rsCargaFora, 0);

  if ($iTipoRegistro == "A") {

    //somente registros aprovados
    $sCondicaoHistMps     = " AND ed62_i_anoref <= $iUltimoAno AND ed62_c_resultadofinal in ('D', 'A') ";
    $sCondicaoHistMpsFora = " AND ed99_i_anoref <= $iUltimoAno AND ed99_c_resultadofinal in ('D', 'A')";
  } else if ($iTipoRegistro == "AR") {

    //registros aprovados e reprovados
    $sCondicaoHistMps     = " AND ed62_i_anoref <= $iUltimoAno ";
    $sCondicaoHistMpsFora = " AND ed99_i_anoref <= $iUltimoAno ";
  } else if ($iTipoRegistro == "U") {

    /* Exibe todos os registros com aprovado,  exceto o último,  que exibe de qualquer forma (aprovado ou não). */
    $sCondicaoHistMps      = " AND ed62_i_anoref <= $iUltimoAno ";
    $sCondicaoHistMps     .= " AND (ed62_c_resultadofinal in ('D', 'A') OR ed62_i_anoref = $iUltimoAno)";
    $sCondicaoHistMpsFora  = " AND ed99_i_anoref <= $iUltimoAno ";
    $sCondicaoHistMpsFora .= "AND (ed99_c_resultadofinal in ('D', 'A') OR ed99_i_anoref = $iUltimoAno)";
  }

  $sCondicaoHistMps     .= $sWhereExibeReclassificacaoRede;
  $sCondicaoHistMpsFora .= $sWhereExibeReclassificacaoFora;

  montaCabecalho($oPdf, $oDadosRelatModel, $oDadosEscola, $oDadosAluno, $oDadosHist, $iEscola,
                 $iCodigoCurso, $sTelefoneEscola, $iCodigoHist, $sCondicaoHistMps, $sCondicaoHistMpsFora,
                 $lExibirReclassificacao);

  $sWhereCursoConcluido = getWhereCursoConcluido();
  $sWhereCurso          = getWhereCurso();

  $sSqlUnion    = " SELECT * FROM (SELECT ed11_i_sequencia,  ed62_i_anoref,  ed62_i_escola,  ed232_c_descrcompleta,  ed11_c_abrev,  ";
  $sSqlUnion   .= "        ed65_i_disciplina,  ed65_i_justificativa,  ed65_i_qtdch,  ed65_c_resultadofinal,  ";
  $sSqlUnion   .= "        ed65_c_situacao,  ed65_c_tiporesultado,  ed65_t_resultobtido, ed29_c_historico, ";
  $sSqlUnion   .= "        ed65_c_termofinal as termofinal, ed65_i_ordenacao as ordenacao, ed62_i_anoref as ano, ed65_lancamentoautomatico, ed62_c_situacao as situacao ";
  $sSqlUnion   .= "       FROM histmpsdisc ";
  $sSqlUnion   .= "            inner join disciplina    on ed12_i_codigo  = ed65_i_disciplina ";
  $sSqlUnion   .= "            inner join caddisciplina on ed232_i_codigo = ed12_i_caddisciplina ";
  $sSqlUnion   .= "            inner join historicomps  on ed62_i_codigo  = ed65_i_historicomps ";
  $sSqlUnion   .= "            inner join serie         on ed11_i_codigo  = ed62_i_serie ";
  $sSqlUnion   .= "            inner join historico     on ed61_i_codigo  = ed62_i_historico ";
  $sSqlUnion   .= "            inner join cursoedu      on ed29_i_codigo  = ed61_i_curso ";
  $sSqlUnion   .= "       WHERE ed61_i_codigo in ($iCodigoHist) and ed29_c_historico = 'S' {$sWhereCursoConcluido} {$sWhereCurso}";
  $sSqlUnion   .= "             $sCondicaoHistMps";
  $sSqlUnion   .= " UNION ";
  $sSqlUnion   .= " SELECT ed11_i_sequencia,  ed99_i_anoref,  ed99_i_escolaproc,  ed232_c_descrcompleta,  ed11_c_abrev,  ";
  $sSqlUnion   .= "        ed100_i_disciplina,  ed100_i_justificativa,  ed100_i_qtdch,  ed100_c_resultadofinal,  ";
  $sSqlUnion   .= "        ed100_c_situacao, ed100_c_tiporesultado, ed100_t_resultobtido, ed29_c_historico, ";
  $sSqlUnion   .= "        ed100_c_termofinal as termofinal, ed100_i_ordenacao as ordenacao, ed99_i_anoref as ano, 'f' as ed65_lancamentoautomatico, ed99_c_situacao as situacao ";
  $sSqlUnion   .= "       FROM histmpsdiscfora ";
  $sSqlUnion   .= "            inner join disciplina       on ed12_i_codigo  = ed100_i_disciplina ";
  $sSqlUnion   .= "            inner join caddisciplina    on ed232_i_codigo = ed12_i_caddisciplina ";
  $sSqlUnion   .= "            inner join historicompsfora on ed99_i_codigo  = ed100_i_historicompsfora ";
  $sSqlUnion   .= "            inner join serie            on ed11_i_codigo  = ed99_i_serie ";
  $sSqlUnion   .= "            inner join historico        on ed61_i_codigo  = ed99_i_historico ";
  $sSqlUnion   .= "            inner join cursoedu         on ed29_i_codigo  = ed61_i_curso ";
  $sSqlUnion   .= "       WHERE ed61_i_codigo in ($iCodigoHist) and ed29_c_historico = 'S' {$sWhereCursoConcluido} {$sWhereCurso}";
  $sSqlUnion   .= "             $sCondicaoHistMpsFora";
  $sSqlUnion   .= "   ) AS X    ORDER BY ed62_i_anoref, ed11_c_abrev, ordenacao ASC ";
  $rsUnion      = db_query($sSqlUnion);
  $iLinhasUnion = pg_num_rows($rsUnion);

  $aSeries       = array();
  $aRetornoUnion = db_utils::getCollectionByRecord( $rsUnion );

  foreach ( $aRetornoUnion as $oRetornoUnion ) {
    $aSeries[ $oRetornoUnion->ed11_i_sequencia ] = $oRetornoUnion->ed62_i_anoref;
  }

  $oPdf->SetY(48);

  $lCor              = true;
  $iCont             = 0;
  $iTopo             = 48.5;
  $iPassou           = 0;
  $iAlturaColuna     = 72;
  $iAlturaLinha      = 4;
  $iAlturaAntes      = 0;
  $iAlturaAtual      = 0;
  $iLinhasAdd        = 0;
  $lInicioFixo       = false;
  $lPrimeiraPassagem = true;

  /* Percorre as disciplinas encontradas no histórico do aluno */
  for ($iContDisc = 0; $iContDisc < $iLinhasUnion; $iContDisc++) {

    $oDadosDisciplina = db_utils::fieldsmemory($rsUnion, $iContDisc);

    if (    (!$lExibirReclassificacao && trim($oDadosDisciplina->situacao) == 'RECLASSIFICADO')
         && array_key_exists( $oDadosDisciplina->ed11_i_sequencia, $aSeries )
         && $oDadosDisciplina->ed62_i_anoref != $aSeries[ $oDadosDisciplina->ed11_i_sequencia ] ) {
      continue;
    }

    /* Variável que irá 'zebrar' as linhas */
    if ($lCor) {
      $lCor = false;
    } else {
      $lCor = true;
    }

    /* Verifico (superficialmente) se a linha irá ser suportada pela coluna */
    $iTeste = round(strlen($oDadosDisciplina->ed232_c_descrcompleta) / 32) * 4;
    if (strlen($oDadosDisciplina->ed232_c_descrcompleta) > 27) {
      $iTeste = 4.1;
    }

    /* Se o teste retornar que a disciplina dará em uma linha */
    if ($iTeste == 4) {

      if (($iAlturaAtual + 4) > $iAlturaColuna) {

        if ($iPassou == 2) {

          montaCabecalho($oPdf, $oDadosRelatModel, $oDadosEscola, $oDadosAluno, $oDadosHist, $iEscola,
                         $iCodigoCurso, $sTelefoneEscola, $iCodigoHist, $sCondicaoHistMps, $sCondicaoHistMpsFora,
                         $lExibirReclassificacao);

          $oPdf->SetY(48.5);
          $iPassou = 0;
        } else {
          $iPassou++;
        }

        $iTopo        = 48.5;
        $iAlturaAtual = 0;
        $iAltura      = 4;
        $iCont        = 0;
      }

      if ($iPassou == 1 && $lPrimeiraPassagem) {
        $lInicioFixo       = true;
      }
    /* Se o teste retornar que a linha dará em mais de uma linha */
    } elseif (($iAlturaAtual + $iTeste) > $iAlturaColuna) {

      if ($iPassou == 2) {

        montaCabecalho($oPdf, $oDadosRelatModel, $oDadosEscola, $oDadosAluno, $oDadosHist, $iEscola,
                       $iCodigoCurso, $sTelefoneEscola, $iCodigoHist, $sCondicaoHistMps, $sCondicaoHistMpsFora,
                       $lExibirReclassificacao);

        $oPdf->SetY(48.5);
        $iPassou = 0;
      } else {
        $iPassou++;
      }

      $iTopo        = 48.5;
      $iAlturaAtual = 0;
      $iAltura      = 4;
      $iCont        = 0;

      if ($iPassou == 1 && $lPrimeiraPassagem) {
        $lInicioFixo       = true;
      }
    }

    /* Posiciona na próxima coluna */
    if ($iPassou == 1) {

      if( $lPrimeiraPassagem ) {

        $iTopo             = 48.5;
        $lInicioFixo       = false;
        $lPrimeiraPassagem = false;
      } else {
        $iTopo = $iTopo + $iAlturaLinha;
      }

      $oPdf->SetXY(102, $iTopo);
      $iAlturaAntes = $iAlturaLinha;
    }

    if ($iPassou == 2) {

      if ($iCont > 0 && !$lInicioFixo) {
        $iTopo = $iTopo + $iAlturaLinha;
      } else {

        $iTopo       = 48.5;
        $lInicioFixo = false;
      }

      $oPdf->SetXY(194, $iTopo);
      $iAlturaAntes = $iAlturaLinha;
    }

    if ($iPassou > 2) {

      montaCabecalho($oPdf, $oDadosRelatModel, $oDadosEscola, $oDadosAluno, $oDadosHist, $iEscola,
                     $iCodigoCurso, $sTelefoneEscola, $iCodigoHist, $sCondicaoHistMps, $sCondicaoHistMpsFora, $lExibirReclassificacao);

      $oPdf->SetY(48.5);
      $iPassou = 0;
    }

    $sResultado   = $oDadosDisciplina->ed65_t_resultobtido;
    $iAltAnterior = $oPdf->getY();

    $oPdf->setfont('arial','',7);
    $oPdf->multicell(34, 4, $oDadosDisciplina->ed232_c_descrcompleta, "LR", "L", $lCor, 0);

    $iAltDepois    = $oPdf->getY();
    $iAltura       = $iAltDepois - $iAltAnterior;
    $iAlturaAtual += $iAltura;
    $iAlturaLinha  = $iAltura;

    $iLinhasAdd   += $iAltura / 4;

    if ($iPassou == 0) {
      $oPdf->setXY($oPdf->getX() + 34, $iAltDepois - $iAltura);
    } elseif ($iPassou == 1) {
      $oPdf->setXY($oPdf->getX() + 126, $oPdf->getY() - $iAltura);
    } else {
      $oPdf->setXY($oPdf->getX() + 218, $iAltDepois - $iAltura);
    }

    $iCont++;

    switch (trim($oDadosDisciplina->ed65_c_resultadofinal)) {

      case 'A':

        $sSituacaoFinal = 'APR';
        break;

      case 'D':

        $sSituacaoFinal = 'AP/DP';
        break;

      case 'R':

        $sSituacaoFinal = 'REP';
        break;

      default:

        $sSituacaoFinal = "REP";
        if ($oDadosDisciplina->ed65_c_situacao != "CONCLUÍDO") {
          $sSituacaoFinal = 'APR';
        }
        break;
    }

    /**
     * Caso a situacao seja 'NÃO OPTANTE' ou 'AMPARADO', e nao exista um termo final, nao apresentamos o resultado final (RF)
     */
    if (   (    $oDadosDisciplina->ed65_c_situacao == "NÃO OPTANTE"
             || $oDadosDisciplina->ed65_c_situacao == "AMPARADO")
        && empty($oDadosDisciplina->termofinal)) {
      $sSituacaoFinal = '';
    } else if (!empty($oDadosDisciplina->termofinal)) {
      $sSituacaoFinal = $oDadosDisciplina->termofinal;
    }

    $oPdf->Cell(11, $iAltura, substr($oDadosDisciplina->ed11_c_abrev, 0, 7), "LR", 0, "C", $lCor);
    $oPdf->SetFont('arial', '', 6);

    /**
     * Verificamos a descricao que sera apresentada no aproveitamento (Ap.)
     */
    $sResultadoFinal = "";
    if ($oDadosDisciplina->ed65_c_situacao != "CONCLUÍDO" && $oDadosDisciplina->ed65_c_situacao != "NÃO OPTANTE") {
      $sResultadoFinal = "Amparo";
    } else if ($oDadosDisciplina->ed65_c_situacao == "NÃO OPTANTE") {
      $sResultadoFinal = $oDadosDisciplina->ed65_t_resultobtido;
    } else if ($sResultado != "") {
      $sResultadoFinal = $sResultado;
    }

    /**
     * Verificamos a carga horaria a ser apresentada
     */
    $nCargaHorariaDisciplina = '';
    if ($oDadosDisciplina->ed65_i_qtdch != "") {
      $nCargaHorariaDisciplina = $oDadosDisciplina->ed65_i_qtdch;
    }

    $nCargaHorariaDisciplina = (int)trim($nCargaHorariaDisciplina);

    $oPdf->Cell(10, $iAltura, $sResultadoFinal, "LR", 0, "C", $lCor);
    $oPdf->SetFont('arial', '', 7);
    $oPdf->Cell(10, $iAltura, $nCargaHorariaDisciplina, "LR", 0, "C", $lCor);
    $oPdf->Cell(8,  $iAltura, $sSituacaoFinal, "LR", 0, "C", $lCor);
    $oPdf->Cell(9,  $iAltura, $oDadosDisciplina->ed62_i_anoref, "LR", 0, "C", $lCor);
    $oPdf->Cell(10, $iAltura, $oDadosDisciplina->ed62_i_escola, "LR", 1, "C", $lCor);

    if ($iAlturaAtual > $iAlturaColuna) {

      $iTopo        = 48.5;
      $iAltura      = 4;
      $iAlturaAtual = 0;
      $iPassou++;

      if ($iPassou == 2) {
        $lInicioFixo = true;
      }
    }
  } //End FOR Percorre Disciplinas
} //End FOR $oDaoAluno->numrows

$oPdf->Output();

/**
 * Busca os atos legais que aparecem no histórico do aluno
 * @param Aluno $oAluno
 * @param Escola $oEscola
 * @return string
 */
function retornaStringAtosLegais(Aluno $oAluno, Escola $oEscola) {

  $aAtosLegaisEscola = array();
  $aAtosLegaisCurso  = array();

  /**
   * Primeiramente separamos os atos legais da escola e os que estão vinculados a algum curso da escola
   */
  foreach ($oEscola->getAtosLegais() as $oAtoLegal) {

    if ($oAtoLegal->existeCursoVinculado()) {
      $aAtosLegaisCurso[] = $oAtoLegal;
    } else {
      $aAtosLegaisEscola[] = $oAtoLegal;
    }
  }

  $aAtosLegais = array();
  foreach ($aAtosLegaisEscola as $oAtoLegal) {

    if (!$oAtoLegal->apareceHistorico()) {
      continue;
    }

    $sAtoLegal  = "{$oAtoLegal->getFinalidade()}  Nº {$oAtoLegal->getNumero()} ";
    $sAtoLegal .= "Data {$oAtoLegal->getDataVigor()->convertTo(DBDate::DATA_PTBR)} ";
    $sAtoLegal .= "D.O.: {$oAtoLegal->getDataDePublicacao()->convertTo(DBDate::DATA_PTBR)} ";

    $aAtosLegais[$oAtoLegal->getCodigoAtoLegal()] = $sAtoLegal;
  }

  $aHistoricoAluno = HistoricoAlunoRepository::getHistoricosPorAluno($oAluno);
  $aCodigoCursos   = array();

  /**
   * Adicionamos em um array, os códigos dos cursos que o aluno cursou
   */
  foreach ($aHistoricoAluno as $oHistoricoAluno) {
    $aCodigoCursos[] = $oHistoricoAluno->getCurso();
  }

  /**
   * Filtra os atos legais dos cursos que o aluno estudou
   */
  foreach ($aAtosLegaisCurso as $oAtoLegal) {

    if (!$oAtoLegal->apareceHistorico()) {
      continue;
    }

    foreach ($oAtoLegal->getCursosVinculado() as $oCurso) {

      if (!in_array($oCurso->getCodigo(), $aCodigoCursos))  {
      	continue;
      }
    }

    $sAtoLegal  = "{$oAtoLegal->getFinalidade()}  Nº {$oAtoLegal->getNumero()} ";
    $sAtoLegal .= "Data {$oAtoLegal->getDataVigor()->convertTo(DBDate::DATA_PTBR)} ";
    $sAtoLegal .= "D.O.: {$oAtoLegal->getDataDePublicacao()->convertTo(DBDate::DATA_PTBR)} ";

    $aAtosLegais[$oAtoLegal->getCodigoAtoLegal()] = $sAtoLegal;
  }

  $sAtoLegal = "";

  if (count($aAtosLegais) > 0) {
    $sAtoLegal = implode("\n", $aAtosLegais);
  }

  return $sAtoLegal;
}
