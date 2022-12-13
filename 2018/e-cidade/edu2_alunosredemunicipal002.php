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

ini_set('memory_limit', -1);

require_once ("fpdf151/pdf.php");
require_once ("libs/db_sql.php");
require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_stdlib.php");
require_once ("libs/db_usuariosonline.php");
require_once ("libs/db_app.utils.php");
require_once ("libs/JSON.php");
require_once ("std/DBDate.php");
require_once ("dbforms/db_funcoes.php");

$oParam = db_utils::postMemory($_GET);

/**
 * stdClass para utilizacao de propriedades no relatorio
 */
$oFiltros = new stdClass();

/**
 * Armazenamos os valores recebidos via GET
 */
$oFiltros->dtMatriculaInicial  = $oParam->dDataMatriculaInicial;
$oFiltros->dtMatriculaFinal    = $oParam->dDataMatriculaFinal;
$oFiltros->iEscola             = $oParam->iEscola;
$oFiltros->iEnsino             = $oParam->iEnsino;
$oFiltros->iEtapa              = $oParam->iEtapa;
$oFiltros->sOrientacao         = $oParam->orientacao;
$oFiltros->sAlinhamento        = $oParam->alinhamento;
$oFiltros->iFonte              = $oParam->tamfonte;
$oFiltros->sCampos             = $oParam->campos;
$oFiltros->aAlinhamento        = explode("|", $oFiltros->sAlinhamento);
$oFiltros->aCampos             = explode(",", $oFiltros->sCampos);
$oFiltros->iTamanhoMaximoLinha = 192;

/**
 * Buscamos a descricao do ensino caso tenha sido selecionado algum especifico
 */
$sEnsino = 'TODOS';
if ($oFiltros->iEnsino != 0) {

  $oEnsino = new Ensino($oFiltros->iEnsino);
  $sEnsino = $oEnsino->getNome();
  unset($oEnsino);
}

/**
 * Buscamos a descricao da etapa caso tenha sido selecionada alguma especifica
 */
$sEtapa = 'TODAS';
if ($oFiltros->iEtapa != 0) {

  $oEtapa = EtapaRepository::getEtapaByCodigo($oFiltros->iEtapa);
  $sEtapa = $oEtapa->getNome();
  EtapaRepository::removerEtapa($oEtapa);
}

/**
 * Setamos os valores iniciais de cada coluna
 */
$oFiltros->iTamanhoCodigo        = 12;
$oFiltros->iTamanhoNome          = 180;
$oFiltros->iTamanhoCurso         = 53;
$oFiltros->iTamanhoEtapa         = 30;
$oFiltros->iTamanhoDataMatricula = 24;

/**
 * Array para controle das quebras
 */
$oFiltros->aQuebras = array();

/**
 * Array com os cabecalhos selecionados para serem impressos
 */
$oFiltros->aCabecalho = array();

if ($oFiltros->sOrientacao == 'L') {

  $oFiltros->iTamanhoMaximoLinha = 280;
  $oFiltros->iTamanhoNome        = 268;
}

/**
 * stdClass referente ao CODIGO DO ALUNO
 * sDescricao: Descricao impressa no cabecalho
 * iTamanho: Tamanho da coluna
 * iTipo: Tipo para controle dos dados a serem impressos
 */
$iPosicaoArray                        = array_search('ed47_i_codigo', $oFiltros->aCampos);
$oFiltros->oCodigoAluno               = new stdClass();
$oFiltros->oCodigoAluno->sDescricao   = 'Código';
$oFiltros->oCodigoAluno->iTamanho     = $oFiltros->iTamanhoCodigo;
$oFiltros->oCodigoAluno->sAlinhamento = $oFiltros->aAlinhamento[$iPosicaoArray];
$oFiltros->oCodigoAluno->iTipo        = 1;
$oFiltros->aCabecalho[$iPosicaoArray] = $oFiltros->oCodigoAluno;


/**
 * stdClass referente ao NOME DO ALUNO
 * sDescricao: Descricao impressa no cabecalho
 * iTamanho: Tamanho da coluna
 * iTipo: Tipo para controle dos dados a serem impressos
 */
$iPosicaoArray                        = array_search('ed47_v_nome', $oFiltros->aCampos);
$oFiltros->oNomeAluno                 = new stdClass();
$oFiltros->oNomeAluno->sDescricao     = 'Nome do Aluno';
$oFiltros->oNomeAluno->iTamanho       = $oFiltros->iTamanhoNome;
$oFiltros->oNomeAluno->sAlinhamento   = $oFiltros->aAlinhamento[$iPosicaoArray];
$oFiltros->oNomeAluno->iTipo          = 2;
$oFiltros->aCabecalho[$iPosicaoArray] = $oFiltros->oNomeAluno;

/**
 * Verifica se deve ser impressa a coluna com o curso
 */
if (in_array('ed10_i_codigo', $oFiltros->aCampos)) {

  $iPosicaoArray = array_search('ed10_i_codigo', $oFiltros->aCampos);

  /**
   * stdClass referente ao CURSO DO ALUNO
   * sDescricao: Descricao impressa no cabecalho
   * iTamanho: Tamanho da coluna
   * iTipo: Tipo para controle dos dados a serem impressos
   */
  $oFiltros->oCurso                      = new stdClass();
  $oFiltros->oCurso->sDescricao          = 'Ensino';
  $oFiltros->oCurso->iTamanho            = $oFiltros->iTamanhoCurso;
  $oFiltros->oCurso->sAlinhamento        = $oFiltros->aAlinhamento[$iPosicaoArray];
  $oFiltros->oCurso->iTipo               = 3;
  $oFiltros->oNomeAluno->iTamanho       -= $oFiltros->oCurso->iTamanho;
  $oFiltros->aCabecalho[$iPosicaoArray]  = $oFiltros->oCurso;
}

/**
 * Verifica se deve ser impressa a coluna com a etapa
 */
if (in_array('ed11_i_codigo', $oFiltros->aCampos)) {

  $iPosicaoArray = array_search('ed11_i_codigo', $oFiltros->aCampos);

  /**
   * stdClass referente a ETAPA QUE SE ENCONTRA O ALUNO
   * sDescricao: Descricao impressa no cabecalho
   * iTamanho: Tamanho da coluna
   * iTipo: Tipo para controle dos dados a serem impressos
   */
  $oFiltros->oEtapa                      = new stdClass();
  $oFiltros->oEtapa->sDescricao          = 'Etapa';
  $oFiltros->oEtapa->iTamanho            = $oFiltros->iTamanhoEtapa;
  $oFiltros->oEtapa->sAlinhamento        = $oFiltros->aAlinhamento[$iPosicaoArray];
  $oFiltros->oEtapa->iTipo               = 4;
  $oFiltros->oNomeAluno->iTamanho       -= $oFiltros->oEtapa->iTamanho;
  $oFiltros->aCabecalho[$iPosicaoArray]  = $oFiltros->oEtapa;
}

/**
 * Verifica se deve ser impressa a coluna com a data da matricula do aluno
 */
if (in_array('ed60_d_datamatricula', $oFiltros->aCampos)) {

  $iPosicaoArray = array_search('ed60_d_datamatricula', $oFiltros->aCampos);

  /**
   * stdClass referente a DATA DE MATRICULA DO ALUNO
   * sDescricao: Descricao impressa no cabecalho
   * iTamanho: Tamanho da coluna
   * iTipo: Tipo para controle dos dados a serem impressos
   */
  $oFiltros->oDataMatricula                = new stdClass();
  $oFiltros->oDataMatricula->sDescricao    = 'Dt. Matrícula';
  $oFiltros->oDataMatricula->iTamanho      = $oFiltros->iTamanhoDataMatricula;
  $oFiltros->oDataMatricula->sAlinhamento  = $oFiltros->aAlinhamento[$iPosicaoArray];
  $oFiltros->oDataMatricula->iTipo         = 5;
  $oFiltros->oNomeAluno->iTamanho         -= $oFiltros->oDataMatricula->iTamanho;
  $oFiltros->aCabecalho[$iPosicaoArray]    = $oFiltros->oDataMatricula;
}

/**
 * Reordenamos o array dos campos
 */
ksort($oFiltros->aCabecalho);

/**
 * Array com as condicoes para SQL
 */
$aWhereEscola = array();

/**
 * Array com as escolas para imprimir o nome no cabecalho
 */
$aEscolas = array();

/**
 * Setamos por padrao a descricao do periodo no cabecalho com a data da sessao
 */
$sPeriodoMatricula = db_getsession("DB_anousu");

/**
 *  Array para buscar os anos do calendario
 */
$aAno = array();

/**
 * Propriedades que receberao instancia DBDate da data de matricula inicial e final, caso tenham sido informadas
 */
$oFiltros->oMatriculaInicial = null;
$oFiltros->oMatriculaFinal   = null;

$aFiltrarMatricula = array();
if (!empty($oFiltros->dtMatriculaInicial)) {

  $oFiltros->oMatriculaInicial = new DBDate($oFiltros->dtMatriculaInicial);
  $aAno[]                      = $oFiltros->oMatriculaInicial->getAno();
  $aFiltrarMatricula[]         = " ed60_d_datamatricula >= '{$oFiltros->oMatriculaInicial->convertTo(DBDate::DATA_EN)}'" ;
}

if (!empty($oFiltros->dtMatriculaFinal)) {

  $oFiltros->oMatriculaFinal = new DBDate($oFiltros->dtMatriculaFinal);
  $aAno[]                    = $oFiltros->oMatriculaFinal->getAno();
  $aFiltrarMatricula[]       = " ed60_d_datamatricula <= '{$oFiltros->oMatriculaFinal->convertTo(DBDate::DATA_EN)}'" ;
}

if (empty($oFiltros->dtMatriculaInicial) && empty($oFiltros->dtMatriculaFinal)) {
  $aWhereEscola[] = "ed52_i_ano = ".db_getsession("DB_anousu");
} else {

  $aWhereEscola[]    = "ed52_i_ano in (".implode(",", $aAno).")";
  $sPeriodoMatricula = "{$oFiltros->dtMatriculaInicial} até {$oFiltros->dtMatriculaFinal}";
}

if ($oFiltros->iEscola != 0) {
  $aWhereEscola[] = "ed18_i_codigo = {$oFiltros->iEscola}";
}

if ($oFiltros->iEnsino != 0) {
  $aWhereEscola[] = "ed10_i_codigo = {$oFiltros->iEnsino}";
}

if ($oFiltros->iEtapa != 0) {
  $aWhereEscola[] = "ed11_i_codigo = {$oFiltros->iEtapa}";
}

$sWhereEscola = implode(" and ", $aWhereEscola);


/**
 * Ao filtrar um período de matricula, retorna somente as escolas que possuem alunos matriculados no período informado
 */
if (count($aFiltrarMatricula) > 0) {

  $sFiltroMatricula = implode(" and ", $aFiltrarMatricula);

  $sWhereEscola .= " and exists( SELECT 1 from matricula ";
  $sWhereEscola .= "              inner join matriculaserie on matriculaserie.ed221_i_matricula = matricula.ed60_i_codigo" ;
  $sWhereEscola .= "                                       and matriculaserie.ed221_i_serie     = ed11_i_codigo ";
  $sWhereEscola .= "              where matricula.ed60_i_turma = turma.ed57_i_codigo ";
  $sWhereEscola .= "                and {$sFiltroMatricula} )";
}

/**
 * SQL para buscar as escolas, ensinos e etapas. Monta um array da seguinte forma
 * $oFiltros->aQuebras[codigo da escola]
 *                    ..[ensinos vinculados a escola]
 *                    ....[codigo das turmas vinculadas ao ensino]
 *                    ......[codigo da etapa da turma] = Descricao da etapa
 * Monta o array das escolas
 */
$oDaoTurma     = new cl_turma();
$sCamposTurma  = "ed18_i_codigo as codigo_escola, ed18_c_nome as nome_escola, ed10_i_codigo as ensino";
$sCamposTurma .= ", ed11_i_codigo as codigo_etapa, ed11_c_descr as descricao_etapa, ed57_i_codigo as codigo_turma";
$sSqlTurma     = $oDaoTurma->sql_query_turma(null, $sCamposTurma, "ed18_i_codigo", $sWhereEscola);
$rsTurma       = $oDaoTurma->sql_record($sSqlTurma);
$iTotalTurma   = $oDaoTurma->numrows;

if ($iTotalTurma > 0) {

  for ($iContador = 0; $iContador < $iTotalTurma; $iContador++) {

    $oDadosSqlTurma  = db_utils::fieldsMemory($rsTurma, $iContador);
    $aAlunos         = buscaAlunos($oDadosSqlTurma->codigo_turma, $oDadosSqlTurma->codigo_etapa);

    if (count($aAlunos) == 0) {
      continue;
    }

    $oFiltros->aQuebras[$oDadosSqlTurma->codigo_escola]
                       [$oDadosSqlTurma->ensino]
                       [$oDadosSqlTurma->codigo_turma]
                       [$oDadosSqlTurma->codigo_etapa]->sDescricao = $oDadosSqlTurma->descricao_etapa;

    $aEscolas[$oDadosSqlTurma->codigo_escola] = $oDadosSqlTurma->nome_escola;
  }
}
// echo "<pre>";
// print_r($oFiltros);

if ($iTotalTurma == 0 || count($oFiltros->aQuebras) == 0) {

  $sMsgErro = "ERRO: Não foram encontrados dados com os filtros informados.<br>";
  db_redireciona("db_erros.php?fechar=true&db_erro={$sMsgErro}");
}
// exit();

/**
 * Iniciamos o PDF
 */
$oPdf = new PDF($oFiltros->sOrientacao);
$oPdf->Open();
$oPdf->AliasNbPages();
$oPdf->SetAutoPageBreak(false);
$oPdf->SetFillColor(225);

$head1 = "Listagem de Alunos";
$head3 = "Período da Matrícula: {$sPeriodoMatricula}";
$head4 = "Ensino: {$sEnsino}";
$head5 = "Etapa: {$sEtapa}";

/**
 * Array para controle dos alunos, nao permitindo contabilizar alunos duplicados
 */
$oFiltros->aControleAlunos = array();

/**
 * Total de escolas existentes no array
 */
$oFiltros->iTotalEscolas = count($oFiltros->aQuebras);

/**
 * Controla o número de escolas percorridas
 */
$oFiltros->iEscolasPercorridas = 0;

/**
 * Controla o total de alunos percorridos por escola
 */
$oFiltros->iTotalAlunos = 0;

/**
 * Controla o total de alunos impressos
 */
$oFiltros->iTotalAlunosImpresso = 0;

/**
 * Controla o total de matrículas ativas na escola
 */
$oFiltros->iTotalMatriculas = 0;

/**
 * Controla o total de matrículas ativas no município
 */
$oFiltros->iTotalMatriculasMunicipio = 0;

/**
 * Percorremos o array quebrado a pagina e totalizando os alunos por escola
 */
foreach ($oFiltros->aQuebras as $iEscola => $aEscola) {

  $oFiltros->iEscolasPercorridas++;
  $oFiltros->iTotalAlunos     = 0;
  $oFiltros->iTotalMatriculas = 0;

  if (array_key_exists($iEscola, $aEscolas)) {
    $head2 = "Escola: {$aEscolas[$iEscola]}";
  }

  $oPdf->AddPage();
  cabecalhoRelatorio($oPdf, $oFiltros);
  corpoRelatorio($oPdf, $oFiltros, $aEscola);
  imprimeTotalizador($oPdf, $oFiltros, 1);
}

/**
 * Busca os alunos com base na turma e etapa
 * @param integer $iTurma
 * @param integer $iEtapa
 * @return array
 */
function buscaAlunos($iTurma, $iEtapa) {

  $oTurma  = TurmaRepository::getTurmaByCodigo($iTurma);
  $oEtapa  = EtapaRepository::getEtapaByCodigo($iEtapa);
  $aAlunos = $oTurma->getAlunosMatriculadosNaTurmaPorSerie($oEtapa);

  return $aAlunos;
}

/**
 * Imprime o cabecalho do relatorio de acordo com as colunas desejadas
 * @param PDF $oPdf
 * @param stdClass $oFiltros
 */
function cabecalhoRelatorio(PDF $oPdf, $oFiltros) {

  $oPdf->SetFont('arial', 'b', $oFiltros->iFonte);
  foreach ($oFiltros->aCabecalho as $oCabecalho) {
    $oPdf->Cell($oCabecalho->iTamanho, 4, $oCabecalho->sDescricao, 1, 0, 'C', 1);
  }
  $oPdf->Ln();
}

/**
 * Imprime o corpo do relatorio de acordo com as colunas desejadas. O corpo eh impresso seguindo a montagem do array.
 * Ou seja, sao impressos todos os alunos das etapas e ensinos existentes na escola, em sequencia
 * @param PDF $oPdf
 * @param stdClass $oFiltros
 * @param array $aEscola
 */
function corpoRelatorio(PDF $oPdf, $oFiltros, $aEscola) {

  foreach ($aEscola as $iEnsino => $aEnsino) {

    foreach ($aEnsino as $iTurma => $aTurma) {

      foreach ($aTurma as $iEtapa => $sEtapa) {

        $aAlunos = buscaAlunos($iTurma, $iEtapa);

        /**
         * Percorremos os alunos matriculados na turma
         */
        foreach ($aAlunos as $oMatricula ) {

          if (($oPdf->GetY() > $oPdf->h - 15)) {

            $oPdf->AddPage();
            cabecalhoRelatorio($oPdf, $oFiltros);
          }

          if ( $oMatricula->getSituacao() != 'MATRICULADO' || !$oMatricula->isAtiva() ) {
            continue;
          }

          $dtMatricula  = $oMatricula->getDataMatricula()->convertTo(DBDate::DATA_PTBR);

          /**
           * Validamos as datas dos periodos que tenham sido setadas, com a data de matricula do aluno
           */

          if (   !empty($oFiltros->dtMatriculaInicial)
              && !empty($oFiltros->dtMatriculaFinal)
              && !DBDate::dataEstaNoIntervalo($oMatricula->getDataMatricula(), $oFiltros->oMatriculaInicial, $oFiltros->oMatriculaFinal)) {

            continue;
          } else if (   !empty($oFiltros->dtMatriculaInicial)
                     && DBDate::calculaIntervaloEntreDatas($oMatricula->getDataMatricula(), $oFiltros->oMatriculaInicial, 'd') < 0) {

            continue;
          } else if (   !empty($oFiltros->dtMatriculaFinal)
                     && DBDate::calculaIntervaloEntreDatas($oMatricula->getDataMatricula(), $oFiltros->oMatriculaFinal, 'd') > 0) {
            continue;
          }

          if (!in_array($oMatricula->getAluno()->getCodigoAluno(), $oFiltros->aControleAlunos)) {
            $oFiltros->aControleAlunos[] = $oMatricula->getAluno()->getCodigoAluno();
            $oFiltros->iTotalAlunos ++;
            $oFiltros->iTotalAlunosImpresso ++;
          }

          $oPdf->SetFont('arial', '', $oFiltros->iFonte);

          /**
           * Percorremos o array dos cabecalhos a serem impressos, e de acordo com o tipo selecionado, imprimimos os
           * devidos campos com tamanho correto
           */
          foreach ($oFiltros->aCabecalho as $oCabecalho) {

            switch ($oCabecalho->iTipo) {

              /**
               * Codigo do Aluno
               */
              case 1:

                $oPdf->Cell($oCabecalho->iTamanho, 4, $oMatricula->getAluno()->getCodigoAluno(), 1, 0, $oCabecalho->sAlinhamento);
                break;

              /**
               * Nome do aluno
               */
              case 2:

                $oPdf->Cell($oCabecalho->iTamanho, 4, $oMatricula->getAluno()->getNome(), 1, 0, $oCabecalho->sAlinhamento);
                break;

              /**
               * Nome do curso
               */
              case 3:

                $sNome = $oMatricula->getTurma()->getBaseCurricular()->getCurso()->getEnsino()->getNome();
                $oPdf->Cell($oCabecalho->iTamanho, 4, $sNome, 1, 0, $oCabecalho->sAlinhamento);
                break;

              /**
               * Descricao da etapa
               */
              case 4:

                $oPdf->Cell($oCabecalho->iTamanho, 4, $sEtapa->sDescricao, 1, 0, $oCabecalho->sAlinhamento);
                break;

              /**
               * Data da matricula
               */
              case 5:

                $oPdf->Cell($oCabecalho->iTamanho, 4, $dtMatricula, 1, 0, $oCabecalho->sAlinhamento);
                break;
            }
          }

          $oPdf->Ln();
          $oFiltros->iTotalMatriculas++;
          $oFiltros->iTotalMatriculasMunicipio++;
        }
      }
    }
  }
}

/**
 * Imprime o totalizador. De acordo com o parametro $sTotal, imprime o totalizador por escola ou geral
 * 1 - Total de alunos por escola
 * 2 - Total de alunos no municipio
 * @param PDF $oPdf
 * @param stdClass $oFiltros
 */
function imprimeTotalizador(PDF $oPdf, $oFiltros, $iTipoTotalizador) {

  $oPdf->SetFont('arial', 'b', $oFiltros->iFonte);

  if ($iTipoTotalizador == 1) {

    $sMensagem  = "Total de Matrículas Ativas na Escola: {$oFiltros->iTotalMatriculas} / ";
    $sMensagem .= "Total de Alunos na Escola: {$oFiltros->iTotalAlunos}";
    $oPdf->Cell($oFiltros->iTamanhoMaximoLinha, 4, $sMensagem, 1, 1, 'R', 1);
  } else {

    $sMensagem  = "Total de Matrículas Ativas no Município: {$oFiltros->iTotalMatriculasMunicipio} / ";
    $sMensagem .= "Total de Alunos no Munícípio: " . count( $oFiltros->aControleAlunos );
    $oPdf->Cell($oFiltros->iTamanhoMaximoLinha, 4, $sMensagem, 1, 1, 'R', 1);
  }
}

/**
 * Imprimimos o totalizador de alunos no municipio, caso tenham sido selecionadas todas as escolas
 */
if ($oFiltros->iEscola == 0) {

  $oPdf->Ln();
  imprimeTotalizador($oPdf, $oFiltros, 2);
}


/**
 * Caso nenhum aluno seja apresentado no relatório e todas as escolas tenham sido percorridas, apresenta a mensagem
 */
if ($oFiltros->iTotalAlunosImpresso == 0 && $oFiltros->iEscolasPercorridas == $oFiltros->iTotalEscolas) {

  $sMsgErro = "ERRO: Não foram encontrados dados com os filtros informados.<br>";
  db_redireciona("db_erros.php?fechar=true&db_erro={$sMsgErro}");
}

$oPdf->Output();
?>